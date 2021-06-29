<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class Matches extends AbstractRule {

  protected $value;

  public function __construct($value) {
    $this->value = $value;
  }

  public function validate($input) : bool {

    return $input === $this->value;

  }

}