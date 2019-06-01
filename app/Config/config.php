<?php

namespace App\Config;
use PDO;

class Config {

  public function slimSettings(){
    return array(
      'settings' => array(
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => false,
        'routerCacheFile' => '',
      )
    );
  }

  public function db(){
    return array (
      'connection_string' => 'mysql:host=localhost;dbname=my_database;charset=utf8mb4',
      'username' => 'root',
      'password' => '',
      'return_result_sets' => false,
      'error_mode' => PDO::ERRMODE_WARNING,
    );
  }

}