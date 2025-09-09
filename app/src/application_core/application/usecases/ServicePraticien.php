<?php

namespace toubilib\core\application\usecases;


use toubilib\core\application\ports\api\PraticienDTO;
use toubilib\core\application\ports\api\ServicePraticienInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;

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
}