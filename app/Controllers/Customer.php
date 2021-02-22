<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait; //เปลี่ยนค่าเป็นjsonอัตโนมัติโดยไม่ต้องเปลี่ยนไปเปลี่ยนมา
use App\Models\Customer_Model;
use App\Models\Login_Model;
use App\Models\Address_Model;
class Customer extends ResourceController
{
    use ResponseTrait;

    //ดูข้อมูลส่วนตัว
    public function getProfile(){
        $db = \Config\Database::connect();
        $builder = $db->table('sp_customer');
        $builder->join('sp_address','sp_address.C_customerid = sp_customer.C_customerid');
        $builder->join('sp_login','sp_login.C_customerid = sp_customer.C_customerid');
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

        $customer_model = new Customer_model();
        $login_model = new Login_model();
        $data = [
            'C_customerid' => $code,
            'C_name' => $this->request->getVar('C_name'),
            'C_lastname' => $this->request->getVar('C_lastname'),
            'C_tel' => $this->request->getVar('C_tel'),
            'C_image' => 'test',
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
    public function addAddress($id=null)
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

       
        $address_model = new Address_model();
        
        $data3= [
            'A_addressid' => $code,
            'A_homenumber' => $this->request->getVar('A_homenumber'),
            'A_province' => $this->request->getVar('A_province'),
            'A_district' => $this->request->getVar('A_district'),
            'A_canton' => $this->request->getVar('A_canton'),
            'A_moo' => $this->request->getVar('A_moo'),
            'A_postal_code' => $this->request->getVar('A_postal_code'),
            'A_receive_name' => $this->request->getVar('A_receive_name'),
            'C_customerid' => $id,
        ];
     
       $address_model->insert($data3);

        return true;
        
        } catch(Exception $e){
            return $e->getMessage();
        }
    }

    //แก้ไขข้อมูลส่วนตัว
    public function updateProfile($id=null){
        $customer_model = new Customer_model();

        $data = [
            'C_name' => $this->request->getVar('C_name'),
            'C_lastname' => $this->request->getVar('C_lastname'),
            'C_tel' => $this->request->getVar('C_tel'),
            'C_image' => $this->request->getVar('C_image')
        ];
        $customer_model->update($id,$data);
        $response = [
            'status' => 201,
            'error' => null,
            'message' => 'Updated Profile success'
        ];
        return $this->respond($response);
    }
    
    //แก้ไขที่ออยู๋
    public function updateAddress($id=null){
        $address_model = new Address_model();

        $data = [
            'A_homenumber' => $this->request->getVar('A_homenumber'),
            'A_province' => $this->request->getVar('A_province'),
            'A_district' => $this->request->getVar('A_district'),
            'A_canton' => $this->request->getVar('A_canton'),
            'A_moo' => $this->request->getVar('A_moo'),
            'A_postal_code' => $this->request->getVar('A_postal_code'),
            'A_receive_name' => $this->request->getVar('A_receive_name'),
        ];
        $customer_model->update($id,$data);
        $response = [
            'status' => 201,
            'error' => null,
            'message' => 'Updated Address success'
        ];
        return $this->respond($response);
    }

}