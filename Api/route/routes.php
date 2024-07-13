<?php

use Src\Controller\ProductTypeController;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$dispatcher = simpleDispatcher(function (RouteCollector $r) {

    $r->addGroup('/product-types', function (RouteCollector $r) {
        $r->post('/new', [ProductTypeController::class, 'new']);
        $r->get('/list', [ProductTypeController::class, 'findAll']);
        $r->get('/{id:[0-9]+}', [ProductTypeController::class, 'findById']);
        $r->put('/{id:[0-9]+}', [ProductTypeController::class, 'update']);
        $r->delete('/{id:[0-9]+}', [ProductTypeController::class, 'delete']);
    });
});
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode(['message' => 'Not found']);

        exit;
    case Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);

        exit;
    case Dispatcher::FOUND:
        $handler = $routeInfo[1];
        [$controller, $method] = $handler;
        $vars = $routeInfo[2];

        global $container;
        $controller = $container->get($controller);
        $response = $controller->$method($vars);
        echo json_encode($response);

        exit;
    default:
        echo json_encode(['message' => 'Not found']);
        exit;
}
