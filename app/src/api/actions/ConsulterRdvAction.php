<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;

final class ConsulterRdvAction
{
    public function __construct(private ServiceRdvInterface $service)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $args['rdvId'] ?? '';
        $dto = $this->service->getRdvById($id);
        if (!$dto) {
            $response->getBody()->write(json_encode(['error' => 'No RDV found with this id']));
            return $response->withStatus(404);
        }
        $payload = json_encode($dto, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}