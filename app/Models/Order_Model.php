<?php

namespace App\Models;
use CodeIgniter\Model;

class Order_Model extends Model{
    protected $table = 'sp_order';
    protected $primaryKey = 'Or_orderid';
    protected $allowedFields =['Or_orderid','Or_date','Or_price','Or_order_code','C_customerid','OS_statusid','A_addressid','Or_imgpayment','Or_Pr_id'];

}

?>