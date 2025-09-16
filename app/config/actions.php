<?php

use toubilib\api\actions\ListerPraticiensAction;
use toubilib\core\application\ports\api\servicesInterfaces\ServicePraticienInterface;

return [

    ListerPraticiensAction::class => static function ($c) {
        return new ListerPraticiensAction(
            $c->get(ServicePraticienInterface::class)
        );
    },

];