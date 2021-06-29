<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class FieldExistsException extends ValidationException
{
  protected $defaultTemplates = [
      self::MODE_DEFAULT => [
          self::STANDARD => '{{name}} does not exist',
      ],
  ];
}