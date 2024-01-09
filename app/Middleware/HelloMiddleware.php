<?php

namespace app\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HelloMiddleware
{

  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {

    echo " HelloMiddleware ";

    return $handler->handle($request);
  }
}
