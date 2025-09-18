<?php

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

use toubilib\core\application\ports\api\dtos\PraticienDetailDTO;

interface PraticienRepositoryInterface
{
    public function getAllPraticiens(): array;

    public function findDetailById(string $id): ?PraticienDetailDTO;
}