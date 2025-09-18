<?php

use toubilib\api\actions\AfficherPraticienAction;
use toubilib\api\actions\ConsulterRdvAction;
use toubilib\api\actions\ListerCreneauxPrisAction;
use toubilib\api\actions\ListerPraticiensAction;
use toubilib\core\application\ports\api\servicesInterfaces\ServicePraticienInterface;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;

return [

    ListerPraticiensAction::class => static function ($c) {
        return new ListerPraticiensAction(
            $c->get(ServicePraticienInterface::class)
        );
    },

    AfficherPraticienAction::class => static function ($c) {
        return new AfficherPraticienAction(
            $c->get(ServicePraticienInterface::class)
        );
    },

    ConsulterRdvAction::class => static function ($c) {
        return new ConsulterRdvAction(
            $c->get(ServiceRdvInterface::class)
        );
    },

    ListerCreneauxPrisAction::class => static function ($c) {
        return new ListerCreneauxPrisAction(
            $c->get(ServiceRdvInterface::class)
        );
    }

];