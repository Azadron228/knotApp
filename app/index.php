<?php

namespace app;

use DI\Container;
use DI\ContainerBuilder;
use knot\Database\Database;
use knot\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';

// require_once 'DatabaseConfig.php';

// $database = new Database($config);

$container = new Container();
$builder = new ContainerBuilder();
$builder->addDefinitions('config.php');
$container = $builder->build();

$router = new Router($container);

require_once 'routes.php';

$router->dispatch();





















//
// namespace app;
//
// use app\Controller\UserController;
// use app\Model\User;
// use DI\Container;
// use knot\Database\Database;
// use knot\Router\Router;
//
// require_once __DIR__ . '/../vendor/autoload.php';
//
// $config = [
//   'sqlite' => [
//     'driver'   => 'sqlite',
//     'database' => 'databas.sqlite',
//     'prefix'   => '',
//   ],
// ];
//
// $databaseManager = new Database($config);
// $container = new Container();
// // $sqliteConnection = $databaseManager->connect('sqlite');
// $router = new Router($container);
//
// $router->get('/register', [UserController::class, 'registerUser']);
//
// $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
