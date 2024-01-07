#!/usr/bin/env php
<?php

use app\Command\CreateMigrationCommand;
use app\Command\MigrateCommand;
use app\Kernel;
use DI\Container;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$application = new Application();
$kernel = new Kernel;
$container = $kernel->getContainer();

$application->add(new MigrateCommand($container->get(Container::class)));
$application->add(new CreateMigrationCommand());

$application->run();
