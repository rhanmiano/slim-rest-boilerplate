<?php

namespace App\Helpers;

/**
 * class DateHelper
 *
 * Define all global utility helpers here
 */
class DateHelper {

  public function __construct() {
    // \Moment\Moment::setDefaultTimezone('Asia/Manila');
  }

  public static function _now() {
    \Moment\Moment::setDefaultTimezone('Asia/Manila');
    $date = new \Moment\Moment();
    return $date->format('Y-m-d G:i:s');
  }

  public static function _time_now() {
    \Moment\Moment::setDefaultTimezone('Asia/Manila');
    $date = new \Moment\Moment('now');
    return $date->format('G:i:s');
  }

  public static function _format_sql_datetime($date) {
    \Moment\Moment::setDefaultTimezone('Asia/Manila');
    $date = new \Moment\Moment($date);
    return $date->format('Y-m-d G:i:s');
  }

  public static function _format_sql_year($date) {
    \Moment\Moment::setDefaultTimezone('Asia/Manila');
    $date = new \Moment\Moment($date);
    return $date->format('Y-m-d');
  }

}