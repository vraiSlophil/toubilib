<?php

namespace toubilib\core\domain\dto;

final class CredentialsDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {}
}