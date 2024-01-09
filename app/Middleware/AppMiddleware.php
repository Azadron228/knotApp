<?php

namespace app\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class AppMiddleware implements MiddlewareInterface {

  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {
    echo " AppMiddleware ";
    return $handler->handle($request);
  }
}
