<?php

namespace App\Controllers\Main;

use App\Controllers\BaseCtrl;
use App\Filters\UserFilters;
use App\Helpers\EmailHelper;
use App\Helpers\UploadHelper;
use App\Helpers\UtilityHelper;
use App\Libraries\Itexmo;
use App\Models\Main\UserModel as User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use Slim\Views\PhpRenderer;

class UserCtrl extends BaseCtrl {

  public function all(Request $request, Response $response, $args) {

    $getData = UtilityHelper::_sanitize_array($request->getQueryParams());
    $filters = new UserFilters($request);

    if (sizeof($getData) > 0) {

      if (isset($getData['page']) && isset($getData['per_page'])) {

        if (!is_numeric($getData['page']) || !is_numeric($getData['per_page'])) {

          $this->res['status']  = 'failed';
          $this->res['message'] = PAGINATION_ERR;
          $data                 = $response
            ->withJson($this->res, 200);

          return $data;
          die();

        }

        $result = User::with('profile')
          ->with('roles')
          ->filter($filters)
          ->paginate($getData['per_page'], ['*'], 'page', $getData['page']);

        $result->withPath(getenv('BASE_URL') . 'users');
        $result->appends($getData);

      } else {

        $result = User::with('profile')->with('roles')->filter($filters)->get();

      }

    } else {

      $result = User::with('profile')->with('roles')->get();

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

    $result = User::with('profile')->with('roles')->find($id);

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

  public function byUuid(Request $request, Response $response, $args) {

    $uuid = (string) UtilityHelper::_sanitize($args['uuid']);

    $result = User::with('profile')->with('roles')->firstWhere('uuid', $uuid);

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

  public function byUserName(Request $request, Response $response, $args) {

    $username = (string) UtilityHelper::_sanitize($args['username']);

    $result = User::where('username', $username)->with('profile')->first();

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

    $body_args = $request->getParsedBody();

    $validate_fields = [
      'username' => v::fieldUnique('App\\Models\\Main\\UserModel')
        ->alnum('_')
        ->noWhitespace()
        ->notEmpty(),
      'phone_no' => v::fieldUnique('App\\Models\\Main\\ProfileModel')
        ->numericVal()
        ->noWhitespace()
        ->notEmpty(),
      'email'    => v::fieldUnique('App\\Models\\Main\\ProfileModel')
        ->email()
        ->noWhitespace()
        ->notEmpty(),
      'role_id'  => v::fieldExists('App\\Models\\Main\\RoleModel', 'id')
        ->numericVal()
        ->noWhitespace()
        ->notEmpty()
    ];

    // User Creation can be done thru registration or by cms
    // Add fields to be validated where it is applicable
    if ($body_args['type'] == 'register') {
      $validate_fields = array_merge($validate_fields, [
        'password'         => v::length(8, null)->noWhitespace()->notEmpty(),
        'confirm_password' => v::matches($body_args['password'])->noWhitespace()->notEmpty()
      ]);

      $body_args['profile'] = array();
    }

    $validation = $this->validator->validate($body_args, $validate_fields);

    if ($validation->failed()) {

      $this->res['status']  = 'failed';
      $this->res['message'] = VLD_ERR;
      $this->res['error']   = $validation->getErrors();

      $response = $response
        ->withJson($this->res, 200);

      return $response;

    }

    if (isset($body_args['profile']) && $body_args['type'] == 'admin') {

      $validation2 = $this->validator->validate($body_args['profile'], [
        'fname'  => v::optional(
          v::alpha(' ')
            ->notEmpty()
        ),
        'lname'  => v::optional(
          v::alpha(' ')
            ->notEmpty()
        ),
        'gender' => v::optional(
          v::noWhitespace()
            ->alpha()
            ->in(['M', 'F'])
        )
      ]);

      if ($validation2->failed()) {

        $this->res['status']  = 'failed';
        $this->res['message'] = VLD_ERR;
        $this->res['error']   = $validation2->getErrors();

        $response = $response
          ->withJson($this->res, 200);

        return $response;

      }

      $body_args['password'] = UtilityHelper::_generate_random_string(8, 'ALPHA_NUMERIC');
    }

    $body_args                        = UtilityHelper::_sanitize_array($body_args);
    $body_args['profile']['email']    = $body_args['email'];
    $body_args['profile']['phone_no'] = $body_args['phone_no'];

    // Create profile
    $result = UserProfile::_create($body_args['profile']);

    if ($result['qry_status']) {

      // Create user record
      $temp_args = array(
        'profile_id' => $result['id']
      );

      $user = User::_create(array_merge($temp_args, $body_args));

      // Create user role
      $temp_args = array(
        'user_id' => $user['id'],
        'role_id' => $body_args['role_id']
      );

      UserRole::_create($temp_args);

      $this->res['status']       = 'success';
      $this->res['message']      = 'Registration successful. Please check the registered email for verification.';
      $this->res['data']         = $user         = User::with('profile')->find($user['id']);
      $this->res['data']['href'] = getenv('BASE_URL') . 'user/' . $user['uuid'];

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

    $uuid      = (string) UtilityHelper::_sanitize($args['uuid']);
    $body_args = $request->getParsedBody();

    $user = User::firstWhere('uuid', $uuid);

    $validation = $this->validator->validate($body_args, [
      'username' => v::fieldUniqueUpdate('App\\Models\\Main\\UserModel', $user->id)
        ->alnum()
        ->noWhitespace()
        ->notEmpty(),
      'phone_no' => v::fieldUniqueUpdate('App\\Models\\Main\\ProfileModel', $user->profile_id)
        ->numericVal()
        ->noWhitespace()
        ->notEmpty(),
      'email'    => v::fieldUniqueUpdate('App\\Models\\Main\\ProfileModel', $user->profile_id)
        ->email()
        ->noWhitespace()
        ->notEmpty()
    ]);

    if ($validation->failed()) {

      $this->res['status']  = 'failed';
      $this->res['message'] = VLD_ERR;
      $this->res['error']   = $validation->getErrors();

      $response = $response
        ->withJson($this->res, 200);

      return $response;

    }

    if (isset($body_args['profile']) && is_array($body_args['profile'])) {
      $validation2 = $this->validator->validate($body_args['profile'], [
        'fname'          => v::optional(
          v::alpha(' ')
        ),
        'lname'          => v::optional(
          v::alpha(' ')
        ),
        'gender'         => v::optional(
          v::noWhitespace()
            ->alpha()
            ->in(['M', 'F'])
        ),
        'position_title' => v::optional(
          v::alpha(' ')
        ),
        'location'       => v::optional(
          v::regex(UtilityHelper::_regex_keyboard_symbols())
        )
      ]);

      if ($validation2->failed()) {

        $this->res['status']  = 'failed';
        $this->res['message'] = VLD_ERR;
        $this->res['error']   = $validation2->getErrors();

        $response = $response
          ->withJson($this->res, 200);

        return $response;

      }

    }

    $body_args = UtilityHelper::_sanitize_array($body_args);
    $result    = User::_update($uuid, $body_args);

    if ($result['qry_status']) {

      $this->res['status']  = 'success';
      $this->res['message'] = isset($result['message']) ? $result['message'] : UPDATE_SUCC;

      $this->res['data']         = User::firstWhere('uuid', $uuid);
      $this->res['data']['href'] = getenv('BASE_URL') . 'user/' . $uuid;

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

    if (isset($body_args['profile']) && is_array($body_args['profile'])) {

      $body_args['profile']['email']    = $body_args['email'];
      $body_args['profile']['phone_no'] = $body_args['phone_no'];

      $result2 = UserProfile::_update($body_args['profile'], $user->profile->uuid);

      if ($result2['qry_status']) {

        $this->res['status']  = 'success';
        $this->res['message'] = UPDATE_SUCC;

        $this->res['data']         = User::firstWhere('uuid', $uuid);
        $this->res['data']['href'] = getenv('BASE_URL') . 'user/' . $uuid;

      } else {

        $this->res['status']  = 'failed';
        $this->res['message'] = isset($result2['message']) ? $result2['message'] : UPDATE_ERR;

        if (isset($result['errors']) && $result2['errors']) {
          $this->res['error']['type']    = APP_ERR;
          $this->res['error']['message'] = $result2['errors'];

          $response = $response
            ->withJson($this->res, 500);

          return $response;
        }

      }

    }

    $response = $response
      ->withJson($this->res, 200);

    return $response;

  }

  public function archive(Request $request, Response $response, $args) {

    $uuid = (string) UtilityHelper::_sanitize($args['uuid']);

    $result = User::_archive($uuid);

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

    $uuid = (string) UtilityHelper::_sanitize($args['uuid']);

    $result = User::_restore($uuid);

    if ($result['qry_status']) {

      $this->res['status']       = 'success';
      $this->res['message']      = RESTORE_SUCC;
      $this->res['data']         = User::find($id);
      $this->res['data']['href'] = getenv('BASE_URL') . 'user/' . $id;

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

    $uuid = (string) UtilityHelper::_sanitize($args['uuid']);

    $result = User::_delete($id);

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

  public function verify(Request $request, Response $response, $args) {

    $body_args = $request->getParsedBody();

    $validation = $this->validator->validate($body_args, [
      'code'              => v::length(7, 7)
        ->numericVal()
        ->noWhitespace()
        ->notEmpty(),
      'uuid'              => v::fieldExists('App\\Models\\Main\\UserModel', 'uuid')
        ->noWhitespace()
        ->notEmpty(),
      'verification_type' => v::numericVal()
        ->in(['1', '2'])
    ]);

    if ($validation->failed()) {

      $this->res['status']  = 'failed';
      $this->res['message'] = VLD_ERR;
      $this->res['error']   = $validation->getErrors();

      $response = $response
        ->withJson($this->res, 200);

      return $response;

    }

    $id = User::where('uuid', $body_args['uuid'])->first()->id;

    $body_args = UtilityHelper::_sanitize_array($body_args);
    $result    = UserVerification::_verify($body_args, $id);

    if ($result['qry_status']) {
      $supplement = '';

      if ($body_args['verification_type'] === '1') {
        // Create verification recordfor the user to be verified in sms
        $code      = UtilityHelper::_generate_random_string(7, 'NUMERIC');
        $temp_args = array(
          'user_id'           => $id,
          'code'              => $code,
          'verification_type' => 2 // 1 = email, 2 =sms
        );

        UserVerification::_create($temp_args);

        // Send SMS
        $phone_no = getenv('ENVIRONMENT') === 'dev'
        ? getenv('ITEXMO_TEST_NO')
        : User::find($id)->phone_no;

        $message = $code . ' is your VMS phone verification code';
        $send    = Itexmo::sendSMS($phone_no, $message, getenv('ITEXMO_API_CODE'));

        $suppplement = ' Please wait for another code to be sent to your phone number.';

      } else

      if ($body_args['verification_type'] === '2') {

        $supplement = ' You may now login to the system.';

      }

      $this->res['status']  = 'success';
      $this->res['message'] = VERIFY_SUCC . $supplement;

    } else {

      $this->res['status']  = 'failed';
      $this->res['message'] = isset($result['message']) ? $result['message'] : VERIFY_ERR;

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

  public function updateProfile(Request $request, Response $response, $args) {

    $uuid      = (string) UtilityHelper::_sanitize($args['uuid']);
    $body_args = $request->getParsedBody();

    $user = User::firstWhere('uuid', $uuid);

    $validation = $this->validator->validate($body_args, [
      'phone_no'       => v::fieldUniqueUpdate('App\\Models\\Main\\ProfileModel', $user->profile_id)
        ->numericVal()
        ->noWhitespace()
        ->notEmpty(),
      'email'          => v::fieldUniqueUpdate('App\\Models\\Main\\ProfileModel', $user->profile_id)
        ->email()
        ->noWhitespace()
        ->notEmpty(),
      'fname'          => v::optional(
        v::alpha(' ')
      ),
      'lname'          => v::optional(
        v::alpha(' ')
      ),
      'gender'         => v::optional(
        v::noWhitespace()
          ->alpha()
          ->in(['M', 'F'])
      ),
      'position_title' => v::optional(
        v::alpha(' ')
      ),
      'location'       => v::optional(
        v::regex(UtilityHelper::_regex_keyboard_symbols())
      )
    ]);

    if ($validation->failed()) {

      $this->res['status']  = 'failed';
      $this->res['message'] = VLD_ERR;
      $this->res['error']   = $validation->getErrors();

      $response = $response
        ->withJson($this->res, 200);

      return $response;

    }

    $body_args = UtilityHelper::_sanitize_array($body_args);
    $profile   = UserProfile::find($user->profile_id);
    $result    = UserProfile::_update($body_args, $profile->uuid);

    if ($result['qry_status']) {

      $this->res['status']  = 'success';
      $this->res['message'] = isset($result['message']) ? $result['message'] : UPDATE_SUCC;

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

  public function changePassword(Request $request, Response $response, $args) {

    $uuid      = (string) UtilityHelper::_sanitize($args['uuid']);
    $body_args = $request->getParsedBody();
    // dd($body_args);
    $user       = User::firstWhere('uuid', $uuid);
    $validation = $this->validator->validate($body_args, [
      'password'         => v::noWhitespace()->notEmpty(),
      'new_password'     => v::length(8, null)->noWhitespace()->notEmpty(),
      'confirm_password' => v::matches($body_args['new_password'])
        ->noWhitespace()
        ->notEmpty()
    ]);

    if ($validation->failed()) {

      $this->res['status']  = 'failed';
      $this->res['message'] = VLD_ERR;
      $this->res['error']   = $validation->getErrors();

      $response = $response
        ->withJson($this->res, 200);

      return $response;

    }

    if (!password_verify($body_args['password'], $user['password'])) {
      $this->res['status']          = 'failed';
      $this->res['message']         = VLD_ERR;
      $this->res['error']['type']   = VLD_ERR_TYPE;
      $this->res['error']['fields'] = array(
        'password' => PASSWORD_INVLD
      );

      $response = $response
        ->withHeader('Content-type', 'application/json')
        ->withJson($this->res);

      return $response;
      die();
    }

    // die(var_dump($body_args));
    $body_args = UtilityHelper::_sanitize_array($body_args);

    $result = User::_updatePassword($uuid, $body_args);

    if ($result['qry_status']) {

      $this->res['status']  = 'success';
      $this->res['message'] = 'Password has been updated successfully';

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

  public function eventsByUuid(Request $request, Response $response, $args) {

    $uuid = (string) UtilityHelper::_sanitize($args['uuid']);

    $result = User::with(['profile.events' => function ($query) {
      $query->with('organizedBy.profile');
    }

    ])->firstWhere('uuid', $uuid);

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

  public function forgotPassword(Request $request, Response $response, $args) {

    $body_args = $request->getParsedBody();

    $validate_fields = [
      'type' => v::in(['email', 'phone'])
        ->alpha()
        ->noWhitespace()
        ->notEmpty()
    ];
    $user = null;

    if ($body_args['type'] == 'email') {
      $validate_fields = array_merge($validate_fields, [
        'email' => v::fieldExists('App\\Models\\Main\\ProfileModel', 'email')
          ->email()
          ->noWhitespace()
          ->notEmpty()
      ]);

      if (isset($body_args['email'])) {
        $profile = UserProfile::firstWhere('email', $body_args['email']);
      }

    } else

    if ($body_args['type'] == 'phone') {
      $validate_fields = array_merge($validate_fields, [
        'phone_no' => v::fieldExists('App\\Models\\Main\\ProfileModel', 'phone_no')
          ->numericVal()
          ->noWhitespace()
          ->notEmpty()
      ]);

      if (isset($body_args['phone_no'])) {
        $profile = UserProfile::firstWhere('phone_no', $body_args['phone_no']);
      }

    }

    $validation = $this->validator->validate($body_args, $validate_fields);

    if ($validation->failed()) {

      $this->res['status']  = 'failed';
      $this->res['message'] = VLD_ERR;
      $this->res['error']   = $validation->getErrors();

      $response = $response
        ->withJson($this->res, 200);

      return $response;

    }

    // dd();
    $body_args                 = UtilityHelper::_sanitize_array($body_args);
    $body_args['new_password'] = UtilityHelper::_generate_random_string(8, 'ALPHA_NUMERIC');

    // $user = User::firstWhere('profile_id', $profiile->id);
    $result = User::_updatePassword($profile->user->uuid, $body_args);

    $sys_message = 'A message containing your new password has been sent to your ';

    if ($result['qry_status']) {

      if ($body_args['type'] == 'email') {

        // Send email verification
        $template = new PhpRenderer(APP_PATH . '/Templates/Email', []);
        $template->setLayout('Container.php');

        $view = $template->fetch('ForgotPasswordNotif.php', [
          'username' => $profile->username,
          'password' => $body_args['new_password']
        ]);

        $data = array(
          'to_email' => $body_args['email'],
          'subject'  => 'VMS Forgot Password',
          'message'  => $view
        );

        $email = new EmailHelper($data);
        $email = $email->get();
        $email->send();

        $sys_message .= 'email address.';
      } else

      if ($body_args['type'] == 'phone') {

        // Send SMS
        $phone_no = getenv('ENVIRONMENT') === 'dev'
        ? getenv('ITEXMO_TEST_NO')
        : $body_args['phone_no'];

        $message = 'Please take note that your new VMS temporary password is ' . $body_args['new_password'] . '.';

        $send = Itexmo::sendSMS($phone_no, $message, getenv('ITEXMO_API_CODE'));

        $sys_message .= 'phone number.';

      }

      $this->res['status']  = 'success';
      $this->res['message'] = $sys_message;

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

  public function updateAvatar(Request $request, Response $response, $args) {

    $uuid    = (string) UtilityHelper::_sanitize($args['uuid']);
    $profile = UserProfile::firstWhere('uuid', $uuid);

    if (!$profile) {
      $this->res['status']  = 'failed';
      $this->res['message'] = FETCH_EMPTY;
      $this->res['data']    = null;

      $data = $response
        ->withJson($this->res, 200);

      return $data;
    }

    $uploadedFiles = $request->getUploadedFiles();
    $directory     = ROOT_PATH . '/api/uploads/';

    if (!file_exists($directory)) {
      mkdir($directory, 0755, true);
    }

    // handle single input with single file upload
    $uploadedFile = $uploadedFiles['avatar'];

    if (!$uploadedFile->getError() === UPLOAD_ERR_OK) {

      $this->res['status']           = 'failed';
      $this->res['error']['type']    = APP_ERR;
      $this->res['error']['message'] = 'There\'s something wrong with the file you are trying to upload.';

      $response = $response
        ->withJson($this->res, 500);

      return $response;
    }

    $upload = UploadHelper::moveUploadedFile($directory, $uploadedFile);

    if (!$upload) {

      $this->res['status']           = 'failed';
      $this->res['error']['type']    = APP_ERR;
      $this->res['error']['message'] = $result['errors'];

      $response = $response
        ->withJson($this->res, 500);

      return $response;
    }

    // $user = User::firstWhere('uuid', $uuid);
    $avatar = getenv('ROOT_URL') . 'uploads/' . $upload;

    $args = array(
      'img_url' => $avatar
    );

    $result = UserProfile::_update($args, $profile->id);

    if ($result['qry_status']) {

      $this->res['status']         = 'success';
      $this->res['message']        = 'Avatar has been updated successfully';
      $this->res['data']['avatar'] = $avatar;

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

}
