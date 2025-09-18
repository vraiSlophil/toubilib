<?php
namespace toubilib\core\application\ports\spi\repositoryInterfaces;
use DateTimeImmutable;
use toubilib\core\domain\entities\Rdv;

interface RdvRepositoryInterface
{
    public function getById(string $rdvId): ?Rdv;

    public function listForPraticienBetween(string $praticienId, DateTimeImmutable $debut, DateTimeImmutable $fin): array;
}