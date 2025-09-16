<?php
namespace App\ApplicationCore\Application\Ports\Api;

interface ServiceRdvInterface
{
    public function getRdvById(string $rdvId): ?RendezVousDTO;

    public function listCreneauxPris(string $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin): array; // of CreneauDTO
}