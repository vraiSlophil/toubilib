<?php

namespace toubilib\core\application\usecases;

use DateTimeImmutable;
use InvalidArgumentException;
use toubilib\core\application\ports\api\dtos\inputs\InputRendezVousDTO;
use toubilib\core\application\ports\api\dtos\outputs\CreneauDTO;
use toubilib\core\application\ports\api\dtos\outputs\RendezVousDTO;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\domain\entities\Rdv;


final class ServiceRdv implements ServiceRdvInterface
{
    public function __construct(
        private RdvRepositoryInterface       $rdvRepository,
        private PraticienRepositoryInterface $praticienRepository
    )
    {
    }

    public function getRdvById(string $rdvId): ?RendezVousDTO
    {
        $rdv = $this->rdvRepository->getById($rdvId);
        return $rdv ? RendezVousDTO::fromEntity($rdv) : null;
    }

    public function listCreneauxPris(string $praticienId, DateTimeImmutable $debut, DateTimeImmutable $fin): array
    {
        $rdvs = $this->rdvRepository->listForPraticienBetween($praticienId, $debut, $fin);
        return array_map(static fn($e) => CreneauDTO::fromRdv($e), $rdvs);
    }

    public function creerRdv(InputRendezVousDTO $input): string
    {
        $praticien = $this->praticienRepository->findDetailById($input->praticienId);
        if ($praticien === null) {
            throw new InvalidArgumentException("Praticien not found");
        }
        $rdvFin = $input->debut->modify('+' . $input->dureeMinutes . ' minutes');
        if (!$praticien->isAvailable($input->debut, $rdvFin)) {
            throw new InvalidArgumentException("Praticien not available");
        }

        $rdv = Rdv::fromInputDTO($input);
        $this->rdvRepository->create($rdv);

        return $rdv->getId();
    }
}