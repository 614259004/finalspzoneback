<?php 

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Codeigniter\API\ResponseTrait;
use App\Models\Order_Model;
use App\Models\Order_detail_Model;
use App\Models\Ems_Model;

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
            'C_customerid' => $this->request->getVar('C_customerid'),
            'OS_statusid' => 5,
            'A_addressid' => $this->request->getVar('A_addressid'),
            'Or_imgpayment' => $this->request->getVar('Or_imgpayment'),
            'Or_Pr_id' => $this->request->getVar('Or_Pr_id'),
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

        $builder = $db->table('sp_promotion');
        $builder->where('Pr_promotion_code',$this->request->getVar('Pr_promotion_code'));
        $query = $builder->get();
        $Pmdata = $query->getRow();
        if($Pmdata->Pr_amountPro == 0){

            $builder = $db->table('sp_promotion');
            $builder->set('Pr_status',9);
            $builder->where('Pr_promotion_code',$this->request->getVar('Pr_promotion_code'));
            $builder->update();

        }else{

            $sum = $Pmdata->Pr_amountPro - 1;
            $builder = $db->table('sp_promotion');
            $builder->set('Pr_amountPro',$sum);
            $builder->where('Pr_promotion_code',$this->request->getVar('Pr_promotion_code'));
            $builder->update();

            if($sum == 0){
                $builder = $db->table('sp_promotion');
                $builder->set('Pr_status',9);
                $builder->where('Pr_promotion_code',$this->request->getVar('Pr_promotion_code'));
                $builder->update();
            }

        }
        

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
        $builder->join('sp_status','sp_status.S_statusid = sp_order.OS_statusid');
        $builder->join('sp_address','sp_address.A_addressid = sp_order.A_addressid');
        $builder->join('province','sp_address.A_province = province.PROVINCE_ID');
        $builder->join('amphur','sp_address.A_district = amphur.AMPHUR_ID');
        $builder->join('district','sp_address.A_canton = district.DISTRICT_ID');
        $builder->join('sp_promotion','sp_promotion.Pr_promotion_code = sp_order.Or_Pr_id');
        $builder->where('sp_order.Or_orderid',$this->request->getVar('Or_orderid'));
        $query = $builder->get();

        return json_encode($query->getResult());

    }

    public function showOderbyCustomerid(){

        $db = \Config\Database::connect();
        $builder = $db->table('sp_order');
        $builder->join('sp_customer','sp_customer.C_customerid = sp_order.C_customerid');
        $builder->join('sp_status','sp_status.S_statusid = sp_order.OS_statusid');
        $builder->join('sp_address','sp_address.A_addressid = sp_order.A_addressid');
        $builder->join('sp_promotion','sp_promotion.Pr_promotion_code = sp_order.Or_Pr_id');
        $builder->orderBy('Or_date','DESC');
        $builder->where('sp_order.C_customerid',$this->request->getVar('C_customerid'));
        $query = $builder->get();

        return json_encode($query->getResult());

    }

    public function conFirmorder($id=null){

        $db = \Config\Database::connect();
        $order_model = new Order_Model();
        $data = [
            'OS_statusid' => 6,
        ];
            
        $order_model->update($id, $data);

          }

    public function cancleOrder($id=null){
        

            $db = \Config\Database::connect();
            $order_model = new Order_Model();
            $data = [
                'OS_statusid' => 7,
            ];

            $order_model->update($id, $data);

            $builder = $db->table('sp_ordertail');   //ตรงนี้
            $builder->where('Or_orderid',$id);
            $query = $builder->get();
    
            foreach($query->getResult() as $row){
                $sql  = "SELECT * FROM sp_size WHERE sp_size.P_productid = '".$row->P_productid."' && sp_size.P_size = '".$row->P_size."'";
                $query = $db->query($sql);
                foreach($query->getResult() as $row2){
                    $sum = $row2->P_size_amount + $row->Od_amount;
    
                    $builder = $db->table('sp_size');
                    $builder->set('P_size_amount',$sum);
                    $builder->where('P_productid',$row->P_productid);
                    $builder->where('P_size',$row->P_size);
                    $builder->update();
                }
    
              } //
    
              }


    public function showOrder(){

            $db = \Config\Database::connect();
            $builder = $db->table('sp_order');
            $builder->join('sp_customer','sp_customer.C_customerid = sp_order.C_customerid');
            $builder->orderBy('Or_date','DESC');
            $query = $builder->get();

        return json_encode($query->getResult());

    }

    public function showOrderdetailbyid(){
        $db = \Config\Database::connect();
        $builder = $db->table('sp_ordertail');
        $builder->join('sp_product','sp_product.P_productid = sp_ordertail.P_productid');
        $builder->where('sp_ordertail.Or_orderid',$this->request->getVar('Or_orderid'));
        $query = $builder->get();
        return json_encode($query->getResult());
    }

    public function showOrderdetailAll(){
        $db = \Config\Database::connect();
        $builder = $db->table('sp_ordertail');
        $builder->join('sp_product','sp_product.P_productid = sp_ordertail.P_productid');
        $query = $builder->get();
        return json_encode($query->getResult());
    }


    public function addTracking()
    {   try{
            
            // $ems_model = new Ems_Model();
        
            
            $tracking= $this->request->getVar('tracking');
            $ems_or_id= $this->request->getVar('ems_or_id');
            
            $db = \Config\Database::connect();

            $sql="INSERT INTO sp_ems VALUES ('$tracking' ,'$ems_or_id')";
            
            if($query = $db->query($sql)){
                echo 'add tracking complete';
            }else{
                echo 'add tracking faile';
            }
                
            // if($ems_model->insert($data3)){
            //     echo 'add tracking complete';
            // }else{
            //     echo 'add tracking faile';
            // }
    
        } catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function showTracking(){

        $db = \Config\Database::connect();
        $builder = $db->table('sp_ems');
        $query = $builder->get();

    return json_encode($query->getResult());

    }

    public function editTracking(){

        $tracking= $this->request->getVar('tracking');
        $ems_or_id= $this->request->getVar('ems_or_id');
        $db = \Config\Database::connect();

            $sql="UPDATE sp_ems SET tracking = '$tracking' WHERE ems_or_id = '$ems_or_id'";
            
            if($query = $db->query($sql)){
                echo 'update tracking complete';
            }else{
                echo 'update tracking faile';
            }

    }



    public function Checkoutstock(){

        $db = \Config\Database::connect();
        $builder = $db->table('sp_cart');   //ตรงนี้
        $builder->where('C_customerid',$this->request->getVar('C_customerid'));
        $query = $builder->get();

        foreach($query->getResult() as $row){
            $sql  = "SELECT * FROM sp_size WHERE sp_size.P_productid = '".$row->P_productid."' && sp_size.P_size = '".$row->P_size."'";
            $query = $db->query($sql);
            foreach($query->getResult() as $row2){
                $sum = $row2->P_size_amount - $row->Ca_amount;

                $builder = $db->table('sp_size');
                $builder->set('P_size_amount',$sum);
                $builder->where('P_productid',$row->P_productid);
                $builder->where('P_size',$row->P_size);
                if($builder->update()){
                    return true;
                }
                else {
                    return false;
                }
            }

          } //ถึงตรงนี้


    }

}

?>