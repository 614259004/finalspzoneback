<?php

namespace App\Models;
use CodeIgniter\Model;

class Login_Model extends Model{
    protected $table = 'sp_login';
    protected $allowedFields = ['C_customerid','L_email', 'L_password'];

}

?>