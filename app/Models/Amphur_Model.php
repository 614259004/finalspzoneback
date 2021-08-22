<?php

namespace App\Models;
use CodeIgniter\Model;

class Amphur_Model extends Model{
    protected $table = 'amphur';
    protected $primaryKey = 'AMPHUR_ID ';
    protected $allowedFields = ['AMPHUR_ID ','AMPHUR_CODE', 'AMPHUR_NAME' , 'POSTCODE', 'GEO_ID' , 'PROVINCE_ID'];

}

?>