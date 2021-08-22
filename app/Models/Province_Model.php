<?php

namespace App\Models;
use CodeIgniter\Model;

class Province_Model extends Model{
    protected $table = 'province';
    protected $primaryKey = 'PROVINCE_ID ';
    protected $allowedFields = ['PROVINCE_ID ','PROVINCE_CODE', 'PROVINCE_NAME' , 'GEO_ID'];

}

?>