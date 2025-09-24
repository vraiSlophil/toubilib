<?php

namespace toubilib\core\application\ports\api\dtos\outputs;

use toubilib\core\domain\entities\Praticien;

final class PraticienDTO
{
    public function __construct(
        public string $id,
        public string $nom,
        public string $prenom,
        public string $ville,
        public string $titre,
        public string $specialite,
        public bool $accepteNouveauPatient,
    )
    {
    }

    public static function fromEntity(Praticien $e): self
    {
        return new self(
            $e->getId(),
            $e->getNom(),
            $e->getPrenom(),
            $e->getVille(),
            $e->getTitre(),
            $e->getSpecialite()->getLibelle(),
            $e->isAccepteNouveauPatient()
        );
    }

}