<?php

use app\Controller\HelloController;
use app\Controller\PostController;
use app\Controller\UserController;
use knot\Router\Router;

return function(Router $router){
  $router->get('/home', [HelloController::class, 'index']);
  // $router->post('/register', [UserController::class, 'createUser']);
  // $router->get('/create', [PostController::class, 'create']);
};
