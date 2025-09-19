<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;
use toubilib\infra\adapters\SlimStyleOutputFormatter;

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
            return SlimStyleOutputFormatter::error(
                $response,
                'Rdv not found',
                null,
                404
            );
        }
        return SlimStyleOutputFormatter::success(
            $response,
            $dto
        );
    }
}