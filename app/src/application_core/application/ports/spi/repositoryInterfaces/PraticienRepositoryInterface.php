<?php

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

interface PraticienRepositoryInterface
{
    public function getAllPraticiens(): array;
    public function findDetailById(string $id): ?PraticienDetailDTO;
}