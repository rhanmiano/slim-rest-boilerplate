<?php

namespace App\Controllers;

class BaseController{

  protected $validator;

  public function __construct($app){
    $this->validator = $app->get('validator');
  }

  public function getValidator(){
    return $this->validator;
  }
}