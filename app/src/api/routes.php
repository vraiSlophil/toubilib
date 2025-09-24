<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use toubilib\api\actions\AfficherPraticienAction;
use toubilib\api\actions\ConsulterRdvAction;
use toubilib\api\actions\CreerRdvAction;
use toubilib\api\actions\ListerCreneauxPrisAction;
use toubilib\api\actions\ListerPraticiensAction;
use toubilib\api\middlewares\InputRdvHydrationMiddleware;
use toubilib\core\application\ports\api\servicesInterfaces\ServiceRdvInterface;


return function (App $app): App {

    $app->group('/api', function (RouteCollectorProxy $app) {
        $app->get('/', function (Request $request, Response $response) {
            $response->getBody()->write(json_encode('Bienvenue sur l\'API des praticiens !'));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        });
        $app->group('/praticiens', function (RouteCollectorProxy $app) {
            $app->get('', ListerPraticiensAction::class);
            $app->group('/{praticienId}', function (RouteCollectorProxy $app) {
                $app->get('', AfficherPraticienAction::class);
                $app->get('/rdvs', ListerCreneauxPrisAction::class);
            });
        });
        $app->group('/rdvs', function (RouteCollectorProxy $app) {
            $app->post('', CreerRdvAction::class)->add(InputRdvHydrationMiddleware::class);
            $app->get('', function ($request, $response) use ($app) {
                $q = $request->getQueryParams();
                // Si un praticien est fourni, déléguer à la méthode existante de /api/praticiens/{id}/rdvs
                $action = $app->getContainer()->get(ListerCreneauxPrisAction::class);
                if (!empty($q['praticienId'])) {
                    return $action(
                        $request->withAttribute('praticienId', $q['praticienId']),
                        $response,
                        ['praticienId' => $q['praticienId']]
                    );
                }
                // Sinon, retourner la liste de tous les rendez-vous disponibles pour la plage donnée
                return $action($request, $response, []);
            });
            $app->get('/{rdvId}', ConsulterRdvAction::class);
        });
    });

    return $app;
};