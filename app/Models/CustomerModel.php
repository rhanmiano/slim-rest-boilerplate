<?php

namespace App\Models;

use ORM;
use App\Models\BaseModel;

class CustomerModel extends BaseModel {

  public function getAllCustomers() {

    return ORM::for_table('customers')->find_array();

  }

  public function getCustomerById($id) {

    $customer = ORM::for_table('customers')
      ->where('id', $id)
      ->find_array();

    return $customer;

  }

  public function createCustomer() {



  }

}