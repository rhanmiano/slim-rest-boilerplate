<?php

namespace App\Validation;

use Respect\Validation\Validator as V;

class Validator {
    public function validate($requst, array $rules) {
        var_dump($rules);
    }
}