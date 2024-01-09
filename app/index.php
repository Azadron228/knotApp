<?php

namespace app;

use app\Controller\HelloController;
use app\Kernel;
use app\Middleware\ExampleMiddleware;
use app\Middleware\HelloMiddleware;
use DI\Container;
use knot\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../vendor/autoload.php';

//
// $kernel = new Kernel();
// $kernel->handleRequest(new Request, new Response);
//

$router = new Router(new Container());
//
// $router->addRoute("/closure", function () {
//   echo "Closure";
// }, 'GET', 'dadw', $middleware = [ExampleMiddleware::class]);
//

$router->get('/hello', function () {
  echo "Nigga!";
})->middleware([HelloMiddleware::class]);


$router->get('/closure/{name}', function ($name) {
  echo "Closure!". $name;
})->middleware([ExampleMiddleware::class, HelloMiddleware::class]);

$router->get('controller', [HelloController::class, 'index'])->middleware([HelloMiddleware::class]);


$requestUri = explode('/', trim(parse_url($_SERVER["REQUEST_URI"])["path"], '/'));
$router->matchRoute($requestUri);

