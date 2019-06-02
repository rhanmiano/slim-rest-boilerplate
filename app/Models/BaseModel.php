<?php

namespace App\Models;

use ORM;

class BaseModel extends ORM{

  protected $data = array (
    'status' => 'failed',
    'message' => '',
  );

  public function __construct() {
    ORM::configure(\App\Config\Config::db());
  }

}