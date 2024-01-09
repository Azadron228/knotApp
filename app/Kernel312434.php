<?php

namespace app;

use app\Middleware\AppMiddleware;
use DI\Container;
use DI\ContainerBuilder;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use knot\Database\Database;
use knot\Exception\ErrorHandler;
use knot\Kernel\KernelInterface;
use knot\Logger\Logger;
use knot\Middleware\QueueRequestHandler;
use knot\Middleware\Stack;
use knot\Routing\Router;
use Psr\Container\ContainerInterface;

class Kernel implements KernelInterface
{
  // protected $configuration = [
  //   $mode => 'development'
  // ];
  protected $middleware = [
    AppMiddleware::class,
  ];

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

  public function getConfiguration()
  {
    return $this->configuration;
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

  public function handleRequest(ServerRequest $request)
  {
    $middleware = [
      new AppMiddleware(),
    ];
    $queueRequesHandler = new QueueRequestHandler(new Response(), $middleware);
    $queueRequesHandler->handle($request);

    $requestUri = explode('/', trim(parse_url($_SERVER["REQUEST_URI"])["path"], '/'));
    $response = $this->router->matchRoute($requestUri);

    return $response;
  }
}
