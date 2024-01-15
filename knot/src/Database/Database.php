<?php

namespace knot\Database;

class Database
{
  public $connections = [];

  public function __construct(array $config)
  {
    foreach ($config['connections'] as $name => $connectionConfig) {
      $driver = $connectionConfig['driver'] ?? null;

      switch ($driver) {
        case 'mysql':
          $connection = $this->createMySQLConnection($connectionConfig);
          break;
        case 'sqlite':
          $connection = $this->createSQLiteConnection($connectionConfig);
          break;
        default:
          throw new \InvalidArgumentException("Unsupported database driver: $driver");
      }

      $this->connections[$name] = $connection;
    }
  }

  public function condwastruct(array $config)
  {
    $driver = $config['driver'] ?? null;

    switch ($driver) {
      case 'mysql':
        $connection = $this->createMySQLConnection($config);
        break;
      case 'sqlite':
        $connection = $this->createSQLiteConnection($config);
        break;
      default:
        throw new \InvalidArgumentException("Unsupported database driver: $driver");
    }

    $this->connections[$config['name']] = $connection;
  }

  private function createMySQLConnection(array $config)
  {
    $host = $config['host'] ?? 'localhost';
    $port = $config['port'] ?? 3306;
    $dbname = $config['dbname'] ?? '';
    $username = $config['username'] ?? '';
    $password = $config['password'] ?? '';

    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";

    try {
      $pdo = new \PDO($dsn, $username, $password);
      $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

      return $pdo;
    } catch (\PDOException $e) {
      throw new \RuntimeException("MySQL Connection Failed: " . $e->getMessage());
    }
  }

  private function createSQLiteConnection(array $config)
  {
    $path = $config['path'] ?? '';

    if (empty($path)) {
      throw new \InvalidArgumentException("SQLite database path not specified.");
    }

    try {
      $pdo = new \PDO("sqlite:$path");
      $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

      return $pdo;
    } catch (\PDOException $e) {
      throw new \RuntimeException("SQLite Connection Failed: " . $e->getMessage());
    }
  }


  public function getConnection($name)
  {
    if (!isset($this->connections[$name])) {
      throw new \InvalidArgumentException("Connection '$name' not found.");
    }

    return $this->connections[$name];
  }

  public function executeQuery($connectionName, $sql, $bindings = [])
  {
    if ($connectionName === null) {
      throw new \InvalidArgumentException("Connection name must be specified for query execution.");
    }

    if (!isset($this->connections[$connectionName])) {
      throw new \InvalidArgumentException("Connection '$connectionName' not found.");
    }

    $connection = $this->connections[$connectionName];

    try {
      $statement = $connection->prepare($sql);
      $statement->execute($bindings);

      return $statement;
    } catch (\PDOException $e) {
      throw new \RuntimeException("Query execution failed: " . $e->getMessage());
    }
  }
}
