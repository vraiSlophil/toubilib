<?php

namespace toubilib\api\middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use toubilib\api\providers\auth\AuthnProviderInterface;

final class AuthnMiddleware
{
    public function __construct(
        private AuthnProviderInterface $authnProvider
    ) {}

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $token = $this->extractToken($request);
        if (!$token) {
            return ApiResponseBuilder::create()->status(401)->error('Missing token')->build($response);
        }

        $authDTO = $this->authnProvider->getSignedInUser($token);
        return $handler->handle($request->withAttribute('auth', $authDTO));
    }
}