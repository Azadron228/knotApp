<?php

namespace app;

use DI\Container;
use DI\ContainerBuilder;
use knot\Database\Database;
use knot\Exception\ErrorHandler;
use knot\Kernel\KernelInterface;
use knot\Logger\Logger;
use knot\Router\Router;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Kernel implements KernelInterface
{
  private $appMode = 'dev';
  protected Router $router;
  protected Logger $logger;
  protected Container $container;
  protected ErrorHandler $errorHandler;

  public function __construct()
  {
    $this->setupErrorHandler();
    $this->initContainer();
    $this->registerServices();
    $this->setupLogger();
  }

  public function setupLogger()
  {
    $logger = new Logger('/home/azardo/knotApp/log.txt');
    $this->logger = $logger;
  }

  public function initContainer(): ContainerInterface
  {
    $builder = new ContainerBuilder();
    $config = __DIR__ . '/config/services.php';

    $builder->addDefinitions($config);
    $this->container = $builder->build();
    return $this->container;
  }

  public function registerServices()
  {
    $this->initDb();
    $this->initRoutes();
  }

  public function initDb()
  {
    $this->container->set(Database::class, function () {
      $config = include __DIR__ . '/config/database.php';
      $db = new Database($config);
      return $db;
    });
  }

  public function initRoutes()
  {
    $this->router = new Router($this->container);
    $routes = include 'routes.php';
    $routes($this->router);
    return $this->router;
  }

  public function setupErrorHandler()
  {
    $this->errorHandler = new ErrorHandler();
    $this->errorHandler->register();
  }

  public function getContainer(): ContainerInterface
  {
    $this->initContainer();
    $this->registerServices();
    return $this->container;
  }

  public function getLogger()
  {
    return $this->logger;
  }

  public function handleRequest(Request $request): Response
  {

    $requestUri = explode('/', trim(parse_url($_SERVER["REQUEST_URI"])["path"], '/'));
    $this->router->matchRoute($requestUri);

    $response = $this->router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    return $response;
  }
}
