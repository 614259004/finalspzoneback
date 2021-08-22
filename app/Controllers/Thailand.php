<?php 

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Codeigniter\API\ResponseTrait;
use App\Models\Province_Model;
use App\Models\Amphur_Model;
use App\Models\District_Model;

class Thailand extends ResourceController
{
    public function getProvince(){
        $db = \Config\Database::connect();
        $builder = $db->table('province');
        $query = $builder->get();

        return json_encode($query->getResult());

    }


    public function getAmphur(){
        $db = \Config\Database::connect();
        $builder = $db->table('amphur');
        $query = $builder->get();

        return json_encode($query->getResult());

    }

    public function getAmphurById(){
        $db = \Config\Database::connect();
        $builder = $db->table('amphur');
        $builder->where('AMPHUR_ID',$this->request->getVar('AMPHUR_ID'));
        $query = $builder->get();

        return json_encode($query->getResult());

    }

    public function getDistrict(){
        $db = \Config\Database::connect();
        $builder = $db->table('district');
        $query = $builder->get();

        return json_encode($query->getResult());

    }

}

?>