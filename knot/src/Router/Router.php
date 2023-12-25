<?php

namespace knot\Router;

use app\Model\User;
use DI\Container;
use knot\Database\Database;
use knot\Router\Route;
use Symfony\Component\HttpFoundation\Request;

class Router
{
  use RouterRequestMethodsTrait;

  protected array $routes = [];
  protected $handler;
  protected string $method;
  protected string $path;
  protected array $middleware;
  protected Container $container;

  public function __construct(Container $container)
  {
    $this->container = $container;
  }

  function cleanPath(string $path): string
  {
    $cleanedPath = trim($path, '/');

    $cleanedPath = strtok($cleanedPath, '?');

    return trim($cleanedPath, '/');
  }

  public function dispatch($method, $requestPath)
  {
    $route = $this->matchRoute($method, $requestPath);
    $params = $this->handlePattern($route->getPath(), explode('/', $this->cleanPath($requestPath)));

    if ($route) {
      $this->executeMiddleware($route);
      $this->callHandler($route->handler, $params);
    } else {
      echo "404 Not Found";
    }
  }

  private function callHandler($handler, $params = [])
  {
    if (is_callable($handler)) {
      call_user_func_array($handler, $params);
    } elseif (is_array($handler)) {
      list($controllerClass, $action) = $handler;
      // $controllerInstance = $this->make($controllerClass);

      $controller = $this->container->get($controllerClass);
      $controller->$action((new Request()));


      // Call the action method on the controller instance
      // (new $controllerClass())->$action();

      // if (!method_exists($controllerInstance, $action)) {
      //   throw new \RuntimeException("Action '$action' not found in controller '$controllerClass'.");
      // }
      // $controllerInstance->$action();
    }
  }

  protected function handlePattern(string $path, array $request)
  {
    $params  = null;
    $pattern = explode('/', ltrim($path, '/'));
    $count   = count($pattern);

    for ($i = 0; $i < $count; $i++) {
      if (preg_match('/{([a-zA-Z0-9_]*?)}/', $pattern[$i]) !== 0) {
        if (array_key_exists($i, $request)) {
          $params[] = $request[$i];
        }
        continue;
      }
    }

    return $params;
  }


  public function make($className)
  {
    $reflector = new \ReflectionClass($className);

    if (!$reflector->isInstantiable()) {
      throw new \Exception("Class {$className} is not instantiable");
    }

    $constructor = $reflector->getConstructor();

    if (is_null($constructor)) {
      return new $className;
    }

    $parameters   = $constructor->getParameters();
    $dependencies = $this->getDependencies($parameters);

    return $reflector->newInstanceArgs($dependencies);
  }

  public function getDependencies($parameters)
  {
    $dependencies = [];

    foreach ($parameters as $parameter) {
      $dependency = $parameter->getClass();

      if ($dependency === null) {
        if ($parameter->isDefaultValueAvailable()) {
          $dependencies[] = $parameter->getDefaultValue();
        } else {
          throw new \Exception("Cannot resolve class dependency {$parameter->name}");
        }
      } else {
        $dependencies[] = $this->make($dependency->name);
      }
    }

    return $dependencies;
  }

  public function executeMiddleware($route)
  {
    foreach ($route->getMiddleware() as $middleware) {
      $middlewareInstance = new $middleware();
      $middlewareInstance->handle();
    }
  }

  function matchRoute($method, $path): ?Route
  {
    foreach ($this->routes as $route) {
      if ($route->getMethod() !== $method) {
        continue;
      }

      $routePathSegments = explode('/', trim($route->getPath(), '/'));
      $pathSegments = explode('/', trim($path, '/'));

      if (count($routePathSegments) !== count($pathSegments)) {
        continue;
      }

      return $route;
    }

    return null;
  }

  public function getRoutes(){
    return $this->routes;
  }
}
