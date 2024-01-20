<?php

namespace knot\Kernel;

define('APP_ROOT', dirname(__DIR__, 3) . '/');

use Dotenv\Dotenv;
use GuzzleHttp\Psr7\Response;
use knot\Container\Container;
use knot\DB\Database;
use knot\Exception\ErrorHandler;
use knot\Kernel\KernelInterface;
use knot\Logger\Logger;
use knot\Middleware\MiddlewareHandler;
use knot\Routing\Router;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class BaseKernel implements KernelInterface
{
  protected $middleware = [
    // AppMiddleware::class,
  ];

  protected Router $router;
  protected Logger $logger;
  protected Container $container;
  protected ErrorHandler $errorHandler;
  protected Database $database;

  public function __construct()
  {
    session_start();
    $this->loadEnvironment();
    $this->setupLogger();
    $this->setupErrorHandler();
    $this->setupContainer();
    $this->registerServices();
  }

  protected function loadEnvironment()
  {
    $dotenv = Dotenv::createImmutable($this->getRootDir());
    $dotenv->load();
  }

  public function getRootDir(){
    return dirname(__DIR__, 3) . '/';
  }

  public function setupLogger()
  {
    $logger = new Logger($this->getRootDir() . '/log.txt');
    $this->logger = $logger;
  }

  public function setupContainer(): ContainerInterface
  {
    $config = require_once APP_ROOT . '/app/config/services.php';

    $this->container = new Container($config);
    return $this->container;
  }

  public function registerServices()
  {
    $this->setupDb();
    $this->setupRoutes();
  }

  public function setupDb()
  {
    $this->container->set(Database::class, function () {
      // $config = include APP_ROOT . 'config/database.php';
      $config = [
        'driver'   => 'sqlite',
        'database' => APP_ROOT . "/database/database.sqlite"
      ];
      return new Database($config);
    });
  }

  public function setupRoutes()
  {
    $this->router = new Router($this->container);
    $routes = require $this->getRootDir() . '/app/routes/routes.php';
    $routes($this->router);
    return $this->router;
  }

  public function setupErrorHandler()
  {
    $this->errorHandler = new ErrorHandler($this->getLogger());
    $this->errorHandler->register();
  }

  public function getContainer(): ContainerInterface
  {
    return $this->container;
  }

  public function getLogger(): LoggerInterface
  {
    return $this->logger;
  }

  public function handleMiddleware($request)
  {
    $RequesHandler = new MiddlewareHandler(new Response(), $this->container, $this->middleware);
    $RequesHandler->handle($request);
  }

  public function handleRequest(ServerRequestInterface $request)
  {
    $this->handleMiddleware($request);
    $this->router->matchRoute($request);
  }
}
