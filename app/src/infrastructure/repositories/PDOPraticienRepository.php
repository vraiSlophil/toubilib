<?php

namespace toubilib\infra\repositories;


use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\domain\entities\praticien\Praticien;
use toubilib\core\domain\entities\praticien\Specialite;

class PDOPraticienRepository implements PraticienRepositoryInterface
{

    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllPraticiens(): array
    {
        $statement = $this->pdo->prepare("
            SELECT p.id, p.nom, p.prenom, p.ville, p.email, p.telephone, 
                   p.rpps_id, p.titre, p.organisation, p.nouveau_patient,
                   s.libelle as specialite_libelle, s.description as specialite_description
            FROM praticien p 
            LEFT JOIN specialite s ON p.specialite_id = s.id
        ");

        $statement->execute();
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $praticiens = [];
        foreach ($results as $row) {
            $praticiens[] = new Praticien(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                $row['ville'],
                $row['email'],
                $row['telephone'],
                $row['rpps_id'],
                $row['titre'],
                (bool)$row['nouveau_patient'],
                (bool)$row['organisation'],
                new Specialite(
                    $row['specialite_id'],
                    $row['specialite_libelle'],
                    $row['specialite_description']
                )
            );
        }

        return $praticiens;
    }
}