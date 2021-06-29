<?php

namespace App\Validation;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Factory;

class Validator {

  protected $errors = array();

  public function __construct() {

    Factory::setDefaultInstance(
      (new Factory())
        ->withRuleNamespace('App\\Validation\\Rules\\')
        ->withExceptionNamespace('App\\Validation\\Exceptions\\')
    );

  }

  public function validate($fields, array $rules) {

    foreach ($rules as $key => $value) {

      if (!array_key_exists($key, $fields)) {
        $this->errors['type'] = VLD_ERR_TYPE;
        $this->errors['note'] = 'Invalid request parameters';
        break;
      }

      try {

        $value->setName(ucfirst(str_replace('_', ' ', trim($key))))->assert($fields[$key]);

      } catch (NestedValidationException $e) {
        $field_name = ucfirst(str_replace('_', ' ', trim($key)));

        $this->errors['type']         = VLD_ERR_TYPE;
        $this->errors['fields'][$key] = $e->getMessages()[$field_name];

      }

    }
    return $this;

  }

  public function failed() {

    return !empty($this->errors);

  }

  public function getErrors() {

    return $this->errors;

  }

}