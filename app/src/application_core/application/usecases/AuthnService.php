<?php

namespace toubilib\core\application\usecases;

use Exception;
use toubilib\core\application\ports\api\dtos\outputs\ProfileDTO;
use toubilib\core\application\ports\api\dtos\inputs\CredentialsDTO;
use toubilib\core\application\ports\spi\repositoryInterfaces\AuthRepositoryInterface;
use toubilib\core\application\ports\api\servicesInterfaces\AuthnServiceInterface;
use toubilib\core\domain\entities\User;
use toubilib\core\domain\exceptions\AuthenticationFailedException;
use toubilib\core\domain\exceptions\RepositoryEntityNotFoundException;

final class AuthnService implements AuthnServiceInterface
{

    public function __construct(
        private AuthRepositoryInterface $authRepository
    )
    {
    }

    public function authenticate(CredentialsDTO $credentials): ProfileDTO
    {
        try {
            $user = $this->authRepository->byEmail($credentials->email);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new AuthenticationFailedException('Invalid credentials');
        }

        if (password_verify($credentials->password, $user->getPassword())) {
            return new ProfileDTO($user->getID(), $user->getEmail(), $user->getRole());
        }
        throw new AuthenticationFailedException('Invalid credentials');

    }

    public function register(CredentialsDTO $credentials, int $role): profileDTO
    {
        try {
            $user = new User(null, $credentials->email, $credentials->password, $role);
            $id = $this->authRepository->save($user);
            $user->setId($id);
        } catch (Exception $e) {
            throw new AuthenticationFailedException('Registration failed: ' . $e->getMessage());
        }
        return new ProfileDTO($user->getID(), $user->getEmail(), $user->getRole());
    }
}