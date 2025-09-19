<?php

namespace toubilib\api\actions;

use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;
use toubilib\infra\adapters\SlimStyleOutputFormatter;

final class ListerCreneauxPrisAction
{
    public function __construct(private ServiceRdvInterface $service)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $pid = $args['praticienId'] ?? '';
        $q = $request->getQueryParams();
        if (empty($q['debut']) || empty($q['fin'])) {
            return SlimStyleOutputFormatter::error(
                $response,
                'Invalid date range'
            );
        }
        try {
            $debut = new DateTimeImmutable($q['debut']);
            $fin = new DateTimeImmutable($q['fin']);
        } catch (Throwable) {
            return SlimStyleOutputFormatter::error(
                $response,
                'Invalid date range'
            );
        }
        if ($debut > $fin) {
            return SlimStyleOutputFormatter::error(
                $response,
                'Invalid date range'
            );
        }
        $slots = $this->service->listCreneauxPris($pid, $debut, $fin);
        return SlimStyleOutputFormatter::success(
            $response,
            $slots
        );
    }
}