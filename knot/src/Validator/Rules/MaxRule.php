<?php

namespace knot\Validator\Rules;

class MaxRule
{
  public function validate($field, $value, $parameters)
  {
    $maxValue = $parameters[0];

    if (strlen($value) > $maxValue) {
      return "The $field must not exceed $maxValue characters.";
    }

    return null;
  }
}
