<?php

namespace App\Models\Main;

use App\Filters\Filterable;
use App\Helpers\DateHelper;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class UserModel extends BaseModel {
  use SoftDeletes;
  use Filterable;

  protected $table = 'user';

  protected $hidden = ['password', 'created_at', 'updated_at', 'deleted_at', 'profile_id'];

  public function profile() {

    return $this->hasOne('App\Models\Main\ProfileModel', 'user_id');

  }

  public function roles() {

    return $this->belongsToMany('App\Models\Main\RoleModel', 'user_role', 'user_id', 'role_id');

  }

  public static function _create($args) {
    $errors = [];

    $User = new Self();

    $columns = $User->getTableColumns($User->getTable());

    foreach ($args as $key => $value) {

      if (in_array($key, $columns)) {
        $User->$key = $value;
      }

    }

    $User->uuid       = Uuid::uuid4();
    $User->password   = password_hash($args['password'], PASSWORD_BCRYPT, array('cost' => 12));
    $User->created_at = DateHelper::_now();

    try {

      $result['qry_status'] = $User->save();

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    // If ever we need last inserted id
    $result['id'] = $User->id;

    if (sizeof($errors) > 0) { // with errors

      $result['qry_status'] = false;
      $result['errors']     = $errors;

    } else {

      return $result;

    }

  }

  public static function _update($uuid, $args) {

    $errors = [];

    $User = Self::firstWhere('uuid', $uuid);

    if (!$User) {
      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    $columns = $User->getTableColumns($User->getTable());

    foreach ($args as $key => $value) {

      if (in_array($key, $columns)) {
        $User->$key = $value;
      }

    }

    if ($User->isClean()) {
      $result['qry_status'] = true;
      $result['message']    = UPDATE_EMPTY;

      return $result;
    }

    try {

      $result['qry_status'] = $User->save();

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors

      $result['qry_status'] = false;
      $result['errors']     = $errors;

    }

    return $result;

  }

  public static function _updatePassword($id, $args) {

    $errors = [];

    $User = Self::firstWhere('uuid', $id);

    if (!$User) {
      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    $User->password = password_hash($args['new_password'], PASSWORD_BCRYPT, array('cost' => 12));

    if ($User->isClean()) {
      $result['qry_status'] = false;
      $result['message']    = UPDATE_EMPTY;

      return $result;
    }

    try {

      $result['qry_status'] = $User->save();

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

    $User = Self::firstWhere('uuid', $id);

    if (!$User) {

      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    try {

      $result['qry_status'] = $User->delete();

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

    $User = Self::onlyTrashed()->where('uuid', $id);

    if (!$User) {

      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    try {

      $result['qry_status'] = $User->restore();

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

    $User = Self::find($id);

    if (!$User) {
      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    try {

      $result['qry_status'] = $User->forceDelete();

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
