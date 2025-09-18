<?php

namespace toubilib\core\application\usecases;


use toubilib\core\application\ports\api\dtos\PraticienDTO;
use toubilib\core\application\ports\api\servicesInterfaces\ServicePraticienInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\application\ports\api\dtos\PraticienDetailDTO;

class ServicePraticien implements ServicePraticienInterface
{
    private PraticienRepositoryInterface $praticienRepository;

    public function __construct(PraticienRepositoryInterface $praticienRepository)
    {
        $this->praticienRepository = $praticienRepository;
    }

    public function listerPraticiens(): array
    {
        $praticiens = $this->praticienRepository->getAllPraticiens();

        $praticiensDTO = [];
        foreach ($praticiens as $praticien) {
            $praticiensDTO[] = new PraticienDTO(
                (string)$praticien->getId(),
                $praticien->getNom(),
                $praticien->getPrenom(),
                $praticien->getVille(),
                $praticien->getTitre(),
                $praticien->getSpecialite() ? $praticien->getSpecialite()->getLibelle() : 'Non spécifiée',
                $praticien->isAccepteNouveauPatient()
            );
        }
        return $praticiensDTO;
    }

    public function getPraticienDetail(string $id): ?PraticienDetailDTO
    {
        $detail = $this->praticienRepository->findDetailById($id);
        if (!$detail) {
            return null;
        }

        $specialite = $detail->getSpecialite();
        $structure = $detail->getStructure();

        return new PraticienDetailDTO(
            id: $detail->getId(),
            nom: $detail->getNom(),
            prenom: $detail->getPrenom(),
            titre: $detail->getTitre(),
            email: $detail->getEmail(),
            telephone: $detail->getTelephone(),
            ville: $detail->getVille(),
            rppsId: $detail->getRppsId(),
            organisation: $detail->isOrganisation(),
            nouveauPatient: $detail->isNouveauPatient(),
            specialite: [
//                'id' => $specialite->getId(),
                'libelle' => $specialite->getLibelle(),
                'description' => $specialite->getDescription(),
            ],
            structure: $structure ? [
                'id' => $structure->getId(),
                'nom' => $structure->getNom(),
                'adresse' => $structure->getAdresse(),
                'ville' => $structure->getVille(),
                'code_postal' => $structure->getCodePostal(),
                'telephone' => $structure->getTelephone(),
            ] : null,
            motifs: array_map(static fn($m) => [
//                'id' => $m->getId(),
                'libelle' => $m->getLibelle()
            ],
                $detail->getMotifs()),
            moyens: array_map(static fn($m) => [
//                'id' => $m->getId(),
                'libelle' => $m->getLibelle()
            ],
                $detail->getMoyens())
        );
    }
}