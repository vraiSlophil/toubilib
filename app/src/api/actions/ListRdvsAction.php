<?php

declare(strict_types=1);

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\api\dtos\outputs\ProfileDTO;
use toubilib\core\application\ports\api\dtos\outputs\RendezVousDTO;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;
use toubilib\infra\adapters\ApiResponseBuilder;

final class ListRdvsAction
{
    public function __construct(private ServiceRdvInterface $serviceRdv) {}

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var ProfileDTO $user */
        $user = $request->getAttribute('authenticated_user');
        $rdvs = $this->serviceRdv->listRdvsForUser($user);

        $data = array_map(fn(RendezVousDTO $dto) => $dto->jsonSerialize() + [
            '_links' => [
                'self' => ['href' => '/api/rdvs/' . $dto->id],
                'cancel' => ['href' => '/api/rdvs/' . $dto->id, 'method' => 'DELETE']
            ]
        ], $rdvs);

        return ApiResponseBuilder::create()
            ->status(200)
            ->data($data)
            ->links(['self' => ['href' => '/api/rdvs']])
            ->build($response);
    }
}
