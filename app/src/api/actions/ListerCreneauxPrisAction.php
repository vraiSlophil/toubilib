<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
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
            return $response->withStatus(400);
        }
        try {
            $debut = new \DateTimeImmutable($q['debut']);
            $fin = new \DateTimeImmutable($q['fin']);
        } catch (\Throwable) {
            return $response->withStatus(400);
        }
        if ($debut > $fin) {
            return $response->withStatus(400);
        }
        $slots = $this->service->listCreneauxPris($pid, $debut, $fin);
        $payload = json_encode($slots, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}