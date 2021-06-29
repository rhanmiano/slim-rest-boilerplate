<?php

namespace App\Filters;

use App\Helpers\UtilityHelper;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;

class QueryFilters {

  protected $request;
  protected $builder;

  public function __construct(Request $request) {

    $this->request = $request;

  }

  public function apply(Builder $builder) {

    $this->builder = $builder;

    foreach ($this->filters() as $key => $value) {

      if (!method_exists($this, $key)) {

        continue;

      }

      if (strlen($value)) {

        $this->$key($value);

      } else {

        $this->$key('');

      }

    }

    return $this->builder;

  }

  public function filters() {

    $getData = UtilityHelper::_sanitize_array($this->request->getQueryParams());

    return $getData;

  }

  public function getValidator() {

    return $this->validator;

  }

}
