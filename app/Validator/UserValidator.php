<?php

namespace app\Validator;

use knot\Validator\Validator;

class UserValidator extends Validator
{
  public function validate(array $data)
  {
    $rules = [
      'username' => 'required|min:3',
      'email' => 'required|unique:users',
      'password' => 'required|max:255',
    ];

    return $this->make($data, $rules);
  }
}
