<?php

namespace app;

use app\Middleware\AppMiddleware;
use app\Middleware\AuthMiddleware;
use knot\Kernel\BaseKernel;

class Kernel extends BaseKernel
{
    protected $middleware = [
      // AppMiddleware::class,
    ];
}
