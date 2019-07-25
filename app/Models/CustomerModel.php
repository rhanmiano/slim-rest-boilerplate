<?php

namespace App\Models;

use ORM;
use App\Models\BaseModel;

class CustomerModel extends BaseModel {

  public function getAllCustomers() {

    $result = ORM::for_table('customers')->find_array();

    return $result;

  }

  public function getCustomerById($id) {

    $result = ORM::for_table('customers')
      ->where('id', $id)
      ->find_array();

    return $result;

  }

  public function insertCustomer($args) {

    // Sample of executing raw query
    $qry1 = "
      INSERT INTO customers 
      (name, email, age)
      VALUES (:name, :email, :age) 
    ";

    $result = ORM::raw_execute($qry1, (array) $args);

    // If ever we need last inserted id
    $id = ORM::get_db()->lastInsertId();

    return $result;

  }

  public function updateCustomer($id, $args) {

    $customer = ORM::for_table('Customers')
      ->find_one($id);

    foreach ((array) $args as $key => $value) {
      $customer->$key = $value;
    }

    $customer->set_expr('updated_at', 'now()');

    $result = $customer->save();

    return $result;

  }

  public function deleteCustomerById($id) {

    $customer = ORM::for_table('Customers')
      ->find_one($id);

    $result = $customer->delete();

    return $result;

  }
}