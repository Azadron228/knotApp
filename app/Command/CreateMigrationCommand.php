<?php

namespace app\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateMigrationCommand extends Command
{
  protected function configure()
  {
    $this
      ->setName('migrate:create')
      ->setDescription('Create a new database migration')
      ->addArgument('name', InputArgument::REQUIRED, 'The name of the migration')
      ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'The directory where migrations are stored', 'src/Migrations');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $migrationName = $input->getArgument('name');
    $migrationPath = $input->getOption('path');

    $this->createMigration($migrationName, $migrationPath, $output);

    return Command::SUCCESS;
  }

  private function createMigration($migrationName, $migrationPath, $output)
  {
    // Example:
    $migrationContent = file_get_contents('/home/azardo/knotApp/app/Stubs/Migration.stub');

    $migrationFileName = $migrationPath . '/' . $migrationName . '_' . date('YmdHis') . '.php';
    $migrationName = $migrationName . '_' . date('YmdHis');

    $migrationContent = str_replace('DummyClass', $migrationName, $migrationContent);

    file_put_contents($migrationFileName, $migrationContent);

    $output->writeln('<info>Migration created successfully:</info> ' . $migrationFileName);
  }
}
