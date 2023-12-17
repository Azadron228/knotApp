<?php

namespace app\Model;

use knot\Database\Database;

class Post
{

  protected $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  public function createPostTable()
  {


$this->db->execute('sqlite', 'CREATE TABLE Post (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    author VARCHAR(100) NOT NULL
);');
  }

  public function create()
  {
    echo "post Create";
  }
}
