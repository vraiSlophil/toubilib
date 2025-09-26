<?php

namespace toubilib\api\actions;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\ports\api\servicesInterfaces\ServicePraticienInterface;
use toubilib\infra\adapters\SlimStyleOutputFormatter;

final class ListerPraticiensAction
{
    private ServicePraticienInterface $servicePraticien;

    public function __construct(ServicePraticienInterface $servicePraticien)
    {
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $praticiens = $this->servicePraticien->listerPraticiens();

            return SlimStyleOutputFormatter::success(
                $response,
                $praticiens
            );

        } catch (Exception $e) {
            return SlimStyleOutputFormatter::error(
                $response,
                'An error occurred while fetching praticiens.',
                $e,
                500
            );
        }
    }
}