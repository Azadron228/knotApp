<?php

namespace knot\Database;

class Migration {
  protected Database $database;

  public function __construct(Database $database) {
    $this->database = $database;
  }

  public function up() {
  }

}
