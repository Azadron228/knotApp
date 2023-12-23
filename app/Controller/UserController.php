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

  public function createUser() {
    $this->userModel->createTable();
    $res = $this->userModel->createUser("Jotaro", "Kujo@mail.com","123123");
    var_dump($res);
    // var_dump($this->userModel->getUserByUsername("Jotaro"));
  }
}
