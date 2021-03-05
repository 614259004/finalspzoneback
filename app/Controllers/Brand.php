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
        $data['sp_band'] = $model -> orderBy('B_bandid','DESC')->findAll();
        return $this->respond($data);
    }
    //get Brand by id
    public function getBrand($id = null){
        $model = new BrandModel();
        $data = $model->where('B_bandid',$id)->first();
        if($data){
            return $this->respond($data);
        }
        else{
            return $this->failNotFound('No Product found');
        }
    }
    //create new brand
    public function creat_brand(){
        $model = new BrandModel();
        $data = [
            
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