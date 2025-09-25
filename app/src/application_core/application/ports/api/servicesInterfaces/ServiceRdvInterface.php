<?php

namespace toubilib\core\application\ports\api\servicesInterfaces;

use toubilib\core\application\ports\api\dtos\inputs\InputRendezVousDTO;
use toubilib\core\application\ports\api\dtos\outputs\RendezVousDTO;

interface ServiceRdvInterface
{
    public function getRdvById(string $rdvId): ?RendezVousDTO;

    public function listCreneauxPris(string $praticienId, \DateTimeImmutable $debut, \DateTimeImmutable $fin): array;

    public function creerRdv(InputRendezVousDTO $input): string;
}