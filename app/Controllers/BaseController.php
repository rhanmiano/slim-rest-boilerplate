<?php

namespace App\Controllers;

use ORM;

class BaseController{

  public function __construct(){    

    ORM::configure(\App\Config\Config::db());
  }
}