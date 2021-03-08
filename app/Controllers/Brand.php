<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\BrandModel;

class Brand extends ResourceController
{
    use ResponseTrait;

    //get all Brand
    public function index(){
        $model = new BrandModel();
        $data['sp_brand'] = $model -> orderBy('B_brandid')->findAll();
        return $this->respond($data);
    }
    //get Brand by id
    public function getBrand($id = null){
        $model = new BrandModel();
        $data = $model->where('B_brandid',$id)->first();
        if($data){
            return $this->respond($data);
        }
        else{
            return $this->failNotFound('No Product found');
        }
    }
    //create new brand
    public function creat_brand(){
        $db = \Config\Database::connect();
        $sql = "SELECT MAX(CAST(SUBSTRING(B_brandid, 3, 6) AS UNSIGNED)) AS maxid FROM sp_brand ";
        $query = $db->query($sql);
        $row = $query->getResult();
        $maxid = $row[0]->maxid;
        $code = '';
       if($maxid == null)
         {
            $code = 'BR0001';
         }else{
                $id = (string) $maxid + 1;
                $fullid =   str_pad($id,4,'0',STR_PAD_LEFT);
                $code = 'BR'.$fullid;

         }
        echo $code;
        $model = new BrandModel();
        $data = [
            'B_brandid'=>$code,
            'B_name'=>$this->request->getVar('B_name'),
            'B_img'=>$this->request->getVar('B_img')
        ];
        $model->insert($data);
        $response =[
            'status' => 201,
            'error' => null,
            'message' => 'Brand creat successfully'
        ];
        return $this->respond($response);
   
    }
    //update brand
    public function update($id = null){
        $model = new BrandModel();
        $data = [
            'B_name'=>$this->request->getVar('B_name'),
            'B_img'=>$this->request->getVar('B_img')
        ];
        $model->update($id,$data);
        $response =[
            'status' => 201,
            'error' => null,
            'message' => 'Brand update successfully'
        ];
        return $this->respond($response);
    }
    //delete brand
    public function delete($id=null){
        $model = new BrandModel();
        $data = $model->find($id);
        if($data){
            $model->delete($id);
            $response =[
                'status' => 201,
                'error' => null,
                'message' => ['Product delete successfully']
            ];
            return $this->respond($response);
        }
        else{
            return $this->failNotFound("No product found");
        }
    }
}