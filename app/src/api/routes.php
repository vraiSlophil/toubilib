<?php
declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use toubilib\api\actions\GetPraticienAction;
use toubilib\api\actions\GetRdvAction;
use toubilib\api\actions\CreateRdvAction;
use toubilib\api\actions\GetRootAction;
use toubilib\api\actions\ListBookedSlotsAction;
use toubilib\api\actions\ListPraticiensAction;
use toubilib\api\actions\CancelRdvAction;
use toubilib\api\middlewares\AuthnMiddleware;
use toubilib\api\middlewares\AuthzMiddleware;
use toubilib\api\middlewares\InputRdvHydrationMiddleware;


return function (App $app): App {

    $app->group('/api', function (RouteCollectorProxy $app) {
        $app->get('/', GetRootAction::class);
        $app->group('/praticiens', function (RouteCollectorProxy $app) {
            $app->get('', ListPraticiensAction::class);
            $app->group('/{praticienId}', function (RouteCollectorProxy $app) {
                $app->get('', GetPraticienAction::class);
                $app->get('/rdvs', ListBookedSlotsAction::class);
            });
        });
        $app->group('/rdvs', function (RouteCollectorProxy $app) {
            $app->get('', function ($request, $response) use ($app) {
                $q = $request->getQueryParams();
                // Si un praticien est fourni, déléguer à la méthode existante de /api/praticiens/{id}/rdvs
                $action = $app->getContainer()->get(ListBookedSlotsAction::class);
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
            $app->get('/{rdvId}', GetRdvAction::class);
            $app->group('', function (RouteCollectorProxy $app) {
                $app->post('', CreateRdvAction::class)->add(InputRdvHydrationMiddleware::class)->add('AuthzMiddleware.praticien');
                $app->delete('/{rdvId}', CancelRdvAction::class)->add('AuthzMiddleware.all');; // nouvelle route
            })->add(AuthnMiddleware::class);
        });
    });

    return $app;
};