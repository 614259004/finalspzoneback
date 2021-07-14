<?php

namespace App\Models;
use CodeIgniter\Model;

class Cart_Model extends Model{
    protected $table = 'sp_cart';
    protected $primaryKey = 'Ca_cartid';
    protected $allowedFields =['Ca_cartid','P_productid','C_customerid','P_size','Ca_amount'];

}

?>
