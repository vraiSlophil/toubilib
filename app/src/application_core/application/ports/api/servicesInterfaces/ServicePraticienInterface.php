<?php

namespace toubilib\core\application\ports\api\servicesInterfaces;

use toubilib\core\application\ports\api\PraticienDetailDTO;

interface ServicePraticienInterface
{
    public function listerPraticiens(): array;
    public function getPraticienDetail(string $id): ?PraticienDetailDTO;

}