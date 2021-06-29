<?php

namespace App\Models\Main;

use App\Helpers\DateHelper;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRoleModel extends BaseModel {
  use SoftDeletes;

  protected $table   = 'user_role';
  protected $hidden  = ['created_at', 'updated_at', 'deleted_at'];
  protected $guarded = [];

  public static function _create($args) {

    $errors = [];

    $UserRole = new Self();

    $UserRole = $UserRole->firstOrNew(
      array(
        'user_id' => $args['user_id'],
        'role_id' => $args['role_id']
      )
    );

    try {

      if ($UserRole->exists) {

        $result['qry_status'] = false;
        $result['message']    = 'Role has been assigned already to the user.';

        return $result;

      } else {

        $UserRole->created_at = DateHelper::_now();

        $result['qry_status'] = $UserRole->save();

        // If ever we need last inserted id
        $result['id'] = $UserRole->id;

      }

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

    $UserRole = Self::find($id);

    if (!$UserRole) {
      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    try {

      // Check if same role has been assigned to the user
      $UserRole = new Self();

      $UserRole = $UserRole->firstOrNew(
        array(
          'user_id' => $args['user_id'],
          'role_id' => $args['role_id']
        )
      );

      if ($UserRole->exists) {

        $result['qry_status'] = false;
        $result['message']    = 'Role has been assigned already to the user.';

        return $result;

      } else {

        $UserRole = Self::find($id);

        foreach ($args as $key => $value) {
          $UserRole->$key = $value;
        }

        if ($UserRole->isClean()) {
          $result['qry_status'] = false;
          $result['message']    = UPDATE_EMPTY;

          return $result;
        }

        $result['qry_status'] = $UserRole->save();

      }

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

    $UserRole = Self::find($id);

    if (!$UserRole) {

      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    try {

      $result['qry_status'] = $UserRole->delete();

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

    $UserRole = Self::onlyTrashed($id);

    if (!$UserRole) {

      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    try {

      $result['qry_status'] = $UserRole->restore();

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

    $UserRole = Self::find($id);

    if (!$UserRole) {
      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    try {

      $result['qry_status'] = $UserRole->forceDelete();

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
