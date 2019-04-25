<?php

namespace App;

class App {

  private $app;

  public function __construct() {
    $app = new \Slim\App(\App\Config\Config::settings());
    $this->app = $app;
    $this->dependencies();
    $this->middleware();
    $this->routes();
  }


  protected function getApp() {
    return $this->app;
  }


  private function dependencies() {
    return new \App\Dependencies($this->getApp());
  }


  private function middleware() {
    return new \App\Middleware($this->getApp());
  }


  private function routes() {
    return []; // Todo Add Routes Here
  }
  
}