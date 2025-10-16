<?php

namespace toubilib\api\middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use toubilib\core\application\usecases\AuthzService;
use toubilib\infra\adapters\ApiResponseBuilder;

final class AuthzMiddleware
{
    public function __construct(
        private AuthzService $authzService,
        private string       $operation // 'viewAgenda', 'viewRdv', 'cancelRdv', 'createRdv'
    )
    {
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $auth = $request->getAttribute('authenticated_user');

        if (!$auth) {
            return ApiResponseBuilder::create()
                ->status(401)
                ->error('Unauthorized')
                ->build(new Response());
        }

        $authorized = match ($this->operation) {
            'viewAgenda' => $this->authzService->canAccessPraticienAgenda(
                $auth,
                $request->getAttribute('praticienId')
            ),
            'viewRdv' => $this->authzService->canAccessRdvDetails(
                $auth,
                $request->getAttribute('rdvId')
            ),
            'cancelRdv' => $this->authzService->canCancelRdv(
                $auth,
                $request->getAttribute('rdvId')
            ),
            'createRdv' => $this->authzService->canCreateRdv($auth),
            default => false
        };

        if (!$authorized) {
            return ApiResponseBuilder::create()
                ->status(403)
                ->error('Forbidden: insufficient permissions')
                ->build(new Response());
        }

        return $handler->handle($request);
    }
}