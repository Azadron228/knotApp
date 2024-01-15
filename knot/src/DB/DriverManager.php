<?php

namespace knot\DB;

use knot\DB\Driver\Mysql\MysqlDriver;
use knot\DB\Driver\Pgsql\PgsqlDriver;
use knot\DB\Driver\Sqlite\SqliteDriver;

class DriverManager
{
  protected array $config;
  private const DRIVER_MAP = [
    'mysqli'             => MysqlDriver::class,
    'pgsql'              => PgsqlDriver::class,
    'sqlite'            => SqliteDriver::class,
  ];

  public function __construct(array $config)
  {
    $this->config = $config;
  }

  public function createConnection()
  {
    $driverName = $this->config['driver'] ?? null;

    if ($driverName && isset(self::DRIVER_MAP[$driverName])) {
      $driverClass = self::DRIVER_MAP[$driverName];
      $driver = new $driverClass($this->config);
      return $driver->createConnection();
    } else {
      throw new \InvalidArgumentException("Unsupported database driver: $driverName");
    }
  }
}
