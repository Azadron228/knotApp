<?php

namespace app\Controller;

use app\Model\User;
use app\Validator\UserValidator;
use knot\Validator\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
  protected User $userModel;

  public function __construct(User $userModel)
  {
    $this->userModel = $userModel;
  }

  public function createUser(Request $request)
  {
    // $validator = new Validator();
    //
    // $rules = [
    //   'username' => 'required|max:255',
    //   'email' => 'required|max:255',
    //   'password' => 'required|max:255',
    // ];
    //
    // $isValid = $validator->validate($request->toArray(), $rules);
  
    $validator = new UserValidator();
    $isValid = $validator->validate($request->toArray());

    if ($isValid) {
      $validated = $validator->getValidatedData();
      var_dump($validated);
      // $this->userModel->createTable();
      // $this->userModel->createUser(
      //   $validated['username'],
      //   $validated['email'],
      //   $validated['password']
      // );
    } else {
      $errors = $validator->getErrors();
    }
  }
}
