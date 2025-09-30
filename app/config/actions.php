<?php

use toubilib\api\actions\GetPraticienAction;
use toubilib\api\actions\getRdvAction;
use toubilib\api\actions\ListBookedSlotsAction;
use toubilib\api\actions\ListPraticiensAction;
use toubilib\core\application\ports\api\servicesInterfaces\ServicePraticienInterface;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;

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

    getRdvAction::class => static function ($c) {
        return new getRdvAction(
            $c->get(ServiceRdvInterface::class)
        );
    },

    ListBookedSlotsAction::class => static function ($c) {
        return new ListBookedSlotsAction(
            $c->get(ServiceRdvInterface::class),
        );
    }

];