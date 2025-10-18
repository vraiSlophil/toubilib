<?php

use toubilib\api\middlewares\AuthnMiddleware;
use toubilib\api\middlewares\AuthzMiddleware;
use toubilib\api\providers\auth\JwtAuthProvider;
use toubilib\api\providers\auth\JwtManager;
use toubilib\core\application\ports\api\providersInterfaces\AuthProviderInterface;
use toubilib\core\application\ports\api\providersInterfaces\JwtManagerInterface;
use toubilib\core\application\ports\api\servicesInterfaces\ServicePraticienInterface;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;
use toubilib\core\application\ports\spi\adapterInterface\MonologLoggerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\AuthRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\application\usecases\AuthnService;
use toubilib\core\application\usecases\AuthzService;
use toubilib\core\application\usecases\ServicePraticien;
use toubilib\core\application\usecases\ServiceRdv;
use toubilib\infra\repositories\PDOAuthRepository;
use toubilib\infra\repositories\PDOPraticienRepository;
use toubilib\infra\repositories\PDORdvRepository;
use function DI\get;

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

    AuthnService::class => static function ($c) {
        return new AuthnService($c->get(AuthRepositoryInterface::class));
    },

    AuthzService::class => static function ($c) {
        return new AuthzService($c->get(RdvRepositoryInterface::class));
    },

    JwtManagerInterface::class => static function ($c) {
        $jwt = $c->get('jwt');  // 👈 Récupère le tableau depuis settings.php
        return new JwtManager(
            $jwt['secret'],
            $jwt['algo'],
            (int)$jwt['access_expiration'],
            (int)$jwt['refresh_expiration']
        );
    },

    AuthProviderInterface::class => static function ($c) {
        return new JwtAuthProvider(
            $c->get(AuthnService::class),
            $c->get(JwtManagerInterface::class)
        );
    },

    // --- Repositories ---
    PraticienRepositoryInterface::class => static function ($c) {
        return new PDOPraticienRepository(
            $c->get('db.praticien'),
            $c->get(RdvRepositoryInterface::class),
        );
    },

    RdvRepositoryInterface::class => static function ($c) {
        return new PDORdvRepository(
            $c->get('db.rdv'),
        );
    },

    AuthRepositoryInterface::class => static function ($c) {
        return new PDOAuthRepository(
            $c->get('db.authentification'),
        );
    },

    // --- Middlewares ---

    AuthnMiddleware::class => function ($c) {
        return new AuthnMiddleware(
            $c->get(AuthProviderInterface::class)
        );
    },
];