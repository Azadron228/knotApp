<?php

namespace app\Controller;

use app\Model\User;
use app\Validator\UserValidator;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
  protected User $userModel;

  public function __construct(User $userModel)
  {
    $this->userModel = $userModel;
  }

  public function createUser(Request $request)
  {
    $validator = new UserValidator();
    $isValid = $validator->validate($request->toArray());

    if ($isValid) {
      $validated = $validator->getValidatedData();
      var_dump($validated);
      $this->userModel->createUser(
        $validated['username'],
        $validated['email'],
        $validated['password']
      );
    } else {
      $errors = $validator->getErrors();
    }
  }
}
