<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class FieldExists extends AbstractRule {

  protected $model;
  protected $col;

  public function __construct($model, $col) {
    $this->model = new $model;
    $this->col = $col;
  }

  public function validate($input) : bool {

    $exists = $this->model->where($this->col, $input)->count() === 1;
    return $exists;

  }

}