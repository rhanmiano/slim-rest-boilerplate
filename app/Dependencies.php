<?php

namespace App;

class Dependencies() {

  private $container;


  public function __construct($app) {
    $this->container = $app->getContainer();
    $this->dependencies();
    $this->inject($app);
  }


  private function dependencies() {
    $this->container['db'] = function($container) {
      $settings = $container['settings']['db'];
      $db = new \Pdo;
    }
  }

}