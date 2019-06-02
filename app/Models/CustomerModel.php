<?php

namespace App\Models;

use ORM;
use App\Models\BaseModel;

class CustomerModel extends BaseModel{

  public function getAllCustomers(){
    $customers = ORM::for_table('customers')->find_array();

    $this->data['status']    = 'success';
    $this->data['message']   = 'Fetched Successfully';
    $this->data['customers'] = $customers;

    return $this->data;
  }

  public function getCustomerById($id){
    $customer = ORM::for_table('customers')
      ->where('id', $id)
      ->find_array();

    $this->data['status']    = 'success';
    $this->data['message']   = 'Fetched Successfully';
    $this->data['customer'] = $customer;

    return $this->data;
  }
}