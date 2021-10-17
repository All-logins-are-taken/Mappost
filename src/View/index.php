<?php
require_once __DIR__.'/../../vendor/autoload.php';

use App\Container\ServiceContainer;
use App\Controller\DistanceController;
use App\Http\Router;
use App\Http\Request;
use App\Http\Response;

$container = new ServiceContainer(['path' => __DIR__.'/../../.env']);
$distance = new DistanceController($container);

Router::get('/', function (Request $request, Response $response) use ($distance) {
    $response->getResponse($distance->index());
});

Router::post('/', function (Request $request, Response $response) use ($distance) {
    $request->getBody()['action'] ?? header('Location: /');

    $action = $request->getBody()['action'];

    if ($action === 'addresses') {
        $request->getBody()['addresses'] ?? header('Location: /');

        $response->getResponse($distance->addresses($request->getBody()['addresses']));
    }
    else {
        header('Location: /');
    }
});
