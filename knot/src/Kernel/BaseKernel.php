<?php

namespace knot\Kernel;

use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use knot\DB\Database;
use knot\DB\DatabaseInterface;
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
    var_dump($this->getRootDir());

    $this->loadEnvironment();
    $this->setupLogger();
    // $this->setupErrorHandler();
    $this->setupContainer();
    $this->registerServices();
  }

  protected function loadEnvironment()
  {
    $dotenv = Dotenv::createImmutable($this->getRootDir());
    $dotenv->load();
  }

  public function getRootDir()
  {
    return dirname(dirname(dirname(__DIR__)));
  }

  public function setupLogger()
  {
    $logger = new Logger($this->getRootDir() . '/log.txt');
    $this->logger = $logger;
  }

  public function setupContainer(): ContainerInterface
  {
    $builder = new ContainerBuilder();
    $config = $this->getRootDir() . '/app/config/services.php';

    $builder->addDefinitions($config);
    $this->container = $builder->build();
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
      // $config = include $this->getRootDir() . '/app/config/database.php';

      $config = [
        'driver'   => 'sqlite',
        'database' => 'database.sqlite'
      ];
      $database = new Database($config);
      return $database;
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
