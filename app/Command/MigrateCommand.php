<?php

namespace app\Command;

use app\Kernel;
use DI\Container;
use knot\Database\Database;
use knot\Database\Migration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command
{
  protected Kernel $kernel;
  protected $container;

  public function __construct(Container $container)
  {
    parent::__construct();
    $this->container = $container;
  }

  protected function configure()
  {
    $this
      ->setName('migrate');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $this->loadMigrations();

    return Command::SUCCESS;
  }

  protected function loadMigrations()
  {
    $migrations = $this->getMigrations();

    foreach ($migrations as $migration) {
      if (method_exists($migration, 'up')) {
        $migration->up();
      }
    }
  }

  public function getMigrations(): array
  {
    $migrations = [];

    $migrationPath = '/home/azardo/knotApp/app/Migrations/';
    $migrationFiles = glob($migrationPath . '*.php');

    foreach ($migrationFiles as $file) {
      require_once $file;

      $className = pathinfo($file, PATHINFO_FILENAME);
      $namespace = 'app\\Migrations\\';

      $fullClassName = $namespace . $className;

      $migrationClass = new $fullClassName($this->container->get(Database::class));


      $migrationClass->down();
      $migrationClass->up();

      if (class_exists($className) && is_subclass_of($className, Migration::class)) {
        $migrations[] = new $className();
      }
    }
    return $migrations;
  }
}
