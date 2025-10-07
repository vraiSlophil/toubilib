<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use toubilib\api\middlewares\CorsMiddleware;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/settings.php');
$builder->addDefinitions(__DIR__ . '/services.php');
$builder->addDefinitions(__DIR__ . '/actions.php');

try {
    $c = $builder->build();
} catch (Throwable $e) {
    echo "Erreur lors de la création du conteneur : " . $e->getMessage();
    exit(1);
}

AppFactory::setContainer($c);
$app = AppFactory::create();

try {
    $cors = $c->get('cors');
} catch (DependencyException $e) {
    echo "Erreur lors de la récupération des paramètres CORS : " . $e->getMessage();
    exit(1);
} catch (NotFoundException $e) {
    echo "Paramètre 'cors' non trouvé dans le conteneur : " . $e->getMessage();
    exit(1);
}

try {
    $settings = $c->get('settings');
} catch (DependencyException $e) {
    echo "Erreur lors de la récupération des paramètres : " . $e->getMessage();
    exit(1);
} catch (NotFoundException $e) {
    echo "Paramètre 'settings' non trouvé dans le conteneur : " . $e->getMessage();
    exit(1);
}

$app->add(new CorsMiddleware([
    (string) 'allowed_origins' => $cors['allowed_origins'],
    (string) 'allowed_methods' => $cors['allowed_methods'],
    (string) 'allowed_headers' => $cors['allowed_headers'],
    (string) 'exposed_headers' => $cors['exposed_headers'],
    (bool) 'allow_credentials' => $cors['allow_credentials'],
    (int) 'max_age' => $cors['max_age'],
], $settings['displayErrorDetails'] ?? false));

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();


$errorMw = $app->addErrorMiddleware(
    (bool)($settings['displayErrorDetails'] ?? true),
    (bool)($settings['logError'] ?? true),
    (bool)($settings['logErrorDetails'] ?? true)
);
$errorMw->getDefaultErrorHandler()->forceContentType('application/json');


$app = (require __DIR__ . '/../src/api/routes.php')($app);

return $app;