<?php

namespace App\Models;

use ORM;

class BaseModel extends ORM{

  public function __construct() {
    ORM::configure(\App\Config\Config::db());
  }

}