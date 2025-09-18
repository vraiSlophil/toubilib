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
        return $this->repo->findDetailById($id);
    }
}