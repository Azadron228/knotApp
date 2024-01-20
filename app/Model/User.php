<?php

namespace app\Model;

use knot\Auth\Auth;
use knot\DB\Database;

class User
{
  protected Database $db;

  public function __construct(Database $database)
  {
    $this->db = $database;
  }

  public function createUser(array $user)
  {

    $hashedPassword = Auth::bcrypt($user["password"]);

    $createUser = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $bindings = [
      ':username' => $user["username"],
      ':email' => $user["email"],
      ':password' => $hashedPassword,
    ];

    return $this->db->executeQuery($createUser, $bindings);
  }

  public function getUserByUsername(string $username)
  {
    $getUser = "SELECT * FROM users WHERE username = :username";
    $bindings = [
      ':username' => $username,
    ];

    return $this->db->executeQuery($getUser, $bindings)->fetch();
  }
}
