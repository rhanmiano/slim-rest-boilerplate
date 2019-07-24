<?php

namespace App\Controllers;

class BaseController{

  protected $validator;

  protected $retval = array (
    'status' => 'failed',
    'message' => '',
  );

  public function __construct($app){
    $this->validator = $app->get('validator');
  }

  public function getValidator(){
    return $this->validator;
  }
}