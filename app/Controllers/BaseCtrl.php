<?php

namespace App\Controllers;

class BaseCtrl{

  protected $validator;
  protected $db;

  protected $res = array (

    'status' => 'failed',
    'message' => '',

  );

  public function __construct($app){

    $this->validator = $app->get('validator');
    $this->db = $app->get('db');
  }

  public function getValidator(){

    return $this->validator;

  }
  
}