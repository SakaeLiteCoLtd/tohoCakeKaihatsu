<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class KensahyouyobidashiesController extends AppController
{

  public function initialize()
  {
   parent::initialize();
   $this->Users = TableRegistry::get('Users');
   $this->Staffs = TableRegistry::get('Staffs');
   $this->Products = TableRegistry::get('Products');
   $this->Customers = TableRegistry::get('Customers');
   $this->Materials = TableRegistry::get('Materials');
   $this->InspectionStandardSizeChildren = TableRegistry::get('InspectionStandardSizeChildren');
   $this->InspectionStandardSizeParents = TableRegistry::get('InspectionStandardSizeParents');
   $this->ProductConditionParents = TableRegistry::get('ProductConditionParents');
   $this->ProductMaterialMachines = TableRegistry::get('ProductMaterialMachines');
   $this->ProductMachineMaterials = TableRegistry::get('ProductMachineMaterials');
   $this->ProductConditonChildren = TableRegistry::get('ProductConditonChildren');
   $this->InspectionDataResultParents = TableRegistry::get('InspectionDataResultParents');
   $this->InspectionDataResultChildren = TableRegistry::get('InspectionDataResultChildren');
   $this->InspectionDataConditonChildren = TableRegistry::get('InspectionDataConditonChildren');
   $this->InspectionDataConditonParents = TableRegistry::get('InspectionDataConditonParents');
}

  public function beforeFilter(Event $event){
    parent::beforeFilter($event);

    // 認証なしでアクセスできるアクションの指定
    $this->Auth->allow(["index",
    "kensakuform","index2"
    ,"menu"]);
  }

    public function index()
    {
      $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
      ->contain(['InspectionStandardSizeParents' => ["Products"]])
      ->where(['InspectionStandardSizeParents.is_active' => 0,
      'InspectionStandardSizeParents.delete_flag' => 0,
      'InspectionStandardSizeChildren.delete_flag' => 0])
      ->order(["inspection_standard_size_parent_id"=>"ASC"])->toArray();

      $arrProducts1 = array();
      for($j=0; $j<count($InspectionStandardSizeChildren); $j++){

        $check = 0;

        if($j == 0){//最初は追加

          $arrProducts1[] = $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"];

        }else{//まだ配列になければ追加

          for($i=0; $i<count($arrProducts1); $i++){
            if($arrProducts1[$i] == $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"]){
              $check = $check + 1;
            }
          }

          if($check == 0){
            $arrProducts1[] = $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"];
          }

        }
      }
      
      $ProductMachineMaterials = $this->ProductMachineMaterials->find()
      ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
      ->where(['ProductConditionParents.delete_flag' => 0,'ProductMachineMaterials.delete_flag' => 0])
      ->order(["ProductMaterialMachines.id"=>"ASC"])->toArray();

      $arrProducts2 = array();
      for($j=0; $j<count($ProductMachineMaterials); $j++){

        $check = 0;

        if($j == 0){//最初は追加

          $arrProducts2[] = $ProductMachineMaterials[$j]["product_material_machine"]["product_condition_parent"]["product"]["product_code"];

        }else{//まだ配列になければ追加

          for($i=0; $i<count($arrProducts2); $i++){
            if($arrProducts2[$i] == $ProductMachineMaterials[$j]["product_material_machine"]["product_condition_parent"]["product"]["product_code"]){
              $check = $check + 1;
            }
          }

          if($check == 0){
            $arrProducts2[] = $ProductMachineMaterials[$j]["product_material_machine"]["product_condition_parent"]["product"]["product_code"];
          }

        }
      }

      $arrProducts = array_merge($arrProducts1, $arrProducts2);
      $arrProducts = array_unique($arrProducts);
      $arrProducts = array_values($arrProducts);

      $arrKensahyous = array();
      for($i=0; $i<count($arrProducts); $i++){

        $Products = $this->Products->find()
        ->where(['product_code' => $arrProducts[$i]])->toArray();

        $product_code_ini = substr($arrProducts[$i], 0, 11);
        $machine_num = "-";

        $kikakucheck = array_search($arrProducts[$i], $arrProducts1);
        if(strlen($kikakucheck) > 0){
          $kikaku = "登録済み";
        }else{
          $kikaku = 0;
        }
        $seikeijoukencheck = array_search($arrProducts[$i], $arrProducts2);
        if(strlen($seikeijoukencheck) > 0){
          $seikeijouken = "登録済み";

          $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
          ->where(['product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
          ->order(["version"=>"DESC"])->toArray();
  
          if(isset($ProductConditionParents[0])){
            $machine_num = $ProductConditionParents[0]["machine_num"];;
          }

        }else{
          $seikeijouken = 0;
        }

        $arrKensahyous[] = [
          "product_code_ini" => $product_code_ini,
          "product_code" => $arrProducts[$i],
          "product_name" => $Products[0]["name"],
          "machine_num" => $machine_num,
          "kikaku" => $kikaku,
          "seikeijouken" => $seikeijouken
        ];

      }
      $this->set('arrKensahyous', $arrKensahyous);
/*
      echo "<pre>";
      print_r($arrKensahyous);
      echo "</pre>";
*/
    }

    public function kensakuform()
    {

    }

    public function kensakuichiran()
    {

    }

}
