<?php
namespace App\ApplicationCore\Application\Usecases;

use App\ApplicationCore\Application\Ports\Api\CreneauDTO;
use App\ApplicationCore\Application\Ports\Api\RendezVousDTO;
use App\ApplicationCore\Application\Ports\Spi\RepositoryInterfaces\RdvRepositoryInterface;

final class ServiceRdv implements \App\ApplicationCore\Application\Ports\Api\ServiceRdvInterface
{
    public function __construct(private RdvRepositoryInterface $repo) {}

    public function getRdvById(string $rdvId): ?RendezVousDTO
    {
        $rdv = $this->repo->getById($rdvId);
        return $rdv ? RendezVousDTO::fromEntity($rdv) : null;
    }

    public function listCreneauxPris(string $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin): array
    {
        $rdvs = $this->repo->listForPraticienBetween($praticienId, $debut, $fin);
        return array_map(static fn($e) => CreneauDTO::fromRdv($e), $rdvs);
    }
}