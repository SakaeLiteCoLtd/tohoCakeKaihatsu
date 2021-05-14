<?php
namespace App\myClass\classprograms;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class htmlproductcheck extends AppController
{
     public function initialize()
    {
        parent::initialize();
        $this->Products = TableRegistry::get('Products');
    }

    public function productcheckprogram($product_code)
   {

     $Products= $this->Products->find()->contain(["Customers"])->where(['product_code' => $product_code])->toArray();

     if(isset($Products[0])){

       $name = $Products[0]["name"];
       $customer= $Products[0]["customer"]["name"];

     }else{

       $name = "no_product";
       $customer= "no_product";

     }

     $arrayproductdate[] = $name;
     $arrayproductdate[] = $customer;

     return $arrayproductdate;

   }

}

?>
