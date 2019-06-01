<?php

namespace App\Models;

use ORM;
use App\Models\BaseModel;

class CustomersModel extends BaseModel{

  public function getAllCustomers(){
    $customers = ORM::for_table('customers')->find_array();

    return $customers;
  }
}