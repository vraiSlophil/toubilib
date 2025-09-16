<?php
namespace App\Api\Actions;

use App\ApplicationCore\Application\Ports\Api\ServiceRdvInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ConsulterRdvAction
{
    public function __construct(private ServiceRdvInterface $service) {}

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $args['rdvId'] ?? '';
        $dto = $this->service->getRdvById($id);
        if (!$dto) {
            return $response->withStatus(404);
        }
        $payload = json_encode($dto, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}