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
    $customers = $customers->getAllCustomers();

    if (!empty($customers)) {

      $this->retval['status']    = 'success';
      $this->retval['message']   = FTCHD_SUCC;
      $this->retval['customers'] = $customers;

    } else {

      $this->retval['status']    = 'failed';
      $this->retval['message']   = FTCHD_EMPTY;
      $this->retval['customers'] = [];

    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->retval);

    return $response;

  }


  public function byId(Request $request, Response $response, $args) {

    $id = (int)$args['id'];
    $customers = new Customers();

    $customer = $customers->getCustomerById($id);

    if (!empty($customer)) {

      $this->retval['status']    = 'success';
      $this->retval['message']   = FTCHD_SUCC;
      $this->retval['customer'] = $customer;

    } else {

      $this->retval['status']    = 'failed';
      $this->retval['message']   = FTCHD_EMPTY;
      $this->retval['customer'] = [];

    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->retval);

    return $response;

  }


  public function create(Request $request, Response $response, $args) {

    $body_args = json_decode($request->getBody());  

    $validation = $this->validator->validate($body_args, [
      'name' => v::notEmpty()->alpha(),
      'email' => v::noWhitespace()->notEmpty()->email(),
      'age' => v::noWhitespace()->notEmpty()->numeric()
    ]);

    if ($validation->failed()) {

      $this->retval['status']    = 'failed';
      $this->retval['message']   = VLD_ERR;
      $this->retval['error']['message'] = $validation->getError();

    }

  }

}