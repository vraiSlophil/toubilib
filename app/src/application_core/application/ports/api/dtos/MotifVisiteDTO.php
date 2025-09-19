<?php
// toubilib/src/application_core/application/ports/api/dtos/MotifVisiteDTO.php
namespace toubilib\core\application\ports\api\dtos;

use JsonSerializable;
use toubilib\core\domain\entities\MotifVisite;

final class MotifVisiteDTO implements JsonSerializable
{
    public function __construct(
        public int $id,
        public string $libelle,
        public ?string $description = null
    ) {}

    public static function fromEntity(MotifVisite $e): self
    {
        // Adaptez si votre entité a d’autres champs (ex: durée, code, etc.)
        $id = $e->getId();
        $lib = $e->getLibelle();

        return new self($id, $lib);
    }

    public function toArray(): array
    {
        return [
            'libelle' => $this->libelle,
        ];
    }

    public static function listToArray(iterable $entities): array
    {
        $out = [];
        foreach ($entities as $e) {
            $out[] = self::fromEntity($e)->toArray();
        }
        return $out;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
