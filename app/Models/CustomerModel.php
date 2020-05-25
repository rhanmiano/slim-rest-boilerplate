<?php

namespace App\Models;

class CustomerModel extends BaseModel {
	protected $table = 'customers';
	protected $hidden = ['password'];

	public function _all() {

    $result = Self::all();

    return $result;

  }

  public function _byId($id) {

    $result = Self::find($id);

    return $result;

  }

  public function _byEmail($email) {

    $result = Self::find($id);

    return $result;

  }

  public function _create($args) {

    $errors = [];

    $customer = new Self();

    $args->password = password_hash($args->password, PASSWORD_BCRYPT, array('cost' => 12));
    
    foreach($args as $key => $value) {
    	$customer->$key = $value;
    }

    try {

      $result['qry_status'] = $customer->save(); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    // If ever we need last inserted id
    $result['id'] = $customer->id;

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    } else {
      
      return $result;

    }

  }

  public function _update($id, $args) {

    $errors = [];

    $customer = Self::find($id);

    if(!$customer) {
      $result['qry_status'] = false;
      $result['message'] = FETCH_EMPTY;
      
      return $result;
    }

    foreach ((array) $args as $key => $value) {
      $customer->$key = $value;
    }

    if($customer->isClean()){
      $result['qry_status'] = false;
      $result['message'] = UPDATE_EMPTY;
      
      return $result;
    }

    try {

      $result['qry_status'] = $customer->save(); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    }

    return $result;

  }

  public function _archive($id) {

    $errors = [];

    $customer = Self::find($id);

    if (!$customer) {

      $result['qry_status'] = false;
      $result['message'] = FETCH_EMPTY;
      
      return $result;
    }

    try {

      $result['qry_status'] = $customer->delete(); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    }

    return $result;

  }

  public function _restore($id) {

    $errors = [];

    $customer = Self::onlyTrashed($id);

    if (!$customer) {

      $result['qry_status'] = false;
      $result['message'] = FETCH_EMPTY;
      
      return $result;
    }

    try {

      $result['qry_status'] = $customer->restore(); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    }

    return $result;

  }

  public function _delete($id) {

    $errors = [];

    $customer = Self::find($id);

    if(!$customer) {
      $result['qry_status'] = false;
      $result['message'] = FETCH_EMPTY;
      
      return $result;
    }
    
    try {

      $result['qry_status'] = $customer->forceDelete(); 

    } catch (PDOException $e) {

      $errors[] = $e->getMessages;

    }

    if (sizeof($errors) > 0) { // with errors
    
      $result['qry_status'] = false;
      $result['errors'] = $errors;

    }

    return $result;

  }

  public function emailExists($email) {

    $result = ORM::for_table('Customers')
      ->where('email', $email)
      ->find_one();

    if (sizeof($result) > 0) {
      
      return true;

    } else {
      
      return false;

    }
  }
}