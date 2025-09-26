<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Exception;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;
use toubilib\infra\adapters\SlimStyleOutputFormatter;

final class AnnulerRdvAction
{
    public function __construct(private ServiceRdvInterface $serviceRdv) {}

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $rdvId = $args['rdvId'] ?? null;
        if (!$rdvId) {
            return SlimStyleOutputFormatter::error($response, 'Bad Request: missing rdvId parameter');
        }
        try {
            $this->serviceRdv->annulerRendezVous($rdvId);
        } catch (Exception $e) {
            return SlimStyleOutputFormatter::error($response, 'Internal error', $e, 500);
        }
        return SlimStyleOutputFormatter::success($response, null, 204);
    }
}

