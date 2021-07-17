<?php

namespace App\Models;
use CodeIgniter\Model;

class Order_detail_Model extends Model{
    protected $table = 'sp_ordertail';
    protected $primaryKey = 'Or_orderid';
    protected $allowedFields =['Od_amount','P_size','P_productid','Or_orderid'];

}

?>