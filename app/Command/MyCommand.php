<?php

namespace app\Command;

use app\Kernel;
use knot\Database\Database;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MyCommand extends Command
{
  protected Kernel $kernel;
  protected $container;

  public function __construct(Kernel $kernel)
  {
    $this->kernel = $kernel;
    $this->container = $kernel->getContainer();
  }

  protected function configure()
  {
    $this
      ->setName('my-command')
      ->setDescription('This is my awesome command');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $this->container->get(Database::class);
    $output->writeln('Hello from my command!');
    return Command::SUCCESS;
  }
}
