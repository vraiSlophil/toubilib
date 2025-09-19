<?php
// toubilib/src/infrastructure/adapters/SlimStyleOutputFormatter.php
namespace toubilib\infra\adapters;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use toubilib\core\application\ports\spi\adapterInterface\OutputFormatterInterface;

final class SlimStyleOutputFormatter implements OutputFormatterInterface
{

    private static ?ContainerInterface $container = null;

    public static function setContainer(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    private static function displayErrorDetails(): bool
    {
        if (!self::$container) {
            return false;
        }
        if (!method_exists(self::$container, 'has') || !self::$container->has('settings')) {
            return false;
        }
        $settings = self::$container->get('settings');
        return (bool)($settings['displayErrorDetails'] ?? false);
    }

    public static function success(ResponseInterface $response, mixed $data, int $status = 200): ResponseInterface
    {
        $payload = json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        $response->getBody()->write($payload ?? 'null');
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }

    public static function error(
        ResponseInterface $response,
        string $publicMessage,
        ?Throwable $exception = null,
        int $status = 400
    ): ResponseInterface {
        if (self::displayErrorDetails() && $exception !== null) {
            $data = [
                'message' => 'Slim Application Error',
                'error' => [
                    'type' => $exception::class,
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => explode("\n", $exception->getTraceAsString()),
                ],
            ];
        } else {
            $data = [
                'message' => $publicMessage,
            ];
        }

        $payload = json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        $response->getBody()->write($payload ?? '{"message":"Unknown error"}');
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}
