<?php

namespace App\Models;
use CodeIgniter\Model;

class Promotion_Model extends Model{
    protected $table = 'sp_promotion';
    protected $primaryKey = 'Pr_promotion_code';
    protected $allowedFields =['Pr_promotion_code',
                                'Pr_time_begin',
                                'Pr_time_out',
                                'Pr_detail',
                                'Pr_sale',
                                'Pr_image',
                                'P_productid',
                                'Pr_amountPro',
                                'Pr_size',
                                'Pr_status'];

}

?>