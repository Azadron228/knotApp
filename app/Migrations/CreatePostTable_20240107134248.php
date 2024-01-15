<?php

namespace app\Migrations;

use knot\Database\Migration;

class CreatePostTable_20240107134248 extends Migration
{
  public function up(): void
  {

    $q = 'CREATE TABLE Post (
      id INT PRIMARY KEY,
      userid INT,
      title VARCHAR(255),
      content TEXT,
      FOREIGN KEY (userid) REFERENCES users(id)
    );';
    $this->database->executeQuery('sqlite', $q);
  }

  public function down(): void
  {
    $q = 'DROP TABLE IF EXISTS Post;';
    $this->database->executeQuery('sqlite', $q);
  }
}
