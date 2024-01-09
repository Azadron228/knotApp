<?php

use app\Kernel;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;

define('APP_ROOT', dirname(__DIR__) . '/app/');

require __DIR__ . '/../vendor/autoload.php';


$kernel = new Kernel();
$request = new ServerRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
$response = new Response();

$result = $kernel->handleRequest($request);
