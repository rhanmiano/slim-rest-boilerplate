<?php

namespace App;

require_once('Constants.php');

class Init {

  private $app;

  public function __construct() {
    $this->app = new \Slim\App(\App\Config\Config::slimSettings());
    $this->dependencies();
    $this->middleware();
    $this->routes();    
  }


  public function getApp() {
    return $this->app;
  }


  private function dependencies() {
    return new \App\Dependencies($this->getApp());
  }


  private function middleware() {
    return new \App\Middleware($this->getApp());
  }


  private function routes() {
    return array(
      new \App\Routes\CustomerRoute($this->getApp()),
    );
  }
  
}