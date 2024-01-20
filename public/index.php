<?php

use app\Kernel;
use DI\Container as DIContainer;
use GuzzleHttp\Client;
use knot\Container\Container;
use knot\DB\Database;
use knot\Logger\Logger;
use knot\Router\Router as RouterRouter;
use knot\Routing\Route;
use knot\Routing\Router;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7Server\ServerRequestCreator;

require __DIR__ . '/../vendor/autoload.php';
//
// $data = [];
//
// $container = new Container($data);
// $di = new DIContainer();
// $r = new Logger('logger');
// $container->set(Logger::class, $r);
// $res = $container->getKnownEntryNames();
//
// var_dump($res);
//
// $logger = $container->get(Logger::class);
//
//
$kernel = new Kernel();
$psr17Factory = new Psr17Factory();

$creator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$request = $creator->fromGlobals();

$kernel->handleRequest($request);
