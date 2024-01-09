<?php

namespace knot\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareHandler implements RequestHandlerInterface
{
    private $middleware = [];
    private $defaultResponse;
    private $container;

    public function __construct(ResponseInterface $response, array $middleware, ContainerInterface $container)
    {
        $this->defaultResponse = $response;
        $this->middleware = $middleware;
        $this->container = $container;
    }

    public function add(MiddlewareInterface $middleware)
    {
        $this->middleware[] = $middleware;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (0 === count($this->middleware)) {
            return $this->defaultResponse;
        }

        $middleware = array_shift($this->middleware);

        $middlewareInstance = $this->container->get($middleware);
        return $middlewareInstance->process($request, $this);
    }
}
