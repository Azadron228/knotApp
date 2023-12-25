<?php

namespace app\Controller;

use app\Model\User;
use app\Validator\UserValidator;
use knot\Validator\Validator;
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

    //
    // $rules = [
    //   'username' => 'required|max:255',
    //   'email' => 'required|max:255',
    //   'password' => 'required|max:255',
    // ];
    //
    //
    // $validator = new Validator;
    // $validated = $validator->validate($request->toArray(), $rules);
    // var_dump($validated);
    // var_dump($validator->getErrors());




    // $validated = (new UserValidator((new Validator)))->validate($request->toArray());
    // $userValidator = new UserValidator((new Validator));
    // $validated = $userValidator->validate($request->toArray());
    // var_dump($validated);
    $validator = new Validator();

    $rules = [
      'username' => 'required|max:255',
      'email' => 'required|max:255',
      'password' => 'required|max:255',
    ];

    $validator->validate($request->toArray(), $rules);

    if ($validated) {
      // $this->userModel->createTable();
      // $this->userModel->createUser(
      //   $validated['username'],
      //   $validated['email'],
      //   $validated['password']
      // );
    } else {
      // $userValidator->composeErrors();
    }
  }
}
