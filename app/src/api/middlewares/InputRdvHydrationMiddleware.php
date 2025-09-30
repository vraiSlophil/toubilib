<?php
declare(strict_types=1);

namespace toubilib\api\middlewares;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use toubilib\core\application\ports\api\dtos\inputs\InputRendezVousDTO;
use toubilib\infra\adapters\MonologLogger;
use toubilib\infra\adapters\ApiResponseBuilder;

final class InputRdvHydrationMiddleware implements MiddlewareInterface
{

    public function __construct(
        private MonologLogger $logger
    )
    {

    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $parsed = $request->getParsedBody();

        $this->logger->debug('Parsed body : ' . json_encode($parsed));

        if (!is_array($parsed)) {
            return ApiResponseBuilder::create()
                ->status(400)
                ->error('Invalid request body: expected JSON object.')
                ->build(new Response(400));
        }

        try {
            $dto = InputRendezVousDTO::fromArray($parsed);
        } catch (InvalidArgumentException $e) {
            return ApiResponseBuilder::create()
                ->status(422)
                ->error('Invalid request body', $e)
                ->build(new Response(422));
        }

        $errors = $dto->validate();
        if (!empty($errors)) {
            return ApiResponseBuilder::create()
                ->status(422)
                ->error('Validation errors : ' . json_encode($errors))
                ->build(new Response(422));
        }

        return $handler->handle($request->withAttribute('input_rdv', $dto));
    }

}