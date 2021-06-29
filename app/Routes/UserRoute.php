<?php

namespace App\Routes;

class UserRoute {

  public function __construct($app) {

    $pathCtrl = 'App\Controllers\Main\UserCtrl';

    $app->get(
      '/users',
      $pathCtrl . ':all'
    );

    $app->get(
      '/user/{id:[0-9]+}',
      $pathCtrl . ':byId'
    );

    $app->get(
      '/user/{uuid:[a-zA-Z0-9 -]+}',
      $pathCtrl . ':byUuid'
    );

    $app->post(
      '/signup',
      $pathCtrl . ':create'
    );

    $app->put(
      '/user/{uuid:[a-zA-Z0-9 -]+}',
      $pathCtrl . ':update'
    );

    $app->put(
      '/user/{uuid:[a-zA-Z0-9 -]+}/archive',
      $pathCtrl . ':archive'
    );

    $app->put(
      '/user/{uuid:[a-zA-Z0-9 -]+}/restore',
      $pathCtrl . ':restore'
    );

    $app->delete(
      '/user/{uuid:[a-zA-Z0-9 -]+}',
      $pathCtrl . ':delete'
    );

    $app->put(
      '/user/{uuid:[a-zA-Z0-9 -]+}/profile',
      $pathCtrl . ':updateProfile'
    );

    $app->put(
      '/user/{uuid:[a-zA-Z0-9 -]+}/password',
      $pathCtrl . ':changePassword'
    );

    $app->get(
      '/user/{uuid:[a-zA-Z0-9 -]+}/events',
      $pathCtrl . ':eventsByUuid'
    );

    // $app->post(
    //   '/profile/{uuid:[a-zA-Z0-9 -]+}/avatar',
    //   $pathCtrl.':updateAvatar'
    // );

    /** Public Endpoints */
    $app->get(
      '/public/user/{username:[a-zA-Z0-9 ]+}',
      $pathCtrl . ':byUserName'
    );

    $app->put(
      '/public/user/verify',
      $pathCtrl . ':verify'
    );

    $app->put(
      '/public/user/forgot_password',
      $pathCtrl . ':forgotPassword'
    );
  }

}