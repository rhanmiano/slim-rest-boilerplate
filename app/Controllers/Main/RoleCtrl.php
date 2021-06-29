<?php

namespace App\Controllers\Main;

use App\Controllers\BaseCtrl;
use App\Helpers\UtilityHelper;
use App\Models\Main\RoleModel as Role;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;

class RoleCtrl extends BaseCtrl {

  public function test(Request $request, Response $response, $args) {

    $helper = new UtilityHelper();

    if (getenv('ENVIRONMENT') !== 'production') {
      $helper->_hello();
    } else {
      $this->res['message'] = FETCH_EMPTY;
      $data                 = $response
        ->withJson($this->res, 404);

      return $data;
    }

  }

  public function all(Request $request, Response $response, $args) {

    $getData = UtilityHelper::_sanitize_array($request->getQueryParams());

    if (sizeof($getData) > 0 && $getData['page'] && $getData['per_page']) {

      if (!is_numeric($getData['page']) || !is_numeric($getData['per_page'])) {
        $this->res['status']  = 'failed';
        $this->res['message'] = PAGINATION_ERR;
        $data                 = $response
          ->withJson($this->res, 200);

        return $data;
        die();
      }

      $result = Role::paginate($getData['per_page'], ['*'], 'page', $getData['page']);
      $result->withPath(getenv('BASE_URL') . 'roles');
      $result->appends(['limit' => $getData['per_page']]);

    } else {

      $result = Role::all();

    }

    if (sizeof($result) > 0) {

      $this->res['status']  = 'success';
      $this->res['message'] = FETCH_SUCC;
      $this->res['data']    = $result;

    } else {

      $this->res['status']  = 'failed';
      $this->res['message'] = FETCH_EMPTY;
      $this->res['data']    = [];

    }

    $data = $response
      ->withJson($this->res, 200);

    return $data;

  }

  public function byId(Request $request, Response $response, $args) {

    $id = (int) UtilityHelper::_sanitize($args['id']);

    $result = Role::find($id);

    if ($result) {

      $this->res['status']  = 'success';
      $this->res['message'] = FETCH_SUCC;
      $this->res['data']    = $result;

    } else {

      $this->res['status']  = 'failed';
      $this->res['message'] = FETCH_EMPTY;
      $this->res['data']    = null;

    }

    $data = $response
      ->withJson($this->res, 200);

    return $data;

  }

  public function create(Request $request, Response $response, $args) {

    $body_args  = (array) $request->getParsedBody();
    $validation = $this->validator->validate($body_args, [
      'name' => v::notEmpty()
        ->alnum(' ')
        ->fieldUnique('App\\Models\\Main\\RoleModel')
    ]);

    if ($validation->failed()) {

      $this->res['status']  = 'failed';
      $this->res['message'] = VLD_ERR;
      $this->res['error']   = $validation->getErrors();

      $response = $response
        ->withJson($this->res, 200);

      return $response;

    }

    $body_args = UtilityHelper::_sanitize_array($request->getParsedBody());
    $result    = Role::_create($body_args);

    if ($result['qry_status']) {

      $this->res['status']       = 'success';
      $this->res['message']      = CREATE_SUCC;
      $this->res['data']         = Role::find($result['id']);
      $this->res['data']['href'] = getenv('BASE_URL') . 'role/' . $result['id'];

    } else {

      $this->res['status']  = 'failed';
      $this->res['message'] = isset($result['message']) ? $result['message'] : CREATE_ERR;

      if (isset($result['errors']) && $result['errors']) {
        $this->res['error']['type']    = APP_ERR;
        $this->res['error']['message'] = $result['errors'];

        $response = $response
          ->withJson($this->res, 500);

        return $response;
      }

    }

    $response = $response
      ->withJson($this->res, 201);

    return $response;

  }

  public function update(Request $request, Response $response, $args) {

    $id        = (int) UtilityHelper::_sanitize($args['id']);
    $body_args = $request->getParsedBody();

    $validation = $this->validator->validate($body_args, [
      'name' => v::notEmpty()
        ->alnum(' ')
        ->fieldUniqueUpdate('App\\Models\\Main\\RoleModel', $id)
    ]);

    if ($validation->failed()) {

      $this->res['status']  = 'failed';
      $this->res['message'] = VLD_ERR;
      $this->res['error']   = $validation->getErrors();

      $response = $response
        ->withJson($this->res, 200);

      return $response;

    }

    $body_args = UtilityHelper::_sanitize_array($request->getParsedBody());
    $result    = Role::_update($id, $body_args);

    if ($result['qry_status']) {

      $this->res['status']  = 'success';
      $this->res['message'] = UPDATE_SUCC;

      $this->res['data']         = Role::find($id);
      $this->res['data']['href'] = getenv('BASE_URL') . 'role/' . $id;

    } else {

      $this->res['status']  = 'failed';
      $this->res['message'] = isset($result['message']) ? $result['message'] : UPDATE_ERR;

      if (isset($result['errors']) && $result['errors']) {
        $this->res['error']['type']    = APP_ERR;
        $this->res['error']['message'] = $result['errors'];

        $response = $response
          ->withJson($this->res, 500);

        return $response;
      }

    }

    $response = $response
      ->withJson($this->res, 200);

    return $response;

  }

  public function archive(Request $request, Response $response, $args) {

    $id = (int) UtilityHelper::_sanitize($args['id']);

    $result = Role::_archive($id);

    if ($result['qry_status']) {

      $this->res['status']  = 'success';
      $this->res['message'] = ARCHIVE_SUCC;

    } else {

      $this->res['status']  = 'failed';
      $this->res['message'] = isset($result['message']) ? $result['message'] : ARCHIVE_ERR;

      if (isset($result['errors']) && $result['errors']) {
        $this->res['error']['type']    = APP_ERR;
        $this->res['error']['message'] = $result['errors'];

        $response = $response
          ->withJson($this->res, 500);

        return $response;
      }

    }

    $response = $response
      ->withJson($this->res, 200);

    return $response;

  }

  public function restore(Request $request, Response $response, $args) {

    $id = (int) UtilityHelper::_sanitize($args['id']);

    $result = Role::_restore($id);

    if ($result['qry_status']) {

      $this->res['status']       = 'success';
      $this->res['message']      = RESTORE_SUCC;
      $this->res['data']         = Role::find($id);
      $this->res['data']['href'] = getenv('BASE_URL') . 'role/' . $id;

    } else {

      $this->res['status']  = 'failed';
      $this->res['message'] = isset($result['message']) ? $result['message'] : RESTORE_ERR;

      if (isset($result['errors']) && $result['errors']) {
        $this->res['error']['type']    = APP_ERR;
        $this->res['error']['message'] = $result['errors'];

        $response = $response
          ->withJson($this->res, 500);

        return $response;
      }

    }

    $response = $response
      ->withJson($this->res, 200);

    return $response;

  }

  public function delete(Request $request, Response $response, $args) {

    $id = (int) UtilityHelper::_sanitize($args['id']);

    $result = Role::_delete($id);

    if ($result['qry_status']) {

      $this->res['status']  = 'success';
      $this->res['message'] = DELETE_SUCC;

    } else {

      $this->res['status']  = 'failed';
      $this->res['message'] = isset($result['message']) ? $result['message'] : DELETE_ERR;

      if (isset($result['errors']) && $result['errors']) {
        $this->res['error']['type']    = APP_ERR;
        $this->res['error']['message'] = $result['errors'];

        $response = $response
          ->withJson($this->res, 500);

        return $response;
      }

    }

    $response = $response
      ->withJson($this->res, 200);

    return $response;

  }

}