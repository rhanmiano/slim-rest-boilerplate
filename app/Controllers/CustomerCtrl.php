<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\BaseCtrl;
use App\Models\CustomerModel as Customer;
use Respect\Validation\Validator as v;
use App\Helpers\UtilityHelper;

class CustomerCtrl extends BaseCtrl{
  
  public function test(Request $request, Response $response, $args) {

    $helper = new UtilityHelper();

    $helper->_hello();

  }

  public function all(Request $request, Response $response, $args) {

    $result = Customer::_all();

    if (!empty($result)) {

      $this->res['status']    = 'success';
      $this->res['message']   = FETCH_SUCC;
      $this->res['customers'] = $result;

      $response->withStatus(200);

    } else {

      $this->res['status']    = 'failed';
      $this->res['message']   = FETCH_EMPTY;
      $this->res['customers'] = [];

      $response->withStatus(204);
    }
    
    $data = $response
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $data;

  }


  public function byId(Request $request, Response $response, $args) {

    $id = (int)$args['id'];

    $result = Customer::_byId($id);

    if (!empty($result)) {

      $this->res['status']   = 'success';
      $this->res['message']  = FETCH_SUCC;
      $this->res['customer'] = $result;

    } else {

      $this->res['status']   = 'failed';
      $this->res['message']  = FETCH_EMPTY;
      $this->res['customer'] = null;

    }

    $data = $response
      ->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $data;

  }

  public function create(Request $request, Response $response, $args) {

    $body_args = json_decode($request->getBody());

    $validation = $this->validator->validate($body_args, [
      'name'     => v::notEmpty()->alpha(),
      'password' => v::noWhitespace()->notEmpty(),
      'email'    => v::noWhitespace()->notEmpty()->email(),
      'age'      => v::noWhitespace()->notEmpty()->numeric()
    ]);

    if ($validation->failed()) {

      $this->res['status']          = 'failed';
      $this->res['message']         = VLD_ERR;
      $this->res['error']['type']   = VLD_ERR_TYPE;
      $this->res['error']['fields'] = $validation->getErrors();

      $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

      return $response;

    }

    $result = Customer::_create($body_args);

    if ($result['qry_status']) {

      $this->res['status']   = 'success';
      $this->res['message']  = CREATE_SUCC;
      $this->res['customer'] = Customer::_byId($result['id']);
      $this->res['customer']['href'] = getenv('BASE_URL').'customer/'.$result['id'];

    } else {

      $this->res['status']  = 'failed';
      $this->res['message'] = CREATE_ERR;

      if (isset($result['errors']) && $result['errors']) {
        $this->res['error']['type']    = APP_ERR;
        $this->res['error']['message'] = $result['errors'];
      }

    }    

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }

  public function update(Request $request, Response $response, $args) {

    $id = (int)$args['id'];
    $body_args = json_decode($request->getBody());

    $validation = $this->validator->validate($body_args, [
      'name' => v::notEmpty()->alpha(),
      'email' => v::noWhitespace()->notEmpty()->email(),
      'age' => v::noWhitespace()->notEmpty()->numeric()
    ]);

    if ($validation->failed()) {

      $this->res['status']          = 'failed';
      $this->res['message']         = VLD_ERR;
      $this->res['error']['type']   = VLD_ERR_TYPE;
      $this->res['error']['fields'] = $validation->getErrors();

      $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

      return $response;

    }

    $result = Customer::_update($id, $body_args);

    if ($result['qry_status']) {

      $this->res['status']  = 'success';
      $this->res['message'] = UPDATE_SUCC;

      $this->res['customer'] = Customer::_byId($id);
      $this->res['customer']['href'] = getenv('BASE_URL').'customer/'.$id;

    } else {

      $this->res['status']  = 'failed';
      $this->res['message'] = isset($result['message']) ? $result['message'] : UPDATE_ERR;

      if (isset($result['errors']) && $result['errors']) {
        $this->res['error']['type']    = APP_ERR;
        $this->res['error']['message'] = $result['errors'];
      }

    }    

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }

  public function archive(Request $request, Response $response, $args) {

    $id = (int) $args['id'];

    $result = Customer::_archive($id);

    if ($result['qry_status']) {

      $this->res['status']  = 'success';
      $this->res['message'] = ARCHIVE_SUCC;

    } else {

      $this->res['status'] = 'failed';
      $this->res['message'] = isset($result['message']) ? $result['message'] : ARCHIVE_ERR;

      if (isset($result['errors']) && $result['errors']) {
        $this->res['error']['type']    = APP_ERR;
        $this->res['error']['message'] = $result['errors'];
      }

    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }

  public function restore(Request $request, Response $response, $args) {

    $id = (int) $args['id'];

    $result = Customer::_restore($id);

    if ($result['qry_status']) {

      $this->res['status']  = 'success';
      $this->res['message'] = RESTORE_SUCC;
      $this->res['customer'] = Customer::_byId($id);
      $this->res['customer']['href'] = getenv('BASE_URL').'customer/'.$id;

    } else {

      $this->res['status'] = 'failed';
      $this->res['message'] = isset($result['message']) ? $result['message'] : RESTORE_ERR;

      if (isset($result['errors']) && $result['errors']) {
        $this->res['error']['type']    = APP_ERR;
        $this->res['error']['message'] = $result['errors'];
      }

    }

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }

  public function delete(Request $request, Response $response, $args) {

    $id = (int) $args['id'];

    $result = Customer::_delete($id);

    if ($result['qry_status']) {

      $this->res['status']  = 'success';
      $this->res['message'] = DELETE_SUCC;

    } else {

      $this->res['status']  = 'failed';
      $this->res['message'] = isset($result['message']) ? $result['message'] : DELETE_ERR;

      if (isset($result['errors']) && $result['errors']) {
        $this->res['error']['type']    = APP_ERR;
        $this->res['error']['message'] = $result['errors'];
      }


    }    

    $response = $response->withStatus(200)
      ->withHeader('Content-type', 'application/json')
      ->withJson($this->res);

    return $response;

  }

}