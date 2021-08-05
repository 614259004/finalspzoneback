<?php 

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Codeigniter\API\ResponseTrait;
use App\Models\Promotion_Model;

class Promotion extends ResourceController
{
    public function addPromotion() {
       
        
        try{

        $db = \Config\Database::connect();
        $sql = "SELECT MAX(CAST(SUBSTRING(Pr_promotion_code, 3, 6) AS UNSIGNED)) AS maxid FROM sp_promotion";
        $query = $db->query($sql);
        $row = $query->getResult();
        $maxid = $row[0]->maxid;
        $code = '';

       if($maxid == null)
         {
            $code = 'PM0001';
         }else{
                $id = (string) $maxid + 1;
                $fullid =   str_pad($id,4,'0',STR_PAD_LEFT);
                $code = 'PM'.$fullid;

         }
        echo $code;

        $promotion_model = new Promotion_Model();
    
        $data = [

            'Pr_promotion_code' => $code,
            'Pr_time_begin' => date('Y-m-d',strtotime($this->request->getVar('Pr_time_begin'))),
            'Pr_time_out' => date('Y-m-d',strtotime($this->request->getVar('Pr_time_out'))),
            'Pr_detail' => $this->request->getVar('Pr_detail'),
            'Pr_sale' => $this->request->getVar('Pr_sale'),
            'Pr_image' => $this->request->getVar('Pr_image'),
            'P_productid' => $this->request->getVar('P_productid'),
            'Pr_amountPro' => $this->request->getVar('Pr_amountPro'),
            'Pr_size' => $this->request->getVar('Pr_size'),
            'Pr_status' => $this->request->getVar('Pr_status'),
            
        ];
        
        $promotion_model->insert($data);
        return true;

            } catch(Exception $e){
                return $e->getMessage();
            }
        }

        public function showPromotion(){
            $db = \Config\Database::connect();
            $promotion_model = new Promotion_Model();
            $builder = $db->table('sp_promotion');
            $builder->join('sp_product','sp_product.P_productid  = sp_promotion.P_productid');
            $builder->join('sp_status','sp_status.S_statusid  = sp_promotion.Pr_status');
            $query = $builder->get();
    
            return json_encode($query->getResult());
    
        }
        public function showPromotionbystatus(){
            $db = \Config\Database::connect();
            $promotion_model = new Promotion_Model();
            $builder = $db->table('sp_promotion');
            $builder->join('sp_product','sp_product.P_productid  = sp_promotion.P_productid');
            $builder->join('sp_status','sp_status.S_statusid  = sp_promotion.Pr_status');
            $builder->where('Pr_status',8)
            $query = $builder->get();
    
            return json_encode($query->getResult());
    
        }


        public function updatePromotion(){
            
            $promotion_model = new Promotion_Model();
            $data = [
                'Pr_time_begin' => date('Y-m-d',strtotime($this->request->getVar('Pr_time_begin'))),
                'Pr_time_out' => date('Y-m-d',strtotime($this->request->getVar('Pr_time_out'))),
                'Pr_detail' => $this->request->getVar('Pr_detail'),
                'Pr_sale' => $this->request->getVar('Pr_sale'),
                'Pr_image' => $this->request->getVar('Pr_image'),
                'Pr_amountPro' => $this->request->getVar('Pr_amountPro'),
                'Pr_size' => $this->request->getVar('Pr_size'),
                'Pr_status' => $this->request->getVar('Pr_status'),
            ];
            $promotion_model->update($this->request->getVar('Pr_promotion_code'),$data);
            //$model->update($id,$data);
            $response =[
                'status' => 201,
                'error' => null,
                'message' => 'Promotion update successfully'
            ];
            return $this->respond($response);
        }
    
       
        public function deletebyid(){
            
            $db = \Config\Database::connect();
            $builder = $db->table('sp_promotion');
            
            $data = [
    
                'Pr_promotion_code' => $this->request->getVar('Pr_promotion_code'),
            ];
    
            $builder -> where($data);
            $builder ->delete();
    
            return true ;
         
        }
        
}