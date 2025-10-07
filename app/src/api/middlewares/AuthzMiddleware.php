<?php

namespace toubilib\api\middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use toubilib\infra\adapters\ApiResponseBuilder;

final class AuthzMiddleware
{
    public function __construct(private array $allowedRoles) {}

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $auth = $request->getAttribute('auth');
        if (!in_array($auth->role, $this->allowedRoles)) {
            return ApiResponseBuilder::create()->status(403)->error('Forbidden')->build($response);
        }
        return $handler->handle($request);
    }
}