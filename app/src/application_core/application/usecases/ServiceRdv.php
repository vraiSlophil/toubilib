<?php

namespace toubilib\core\application\usecases;

use DateTimeImmutable;
use InvalidArgumentException;
use toubilib\core\application\ports\api\dtos\inputs\InputRendezVousDTO;
use toubilib\core\application\ports\api\dtos\outputs\CreneauDTO;
use toubilib\core\application\ports\api\dtos\outputs\RendezVousDTO;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;
use toubilib\core\application\ports\spi\adapterInterface\MonologLoggerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\domain\entities\Rdv;
use toubilib\core\domain\exceptions\RdvNotFoundException;


final class ServiceRdv implements ServiceRdvInterface
{
    public function __construct(
        private RdvRepositoryInterface       $rdvRepository,
        private PraticienRepositoryInterface $praticienRepository,
        private MonologLoggerInterface      $logger
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

        if (!$praticien->isValidMotifVisite($input->motifVisite)) {
            throw new InvalidArgumentException("Motif de visite invalide pour ce praticien");
        }

        $rdvFin = $input->debut->modify('+' . $input->dureeMinutes . ' minutes');

        // Maintenant toutes les dates sont en UTC, pas besoin de conversion
        $rdvsExistants = $this->rdvRepository->listForPraticienBetween(
            $input->praticienId,
            $input->debut->modify('-1 minute'),
            $rdvFin->modify('+1 minute')
        );

        if (!empty($rdvsExistants)) {
            foreach ($rdvsExistants as $rdvExistant) {
                $rdvExistantDebut = $rdvExistant->getDebut();
                $rdvExistantFin = $rdvExistant->getFin();

                // Algorithme simple de détection de chevauchement
                if ($rdvExistantDebut < $rdvFin && $rdvExistantFin > $input->debut) {
                    throw new InvalidArgumentException(
                        "Conflit détecté : un rendez-vous existe déjà de " .
                        $rdvExistantDebut->format('Y-m-d H:i:s') . " à " .
                        $rdvExistantFin->format('Y-m-d H:i:s')
                    );
                }
            }
        }

        // Vérification des créneaux d'ouverture (horaires praticien)
        if (!$praticien->isAvailable($input->debut, $rdvFin)) {
            throw new InvalidArgumentException("Praticien not available");
        }

        $rdv = Rdv::fromInputDTO($input);
        $this->rdvRepository->create($rdv);

        return $rdv->getId();
    }

    public function annulerRendezVous(string $rdvId): void
    {
        $rdv = $this->rdvRepository->getById($rdvId);
        if ($rdv === null) {
            throw new RdvNotFoundException("Rendez-vous not found");
        }
        $rdv->annuler();
        $this->rdvRepository->delete($rdvId);
        $this->logger->log('info', 'Rendez-vous annulé (supprimé)', ['rdv_id' => $rdvId]);
    }
}