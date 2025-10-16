<?php

use toubilib\api\actions\auth\SigninAction;
use toubilib\api\actions\CancelRdvAction;
use toubilib\api\actions\CreateRdvAction;
use toubilib\api\actions\GetPraticienAction;
use toubilib\api\actions\getRdvAction;
use toubilib\api\actions\ListBookedSlotsAction;
use toubilib\api\actions\ListPraticiensAction;
use toubilib\core\application\ports\api\providersInterfaces\AuthProviderInterface;
use toubilib\core\application\ports\api\servicesInterfaces\ServicePraticienInterface;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;
use toubilib\core\application\ports\spi\adapterInterface\MonologLoggerInterface;

return [

    ListPraticiensAction::class => static function ($c) {
        return new ListPraticiensAction(
            $c->get(ServicePraticienInterface::class)
        );
    },

    GetPraticienAction::class => static function ($c) {
        return new GetPraticienAction(
            $c->get(ServicePraticienInterface::class)
        );
    },

    CreateRdvAction::class => static function ($c) {
        return new CreateRdvAction(
            $c->get(ServiceRdvInterface::class),
        );
    },

    GetRdvAction::class => static function ($c) {
        return new GetRdvAction(
            $c->get(ServiceRdvInterface::class)
        );
    },

    CancelRdvAction::class => static function ($c) {
        return new CancelRdvAction(
            $c->get(ServiceRdvInterface::class),
            $c->get(MonologLoggerInterface::class)
        );
    },

    ListBookedSlotsAction::class => static function ($c) {
        return new ListBookedSlotsAction(
            $c->get(ServiceRdvInterface::class),
        );
    },

    SigninAction::class => static function ($c) {
        return new SigninAction(
            $c->get(AuthProviderInterface::class)
        );
    },

    SignupAction::class => static function ($c) {
        return new SignupAction(
            $c->get(AuthProviderInterface::class)
        );
    },

];