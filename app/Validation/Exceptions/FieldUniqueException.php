<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class FieldUniqueException extends ValidationException
{
  protected $defaultTemplates = [
      self::MODE_DEFAULT => [
          self::STANDARD => '{{name}} already exists',
      ],
  ];
}