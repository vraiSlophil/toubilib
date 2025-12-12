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
use toubilib\core\domain\entities\Roles;
use toubilib\infra\adapters\MonologLogger;
use toubilib\infra\adapters\ApiResponseBuilder;

final class InputRdvHydrationMiddleware implements MiddlewareInterface
{
    public function __construct(private MonologLogger $logger) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $parsed = $request->getParsedBody();
        $this->logger->debug('Parsed body : ' . json_encode($parsed));

        if (!is_array($parsed)) {
            return $this->errorResponse('Invalid request body: expected JSON object.', 400);
        }

        $user = $request->getAttribute('authenticated_user');
        if ($user === null || $user->role !== Roles::PATIENT) {
            return $this->errorResponse('Only patients can create appointments.', 403);
        }

        $payload = array_merge($parsed, [
            'patientId' => $user->ID,
            'patientEmail' => $user->email,
        ]);

        try {
            $dto = InputRendezVousDTO::fromArray($payload);
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse('Invalid request body', 422, $e);
        }

        $errors = $dto->validate();
        if (!empty($errors)) {
            return $this->errorResponse('Validation errors : ' . json_encode($errors), 422);
        }

        return $handler->handle($request->withAttribute('input_rdv', $dto));
    }

    private function errorResponse(string $message, int $status, ?\Throwable $e = null): ResponseInterface
    {
        return ApiResponseBuilder::create()
            ->status($status)
            ->error($message, $e)
            ->build(new Response($status));
    }
}