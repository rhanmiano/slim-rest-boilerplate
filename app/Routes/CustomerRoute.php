<?php

namespace App\Routes;

class CustomerRoute {

  public function __construct($app) {

    $app->get('/test', 'App\Controllers\CustomerCtrl:test');
    $app->get('/customers', 'App\Controllers\CustomerCtrl:all');
    $app->get('/customer/{id}', 'App\Controllers\CustomerCtrl:byId');
    $app->post('/customer', 'App\Controllers\CustomerCtrl:create');
    $app->put('/customer/{id}', 'App\Controllers\CustomerCtrl:update');
    $app->put('/customer/{id}/archive', 'App\Controllers\CustomerCtrl:archive');
    $app->put('/customer/{id}/restore', 'App\Controllers\CustomerCtrl:restore');
    $app->delete('/customer/{id}', 'App\Controllers\CustomerCtrl:delete');

  }

}