<?php

// Paths
if (!defined('ROOT_PATH')) {
  define('ROOT_PATH', dirname(dirname(__FILE__)));
}

if (!defined('APP_PATH')) {
  define('APP_PATH', ROOT_PATH . '/app');
}


/** System messages **/

// System errors
if (!defined('SYS_ERR')) {
  define('SYS_ERR', 'Something went wrong. Please try again.');
}

// Fetch
if (!defined('FTCHD_SUCC')) {
  define('FTCHD_SUCC', 'Data fetched successfully.');
}

if (!defined('FTCHD_EMPTY')) {
  define('FTCHD_EMPTY', 'Nothing to be fetched.');
}

if (!defined('FTCHD_ERR')) {
  define('FTCHD_ERR', 'Error in fetching data.');
}

// Create
if (!defined('CRT_SUCC')) {
  define('FTCHD_SUCC', 'Data inserted successfully.');
}

if (!defined('CRT_ERR')) {
  define('FTCHD_ERR', 'Error in inserting the data.');
}


// Update

// Delete

// Validation
if (!defined('VLD_ERR')) {
  define('VLD_ERR', 'Validtion error');
}