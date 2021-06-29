<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

final class FieldUniqueUpdateException extends ValidationException
{
  protected $defaultTemplates = [
      self::MODE_DEFAULT => [
          self::STANDARD => '{{name}} already exists',
      ],
  ];
}