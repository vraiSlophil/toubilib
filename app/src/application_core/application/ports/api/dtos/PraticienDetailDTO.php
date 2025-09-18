<?php
namespace toubilib\core\application\ports\api\dtos;

final class PraticienDetailDTO
{
    public function __construct(
        public string $id,
        public string $nom,
        public string $prenom,
        public string $titre,
        public string $email,
        public string $telephone,
        public string $ville,
        public ?string $rppsId,
        public bool $organisation,
        public bool $nouveauPatient,
        public array $specialite, // [ id, libelle, description ]
        public ?array $structure, // [ id, nom, adresse, ville, code_postal, telephone ]|null
        public array $motifs,     // [ { id, libelle }, ... ]
        public array $moyens      // [ { id, libelle }, ... ]
    ) {}
}