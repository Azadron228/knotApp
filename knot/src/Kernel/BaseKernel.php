<?php

namespace knot\Kernel;

use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use knot\Database\Database;
use knot\Exception\ErrorHandler;
use knot\Kernel\KernelInterface;
use knot\Logger\Logger;
use knot\Middleware\MiddlewareHandler;
use knot\Middleware\QueueRequestHandler;
use knot\Routing\Router;
use Psr\Container\ContainerInterface;

class BaseKernel implements KernelInterface
{
    protected $middleware = [
      // AppMiddleware::class,
    ];

    protected Router $router;
    protected Logger $logger;
    protected Container $container;
    protected ErrorHandler $errorHandler;

    public function __construct()
    {

        // $this->loadEnvironment();
        // $this->setupErrorHandler();
        $this->initContainer();
        $this->registerServices();
        $this->setupLogger();
    }

    protected function loadEnvironment()
    {
        $dotenv = Dotenv::createImmutable(APP_ROOT); // Adjust the path to your .env file
        $dotenv->load();
    }

    public function setupLogger()
    {
        $logger = new Logger('/home/azardo/knotApp/log.txt');
        $this->logger = $logger;
    }

    public function initContainer(): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $config = APP_ROOT . '/config/services.php';

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
            $config = include APP_ROOT . '/config/database.php';
            $db = new Database($config);
            return $db;
        });
    }

    public function initRoutes()
    {
        $this->router = new Router($this->container);
        $routes = require APP_ROOT . '/routes/routes.php';
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
        $RequesHandler = new MiddlewareHandler(new Response(), $this->middleware, $this->container);
        $RequesHandler->handle($request);

        $requestUri = explode('/', trim(parse_url($_SERVER["REQUEST_URI"])["path"], '/'));
        $response = $this->router->matchRoute($requestUri);

        return $response;
    }
}
