<?php

namespace App\Routes;

class ProfileRoute {

  public function __construct($app) {

    $pathCtrl = 'App\Controllers\Main\ProfileCtrl';

    $app->get(
      '/profiles',
      $pathCtrl . ':all'
    );

    $app->get(
      '/profile/{id:[0-9]+}',
      $pathCtrl . ':byId'
    );

    $app->get(
      '/profile/{uuid:[a-zA-Z0-9 -]+}',
      $pathCtrl . ':byUuid'
    );

    $app->get(
      '/public/profile/{uuid:[a-zA-Z0-9 -]+}/avatar',
      $pathCtrl . ':getAvatar'
    );

    $app->post(
      '/profile',
      $pathCtrl . ':create'
    );

    $app->put(
      '/profile/{uuid:[a-zA-Z0-9 -]+}',
      $pathCtrl . ':update'
    );

    $app->put(
      '/profile/{uuid:[a-zA-Z0-9 -]+}/archive',
      $pathCtrl . ':archive'
    );

    $app->put(
      '/profile/{uuid:[a-zA-Z0-9 -]+}/restore',
      $pathCtrl . ':restore'
    );

    $app->post(
      '/profile/{uuid:[a-zA-Z0-9 -]+}/avatar',
      $pathCtrl . ':updateAvatar'
    );
  }

}