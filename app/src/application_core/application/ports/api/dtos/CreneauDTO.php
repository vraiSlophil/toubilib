<?php
namespace App\ApplicationCore\Application\Ports\Api;

use App\ApplicationCore\Domain\Entities\Rdv\Rdv;

final class CreneauDTO
{
    public function __construct(
        public string $id,
        public string $start, // ISO‑8601
        public string $end    // ISO‑8601
    ) {}

    public static function fromRdv(Rdv $e): self
    {
        return new self(
            $e->getId(),
            $e->getDebut()->format(\DateTimeInterface::ATOM),
            $e->getFin()->format(\DateTimeInterface::ATOM)
        );
    }
}