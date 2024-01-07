<?php

namespace knot\Validator;

use knot\Validator\Rules\MaxRule;
use knot\Validator\Rules\MinRule;
use knot\Validator\Rules\RequiredRule;

class Validator
{
  protected $errors = [];
  protected $validatedData = [];

  protected $ruleMappings = [
    'required' => RequiredRule::class,
    'max' => MaxRule::class,
    'min' => MinRule::class,
  ];

  public function make(array $data, array $rules)
  {
    foreach ($rules as $field => $rule) {
      $rulesArray = explode('|', $rule);
      foreach ($rulesArray as $singleRule) {
        $this->applyRule($field, $singleRule, $data[$field] ?? null);
      }
    }

    return empty($this->errors);
  }

  protected function applyRule($field, $rule, $value)
  {
    [$ruleName, $parameters] = $this->parseRule($rule);

    if (!isset($this->ruleMappings[$ruleName])) {
        throw new \InvalidArgumentException("Rule '$ruleName' not found in the mappings.");
    }

    $ruleClass = $this->ruleMappings[$ruleName];

    if (class_exists($ruleClass)) {
      $ruleInstance = new $ruleClass;

      $errorMessage = $ruleInstance->validate($field, $value, $parameters);

      if ($errorMessage !== null) {
        $this->addError($field, $errorMessage);
      } else {
        $this->validatedData[$field] = $value;
      }
    } else {
      throw new \InvalidArgumentException("Rule class '$ruleClass' not found.");
    }
  }

  protected function parseRule($rule)
  {
    $parts = explode(':', $rule, 2);

    return [
      $parts[0],
      isset($parts[1]) ? explode(',', $parts[1]) : [],
    ];
  }

  protected function addError($field, $message)
  {
    $this->errors[$field][] = $message;
  }

  public function getValidatedData()
  {
    return $this->validatedData;
  }

  public function getErrors()
  {
    return $this->errors;
  }
}
