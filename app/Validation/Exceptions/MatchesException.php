<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class MatchesException extends ValidationException
{
  protected $defaultTemplates = [
      self::MODE_DEFAULT => [
          self::STANDARD => '{{name}} did not match',
      ],
      self::MODE_NEGATIVE => [
          self::STANDARD => '{{name}} did not match',
      ],
  ];
}