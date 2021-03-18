<?php 
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Codeigniter\API\ResponseTrait;
use App\Models\Category_Model;
use App\Models\Status_Model;

class Category extends ResourceController
{
    use ResponseTrait;

    //show all category
    public function showCate()
    {
        $category_model = new Category_Model();
        $data['sp_category'] = $category_model ->where('S_statusid',3) -> findAll();
        return $this -> respond($data);
    }

    //show Product by Category
    public function showProductbycate(){
        $db = \Config\Database::connect();
        $builder = $db->table('sp_category');
        $builder->join('sp_product','sp_product.Cg_categoryid = sp_category.Cg_categoryid');
        $builder->where('sp_category.Cg_categoryid',$this->request->getVar('Cg_categoryid'));
        $query = $builder->get();

        return json_encode($query->getResult());

    }

    //add category
    public function addCate()
    {
        try{
            $db = \Config\Database::connect();
            $sql = "SELECT MAX(CAST(SUBSTRING(Cg_categoryid, 3, 6) AS UNSIGNED)) AS maxid FROM sp_category ";
            $query = $db->query($sql);
            $row = $query->getResult();
            $maxid = $row[0]->maxid;
            $code = '';
           if($maxid == null)
             {
                $code = 'CG0001';
             }else{
                    $id = (string) $maxid + 1;
                    $fullid =   str_pad($id,4,'0',STR_PAD_LEFT);
                    $code = 'CG'.$fullid;
    
             }
            echo $code;
    
            $category_model = new Category_Model();
            $data = [
                'Cg_categoryid' => $code,
                'Cg_name' => $this->request->getVar('Cg_name'),
                'S_statusid' => 3,
            ];

            $category_model->insert($data);
            return true;

            } catch(Exception $e){
                return $e->getMessage();
         }
    }

    //อัพเดรทหมวดหมู่
    public function updateCate($id=null){
        $category_model = new Category_Model();

        $data = [
            'Cg_name' => $this->request->getVar('Cg_name')
        ];

        $category_model->update($id, $data);
        $response = [
            'status' => 201,
            'error' => null,
            'message' => 'Updated Category success'
        ];
        echo $id;
        
        return $this->respond($response);
    }

    //เปลี่ยนค่าเป็น non-active
    public function updateStatus($id=null){
        $category_model = new Category_Model();

        $data = [
            'S_statusid' => 4
        ];

        $category_model->update($id, $data);
        $response = [
            'status' => 201,
            'error' => null,
            'message' => 'Delete success'
        ];
        echo $id;
        
        return $this->respond($response);

    }

    public function deleteCate($id=null){

        $db = \Config\Database::connect();
        $category_model = new Category_Model();
        $builder = $db->table('sp_product');
        $builder ->where('Cg_categoryid',$id);
        
        if($builder ->countAllResults() == 0)

            {
            $category_model->delete($id);
            $response =[
                'status' => 201,
                'error' => null,
                'message' => ['Category delete successfully']
            ];
            return $this->respond($response);
        }
        else{
            return "Cannot Delete Category Because Category ".$id." Used in Product";
        }
    }

}

?>