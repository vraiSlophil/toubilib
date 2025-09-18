<?php

namespace toubilib\infra\repositories;


use PDO;
use toubilib\core\application\ports\spi\adapterInterface\MonologLoggerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\domain\entities\Praticien;
use toubilib\core\domain\entities\Specialite;
use toubilib\infra\adapters\MonologLogger;

class PDOPraticienRepository implements PraticienRepositoryInterface
{

    private PDO $pdo;
    private MonologLogger $logger;


    public function __construct(PDO $pdo, MonologLoggerInterface $logger)
    {
        $this->pdo = $pdo;
        $this->logger = $logger;
    }

    public function getAllPraticiens(): array
    {
        $statement = $this->pdo->prepare("
            SELECT p.id, p.nom, p.prenom, p.ville, p.email, p.telephone, 
                   p.rpps_id, p.titre, p.organisation, p.nouveau_patient,
                   s.id as specialite_id, s.libelle as specialite_libelle, s.description as specialite_description
            FROM praticien p 
            LEFT JOIN specialite s ON p.specialite_id = s.id
        ");

        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $this->logger->debug(print_r($results, true));

        $praticiens = [];
        foreach ($results as $row) {
            $praticiens[] = new Praticien(
                $row['id'] ?? 0,
                $row['nom'] ?? 'Inconnu',
                $row['prenom'] ?? 'Inconnu',
                $row['ville'] ?? 'Inconnue',
                $row['email'] ?? 'inconnu@example.com',
                $row['telephone'] ?? '0000000000',
                $row['rpps_id'] ?? 0,
                $row['titre'] ?? 'Non renseigné',
                (bool)$row['nouveau_patient'] ?? false,
                (bool)$row['organisation'] ?? false,
                new Specialite(
                    $row['specialite_id'] ?? 0,
                    $row['specialite_libelle'] ?? 'Non renseignée',
                    $row['specialite_description'] ?? 'Aucune description'
                )
            );
        }

        return $praticiens;
    }

    public function findDetailById(string $id): ?PraticienDetailDTO
    {
        $sql = 'SELECT p.*, s.id AS specialite_id, s.libelle AS specialite_libelle, s.description AS specialite_description,
                       st.id AS structure_id, st.nom AS structure_nom, st.adresse AS structure_adresse,
                       st.ville AS structure_ville, st.code_postal AS structure_code_postal, st.telephone AS structure_telephone
                FROM praticien p
                JOIN specialite s ON s.id = p.specialite_id
                LEFT JOIN structure st ON st.id = p.structure_id
                WHERE p.id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $p = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$p) { return null; }

        $motifs = $this->fetchAllAssoc('SELECT m.id, m.libelle
                                        FROM praticien2motif pm
                                        JOIN motif_visite m ON m.id = pm.motif_id
                                        WHERE pm.praticien_id = :id
                                        ORDER BY m.libelle', [':id' => $id]);

        $moyens = $this->fetchAllAssoc('SELECT mp.id, mp.libelle
                                        FROM praticien2moyen pm
                                        JOIN moyen_paiement mp ON mp.id = pm.moyen_id
                                        WHERE pm.praticien_id = :id
                                        ORDER BY mp.libelle', [':id' => $id]);

        $structure = $p['structure_id'] ? [
            'id' => $p['structure_id'],
            'nom' => $p['structure_nom'],
            'adresse' => $p['structure_adresse'],
            'ville' => $p['structure_ville'],
            'code_postal' => $p['structure_code_postal'],
            'telephone' => $p['structure_telephone'],
        ] : null;

        return new PraticienDetailDTO(
            id: (string)$p['id'],
            nom: (string)$p['nom'],
            prenom: (string)$p['prenom'],
            titre: (string)$p['titre'],
            email: (string)$p['email'],
            telephone: (string)$p['telephone'],
            ville: (string)$p['ville'],
            rppsId: $p['rpps_id'] ?: null,
            organisation: ((string)$p['organisation']) === '1',
            nouveauPatient: ((string)$p['nouveau_patient']) === '1',
            specialite: [
                'id' => (int)$p['specialite_id'],
                'libelle' => (string)$p['specialite_libelle'],
                'description' => $p['specialite_description'],
            ],
            structure: $structure,
            motifs: $motifs,
            moyens: $moyens
        );
    }

    private function fetchAllAssoc(string $sql, array $params): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}