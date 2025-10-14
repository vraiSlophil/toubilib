<?php

namespace toubilib\api\middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use toubilib\infra\adapters\ApiResponseBuilder;

final class AuthzMiddleware
{
    public function __construct(private int $minRole) {}

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $auth = $request->getAttribute('authenticated_user');
        if (!$auth) {
            return ApiResponseBuilder::create()->status(401)->error('Unauthorized')->build(new Response());
        }
        if ($auth->role < $this->minRole) {
            return ApiResponseBuilder::create()->status(403)->error('Forbidden: insufficient role')->build(new Response());
        }
        return $handler->handle($request);
    }
}