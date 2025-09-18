<?php

namespace toubilib\api\actions;

use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;

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
            $response->getBody()->write(json_encode(['error' => 'Missing date parameters']));
            return $response->withStatus(400);
        }
        try {
            $debut = new DateTimeImmutable($q['debut']);
            $fin = new DateTimeImmutable($q['fin']);
        } catch (Throwable) {
            $response->getBody()->write(json_encode(['error' => 'Invalid date format']));
            return $response->withStatus(400);
        }
        if ($debut > $fin) {
            $response->getBody()->write(json_encode(['error' => 'Invalid date range']));
            return $response->withStatus(400);
        }
        $slots = $this->service->listCreneauxPris($pid, $debut, $fin);
        $payload = json_encode($slots, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}