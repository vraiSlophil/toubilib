<?php

use toubilib\core\application\ports\api\servicesInterfaces\ServicePraticienInterface;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;
use toubilib\core\application\ports\spi\adapterInterface\MonologLoggerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\application\usecases\ServicePraticien;
use toubilib\core\application\usecases\ServiceRdv;
use toubilib\infra\repositories\PDOPraticienRepository;
use toubilib\infra\repositories\PDORdvRepository;

return [
    // --- Services ---
    ServicePraticienInterface::class => static function ($c) {
        return new ServicePraticien(
            $c->get(PraticienRepositoryInterface::class),
            $c->get(MonologLoggerInterface::class)
        );
    },
    ServiceRdvInterface::class => static function ($c) {
        return new ServiceRdv(
            $c->get(RdvRepositoryInterface::class),
            $c->get(PraticienRepositoryInterface::class),
            $c->get(MonologLoggerInterface::class)
        );
    },

    // --- Repositories ---
    PraticienRepositoryInterface::class => static function ($c) {
        return new PDOPraticienRepository(
            $c->get('db.praticien'),
            $c->get(MonologLoggerInterface::class),
            $c->get(RdvRepositoryInterface::class)
        );
    },

    RdvRepositoryInterface::class => static function ($c) {
        return new PDORdvRepository(
            $c->get('db.rdv'),
        );
    },

    // --- Middlewares ---

    AuthnMiddleware::class => function (ContainerInterface $c) {
        return new AuthnMiddleware(
            $c->get(AuthnProviderInterface::class)
        );
    },

    // AuthzMiddleware nécessite une factory car il a des paramètres variables
    'AuthzMiddleware.praticien' => function (ContainerInterface $c) {
        return new AuthzMiddleware([1]); // 1 = role praticien
    },

    'AuthzMiddleware.patient' => function (ContainerInterface $c) {
        return new AuthzMiddleware([2]); // 2 = role patient
    },

    'AuthzMiddleware.all' => function (ContainerInterface $c) {
        return new AuthzMiddleware([1, 2]); // tous les rôles
    },

];