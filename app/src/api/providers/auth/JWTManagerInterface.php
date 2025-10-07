<?php

namespace toubilib\api\providers\auth;

interface JWTManagerInterface
{
    public function createAccessToken(array $payload): string;
    public function createRefreshToken(array $payload): string;
    public function decodeToken(string $token): array;
}