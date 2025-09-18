<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use toubilib\api\actions\AfficherPraticienAction;
use toubilib\api\actions\ConsulterRdvAction;
use toubilib\api\actions\ListerCreneauxPrisAction;
use toubilib\api\actions\ListerPraticiensAction;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;


return function (App $app): App {

    $app->group('/api', function (RouteCollectorProxy $app) {
        $app->get('/', function (Request $request, Response $response) {
            $response->getBody()->write('Bienvenue sur l\'API des praticiens !');
            return $response;
        });
        $app->group('/praticiens', function (RouteCollectorProxy $app) {
            $app->get('', ListerPraticiensAction::class);
            $app->group('/{praticienId}', function (RouteCollectorProxy $app) {
                $app->get('', AfficherPraticienAction::class);
                $app->get('/rdvs', ListerCreneauxPrisAction::class);
            });
        });
        $app->get('/rdvs/{rdvId}', ConsulterRdvAction::class);

        $app->get('/rdvs', function ($request, $response) use ($app) {
            $q = $request->getQueryParams();
            if (!isset($q['praticienId'], $q['debut'], $q['fin'])) {
                return $response->withStatus(400);
            }
            return new ListerCreneauxPrisAction($app->getContainer()->get(ServiceRdvInterface::class))
            ($request->withAttribute('praticienId', $q['praticienId']), $response, ['praticienId' => $q['praticienId']]);
        });
    });


    return $app;
};