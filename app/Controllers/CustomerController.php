<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\BaseController;
use App\Models\CustomerModel as Customers;
use Respect\Validation\Validator as v;

class CustomerController extends BaseController{

  public function test(Request $request, Response $response, $args) {
    $response->write('Hello World');
  }

  public function all(Request $request, Response $response, $args) {
    $customers = new Customers();

    $retval = $customers->getAllCustomers();

    $response = $response->withStatus(200)
        ->withHeader('Content-type', 'application/json')
        ->withJson($retval);
    return $response;
  }


  public function byId(Request $request, Response $response, $args) {
    $id = (int)$args['id'];
    $customers = new Customers();

    $retval = $customers->getCustomerById($id);

    $response = $response->withStatus(200)
        ->withHeader('Content-type', 'application/json')
        ->withJson($retval);
    return $response;
  }


  public function create(Request $request, Response $response, $args) {
    $body_args = json_decode($request->getBody());

    $errors = [];
    $validation = $this->validator->validate($request, [
      'name' => v::notEmpty(),
      'email' => v::notEmpty(),
      'age' => v::notEmpty()
    ]);
  }
}