<?php

use Src\Test;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$dispatcher = simpleDispatcher(function (RouteCollector $r) {

   $r->get('/login', [Test::class, 'test']);

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
        global $container;
        global $handler;
        [$controller, $method] = $handler;
        $vars = $routeInfo[2];
        $controller = $container->get($controller);
        $response = $controller->$method($vars);
        echo json_encode($response);

        exit;
    default:
        echo json_encode(['message' => 'Not found']);
        exit;
}
