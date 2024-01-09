<?php

namespace knot\Kernel;

use GuzzleHttp\Psr7\Request as Psr7Request;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface KernelInterface
{
  public function initDb();
  public function initRoutes();

  public function initContainer(): ContainerInterface;
  public function getContainer(): ContainerInterface;

}
