<?php
namespace App\myClass\classprograms;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class htmlautolists extends AppController
{
     public function initialize()
    {
        parent::initialize();
        $this->Customers = TableRegistry::get('Customers');
        $this->Products = TableRegistry::get('Products');
    }

    public function customerlist()
   {
    $Customer_name_list = $this->Customers->find()
    ->where(['delete_flag' => 0])->toArray();
    $arrCustomer_name_list = array();
    for($j=0; $j<count($Customer_name_list); $j++){
      array_push($arrCustomer_name_list,$Customer_name_list[$j]["name"]);
    }

   return $arrCustomer_name_list;
   }

   public function productlistall($product_code)
   {

     return $arrayproductdate;
   }

   public function productlistcustomer($product_code)
   {

     return $arrayproductdate;
   }

}

?>
