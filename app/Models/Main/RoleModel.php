<?php

namespace App\Models\Main;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class RoleModel extends BaseModel {
  use SoftDeletes;

  protected $table  = 'role';
  protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

  public function _roles() {

    return $this->belongsToMany('App\Models\Main\UserModel', 'user_role', 'role_id', 'user_id');

  }

  public static function _create($args) {

    $errors = [];

    $Role = new Self();

    foreach ($args as $key => $value) {
      $Role->$key = $value;
    }

    $Role->uuid = Uuid::uuid4();

    try {

      $result['qry_status'] = $Role->save();

      // If ever we need last inserted id
      $result['id'] = $Role->id;

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors

      $result['qry_status'] = false;
      $result['errors']     = $errors;

    } else {

      return $result;

    }

  }

  public static function _update($id, $args) {

    $errors = [];

    $Role = Self::find($id);

    if (!$Role) {
      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    foreach ($args as $key => $value) {
      $Role->$key = $value;
    }

    if ($Role->isClean()) {
      $result['qry_status'] = false;
      $result['message']    = UPDATE_EMPTY;

      return $result;
    }

    try {

      $result['qry_status'] = $Role->save();

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors

      $result['qry_status'] = false;
      $result['errors']     = $errors;

    }

    return $result;

  }

  public static function _archive($id) {

    $errors = [];

    $Role = Self::find($id);

    if (!$Role) {

      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    try {

      $result['qry_status'] = $Role->delete();

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors

      $result['qry_status'] = false;
      $result['errors']     = $errors;

    }

    return $result;

  }

  public static function _restore($id) {

    $errors = [];

    $Role = Self::onlyTrashed($id);

    if (!$Role) {

      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    try {

      $result['qry_status'] = $Role->restore();

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors

      $result['qry_status'] = false;
      $result['errors']     = $errors;

    }

    return $result;

  }

  public static function _delete($id) {

    $errors = [];

    $Role = Self::find($id);

    if (!$Role) {
      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    try {

      $result['qry_status'] = $Role->forceDelete();

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors

      $result['qry_status'] = false;
      $result['errors']     = $errors;

    }

    return $result;

  }

}
