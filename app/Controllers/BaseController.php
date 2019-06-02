<?php

namespace App\Controllers;

class BaseController{

  protected $validator;

  public function __construct($validator){    
    $this->validator = $validator;
  }

  public function getValidator(){
    return $this->validator;
  }
}