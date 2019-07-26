<?php

namespace App\Controllers;

class BaseCtrl{

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