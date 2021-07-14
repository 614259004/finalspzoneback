<?php 
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Codeigniter\API\ResponseTrait;
use App\Models\Cart_Model;

class Cart extends ResourceController
{
    public function addCart() {
       
        try{
        $cart_model = new Cart_Model();
        $data = [
            'Ca_cartid',
            'P_productid' => $this->request->getVar('P_productid'),
            'C_customerid' => $this->request->getVar('C_customerid'),
            'P_size' => $this->request->getvar('P_size'),
            'Ca_amount'  => $this->request->getVar('Ca_amount'),
        ];

        $cart_model->insert($data);
        return true;

            } catch(Exception $e){
                return $e->getMessage();
            }
        }

    public function showCartbyid(){

        
        $cart_model = new Cart_Model();
        $data['sp_cart'] = $cart_model ->where('C_customerid',$this->request->getVar('C_customerid')) -> findAll();
        return $this -> respond($data);

        }

        public function updateCartbyid($id=null){
            
            
            $db = \Config\Database::connect();
            $cart_model = new Cart_Model();
            $data = [
    
                'Ca_amount' => $this->request->getVar('Ca_amount'),
            
            ];
            $cart_model->where('P_productid',$this->request->getVar('P_productid'))->update($id, $data);
            
            $response = [
                'status' => 201,
                'error' => null,
                'message' => 'Updated amount in cart success'
            ];
            echo $id;
            
            return $this->respond($response);
    
        }

        public function deleteCartbyid(){

            

            $db = \Config\Database::connect();
            $builder = $db->table('sp_cart');
            
            $data = [
    
                'Ca_cartid' => $this->request->getVar('Ca_cartid')
            ];
    
            $builder -> where($data);
            $builder ->delete();
    
            return true ;
         
        }
    }

?>