<?php

namespace App\Helpers;

/**
 * class UtilityHelper
 *
 * Define all global utility helpers here
 */
class UtilityHelper {

  public static function _hello() {
    echo 'Can properly access this resource.';
  }

  public static function _regex_keyboard_symbols() {
    // \w matches any word character (equal to [a-zA-Z0-9_])
    // \W matches any non-word character (equal to [^a-zA-Z0-9_])
    return "/\w|\W/";
  }

  public static function _sanitize($args) {
    return filter_var(trim($args), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }

  public static function _sanitize_array($args, $exempted = null, $cast_object = false) {
    if (!is_array($args)) {
      if (gettype($args) === 'object') {
        $args = (array) $args;
      } else {
        throw new Exception('Sanitize data must be of type object/array');
      }

    }

    foreach ($args as $key => $value) {
      if ($exempted) {
        if (is_array($exempted)) {
          if (in_array($key, $exempted)) {
            continue;
          }

        } else {
          if ($key == $exempted) {
            continue;
          }

        }
      }if (!is_array($value)) {
        $value      = Self::_sanitize($value);
        $args[$key] = ($value ? $value : NULL);
      } else {
        foreach ($value as $k => $v) {
          $v              = Self::_sanitize($v);
          $args[$key][$k] = ($v ? $v : NULL);
        }
      }
    }

    return $cast_object ? (object) $args : $args;
  }

  public static function _get_ip($server) {
    //whether ip is from share internet
    if (!empty($server['HTTP_CLIENT_IP'])) {
      $ip_address = $server['HTTP_CLIENT_IP'];
    }
    //whether ip is from proxy
    elseif (!empty($server['HTTP_X_FORWARDED_FOR'])) {
      $ip_address = $server['HTTP_X_FORWARDED_FOR'];
    }
    //whether ip is from remote address
    else {
      $ip_address = $server['REMOTE_ADDR'];
    }
    return $ip_address;
  }

  public static function _generate_random_string($length = 8, $mode = 'ALPHA_NUMERIC') {
    $alphabetUpper = 'ABCDEFGHIJKLMNOPQRSTUWXYZ';
    $numberd       = '0123456789';
    $pass          = array(); //remember to declare $pass as an array

    if ($mode === 'ALPHA_NUMERIC') {
      for ($i = 0; $i < $length; $i++) {
        // Randomize from 0 - 1
        // If 0 get random char from $alphabetUpper
        // else get random char from $numberd
        if (rand(0, 1) == 0) {
          $n      = rand(0, strlen($alphabetUpper) - 1);
          $pass[] = $alphabetUpper[$n];
        } else {
          $n      = rand(0, strlen($numberd) - 1);
          $pass[] = $numberd[$n];
        }
      }
    } else if ($mode === 'ALPHA') {
      for ($i = 0; $i < $length; $i++) {
        $n      = rand(0, strlen($alphabetUpper) - 1);
        $pass[] = $alphabetUpper[$n];
      }

    } else if ($mode === 'NUMERIC') {
      for ($i = 0; $i < $length; $i++) {
        $n      = rand(0, strlen($numberd) - 1);
        $pass[] = $numberd[$n];
      }
    }

    return implode($pass); //turn the array into a string
  }

  public static function _generate_event_code() {

    return Self::_generate_random_string(2, 'ALPHA') . Self::_generate_random_string(2, 'NUMERIC');

  }
}