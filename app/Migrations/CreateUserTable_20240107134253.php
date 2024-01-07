<?php

namespace app\Migrations;

use knot\Database\Migration;

class CreateUserTable_20240107134253 extends Migration
{
  public function up(): void
  {
    $q = 'CREATE TABLE users (
      id INT PRIMARY KEY,
      username VARCHAR(255)
    );';
    $this->database->executeQuery('sqlite', $q);
  }

  public function down(): void
  {
  }
}
