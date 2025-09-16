<?php

use toubilib\core\application\ports\api\servicesInterfaces\ServicePraticienInterface;
use toubilib\core\application\ports\spi\adapterInterface\MonologLoggerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\application\usecases\ServicePraticien;
use toubilib\infra\repositories\PDOPraticienRepository;

return [
    // --- Services ---
    ServicePraticienInterface::class => static function ($c) {
        return new ServicePraticien(
            $c->get(PraticienRepositoryInterface::class)
        );
    },

    // --- Repositories ---
    PraticienRepositoryInterface::class => static function ($c) {
        return new PDOPraticienRepository(
            $c->get('db.praticien'),
            $c->get(MonologLoggerInterface::class)
        );
    },

];