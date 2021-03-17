<?php

namespace App\Models;
use CodeIgniter\Model;

class Customer_Model extends Model{
    protected $table = 'sp_customer';
    protected $primaryKey = 'C_customerid';
    protected $allowedFields = ['C_customerid','C_name','C_lastname','C_tel','C_image','S_statusid'];

}

?>