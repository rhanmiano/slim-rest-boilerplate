<?php

namespace App\Routes;

class CustomerRoute {

  public function __construct($app) {
    $app->get('/customers', 'App\Controllers\CustomerController:all');
  }

}