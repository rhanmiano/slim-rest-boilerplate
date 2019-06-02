<?php

namespace App;


class Dependencies {

  private $container;


  public function __construct($app) {
    $this->container = $app->getContainer();
    $this->dependencies();
    // $this->inject($app);
  }


  private function dependencies() {
    $this->container['validator'] = function($c) {
        return new \Respect\Validation\Validator();
    };
  }

  // // Inject dependencies into controllers
  // private function inject($app) {
  //   $this->container['\App\Controllers\BaseController'] = function($c) use ($app) {

  //     return new \App\Controllers\BaseController($c->get('validator'));
  //   };
  // }
}