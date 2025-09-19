<?php

namespace toubilib\core\application\usecases;


use toubilib\core\application\ports\api\dtos\PraticienDTO;
use toubilib\core\application\ports\api\servicesInterfaces\ServicePraticienInterface;
use toubilib\core\application\ports\spi\adapterInterface\MonologLoggerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\application\ports\api\dtos\PraticienDetailDTO;
use toubilib\infra\adapters\MonologLogger;

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
            fn($praticien) => new PraticienDTO($praticien),
            $praticiens
        );
    }

    public function
    getPraticienDetail(string $id): ?PraticienDetailDTO
    {
        $detail = $this->praticienRepository->findDetailById($id);
        $this->MonologLogger->debug(print_r($detail, true));
        return $detail ? new PraticienDetailDTO($detail) : null;
    }
}