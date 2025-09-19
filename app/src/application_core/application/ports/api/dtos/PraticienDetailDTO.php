<?php

namespace toubilib\core\application\ports\api\dtos;

use toubilib\core\domain\entities\MotifVisite;
use toubilib\core\domain\entities\MoyenPaiement;
use toubilib\core\domain\entities\PraticienDetail;
use toubilib\core\domain\entities\Structure;

final class PraticienDetailDTO
{

    public string $id;
    public string $nom;
    public string $prenom;
    public string $titre;
    public string $email;
    public string $telephone;
    public string $ville;
    public ?string $rppsId;
    public bool $organisation;
    public bool $nouveauPatient;
    public SpecialiteDTO $specialite;
    public StructureDTO $structure;
    public array $motifs;
    public array $moyens;

    public function __construct(PraticienDetail $praticien)
    {
        $this->id = $praticien->getId();
        $this->nom = $praticien->getNom();
        $this->prenom = $praticien->getPrenom();
        $this->titre = $praticien->getTitre();
        $this->email = $praticien->getEmail();
        $this->telephone = $praticien->getTelephone();
        $this->ville = $praticien->getVille();
        $this->rppsId = $praticien->getRppsId();
        $this->organisation = $praticien->isOrganisation();
        $this->nouveauPatient = $praticien->isNouveauPatient();
        $this->specialite = SpecialiteDTO::fromEntity($praticien->getSpecialite()) ?? null;
        $this->structure = StructureDTO::fromEntity($praticien->getStructure());
        $this->motifs = array_map(fn(MotifVisite $m) => MotifVisiteDTO::fromEntity($m), $praticien->getMotifs());
        $this->moyens = array_map(fn(MoyenPaiement $m) => MoyenPaiementDTO::fromEntity($m), $praticien->getMoyens());
    }
}