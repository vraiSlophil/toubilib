<?php

namespace toubilib\core\application\ports\api\dtos;

class PraticienDTO
{
    public string $id;
    public string $nom;
    public string $prenom;
    public string $ville;
    public string $titre;
    public string $specialite;
    public bool $accepteNouveauPatient;

    public function __construct(
        string $id,
        string $nom,
        string $prenom,
        string $ville,
        string $titre,
        string $specialite,
        bool $accepteNouveauPatient
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->ville = $ville;
        $this->titre = $titre;
        $this->specialite = $specialite;
        $this->accepteNouveauPatient = $accepteNouveauPatient;
    }
}