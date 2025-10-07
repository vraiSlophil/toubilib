<?php

namespace toubilib\api\providers\auth;



interface AuthnProviderInterface
{
    public function registerCredentials(CredentialsDTO $credentials, int $role): void;
    public function signinCredentials(CredentialsDTO $credentials): AuthDTO;
    public function refreshToken(string $token): AuthDTO;
    public function getSignedInUser(string $token): AuthDTO;
}