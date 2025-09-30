<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use toubilib\core\application\ports\api\servicesInterfaces\ServicePraticienInterface;
use toubilib\infra\adapters\ApiResponseBuilder;

final class ListPraticiensAction
{
    public function __construct(private ServicePraticienInterface $service)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $praticiens = $this->service->listerPraticiens();
            $items = array_map(function ($dto) {
                $data = $dto->jsonSerialize();
                $data['_links'] = [
                    'self' => ['href' => '/api/praticiens/' . $data['id']],
                    'rdvs' => [
                        'href' => '/api/praticiens/' . $data['id'] . '/rdvs{?debut,fin}',
                        'templated' => true
                    ]
                ];
                return $data;
            }, $praticiens);

            $links = [
                'self' => ['href' => '/api/praticiens']
            ];

            return ApiResponseBuilder::create()
                ->status(200)
                ->data($items)
                ->links($links)
                ->build($response);
        } catch (Throwable $e) {
            return ApiResponseBuilder::create()
                ->status(500)
                ->error('Failed to list praticiens', $e)
                ->build($response);
        }
    }
}