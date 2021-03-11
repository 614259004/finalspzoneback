<?php

namespace App\Models;
use CodeIgniter\Model;

class Size_Model extends Model{
    protected $table = 'sp_size';
    protected $primaryKey = 'P_productid';
    protected $allowedFields = ['P_productid','P_size','P_size_amount'];
}
?>