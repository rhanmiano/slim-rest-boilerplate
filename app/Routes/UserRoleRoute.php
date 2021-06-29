<?php

namespace App\Routes;

class UserRoleRoute {

  public function __construct($app) {

    $pathCtrl = 'App\Controllers\Main\UserRoleCtrl';

    $app->get(
      '/user_role-test',
      $pathCtrl . ':test'
    );

    $app->get(
      '/user_roles',
      $pathCtrl . ':all'
    );

    $app->get(
      '/user_role/{id}',
      $pathCtrl . ':byId'
    );

    $app->post(
      '/user_role',
      $pathCtrl . ':create'
    );

    $app->put(
      '/user_role/{id}',
      $pathCtrl . ':update'
    );

    $app->put(
      '/user_role/{id}/archive',
      $pathCtrl . ':archive'
    );

    $app->put(
      '/user_role/{id}/restore',
      $pathCtrl . ':restore'
    );

    $app->delete(
      '/user_role/{id}',
      $pathCtrl . ':delete'
    );

  }

}