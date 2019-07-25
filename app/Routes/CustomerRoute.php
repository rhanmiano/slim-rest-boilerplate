<?php

namespace App\Routes;

class CustomerRoute {

  public function __construct($app) {

    $app->get('/test', 'App\Controllers\CustomerController:test');
    $app->get('/customers', 'App\Controllers\CustomerController:all');
    $app->get('/customer/{id}', 'App\Controllers\CustomerController:byId');
    $app->post('/customer/create', 'App\Controllers\CustomerController:create');
    $app->put('/customer/update/{id}', 'App\Controllers\CustomerController:update');
    $app->delete('/customer/delete/{id}', 'App\Controllers\CustomerController:delete');

  }

}