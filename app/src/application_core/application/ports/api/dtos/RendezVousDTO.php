<?php
namespace App\ApplicationCore\Application\Ports\Api;

use App\ApplicationCore\Domain\Entities\Rdv\Rdv;

final class RendezVousDTO
{
    public function __construct(
        public string $id,
        public string $praticienId,
        public string $patientId,
        public ?string $patientEmail,
        public string $debut, // ISO‑8601
        public string $fin,   // ISO‑8601
        public int $status,
        public int $duree,
        public ?string $motifVisite
    ) {}

    public static function fromEntity(Rdv $e): self
    {
        return new self(
            $e->getId(),
            $e->getPraticienId(),
            $e->getPatientId(),
            $e->getPatientEmail(),
            $e->getDebut()->format(\DateTimeInterface::ATOM),
            $e->getFin()->format(\DateTimeInterface::ATOM),
            $e->getStatus(),
            $e->getDureeMinutes(),
            $e->getMotifVisite()
        );
    }
}