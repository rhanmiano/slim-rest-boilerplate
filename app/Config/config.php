<?php

namespace App\Config;

class Config {

  public static function config(){
    return [
      'settings' => [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => false,
        'routerCacheFile' => '',
        'db' => self::db()
      ]
    ];
  }

  private function db(){
    return [
      'driver' => 'mysql',
      'host' => 'localhost',
      'database' => 'ilista-demo',
      'user' => 'root',
      'password' => '',
      'charset' => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix' => ''
    ]
  }

}