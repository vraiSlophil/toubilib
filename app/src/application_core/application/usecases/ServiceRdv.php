<?php

namespace toubilib\core\application\usecases;

use DateTimeImmutable;
use toubilib\core\application\ports\api\dtos\inputs\InputRendezVousDTO;
use toubilib\core\application\ports\api\dtos\outputs\CreneauDTO;
use toubilib\core\application\ports\api\dtos\outputs\RendezVousDTO;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;
use toubilib\core\application\ports\spi\adapterInterface\MonologLoggerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\domain\entities\Rdv;
use toubilib\core\domain\exceptions\RdvNotFoundException;
use toubilib\core\domain\exceptions\PraticienNotFoundException;
use toubilib\core\domain\exceptions\InvalidMotifException;
use toubilib\core\domain\exceptions\SlotConflictException;
use toubilib\core\domain\exceptions\PraticienUnavailableException;

final class ServiceRdv implements ServiceRdvInterface
{
    public function __construct(
        private RdvRepositoryInterface       $rdvRepository,
        private PraticienRepositoryInterface $praticienRepository,
        private MonologLoggerInterface       $logger
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
            throw new PraticienNotFoundException('Praticien not found');
        }
        if (!$praticien->isValidMotifVisite($input->motifVisite)) {
            throw new InvalidMotifException('Motif invalid for this praticien');
        }

        $fin = $input->debut->modify('+' . $input->dureeMinutes . ' minutes');

        $existants = $this->rdvRepository->listForPraticienBetween(
            $input->praticienId,
            $input->debut->modify('-1 minute'),
            $fin->modify('+1 minute')
        );

        foreach ($existants as $rdvExistant) {
            if ($rdvExistant->getDebut() < $fin && $rdvExistant->getFin() > $input->debut) {
                throw new SlotConflictException('Slot conflict');
            }
        }

        if (!$praticien->isAvailable($input->debut, $fin)) {
            throw new PraticienUnavailableException('Praticien unavailable');
        }

        $rdv = Rdv::fromInputDTO($input);
        $this->rdvRepository->create($rdv);
        return $rdv->getId();
    }

    public function annulerRendezVous(string $rdvId): void
    {
        $rdv = $this->rdvRepository->getById($rdvId);
        if ($rdv === null) {
            throw new RdvNotFoundException('Rdv not found');
        }
        $rdv->annuler();
        $this->rdvRepository->delete($rdvId);
        $this->logger->log('info', 'Rdv cancelled', ['rdv_id' => $rdvId]);
    }
}