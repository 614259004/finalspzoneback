<?php

namespace App\Models;
use CodeIgniter\Model;

class Ems_Model extends Model{
    protected $table = 'sp_ems';
    protected $primaryKey = 'ems_or_id';
    protected $allowedFields = ['tracking','ems_or_id'];
}
?>