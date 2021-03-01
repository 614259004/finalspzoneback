<?php 

namespace App\Models;
use CodeIgniter\Model;

class Category_Model extends Model{
    protected $table = 'sp_category';
    protected $primaryKey = 'Cg_categoryid';
    protected $allowedFields =['Cg_categoryid','Cg_name','S_statusid'];
}


?>