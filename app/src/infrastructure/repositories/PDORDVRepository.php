<?php
namespace App\Infrastructure\Repositories;

use App\ApplicationCore\Application\Ports\Spi\RepositoryInterfaces\RdvRepositoryInterface;
use App\ApplicationCore\Domain\Entities\Rdv\Rdv;
use PDO;

final class PDORDVRepository implements RdvRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function getById(string $rdvId): ?Rdv
    {
        $sql = 'SELECT id, praticien_id, patient_id, patient_email, date_heure_debut, duree, date_heure_fin, status, motif_visite
                FROM rdv WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $rdvId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->map($row) : null;
    }

    public function listForPraticienBetween(string $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin): array
    {
        $sql = 'SELECT id, praticien_id, patient_id, patient_email, date_heure_debut, duree, date_heure_fin, status, motif_visite
                FROM rdv
                WHERE praticien_id = :pid
                  AND date_heure_debut < :fin
                  AND (date_heure_fin IS NULL OR date_heure_fin > :debut)
                ORDER BY date_heure_debut ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':pid'   => $praticienId,
            ':debut' => $debut->format('Y-m-d H:i:sP'),
            ':fin'   => $fin->format('Y-m-d H:i:sP'),
        ]);
        $out = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $out[] = $this->map($row);
        }
        return $out;
    }

    private function map(array $r): Rdv
    {
        return new Rdv(
            id: (string)$r['id'],
            praticienId: (string)$r['praticien_id'],
            patientId: (string)$r['patient_id'],
            patientEmail: $r['patient_email'] ?? null,
            debut: new \DateTimeImmutable((string)$r['date_heure_debut']),
            dureeMinutes: (int)$r['duree'],
            fin: $r['date_heure_fin'] ? new \DateTimeImmutable((string)$r['date_heure_fin']) : null,
            status: (int)$r['status'],
            motifVisite: $r['motif_visite'] ?? null
        );
    }
}