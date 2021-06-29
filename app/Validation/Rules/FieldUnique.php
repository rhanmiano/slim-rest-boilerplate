<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class FieldUnique extends AbstractRule {

  protected $model;

  public function __construct($model) {
    $this->model = new $model;
  }

  public function validate($input): bool {
    $field_name = str_replace(' ', '_', trim($this->getName()));

    $exists = $this->model->where($field_name, $input)
      ->where('deleted_at', NULL)
      ->count() === 0;
    return $exists;

  }

}