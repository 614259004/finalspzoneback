<?php 
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Codeigniter\API\ResponseTrait;
use App\Models\Category_Model;
use App\Models\Size_Model;

class Recheck extends ResourceController{

    public function checkDuplicateName(){
       
        $db = \Config\Database::connect();
        $name = $this->request->getVar('name');
        $table = $this->request->getVar('table_name');
        $builder = $db->table($table);
        switch($table){
            case 'sp_category' : $builder->where('Cg_name',$name); break;
            case 'sp_brand' : $builder->where('B_name',$name); break;
            case 'sp_customer' : $builder->where('C_name',$name); break;
            case 'sp_login' : $builder->where('L_email',$name); break;
            case 'sp_product' : $builder->where('P_name',$name); break;
            default : return 'ไม่พบตาราง'; break;
        }
        
       if($builder->countAllResults() >= 1){
          echo "true"; 
          return true;
            
       }else{
           echo "false";
           return false;
           
       }
        

    }

    public function checkSize(){

        $db = \Config\Database::connect();
        $id = $this->request->getVar('P_productid');
        $size = $this->request->getVar('P_size');
        $builder = $db->table('sp_size');
        $builder->where('P_productid',$id);
        $builder->where('P_size',$size);
 
      if($builder->countAllResults() >=1){
            echo "true"; 
            return true;
        }else{
            echo "false";
            return false;
        }


    }

    
    
}
