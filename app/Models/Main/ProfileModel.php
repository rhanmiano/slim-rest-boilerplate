<?php

namespace App\Models\Main;

use App\Filters\Filterable;
use App\Helpers\DateHelper;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class ProfileModel extends BaseModel {
  use SoftDeletes;
  use Filterable;

  protected $table  = 'profile';
  protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

  protected $guarded = [];

  public function user() {

    return $this->belongsTo('App\Models\Main\UserModel', 'user_id');

  }

  public static function _create($args) {

    $errors = [];

    $Profile = new Self();

    $columns = $Profile->getTableColumns($Profile->getTable());

    foreach ($args as $key => $value) {

      if (in_array($key, $columns)) {
        $Profile->$key = $value;
      }

    }

    $Profile->uuid       = Uuid::uuid4();
    $Profile->created_at = DateHelper::_now();

    try {

      $result['qry_status'] = $Profile->save();

      // If ever we need last inserted id
      $result['id'] = $Profile->id;

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

  public static function _update($args, $uuid) {

    $errors = [];

    $Profile = Self::firstWhere('uuid', $uuid);

    if (!$Profile) {
      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    $columns = $Profile->getTableColumns($Profile->getTable());

    foreach ($args as $key => $value) {

      if (in_array($key, $columns)) {
        $Profile->$key = $value;
      }

    }

    if ($Profile->isClean()) {
      $result['qry_status'] = true;
      $result['message']    = UPDATE_EMPTY;

      return $result;
    }

    $Profile->updated_at = DateHelper::_now();

    try {

      $result['qry_status'] = $Profile->save();

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors

      $result['qry_status'] = false;
      $result['errors']     = $errors;

    }

    return $result;

  }

  public static function _archive($uuid) {

    $errors = [];

    $Profile = Self::firstWhere('uuid', $uuid);

    if (!$Profile) {

      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    try {

      $result['qry_status'] = $Profile->delete();

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors

      $result['qry_status'] = false;
      $result['errors']     = $errors;

    }

    return $result;

  }

  public static function _restore($uuid) {
    $errors = [];

    $Profile = Self::onlyTrashed()
      ->firstwhere('uuid', $uuid);

    if (!$Profile) {

      $result['qry_status'] = false;
      $result['message']    = FETCH_EMPTY;

      return $result;
    }

    try {

      $result['qry_status'] = $Profile->restore();

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
