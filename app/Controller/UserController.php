<?php

namespace app\Controller;

use app\Model\User;

class UserController
{
  protected User $userModel;

  public function __construct(User $userModel)
  {
    $this->userModel = $userModel;
  }

  public function registerUser(string $username, string $email, string $password): void
  {
    if ($this->userModel->getUserByUsername($username)) {
      echo "Username already exists!";
      return;
    }

    if ($this->userModel->getUserByEmail($email)) {
      echo "Email already exists!";
      return;
    }

    $this->userModel->createUser($username, $email, $password);
    echo "User registered successfully!";
  }
}
