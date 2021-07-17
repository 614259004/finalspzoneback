<?php 

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Codeigniter\API\ResponseTrait;
use App\Models\Order_Model;
use App\Models\Order_detail_Model;

class Order extends ResourceController
{
    public function addOrder() {
       
        
        try{

        $db = \Config\Database::connect();
        $sql = "SELECT MAX(CAST(SUBSTRING(Or_orderid, 3, 6) AS UNSIGNED)) AS maxid FROM sp_order";
        $query = $db->query($sql);
        $row = $query->getResult();
        $maxid = $row[0]->maxid;
        $code = '';

       if($maxid == null)
         {
            $code = 'OR0001';
         }else{
                $id = (string) $maxid + 1;
                $fullid =   str_pad($id,4,'0',STR_PAD_LEFT);
                $code = 'OR'.$fullid;

         }
        echo $code;

        $order_model = new Order_Model();
    
        $data = [
            'Or_orderid' => $code,
            'Or_price' => $this->request->getVar('Or_price'),
            'Or_order_code' => $this->request->getVar('Or_order_code'),
            'C_customerid' => $this->request->getVar('C_customerid'),
            'S_statusid' => $this->request->getVar('S_statusid'),
            'A_addressid' => $this->request->getVar('A_addressid'),
            'Or_imgpayment' => $this->request->getVar('Or_imgpayment'),
        ];

       
        $order_model->insert($data);

        $builder = $db->table('sp_cart');
        $builder->where('C_customerid',$this->request->getVar('C_customerid'));
        $query = $builder->get();
       
        foreach($query->getResult() as $row){
          $sql  = "INSERT INTO sp_ordertail VALUES ('".$row->Ca_amount."','".$row->P_size."','".$row->P_productid."','".$code."')";
          $query = $db->query($sql);
         
        
        
        }

        $builder = $db->table('sp_cart');
        
        $data = [

            'C_customerid' => $this->request->getVar('C_customerid'),
            
        ];

        $builder -> where($data);
        $builder ->delete();

       
        return true;

        } 
        
        catch(Exception $e){

            return $e->getMessage();
        }

    }


    public function showOderbyid(){

        $db = \Config\Database::connect();
        $builder = $db->table('sp_order');
        $builder->join('sp_customer','sp_customer.C_customerid = sp_order.C_customerid');
        $builder->join('sp_status','sp_status.S_statusid = sp_order.S_statusid');
        $builder->join('sp_address','sp_address.A_addressid = sp_order.A_addressid');
        $builder->where('sp_order.Or_orderid',$this->request->getVar('Or_orderid'));
        $query = $builder->get();

        return json_encode($query->getResult());

    }

}

?>