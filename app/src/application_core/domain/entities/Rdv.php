<?php

namespace toubilib\core\domain\entities;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use toubilib\core\application\ports\api\dtos\inputs\InputRendezVousDTO;

final class Rdv
{
    public function __construct(
        private string             $id,
        private string             $praticienId,
        private string             $patientId,
        private ?string            $patientEmail,
        private DateTimeImmutable  $debut,
        private int                $dureeMinutes,
        private ?DateTimeImmutable $fin,
        private DateTimeImmutable  $dateCreation,
        private int                $status,
        private ?string            $motifVisite
    )
    {
    }

    public static function fromInputDTO(InputRendezVousDTO $inputRendezVousDTO): self
    {
        return new self(
            Uuid::uuid7()->toString(),
            $inputRendezVousDTO->praticienId,
            $inputRendezVousDTO->patientId,
            $inputRendezVousDTO->patientEmail,
            $inputRendezVousDTO->debut,
            $inputRendezVousDTO->dureeMinutes,
            new DateTimeImmutable()->modify('+' . $inputRendezVousDTO->dureeMinutes . ' minutes'),
            new DateTimeImmutable(),
            0,
            $inputRendezVousDTO->motifVisite
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPraticienId(): string
    {
        return $this->praticienId;
    }

    public function getPatientId(): string
    {
        return $this->patientId;
    }

    public function getPatientEmail(): ?string
    {
        return $this->patientEmail;
    }

    public function getDebut(): DateTimeImmutable
    {
        return $this->debut;
    }

    public function getDureeMinutes(): int
    {
        return $this->dureeMinutes;
    }

    public function getFin(): DateTimeImmutable
    {
        if ($this->fin) {
            return $this->fin;
        }
        return $this->debut->modify('+' . $this->dureeMinutes . ' minutes');
    }

    public function getDateCreation(): DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getMotifVisite(): ?string
    {
        return $this->motifVisite;
    }
}