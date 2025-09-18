<?php
namespace toubilib\core\domain\entities;

final class PraticienDetail
{
    public function __construct(
        private string $id,
        private string $nom,
        private string $prenom,
        private string $titre,
        private string $email,
        private string $telephone,
        private string $ville,
        private ?string $rppsId,
        private bool $organisation,
        private bool $nouveauPatient,
        private Specialite $specialite,
        private ?Structure $structure,
        /** @var MotifVisite[] */
        private array $motifs,
        /** @var MoyenPaiement[] */
        private array $moyens
    ) {}

    public function getId(): string { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getTitre(): string { return $this->titre; }
    public function getEmail(): string { return $this->email; }
    public function getTelephone(): string { return $this->telephone; }
    public function getVille(): string { return $this->ville; }
    public function getRppsId(): ?string { return $this->rppsId; }
    public function isOrganisation(): bool { return $this->organisation; }
    public function isNouveauPatient(): bool { return $this->nouveauPatient; }
    public function getSpecialite(): Specialite { return $this->specialite; }
    public function getStructure(): ?Structure { return $this->structure; }
    /** @return MotifVisite[] */
    public function getMotifs(): array { return $this->motifs; }
    /** @return MoyenPaiement[] */
    public function getMoyens(): array { return $this->moyens; }
}
