<?php

namespace app;

use app\Kernel;

require_once __DIR__ . '/../vendor/autoload.php';


$kernel = new Kernel();
$kernel->run();


// $db = new Database();
// $sqliteConfig = [
//   'driver' => 'sqlite',
//   'name' => 'sqlite_connection',
//   'path' => 'database.sqlite',
// ];
//
// $db->addConnection($sqliteConfig);
//
// $sqliteConnection = $db->getConnection('sqlite_connection');
//
// $sqliteQuery = "
//   CREATE TABLE IF NOT EXISTS users (
//       id INTEGER PRIMARY KEY AUTOINCREMENT,
//       username TEXT NOT NULL,
//       email TEXT NOT NULL,
//       password TEXT NOT NULL,
//       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
//   )
// ";
//
// $createUser = "
//     INSERT INTO users (username, email, password)
//     VALUES ('John', 'john@example.com', 'hashed_password')
// ";
//
// $db->executeQuery('sqlite_connection', $sqliteQuery);
//
// $newUser = $db->executeQuery('sqlite_connection', $createUser);
//
// $query = "SELECT * FROM users WHERE id = :id";
// $bindings = [':id' => 1];
//
// $result = $db->executeQuery('sqlite_connection', $query, $bindings);
// $user = $result->fetch(\PDO::FETCH_ASSOC);
//
// var_dump($user);



// var_dump($newUser->fetchAll());


// $container = new Container();
// $builder = new ContainerBuilder();
// $builder->addDefinitions('config.php');
// $container = $builder->build();

// $router = new Router($container);

// require_once 'routes.php';

// $router->dispatch();





















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
