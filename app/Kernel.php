<?php

namespace app;

use DI\Container;
use DI\ContainerBuilder;
use knot\Database\Database;
use knot\Router\Router;

class Kernel
{
  private Router $router;
  private Container $container;

  public function initContainer()
  {
    $builder = new ContainerBuilder();
    $builder->addDefinitions('config.php');
    $this->container = $builder->build();

    $this->container->set(Database::class, function (Container $container) {
      $db = new Database();
      $sqliteConfig = [
        'driver' => 'sqlite',
        'name' => 'sqlite_connection',
        'path' => 'database.sqlite',
      ];
      $db->addConnection($sqliteConfig);

      return $db;
    });



    // $this->container->set(Database::class, function (Container $container) {
    //   return $this->initDb();
    // });
  }

  public function initDb()
  {
    $db = new Database();
    $sqliteConfig = [
      'driver' => 'sqlite',
      'name' => 'sqlite_connection',
      'path' => 'database.sqlite',
    ];

    $db->addConnection($sqliteConfig);
  }

  public function initRoutes()
  {
    $this->router = new Router($this->container);
    $routes = include 'routes.php';
    $routes($this->router);
  }

  public function bootstrap()
  {
    $this->initDb();
    $this->initContainer();
    $this->initRoutes();
  }

  public function run()
  {
    $this->bootstrap();
    $this->router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
  }
}
