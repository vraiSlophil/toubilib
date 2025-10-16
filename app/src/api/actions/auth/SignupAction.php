<?php

namespace toubilib\api\actions\auth;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use toubilib\core\application\ports\api\dtos\inputs\CredentialsDTO;
use toubilib\core\application\ports\api\providersInterfaces\AuthProviderInterface;
use toubilib\core\domain\exceptions\AuthenticationFailedException;
use toubilib\infra\adapters\ApiResponseBuilder;

class SignupAction
{
    private AuthProviderInterface $authProvider;

    public function __construct(AuthProviderInterface $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (empty($data['email']) || empty($data['password'])) {
            throw new HttpBadRequestException($request, 'Email and password are required');
        }

        // Par défaut, les nouveaux comptes sont des patients (rôle 1)
        $role = $data['role'] ?? 1;

        // Optionnel : restreindre la création de praticiens
        if ($role === 10) {
            throw new HttpBadRequestException($request, 'Cannot register as practitioner');
        }

        $credentials = new CredentialsDTO($data['email'], $data['password']);

        try {
            $profile = $this->authProvider->register($credentials, $role);
        } catch (AuthenticationFailedException $e) {
            throw new HttpBadRequestException($request, $e->getMessage());
        }

        return ApiResponseBuilder::create()
            ->status(201)
            ->data([
                'id' => $profile->ID,
                'email' => $profile->email,
                'role' => $profile->role
            ])
            ->build($response);
    }
}