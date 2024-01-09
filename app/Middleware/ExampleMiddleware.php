<?php

namespace app\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ExampleMiddleware
{

  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {
    echo " ExampleMiddleware ";

    return $handler->handle($request);
  }
}
