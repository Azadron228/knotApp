<?php

namespace knot\Validator\Rules;

interface RuleInterface
{
  public function validate($field, $value, $parameters = []);
}
