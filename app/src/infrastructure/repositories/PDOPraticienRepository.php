<?php

namespace toubilib\infra\repositories;


use PDO;
use Psr\Log\LoggerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\domain\entities\praticien\Praticien;
use toubilib\core\domain\entities\praticien\Specialite;

class PDOPraticienRepository implements PraticienRepositoryInterface
{

    private PDO $pdo;
    private LoggerInterface $logger;


    public function __construct(PDO $pdo, LoggerInterface $logger)
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
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $this->logger->info(print_r($results, true));

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
}