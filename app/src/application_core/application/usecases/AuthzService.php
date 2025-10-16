<?php

namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\api\dtos\outputs\ProfileDTO;
use toubilib\core\application\ports\api\servicesInterfaces\AuthzServiceInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;

final class AuthzService implements AuthzServiceInterface
{
    public function __construct(
        private RdvRepositoryInterface $rdvRepository
    ) {}

    public function canAccessPraticienAgenda(ProfileDTO $user, string $praticienId): bool
    {
        // Praticien peut voir son propre agenda
        if ($user->role === 10 && $user->ID === $praticienId) {
            return true;
        }
        // Les patients peuvent voir l'agenda (créneaux disponibles)
        return true;
    }

    public function canAccessRdvDetails(ProfileDTO $user, string $rdvId): bool
    {
        $rdv = $this->rdvRepository->getById($rdvId);

        // Praticien propriétaire du RDV
        if ($user->role === 10 && $rdv->getPraticienId() === $user->ID) {
            return true;
        }

        // Patient propriétaire du RDV
        if ($user->role === 1 && $rdv->getPatientId() === $user->ID) {
            return true;
        }

        return false;
    }

    public function canCancelRdv(ProfileDTO $user, string $rdvId): bool
    {
        return $this->canAccessRdvDetails($user, $rdvId);
    }

    public function canCreateRdv(ProfileDTO $user): bool
    {
        // Seuls les praticiens peuvent créer des RDV
        return $user->role === 10;
    }
}