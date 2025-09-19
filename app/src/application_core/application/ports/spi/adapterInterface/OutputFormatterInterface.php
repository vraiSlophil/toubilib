<?php
// toubilib/src/application_core/application/ports/spi/adapterInterface/OutputFormatterInterface.php
namespace toubilib\core\application\ports\spi\adapterInterface;

use Psr\Http\Message\ResponseInterface;
use Throwable;

interface OutputFormatterInterface
{
    /**
     * Sérialise et écrit une réponse JSON de succès.
     *
     * - Définit Content-Type: application/json
     * - Sérialise $data avec JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
     */
    public static function success(ResponseInterface $response, mixed $data, int $status = 200): ResponseInterface;

    /**
     * Sérialise et écrit une réponse JSON d'erreur.
     *
     * - Si $displayErrorDetails=true (config de l'implémentation) et $exception !== null,
     *   émet un objet "Slim-like":
     *     {
     *       "message": "Slim Application Error",
     *       "error": {
     *         "type": "<FQCN>",
     *         "message": "<exception message>",
     *         "file": "...",
     *         "line": 123,
     *         "trace": ["..."]
     *       }
     *     }
     * - Sinon, émet un objet:
     *     { "message": "<publicMessage>" }
     */
    public static function error(
        ResponseInterface $response,
        string $publicMessage,
        ?Throwable $exception = null,
        int $status = 400
    ): ResponseInterface;
}
