<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Product_Model;
use App\Models\Size_Model;
use App\Models\Brand_Model;

class Product extends ResourceController
{
    use ResponseTrait;

    public function showProduct(){
        $db = \Config\Database::connect();
        $builder = $db->table('sp_product');
        $builder->join('sp_category','sp_category.Cg_categoryid = sp_product.Cg_categoryid');
        $builder->join('sp_brand','sp_brand.B_brandid = sp_product.B_brandid');
        $query = $builder->get();
        
        return json_encode($query->getResult());

    }

    public function showProductbyid(){
        $db = \Config\Database::connect();
        $builder = $db->table('sp_product');
        $builder->join('sp_category','sp_category.Cg_categoryid = sp_product.Cg_categoryid');
        $builder->join('sp_brand','sp_brand.B_brandid = sp_product.B_brandid');
        $builder->where('sp_product.P_productid',$this->request->getVar('P_productid'));
        $query = $builder->get();

        return json_encode($query->getResult());

    }
   

    public function showSize()
    {
        $size_model = new Size_Model();
        $data['sp_size'] =  $size_model -> findAll();
        return $this -> respond($data);
    }

    public function showProductandSize($id=null){
        $db = \Config\Database::connect();
        $builder = $db->table('sp_size');
        $builder->select('P_size, P_size_amount, sp_product.P_productid, P_name');
        $builder->join('sp_product','sp_product.P_productid = sp_size.P_productid');
        $builder->where('sp_size.P_productid',$id);
        $query = $builder->get();

        return json_encode($query->getResult());

    }

    public function addProduct()

    {   
        try{
        $db = \Config\Database::connect();

        //$builder = $db->table('sp_product');
        // $builder ->where('P_name',$this->request->getVar('P_name'));
        //if($builder ->countAllResults() == 0) 
        //{

        $sql = "SELECT MAX(CAST(SUBSTRING(P_productid, 3, 6) AS UNSIGNED)) AS maxid FROM sp_product";
        $query = $db->query($sql);
        $row = $query->getResult();
        $maxid = $row[0]->maxid;
        $code = '';

       if($maxid == null)
         {
            $code = 'PD0001';
         }else{
                $id = (string) $maxid + 1;
                $fullid =   str_pad($id,4,'0',STR_PAD_LEFT);
                $code = 'PD'.$fullid;

         }
        echo $code;

        $product_model = new Product_Model();
        $data = [

            'P_productid' => $code,
            'P_name' => $this->request->getVar('P_name'),
            'P_price' => $this->request->getVar('P_price'),
            'P_detail' => $this->request->getVar('P_detail'),
            'Pr_promotion_code' => 'PM9999',
            'B_brandid' => $this->request->getVar('B_brandid'),
            'Cg_categoryid' => $this->request->getVar('Cg_categoryid'),
            'P_image1' => $this->request->getVar('P_image1'),
            'P_image2' => $this->request->getVar('P_image2'),
            'P_image3' => $this->request->getVar('P_image3'),
        ];

        $product_model->insert($data);

        //}else{

        /*    $sql = "SELECT P_productid FROM `sp_product` WHERE P_name  = '". $this->request->getVar('P_name')."' " ;
            $query = $db->query($sql);
            $row = $query->getResult();
            $code = $row[0]-> P_productid ;
            echo $code;

            
        }*/

        return true;
        
        } catch(Exception $e){
            return $e->getMessage();
        }
        
    }

    public function addSize()
    {
        try{
            $db = \Config\Database::connect();
            $size_model = new Size_Model();
            $data = [
                'P_productid' => $this->request->getVar('P_productid'),
                'P_size' => $this->request->getVar('P_size'),
                'P_size_amount' => $this->request->getVar('P_size_amount'),
            ];

            $size_model->insert($data);

            $response = [
                'status' => 201,
                'error' => null,
                'message' => 'ADD size success'
            ];
            
            return $this->respond($response);

            } catch(Exception $e){
                return $e->getMessage();
         }
    }

    public function updateProduct($id=null){

        $product_model = new Product_Model();
       // $size_model = new Size_Model();

        $data = [
            'P_name' => $this->request->getVar('P_name'),
            'P_price' => $this->request->getVar('P_price'),
            'P_detail' => $this->request->getVar('P_detail'),
            'Pr_promotion_code' => $this->request->getVar('Pr_promotion_code'),
            'B_brandid' => $this->request->getVar('B_brandid'),
            'Cg_categoryid' => $this->request->getVar('Cg_categoryid'),
            'P_image1' => $this->request->getVar('P_image1'),
            'P_image2' => $this->request->getVar('P_image2'),
            'P_image3' => $this->request->getVar('P_image3'),
        ];

       /* $data2 = [
            'P_size_amount' => $this->request->getVar('P_size_amount'),
        ];*/

        $product_model->update($id, $data);
        //$size_model->where('P_size',$this->request->getVar('P_size')) ->update($id, $data2);

        $response = [
            'status' => 201,
            'error' => null,
            'message' => 'Updated Product success'
        ];
        echo $id;
        
        return $this->respond($response);
        
    }

    public function updateSize($id=null){
        $db = \Config\Database::connect();
        $size_model = new Size_Model();
        $data = [

            'P_size_amount' => $this->request->getVar('P_size_amount'),
        
        ];
        $size_model->where('P_size',$this->request->getVar('P_size'))->update($id, $data);
        
        $response = [
            'status' => 201,
            'error' => null,
            'message' => 'Updated Size success'
        ];
        echo $id;
        
        return $this->respond($response);

    }

    public function deleteSize(){

        $db = \Config\Database::connect();
        $builder = $db->table('sp_size');
        
        $data = [

            'P_productid' => $this->request->getVar('P_productid'),
            'P_size' => $this->request->getVar('P_size')
        ];

        $builder -> where($data);
        $builder ->delete();

        return true ;
     
    }




}

?>