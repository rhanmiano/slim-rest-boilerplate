<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class FieldUniqueUpdate extends AbstractRule {

  protected $model;
  protected $id;

  public function __construct($model, $id) {
    $this->model = new $model;
    $this->id = $id;
  }

  public function validate($input) : bool {
    $field_name = lcfirst(str_replace(' ', '_', trim($this->getName())));

    $result = $this->model->find($this->id);

    if ($result[$field_name] !== $input) {
      $exists = $this->model->where($field_name, $input)
                          ->where('id', '!=', $this->id)
                          ->where('deleted_at', NULL)
                          ->count() === 0;
      return $exists;
    }
    
    return true;

  }

}