<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait; //เปลี่ยนค่าเป็นjsonอัตโนมัติโดยไม่ต้องเปลี่ยนไปเปลี่ยนมา
use App\Models\Customer_Model;
use App\Models\Login_Model;
use App\Models\Address_Model;
use App\Models\Province_Model;
use App\Models\Amphur_Model;
use App\Models\District_Model;
class Customer extends ResourceController
{
    use ResponseTrait;

    //ดูข้อมูลส่วนตัว
    public function getProfile(){
        $db = \Config\Database::connect();
        $builder = $db->table('sp_customer');
        $builder->join('sp_login','sp_login.C_customerid = sp_customer.C_customerid');
        $builder->where('sp_customer.C_customerid',$this->request->getVar('C_customerid'));
        $query = $builder->get();

        return json_encode($query->getResult());

    }

    public function getAllCustomer(){
        $db = \Config\Database::connect();
        $builder = $db->table('sp_customer');
        $builder->join('sp_status','sp_status.S_statusid  = sp_customer.S_statusid');
        $builder->where('sp_customer.S_statusid',2);
        $query = $builder->get();

        return json_encode($query->getResult());
        
    }

    public function getBlockCustomer(){
        $db = \Config\Database::connect();
        $builder = $db->table('sp_customer');
        $builder->join('sp_status','sp_status.S_statusid  = sp_customer.S_statusid');
        $builder->where('sp_customer.S_statusid',14);
        $query = $builder->get();

        return json_encode($query->getResult());
        
    }

    public function blockCustomer($id=null){
        $customer_model = new Customer_Model();
        $data = [
            'S_statusid' => 14
        ];
            
        if($customer_model->update($id, $data)){
            return "Block success";
        }else{
            return "Block fail";
        }
        
    }

    public function unblockCustomer($id=null){
        $customer_model = new Customer_Model();
        $data = [
            'S_statusid' => 2
        ];
            
        if($customer_model->update($id, $data)){
            return "Block success";
        }else{
            return "Block fail";
        }
        
    }


    //ดูข้อมูลที่อยู่
    public function getAddress(){
        $db = \Config\Database::connect();
        $builder = $db->table('sp_customer');
        $builder->join('sp_address','sp_address.C_customerid = sp_customer.C_customerid');
        $builder->join('province','sp_address.A_province = province.PROVINCE_ID');
        $builder->join('amphur','sp_address.A_district = amphur.AMPHUR_ID');
        $builder->join('district','sp_address.A_canton = district.DISTRICT_ID');
        $builder->where('sp_customer.C_customerid',$this->request->getVar('C_customerid'));
        $query = $builder->get();

        return json_encode($query->getResult());

    }

    //สมัครสมาชิก
    public function addCustomer()
    {   
        try{
        $db = \Config\Database::connect();
        $sql = "SELECT MAX(CAST(SUBSTRING(C_customerid, 3, 6) AS UNSIGNED)) AS maxid FROM sp_customer ";
        $query = $db->query($sql);
        $row = $query->getResult();
        $maxid = $row[0]->maxid;
        $code = '';
       if($maxid == null)
         {
            $code = 'CM0001';
         }else{
                $id = (string) $maxid + 1;
                $fullid =   str_pad($id,4,'0',STR_PAD_LEFT);
                $code = 'CM'.$fullid;

         }
        echo $code;

        $customer_model = new Customer_Model();
        $login_model = new Login_Model();
        $data = [
            'C_customerid' => $code,
            'C_name' => $this->request->getVar('C_name'),
            'C_lastname' => $this->request->getVar('C_lastname'),
            'C_tel' => $this->request->getVar('C_tel'),
            'C_image' =>  $this->request->getVar('C_image'),
            'S_statusid' => 2,

        ];
        $data2 = [
            'C_customerid' => $code,
            'L_email' => $this->request->getVar('L_email'),
            'L_password' => md5($this->request->getVar('L_password')),
        ];
        
        $customer_model->insert($data);
        $login_model->insert($data2);


        return true;
        
        } catch(Exception $e){
            return $e->getMessage();
        }
    }

