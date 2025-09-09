<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\actions\ListerPraticiensAction;


return function (\Slim\App $app): \Slim\App {


    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Bienvenue sur l\'API des praticiens !');
        return $response;
    });

  $app->get('/praticiens', ListerPraticiensAction::class);

    return $app;
};