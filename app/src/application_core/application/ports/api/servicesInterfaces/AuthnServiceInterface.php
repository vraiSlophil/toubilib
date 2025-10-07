<?php

namespace toubilib\core\application\ports\api\servicesInterfaces;

interface AuthnServiceInterface
{
    public function authenticate(string $email, string $password): AuthDTO;
    public function register(string $email, string $password, int $role): void;
}