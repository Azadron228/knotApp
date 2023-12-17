<?php

namespace knot\Database;

use Exception;
use PDO;

class Database
{

  protected array $config;
  protected $connections = [];

  public function __construct($config)
  {
    $this->config = $config;
  }

  public function connect(string $name): PDO
  {
    if (!isset($this->connections[$name])) {
      $connection = $this->createConnection($name);
      $this->connections[$name] = $connection;
    }

    return $this->connections[$name];
  }

  protected function createConnection(string $name): PDO
  {
    $config = $this->config[$name];

    try {
      switch ($config['driver']) {
        case 'mysql':
          $dsn = "mysql:host={$config['host']};dbname={$config['database']}";
          $connection = new PDO($dsn, $config['username'], $config['password']);
          $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $connection;
        case 'sqlite':
          $dsn = "sqlite:{$config['database']}";
          $connection = new PDO($dsn);
          $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $connection;
        default:
          throw new Exception("Unsupported database driver: {$config['driver']}");
      }
    } catch (Exception $e) {
      throw new DatabaseConnectionException($name, $e->getMessage());
    }
  }

  public function disconnect(string $name): void
  {
    unset($this->connections[$name]);
  }

  public function execute(string $name, string $sql, array $parameters = [])
  {
    $connection = $this->connect($name);
    $statement = $connection->prepare($sql);
    $statement->execute($parameters);
    // $statement->fetchAll(PDO::FETCH_ASSOC);
  }
}
