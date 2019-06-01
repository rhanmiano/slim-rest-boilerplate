<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\BaseController;
use App\Models\CustomersModel as Customers;

class CustomerController extends BaseController{

  public function all(Request $request, Response $response, $args) {
    $customers = new Customers();
    $retval = $customers->getAllCustomers();

    $response = $response->withStatus(200)
        ->withHeader('Content-type', 'application/json')
        ->withJson($retval);
    return $response;
  }


  public function create(Request $request, Response $response, $args) {

  }
}