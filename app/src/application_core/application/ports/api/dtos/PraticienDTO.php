<?php

namespace toubilib\core\application\ports\api\dtos;

use toubilib\core\domain\entities\Praticien;

class PraticienDTO
{
    public string $id;
    public string $nom;
    public string $prenom;
    public string $ville;
    public string $titre;
    public string $specialite;
    public bool $accepteNouveauPatient;

    public function __construct(Praticien $praticien) {
        $this->id = $praticien->getId();
        $this->nom = $praticien->getNom();
        $this->prenom = $praticien->getPrenom();
        $this->ville = $praticien->getVille();
        $this->titre = $praticien->getTitre();
        $this->specialite = $praticien->getSpecialite()->getLibelle();
        $this->accepteNouveauPatient = $praticien->isAccepteNouveauPatient();
    }
}