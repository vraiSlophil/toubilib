<?php

namespace toubilib\api\actions;

use DateMalformedStringException;
use DateTimeImmutable;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;
use toubilib\core\domain\entities\Rdv;
use toubilib\infra\adapters\SlimStyleOutputFormatter;

class CreerRdvAction
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
                $response->withStatus(500),
                'Internal error.',
                $e
            );
        }

        return SlimStyleOutputFormatter::success(
            $response->withStatus(201),
            ['rdv_id' => $rdvId]
        );

    }

}