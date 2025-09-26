<?php

namespace toubilib\api\actions;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;
use toubilib\infra\adapters\SlimStyleOutputFormatter;

final class CreerRdvAction
{
    public function __construct(private ServiceRdvInterface $serviceRdv)
    {
    }
    
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $inputRdv = $request->getAttribute('input_rdv');
        if ($inputRdv === null) {
            return SlimStyleOutputFormatter::error(
                $response->withStatus(500),
                'Internal error: missing input_rdv attribute.'
            );
        }

        try {
            $rdvId = $this->serviceRdv->creerRdv($inputRdv);
        } catch (Exception $e) {
            return SlimStyleOutputFormatter::error(
                $response,
                $e->getMessage(),
                $e
            );
        }

        return SlimStyleOutputFormatter::success(
            $response,
            ['rdv_id' => $rdvId],
            201
        );

    }

}