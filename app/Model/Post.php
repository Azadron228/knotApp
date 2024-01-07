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

  public function getPostById($postId)
  {
    $query = "SELECT * FROM posts WHERE id = :id";
    $bindings = [':id' => $postId];

    $result = $this->db->executeQuery('sqlite', $query, $bindings);
        return $result->fetch(\PDO::FETCH_ASSOC);

  }

  public function getAllPosts()
  {
    $query = "SELECT * FROM posts";
    $result = $this->db->executeQuery('sqlite', $query);
    return $result->fetch(\PDO::FETCH_ASSOC);
  }

  public function createPost($userid, $title, $content)
  {
    $query = "INSERT INTO posts (userid, title, content) VALUES (:userid, :title, :content)";
    $bindings = [':userid' => $userid, ':title' => $title, ':content' => $content];

    $result = $this->db->executeQuery('sqlite', $query, $bindings);
    return $result->fetch(\PDO::FETCH_ASSOC);
  }

  public function updatePost($postId, $title, $content)
  {
    $query = "UPDATE posts SET title = :title, content = :content WHERE id = :id";
    $bindings = [':id' => $postId, ':title' => $title, ':content' => $content];

    $result = $this->db->executeQuery('sqlite', $query, $bindings);
    return $result->fetch(\PDO::FETCH_ASSOC);
  }

  public function deletePost($postId)
  {
    $query = "DELETE FROM posts WHERE id = :id";
    $bindings = [':id' => $postId];

    return $this->db->executeQuery('sqlite', $query, $bindings);
  }

  public function create($userid, $title, $content)
  {
    $query = "INSERT INTO posts (userid, title, content) VALUES (:userid, :title, :content)";

    $bindings = [
      ':userid' => $userid,
      ':title' => $title,
      ':content' => $content,
    ];

    return $this->db->executeQuery('sqlite', $query, $bindings);
  }
}
