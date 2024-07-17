<?php

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Src\Controller\{ProductController, ProductTypeController, SaleController};
use function FastRoute\simpleDispatcher;

$dispatcher = simpleDispatcher(function (RouteCollector $r) {

    $r->addGroup('/product-types', function (RouteCollector $r) {
        $r->post('/new', [ProductTypeController::class, 'new']);
        $r->get('/list', [ProductTypeController::class, 'findAll']);
        $r->get('/{id:[0-9]+}', [ProductTypeController::class, 'findById']);
        $r->put('/{id:[0-9]+}', [ProductTypeController::class, 'update']);
        $r->delete('/{id:[0-9]+}', [ProductTypeController::class, 'delete']);
    });

    $r->addGroup('/product', function (RouteCollector $r) {
        $r->post('/new', [ProductController::class, 'new']);
        $r->get('/list', [ProductController::class, 'findAll']);
        $r->get('/{id:[0-9]+}', [ProductController::class, 'findById']);
        $r->put('/{id:[0-9]+}', [ProductController::class, 'update']);
        $r->delete('/{id:[0-9]+}', [ProductController::class, 'delete']);
    });

    $r->addGroup('/sales', function (RouteCollector $r) {
        $r->post('/new', [SaleController::class, 'new']);
        $r->get('/list', [SaleController::class, 'findAll']);
        $r->get('/{id:[0-9]+}', [SaleController::class, 'findById']);
//        $r->put('/{id:[0-9]+}', [SaleController::class, 'update']); //TODO not implemented yet
        $r->delete('/{id:[0-9]+}', [SaleController::class, 'delete']);
    });
});
$httpMethod = $_SERVER['REQUEST_METHOD'];

// Parse PUT requests body
$parsedData = ['PUT' => []];
if ($httpMethod === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() == JSON_ERROR_NONE) {
        $parsedData['PUT'] = $data;
    }
}

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

        if (!empty($parsedData)) {
            $vars = array_merge($vars, $parsedData);
        }

        global $container;
        $controller = $container->get($controller);
        try {
            $response = $controller->$method($vars);
            echo json_encode($response);
        } catch (Exception $e) {
          http_response_code($e->statusCode ?? 500);
          echo json_encode(['message' => $e->getMessage()]);
        }
        exit;
    default:
        echo json_encode(['message' => 'Not found']);
        exit;
}
