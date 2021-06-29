<?php

namespace App\Routes;

class RoleRoute {

  public function __construct($app) {

    $pathCtrl = 'App\Controllers\Main\RoleCtrl';

    $app->get(
      '/role-test',
      $pathCtrl . ':test'
    );

    $app->get(
      '/roles',
      $pathCtrl . ':all'
    );

    $app->get(
      '/role/{id}',
      $pathCtrl . ':byId'
    );

    $app->post(
      '/role',
      $pathCtrl . ':create'
    );

    $app->put(
      '/role/{id}',
      $pathCtrl . ':update'
    );

    $app->put(
      '/role/{id}/archive',
      $pathCtrl . ':archive'
    );

    $app->put(
      '/role/{id}/restore',
      $pathCtrl . ':restore'
    );

    $app->delete(
      '/role/{id}',
      $pathCtrl . ':delete'
    );

  }

}