<?php
namespace toubilib\core\application\usecases;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use toubilib\core\application\ports\api\dtos\outputs\CreneauDTO;
use toubilib\core\application\ports\api\dtos\outputs\RendezVousDTO;
use toubilib\core\application\ports\api\servicesInterfaces\InputRendezVousDTO;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\domain\entities\Rdv;


final class ServiceRdv implements ServiceRdvInterface
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

    public function creerRdv(InputRendezVousDTO $rdvDto): string
    {
        $rdv = Rdv::fromInputDTO($rdvDto);
        $this->repo->create($rdv);

        return $rdv->getId();
    }
}