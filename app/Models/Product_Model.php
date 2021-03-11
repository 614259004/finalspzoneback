<?php

namespace App\Models;
use CodeIgniter\Model;

class Product_Model extends Model{
    protected $table = 'sp_product';
    protected $primaryKey = 'P_productid';
    protected $allowedFields = ['P_productid',
                                    'P_name',
                                        'P_price',
                                            'P_detail',
                                                'Pr_promotion_code',
                                                        'B_brandid',
                                                            'Cg_categoryid',
                                                                'P_image1',
                                                                    'P_image2',
                                                                        'P_image3'];
}
?>