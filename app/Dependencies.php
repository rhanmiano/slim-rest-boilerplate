<?php

namespace App;


class Dependencies {

  private $container;


  public function __construct($app) {
    $this->container = $app->getContainer();
    $this->inject();
  }


  private function inject() {
    // Monolog
    $this->container['logger'] = function($c) {
        $logger = new \Monolog\Logger('myLogger');
        $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
        $logger->pushHandler($file_handler);
        return $logger;
    };

    // Eloquent ORM
    $this->container['db'] = function($c) {
      $capsule = new \Illuminate\Database\Capsule\Manager;
      $capsule->addConnection(\App\Config\Config::db());

      $capsule->setAsGlobal();
      $capsule->bootEloquent();

      return $capsule;
    };

    // Respect Validator
    $this->container['validator'] = function($c) {
        return new \App\Validation\Validator;
    };
  }
}