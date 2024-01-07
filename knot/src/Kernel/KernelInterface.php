<?php

namespace knot\Kernel;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface KernelInterface
{
  public function initDb();
  public function initRoutes();

  public function initContainer(): ContainerInterface;
  public function getContainer(): ContainerInterface;

  public function handleRequest(Request $request): Response;
}
