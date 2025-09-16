<?php
namespace App\ApplicationCore\Application\Ports\Spi\RepositoryInterfaces;

use App\ApplicationCore\Domain\Entities\Rdv\Rdv;

interface RdvRepositoryInterface
{
    public function getById(string $rdvId): ?Rdv;

    public function listForPraticienBetween(string $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin): array; // of Rdv
}