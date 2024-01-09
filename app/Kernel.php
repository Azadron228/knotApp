<?php

namespace app;

use app\Middleware\AppMiddleware;
use knot\Kernel\BaseKernel;

class Kernel extends BaseKernel
{
    protected $middleware = [
      AppMiddleware::class,
    ];
}
