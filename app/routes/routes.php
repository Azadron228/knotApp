<?php

use app\Controller\HelloController;
use app\Controller\PostController;
use app\Controller\UserController;
use app\Middleware\ExampleMiddleware;
use app\Middleware\HelloMiddleware;
use knot\Routing\Router;

return function(Router $router){
  $router->get('/user/{name}/post/{id}', [HelloController::class, 'index'])->middleware([HelloMiddleware::class, ExampleMiddleware::class]);
  // $router->post('/register', [UserController::class, 'createUser']);
  // $router->get('/create', [PostController::class, 'create']);
};
