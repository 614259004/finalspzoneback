<?php

namespace App\Models;
use CodeIgniter\Model;

class Address_Model extends Model{
    protected $table = 'sp_address';
    protected $primaryKey = 'A_addressid';
    protected $allowedFields = ['A_addressid','A_homenumber', 'A_province' , 'A_district', 'A_canton', 'A_moo','A_postal_code','A_receive_name','C_customerid'];

}

?>