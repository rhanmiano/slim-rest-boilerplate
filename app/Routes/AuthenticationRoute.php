<?php

namespace App\Routes;

class AuthenticationRoute {

  public function __construct($app) {

    $pathCtrl = 'App\Controllers\Authentication\AuthCtrl';

    $app->get(
      '/auth/test',
      $pathCtrl . ':test'
    );

    $app->post(
      '/auth/signin',
      $pathCtrl . ':signin'
    );

    $app->post(
      '/auth/signout',
      $pathCtrl . ':signout'
    );

  }

}
