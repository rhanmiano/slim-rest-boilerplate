<?php

/** PATHS **/
if (!defined('ROOT_PATH')) {
  define('ROOT_PATH', dirname(dirname(__FILE__)));
}

if (!defined('APP_PATH')) {
  define('APP_PATH', ROOT_PATH . '/app');
}


/** SYSTEM MESSAGES **/

// System errors
if (!defined('SYS_ERR')) {
  define('SYS_ERR', 'Something went wrong. Please try again.');
}

// Fetch
if (!defined('FETCH_SUCC')) {
  define('FETCH_SUCC', 'Data fetched successfully.');
}

if (!defined('FETCH_EMPTY')) {
  define('FETCH_EMPTY', 'Resource could not be found.');
}

if (!defined('FETCH_ERR')) {
  define('FETCH_ERR', 'Error fetching the data.');
}

// Create
if (!defined('CREATE_SUCC')) {
  define('CREATE_SUCC', 'Data inserted successfully.');
}

if (!defined('CREATE_ERR')) {
  define('CREATE_ERR', 'Error inserting the data.');
}


// Update
if (!defined('UPDATE_SUCC')) {
  define('UPDATE_SUCC', 'Data updated successfully.');
}

if (!defined('UPDATE_ERR')) {
  define('UPDATE_ERR', 'Error updating the data.');
}

if (!defined('UPDATE_EMPTY')) {
  define('UPDATE_EMPTY', 'Nothing to be updated');
}

// Archive
if (!defined('ARCHIVE_SUCC')) {
  define('ARCHIVE_SUCC', 'Data archived successfully.');
}

if (!defined('ARCHIVE_ERR')) {
  define('ARCHIVE_ERR', 'Error archiving the data.');
}

// Restore
if (!defined('RESTORE_SUCC')) {
  define('RESTORE_SUCC', 'Data restored successfully.');
}

if (!defined('RESTORE_ERR')) {
  define('RESTORE_ERR', 'Error restoring the data.');
}

// Delete
if (!defined('DELETE_SUCC')) {
  define('DELETE_SUCC', 'Data deleted successfully.');
}

if (!defined('DELETE_ERR')) {
  define('DELETE_ERR', 'Error updating the data.');
}

// Validation
if (!defined('APP_ERR')) {
  define('APP_ERR', 'Application/Server Error');
}

if (!defined('VLD_ERR')) {
  define('VLD_ERR', 'Please check for field errors');
}

if (!defined('VLD_ERR_TYPE')) {
  define('VLD_ERR_TYPE', 'Validation Error');
}

// Authentication
if (!defined('EMAIL_NULL')) {
  define('EMAIL_NULL', 'Username doesn\'t exist in the system');
}

if (!defined('PASSWORD_INVLD')) {
  define('PASSWORD_INVLD', 'Invalid Password');
}

if (!defined('LOGIN_SUCC')) {
  define('LOGIN_SUCC', 'You\'ve login successfully');
}