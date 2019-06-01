<?php

namespace App;


class Dependencies {

  private $container;


  public function __construct($app) {
    $this->container = $app->getContainer();
    $this->dependencies();
    $this->inject($app);
  }


  private function dependencies() {
  }

  // Inject dependencies into controllers
  private function inject($app) {
  }
}