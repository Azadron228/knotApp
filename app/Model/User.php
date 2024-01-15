<?php

namespace app\Model;

use knot\DB\Database;

class User
{
  protected Database $db;

  public function __construct(Database $database)
  {
    $this->db = $database;
  }

  public function createUser(string $username, string $email, string $password)
  {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $createUser = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $bindings = [
      ':username' => $username,
      ':email' => $email,
      ':password' => $hashedPassword,
    ];

    $result = $this->db->executeQuery($createUser, $bindings);

    return $result->fetch(\PDO::FETCH_ASSOC);
  }

  public function getUserByUsername(string $username)
  {

    $getUser = "SELECT * FROM users WHERE username = :username";
    $bindings = [
      ':username' => $username,
    ];

    $result =  $this->db->executeQuery($getUser, $bindings);

    return $result->fetch(\PDO::FETCH_ASSOC);
  }
}
