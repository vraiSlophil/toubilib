<?php

namespace toubilib\core\application\usecases;


use toubilib\core\application\ports\api\dtos\outputs\PraticienDetailDTO;
use toubilib\core\application\ports\api\dtos\outputs\PraticienDTO;
use toubilib\core\application\ports\api\servicesInterfaces\ServicePraticienInterface;
use toubilib\core\application\ports\spi\adapterInterface\MonologLoggerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;

class ServicePraticien implements ServicePraticienInterface
{
    private PraticienRepositoryInterface $praticienRepository;
    private MonologLoggerInterface $MonologLogger;

    public function __construct(PraticienRepositoryInterface $praticienRepository, MonologLoggerInterface $MonologLogger)
    {
        $this->praticienRepository = $praticienRepository;
        $this->MonologLogger = $MonologLogger;
    }

    public function listerPraticiens(): array
    {
       $praticiens = $this->praticienRepository->getAllPraticiens();

        return array_map(
            fn($praticien) => PraticienDTO::fromEntity($praticien),
            $praticiens
        );
    }

    public function getPraticienDetail(string $id): ?PraticienDetailDTO
    {
        $detail = $this->praticienRepository->findDetailById($id);
        return $detail ? PraticienDetailDTO::fromEntity($detail) : null;
    }
}