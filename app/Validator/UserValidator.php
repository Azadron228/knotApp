<?php

namespace app\Validator;

use knot\Validator\Validator;

class UserValidator extends Validator
{
  public function validate(array $data)
  {
    $rules = [
      'username' => 'required|max:255',
      'email' => 'required|max:255',
      'password' => 'required|max:255',
    ];

    return $this->make($data, $rules);
  }
}
