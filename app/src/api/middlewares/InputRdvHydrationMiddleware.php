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
use toubilib\infra\adapters\SlimStyleOutputFormatter;

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
            return SlimStyleOutputFormatter::error(
                new Response(400),
                'Invalid request body: expected JSON object.'
            );
        }

        try {
            $dto = InputRendezVousDTO::fromArray($parsed);
        } catch (InvalidArgumentException $e) {
            return SlimStyleOutputFormatter::error(
                new Response(422),
                'Invalid request body',
                $e
            );
        }

        $errors = $dto->validate();
        if (!empty($errors)) {
            return SlimStyleOutputFormatter::error(
                new Response(422),
                'Validation errors : ' . json_encode($errors)
            );
        }

        return $handler->handle($request->withAttribute('input_rdv', $dto));
    }

}