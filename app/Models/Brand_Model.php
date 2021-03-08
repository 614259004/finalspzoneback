<?php

namespace App\Models;
use CodeIgniter\Model;

class Brand_Model extends Model{
    protected $table = 'sp_brand';
    protected $primaryKey = 'B_brandid';
    protected $allowedFields = ['B_brandid','B_name','B_image'];
}