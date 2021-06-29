<?php

namespace App\Filters;

use Psr\Http\Message\ServerRequestInterface as Request;

class UserFilters extends QueryFilters {

  protected $request;

  public function __construct(Request $request) {

    $this->request = $request;
    parent::__construct($request);

  }

  public function username($term) {

    return $this->builder->where('username', 'LIKE', "%$term%");

  }

  public function email($term) {

    return $this->builder->where('email', 'LIKE', "%$term%");

  }

  public function phone_no($term) {

    return $this->builder->where('phone_no', 'LIKE', "%$term%");

  }

  public function role($value) {

    $this->builder
      ->select('user.id', 'user.uuid', 'user.username', 'user.max_companion', 'user.enabled', 'user.profile_id')
      ->leftJoin('profile', function ($join) {
        $join->on('profile.id', '=', 'user.profile_id');
      })
      ->leftJoin('user_role', function ($join) {
        $join->on('user.id', '=', 'user_role.user_id');
      })
      ->leftJoin('role', function ($join) {
        $join->on('role.id', '=', 'user_role.role_id');
      })
      ->where('role.id', '=', "$value");

    return $this->builder;

  }

  public function sort($value) {

    // default sorting
    $sort_by    = 'id';
    $sort_order = 'asc';
    sscanf($value, '%[^|]|%[^|]', $sort_by, $sort_order);

    return $this->builder->orderBy($sort_by, $sort_order);

  }

}