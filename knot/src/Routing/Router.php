<?php

namespace knot\Routing;

use DI\Container;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use knot\Middleware\MiddlewareHandler;
use knot\Middleware\Stack;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
  use RouterRequestMethodsTrait;

  protected array $middleware = [];
  protected array $routes = [];
  protected Container $container;

  public function __construct(Container $container)
  {
    $this->container = $container;
  }

  public function handle($route, $requestUri)
  {
    $routeSegments = explode('/', trim($route->getUri(), '/'));

    $processedUri = [];
    $parameters = [];

    foreach ($routeSegments as $index => $segment) {
      if (preg_match('/{([a-zA-Z0-9_]*)}/', $segment)) {

        if (isset($requestUri[$index])) {
          $processedUri[] = $requestUri[$index];
          $parameters[] = $requestUri[$index];
        }
      } else {
        $processedUri[] = $segment;
      }
    }

    return [$processedUri, $parameters];
  }

  public function matchRoute(array $requestUri): void
  {
    foreach ($this->routes as $route) {
      if ($route->getMethod() === $_SERVER["REQUEST_METHOD"]) {
        $pattern = $this->handle($route, $requestUri);

        $uri = $pattern[0];
        $params = $pattern[1];

        if ($requestUri === $uri) {

          $middlewareInstances = [];

          foreach ($route->getMiddleware() as $middlewareClass) {
            $middlewareInstances[] = new $middlewareClass();
          }

          $RequestHandler = new MiddlewareHandler(new Response(), $route->getMiddleware(), $this->container);
          $RequestHandler->handle(new ServerRequest($_SERVER['REQUEST_METHOD'], implode('/', $requestUri)));

          $this->executeClosure($route, $params);

          return;
        }
      }
    }

    http_response_code(404);
  }

  public function executeClosure($route, $params)
  {
    $action = $route->getAction();

    // If closure is function
    if ($action instanceof \Closure) {
      if (is_array($params)) {
        $action(...$params);
      }
      return;
    }

    $controller = $this->container->get($route->getAction()[0]);
    $action = $route->getAction()[1];

    $this->callController($params, $action, $controller);
  }

  protected function callController($params, $action, $controller): void
  {
    if (!isset($params)) {
      $controller->{$action}();
    } else {
      $controller->{$action}(...$params);
    }
  }
}
