<?php

namespace App\Routes;

class CustomerRoute {

  public function __construct($app) {
    $app->get('/test', 'App\Controllers\CustomerController:test');
  }

}