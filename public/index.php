<?php

use app\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require __DIR__ . '/../vendor/autoload.php';


$kernel = new Kernel();
$kernel->handleRequest(new Request, new Response);


