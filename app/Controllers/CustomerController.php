<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\BaseController;

class CustomerController extends BaseController{

  public function test(Request $request, Response $response, $args) {
    $response->write('Hello World');
  }


  public function create(Request $request, Response $response, $args) {

  }
}