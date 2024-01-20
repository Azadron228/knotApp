<?php

namespace app\Middleware;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {
    echo "AuthMiddleware is executing";

    if ($this->isAuthenticated($request)) {
      echo "user is authenticated";
      return $handler->handle($request);
    }

    $response = new Response();
    return $response->withHeader('Location', '/login')->withStatus(302);
    echo 302;
  }

  private function isAuthenticated(ServerRequestInterface $request): bool
  {
    // Replace 'your_cookie_name' with the actual name of your authentication cookie
    $cookieName = 'your_cookie_name';

    // Check if the cookie exists
    $cookies = $request->getCookieParams();
    if (isset($cookies[$cookieName])) {
      // Check if the cookie value is valid (you may perform more sophisticated checks here)
      $cookieValue = $cookies[$cookieName];

      // Replace 'your_secret_key' with your actual secret key for validation
      $secretKey = 'your_secret_key';

      // Sample validation - you may use a more secure method in a real-world scenario
      return hash_equals($cookieValue, hash_hmac('sha256', 'user_id', $secretKey));
    }

    return false;
  }
}
