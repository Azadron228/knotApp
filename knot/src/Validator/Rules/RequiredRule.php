<?php

namespace knot\Validator\Rules;

class RequiredRule implements RuleInterface
{
  public function validate($field, $value, $parameters = [])
  {
    if (empty($value)) {
      return 'The ' . $field . ' field is required.';
    }

    return null;
  }
}
