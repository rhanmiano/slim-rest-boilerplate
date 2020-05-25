<?php

namespace App\Config;

use PDO;

class Config {

  public function slimSettings(){
    return array(
      'settings' => array(
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => true,
        'routerCacheFile' => '',
      )
    );
  }

  public function db(){
    return array (
      'connection_string' => 'mysql:host='.getenv('DB_HOST').';dbname='.getenv('DB_NAME').';charset=utf8mb4',
      'driver' => 'mysql',
      'host' => getenv('DB_HOST'),
      'database' => getenv('DB_NAME'),
      'username' => getenv('DB_USER'),
      'password' => getenv('DB_PASS'),
      'charset'   => 'utf8',
      'collation' => 'utf8_general_ci',
      'prefix'    => '',
      'return_result_sets' => false,
      'error_mode' => PDO::ERRMODE_WARNING,
    );
  }

}