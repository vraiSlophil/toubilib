<?php

namespace toubilib\api\middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Psr7\Response;
use toubilib\core\application\ports\api\providersInterfaces\AuthProviderInterface;
use toubilib\core\domain\exceptions\AuthProviderExpiredAccessToken;
use toubilib\core\domain\exceptions\AuthProviderInvalidAccessToken;
use toubilib\infra\adapters\ApiResponseBuilder;

class AuthnMiddleware
{
    private AuthProviderInterface $authProvider;

    public function __construct(AuthProviderInterface $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token_line = $request->hasHeader('Authorization')
            ? $request->getHeaderLine('Authorization')
            : ApiResponseBuilder::create()->error('missing authorization header', throw new HttpUnauthorizedException($request, "missing authorization header"))->status(400)->build(new Response());

        $token = sscanf($token_line, "Bearer %s")[0];

        try {
            $authDto = $this->authProvider->getSignedInUser($token);
        } catch (AuthProviderInvalidAccessToken $e) {
            throw new HttpUnauthorizedException($request, "invalid jwt token");
        } catch (AuthProviderExpiredAccessToken $e) {
            throw new HttpUnauthorizedException($request, "expired jwt token");
        }

        $request = $request->withAttribute('authenticated_user', $authDto);
        $response = $handler->handle($request);
        return $response;
    }
}