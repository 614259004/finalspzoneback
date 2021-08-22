<?php

namespace App\Models;
use CodeIgniter\Model;

class District_Model extends Model{
    protected $table = 'district';
    protected $primaryKey = 'DISTRICT_ID';
    protected $allowedFields = ['DISTRICT_ID','DISTRICT_CODE', 'DISTRICT_NAME' , 'AMPHUR_ID', 'GEO_ID' , 'PROVINCE_ID'];

}

?>