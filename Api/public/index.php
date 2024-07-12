<?php
header('Access-Control-Allow-Origin: http://localhost:3000');

require '../vendor/autoload.php';
require '../config/diconfig.php';

use Src\Test;

$test = new Test();
echo json_encode(['data' => $test->sayHello(2222)]);