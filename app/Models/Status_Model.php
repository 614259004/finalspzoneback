<?php

namespace App\Models;
use CodeIgniter\Model;

class Status_Model extends Model{
    protected $table = 'sp_status';
    protected $primaryKey = 'S_statusid';
    protected $allowedFields = ['S_statusid', 'S_name'];

}

?>