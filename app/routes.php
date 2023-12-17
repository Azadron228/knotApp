<?php

use app\Controller\HelloController;
use app\Controller\PostController;
use app\Controller\UserController;

// $router->get('/home', [HelloController::class, 'index']);
// $router->get('/register', [UserController::class, 'registerUser']);
$router->get('/create', [PostController::class, 'create']);