    //เพื่มที่อยู่
    public function addAddress()
    {   
        try{
        $db = \Config\Database::connect();
        $sql = "SELECT MAX(CAST(SUBSTRING(A_addressid, 3, 6) AS UNSIGNED)) AS maxid FROM sp_address ";
        $query = $db->query($sql);
        $row = $query->getResult();
        $maxid = $row[0]->maxid;
        $code = '';
       if($maxid == null)
         {
            $code = 'AD0001';
         }else{
                $id = (string) $maxid + 1;
                $fullid = str_pad($id,4,'0',STR_PAD_LEFT);
                $code = 'AD'.$fullid;

         }
        echo $code;

       
        $address_model = new Address_Model();
        
        $data3= [
            'A_addressid' => $code,
            'A_homenumber' => $this->request->getVar('A_homenumber'),
            'A_province' => $this->request->getVar('A_province'),
            'A_district' => $this->request->getVar('A_district'),
            'A_canton' => $this->request->getVar('A_canton'),
            'A_moo' => $this->request->getVar('A_moo'),
            'A_postal_code' => $this->request->getVar('A_postal_code'),
            'A_receive_name' => $this->request->getVar('A_receive_name'),
            'A_phone' => $this->request->getVar('A_phone'),
            'C_customerid' => $this->request->getVar('C_customerid')
        ];
     
       $address_model->insert($data3);

        return true;
        
        } catch(Exception $e){
            return $e->getMessage();
        }
    }

    //แก้ไขข้อมูลส่วนตัว
    public function updateProfile(){

        $customer_model = new Customer_Model();
        $data = [

            'C_name' => $this->request->getVar('C_name'),
            'C_lastname' => $this->request->getVar('C_lastname'),
            'C_tel' => $this->request->getVar('C_tel'),
            'C_image' => $this->request->getVar('C_image'),
            'S_statusid' => 2
        ];

        $login_model = new Login_Model();
        $data2 = [
            
            'L_email' => $this->request->getVar('L_email'),
        ];

        $customer_model->update( $this->request->getVar('C_customerid'),$data);
        $login_model->update($this->request->getVar('C_customerid'),$data2);

        $response = [
            'status' => 201,
            'error' => null,
            'message' => 'Updated Profile success'
        ];
        echo $id;
        
        return $this->respond($response);
        
    }

    
    //แก้ไขที่ออยู๋
    public function updateAddress(){
        $address_model = new Address_Model();
        $data = [
            'A_homenumber' => $this->request->getVar('A_homenumber'),
            'A_province' => $this->request->getVar('A_province'),
            'A_district' => $this->request->getVar('A_district'),
            'A_canton' => $this->request->getVar('A_canton'),
            'A_moo' => $this->request->getVar('A_moo'),
            'A_postal_code' => $this->request->getVar('A_postal_code'),
            'A_receive_name' => $this->request->getVar('A_receive_name'),
            'A_phone' => $this->request->getVar('A_phone'),
            'C_customerid' => $this->request->getVar('C_customerid')
        ];
        $address_model->update($this->request->getVar('A_addressid'),$data);
        $response = [
            'status' => 201,
            'error' => null,
            'message' => 'Updated Address success'
        ];
        return $this->respond($response);
    }

    //login 

    public function login(){
        $db = \Config\Database::connect();
        
        $email = $this->request->getVar('L_email');
        $password = md5($this->request->getVar('L_password'));
        $builder = $db->table('sp_login');
        $builder->join('sp_customer','sp_login.C_customerid = sp_customer.C_customerid');
        $builder->where('L_email',$email);
        $builder->where('L_password',$password);
        $query = $builder->get();
        return json_encode($query->getResult());
        
        

    }

    public function deleteAddressbyid(){

        $db = \Config\Database::connect();
        $builder = $db->table('sp_address');
        
        $data = [

            'A_addressid' => $this->request->getVar('A_addressid')
        ];

        $builder -> where($data);
        $builder ->delete();

        return true ;
     
    }

}