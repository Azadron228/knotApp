<?php

namespace app\Model;

use knot\Database\Database;

class User
{
  protected Database $database;

  public function __construct(Database $database)
  {
    $this->database = $database;
  }

  public function createUser(string $username, string $email, string $password): void
  {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $parameters = [
      ':username' => $username,
      ':email' => $email,
      ':password' => $hashedPassword,
    ];

    $this->database->execute('default', $sql, $parameters);
  }

  public function getUserByUsername(string $username): array
  {
    $sql = "SELECT * FROM users WHERE username = :username";
    $parameters = [':username' => $username];

    return $this->database->execute('default', $sql, $parameters);
  }

  public function getUserByEmail(string $email): array
  {
    $sql = "SELECT * FROM users WHERE email = :email";
    $parameters = [':email' => $email];

    return $this->database->execute('default', $sql, $parameters);
  }
}
