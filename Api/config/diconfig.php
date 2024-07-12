<?php

use Dotenv\Dotenv;
use DI\ContainerBuilder;

$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    PDO::class => function () {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $dbname = $_ENV['DB_DATABASE'];

        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    },
]);

$container = $containerBuilder->build();