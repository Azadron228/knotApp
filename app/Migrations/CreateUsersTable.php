<?php

namespace app\Migrations;

use knot\Database\Database;

class CreateUsersTable
{
  private Database $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  public function up()
  {
    $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        )";
    $this->db->executeQuery('sqlite_connection',$sql);
  }

  public function down()
  {
    $sql = "DROP TABLE users";
    $this->db->executeQuery('sqlite_connection',$sql);
  }
}
