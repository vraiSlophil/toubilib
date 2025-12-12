<?php

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

use toubilib\core\domain\entities\PraticienDetail;

interface PraticienRepositoryInterface
{
    public function getAllPraticiens(): array;

    public function findDetailById(string $id): ?PraticienDetail;
}