<?php
namespace toubilib\core\domain\entities;

final class Rdv
{
    public function __construct(
        private string $id,
        private string $praticienId,
        private string $patientId,
        private ?string $patientEmail,
        private \DateTimeImmutable $debut,
        private int $dureeMinutes,
        private ?\DateTimeImmutable $fin,
        private int $status,
        private ?string $motifVisite
    ) {}

    public function getId(): string { return $line = $this->id; }
    public function getPraticienId(): string { return $this->praticienId; }
    public function getPatientId(): string { return $this->patientId; }
    public function getPatientEmail(): ?string { return $this->patientEmail; }
    public function getDebut(): \DateTimeImmutable { return $this->debut; }
    public function getDureeMinutes(): int { return $this->dureeMinutes; }
    public function getFin(): \DateTimeImmutable
    {
        if ($this->fin) { return $this->fin; }
        return $this->debut->modify('+' . $this->dureeMinutes . ' minutes');
    }
    public function getStatus(): int { return $this->status; }
    public function getMotifVisite(): ?string { return $this->motifVisite; }
}