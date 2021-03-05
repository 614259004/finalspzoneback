<?php

namespace App\Models;
use CodeIgniter\Model;

class BrandModel extends Model{
    protected $table = 'sp_band';
    protected $primaryKey = 'B_bandid';
    protected $allowedFields = ['B_bandid','B_name','B_img'];
}