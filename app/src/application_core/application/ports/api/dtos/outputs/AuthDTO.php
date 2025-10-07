<?php

namespace toubilib\core\domain\dto;

final class AuthDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly string $email,
        public readonly int $role,
        public readonly string $jwt,
        public readonly ?string $refreshToken = null
    ) {}
}