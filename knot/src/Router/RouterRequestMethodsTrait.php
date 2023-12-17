<?php

namespace knot\Router;

use knot\Router\Route;

trait RouterRequestMethodsTrait
{

  public function add($method, $path, $handler, $middleware = []): Route
  {
    $route = new Route($method, $path, $handler, $middleware);
    $this->routes[] = $route;
    return $route;
  }

  public function get(string $path, $handler): Route
  {
    return $this->add('GET', $path, $handler);
  }

  function post(string $path, $handler): Route
  {

    return $this->add('POST', $path, $handler);
  }

  function put(string $path, $handler): Route
  {

    return $this->add('PUT', $path, $handler);
  }

  public function delete(string $path, $handler): Route
  {
    return $this->add('DELETE', $path, $handler);
  }

  function patch(string $path, $handler): Route
  {

    return $this->add('PATCH', $path, $handler);
  }

  function options(string $path, $handler): Route
  {

    return $this->add('OPTIONS', $path, $handler);
  }

  function any(string $path, $handler): Route
  {

    return $this->add('ANY', $path, $handler);
  }
}
