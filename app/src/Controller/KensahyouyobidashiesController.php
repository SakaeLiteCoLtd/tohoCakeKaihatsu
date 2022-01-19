<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション

use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
$htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
use App\myClass\classprograms\htmlkensahyoulogincheck;//myClassフォルダに配置したクラスを使用
$htmlkensahyoulogincheck = new htmlkensahyoulogincheck();

class KensahyouyobidashiesController extends AppController
{

  public function initialize()
  {
   parent::initialize();
   $this->Seikeikis = TableRegistry::get('Seikeikis');
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
   $this->Linenames = TableRegistry::get('Linenames');

   $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
   $this->Groups = TableRegistry::get('Groups');

   if(!isset($_SESSION)){//フォーム再送信の確認対策//戻りたい画面でわざとwarningを出しておけば戻れる
    session_start();
  }
  header('Expires:');
  header('Cache-Control:');
  header('Pragma:');

  }

  public function beforeFilter(Event $event){
    parent::beforeFilter($event);

  }

  public function menu()
  {
    $Data = $this->request->query('s');

    echo "<pre>";
    print_r($Data);
    echo "</pre>";

  }

    public function index()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $mess = "";
      $this->set('mess',$mess);

      $session = $this->request->getSession();
      $datasession = $session->read();

      if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

        $Groups = $this->Groups->find()->contain(["Menus"])
        ->where(['Groups.group_code' => $datasession['Auth']['User']['group_code'], 'Menus.id' => 40, 'Groups.delete_flag' => 0])
        ->toArray();
 
        if(!isset($Groups[0])){//権限がない場合
          $account_check = 0;
        }else{
          $account_check = 1;
        }
        $this->set('account_check', $account_check);

      }

      $Users = $this->Users->find('all')->contain(["Staffs"])->where(['user_code' => $datasession['Auth']['User']['user_code'], 'Users.delete_flag' => 0])->toArray();
      $user_code = $datasession['Auth']['User']['user_code'];
      $staff_id = $datasession['Auth']['User']['staff_id'];
      $Staffs = $this->Staffs->find()->where(['id' => $staff_id])->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      $data = $this->request->getData();

      $arrkensaku = array_keys($data, '検索');
      $arrtouroku = array_keys($data, '登録');

      if(isset($arrkensaku[0])){//検索の場合

        $data["machine_num"];

        $arrmachine_num = explode("_",$data["machine_num"]);
        $line_factory_id = $arrmachine_num[0];
        $machine_num = $arrmachine_num[1];

        return $this->redirect(['action' => 'kensakuichiran',
        's' => ['product_name' => $data["product_name"], 'machine_num' => $machine_num, 'line_factory_id' => $line_factory_id]]);

      }

      if(isset($arrtouroku[0])){//登録の場合

        if($account_check == 0){//権限がない時

          $mess = "成形条件を登録する権限がありません。責任者に報告してください。";
          $this->set('mess',$mess);
 
        }else{

        $arrcheck = explode("_",$arrtouroku[0]);
        $check_what = $arrcheck[0];
  
        if($check_what === "kikaku"){//規格の登録

          if(isset($arrcheck[2])){
            $product_code = $arrcheck[1]."_".$arrcheck[2];
          }else{
            $product_code = $arrcheck[1];
          }

          return $this->redirect(['controller' => 'kensahyoukikakus',  'action' => 'addimageform',
          's' => ['product_code' => $product_code, 'user_code' => $user_code]]);
  
        }else{//成形条件の登録

          $product_code = $arrtouroku[0];
          return $this->redirect(['controller' => 'kensahyougenryous',  'action' => 'addformpregouki',
          's' => ['product_code' => $product_code, 'user_code' => $user_code]]);
    
        }

      }        

      }

      if($factory_id == 5){//本部の人がログインしている場合

        $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
        ->contain(['InspectionStandardSizeParents' => ["Products"]])
        ->where(['InspectionStandardSizeParents.is_active' => 0,
        'InspectionStandardSizeParents.delete_flag' => 0,
        'InspectionStandardSizeChildren.delete_flag' => 0])
        ->order(["inspection_standard_size_parent_id"=>"ASC"])->toArray();

      }else{

        $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
        ->contain(['InspectionStandardSizeParents' => ["Products"]])
        ->where(['InspectionStandardSizeParents.is_active' => 0,
        'InspectionStandardSizeParents.delete_flag' => 0,
        'InspectionStandardSizeChildren.delete_flag' => 0,
        'OR' => [['Products.factory_id' => $factory_id], ['Products.factory_id' => 5]]])
        ->order(["inspection_standard_size_parent_id"=>"ASC"])->toArray();
  
      }

      $arrProducts1 = array();
      $arrProduct_created_at = array();
      for($j=0; $j<count($InspectionStandardSizeChildren); $j++){

        $check = 0;

        if($j == 0){//最初は追加

          $arrProducts1[] = $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"];
          $product_code_ini = substr($InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"], 0, 11);
          $arrProduct_created_at[] = [
            "product_code_ini" => $product_code_ini,
            "product_code" => $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"],
            "kikaku_created_at" => $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["created_at"]->format("Y-m-d H:i:s")
          ];

        }else{//まだ配列になければ追加

          for($i=0; $i<count($arrProducts1); $i++){
            if($arrProducts1[$i] == $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"]){
              $check = $check + 1;
            }
          }

          if($check == 0){
            $arrProducts1[] = $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"];
            $product_code_ini = substr($InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"], 0, 11);
            $arrProduct_created_at[] = [
              "product_code_ini" => $product_code_ini,
              "product_code" => $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"],
              "kikaku_created_at" => $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["created_at"]->format("Y-m-d H:i:s")
            ];
          }

        }

      }
      
      if($factory_id == 5){//本部の人がログインしている場合

        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['ProductConditionParents.delete_flag' => 0,'ProductMachineMaterials.delete_flag' => 0])
        ->order(["ProductMaterialMachines.id"=>"ASC"])->toArray();

        }else{

        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['ProductConditionParents.delete_flag' => 0,'ProductMachineMaterials.delete_flag' => 0,
        'OR' => [['Products.factory_id' => $factory_id], ['Products.factory_id' => 5]]])
        ->order(["ProductMaterialMachines.id"=>"ASC"])->toArray();

        }

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
        $seikeijouken_created_at = date('Y-m-d H:i:s');
        $kikaku_created_at = date('Y-m-d H:i:s');

        $kikakucheck = array_search($arrProducts[$i], $arrProducts1);
        if(strlen($kikakucheck) > 0){
          $kikaku = "登録済み";

          for($j=0; $j<count($arrProduct_created_at); $j++){
            if($arrProduct_created_at[$j]["product_code_ini"] == $product_code_ini){
              $kikaku_created_at = $arrProduct_created_at[$j]["kikaku_created_at"];
            }
          }

        }else{
          $kikaku = 0;
        }
        $seikeijoukencheck = array_search($arrProducts[$i], $arrProducts2);
        if(strlen($seikeijoukencheck) > 0){
          $seikeijouken = "登録済み";

          if($factory_id == 5){//本部の人がログインしている場合

            $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
            ->where(['product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
            ->order(["version"=>"DESC"])->toArray();
  
          }else{

            $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
            ->where(['product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0,
            'OR' => [['Products.factory_id' => $factory_id], ['Products.factory_id' => 5]]])
            ->order(["version"=>"DESC"])->toArray();

            }
  
          if(isset($ProductConditionParents[0])){

            for($k=0; $k<count($ProductConditionParents); $k++){

              $machine_num = $ProductConditionParents[$k]["machine_num"];;
              $seikeijouken_created_at = $ProductConditionParents[$k]["created_at"]->format("Y-m-d H:i:s");
  
              if(strtotime($kikaku_created_at) > strtotime($seikeijouken_created_at)){
                $datetime = $kikaku_created_at;
              }else{
                $datetime = $seikeijouken_created_at;
              }
      
              if($machine_num == "-"){
                $Linename = "-";
              }else{
                $LinenameDatas = $this->Linenames->find()
                ->where(['delete_flag' => 0, 'factory_id' => $Products[0]["factory_id"], 'machine_num' => $machine_num])->toArray();
                $Linename = $LinenameDatas[0]["name"];
                }
        
              $arrKensahyous[] = [
                "product_code_ini" => $product_code_ini,
                "product_code" => $arrProducts[$i],
                "product_name" => $Products[0]["name"],
                "machine_num" => $Linename,
                "kikaku" => $kikaku,
                "seikeijouken" => $seikeijouken,
                "kikaku_created_at" => $kikaku_created_at,
                "seikeijouken_created_at" => $seikeijouken_created_at,
                "datetime" => $datetime
              ];
  
            }
    
          }

        }else{
          
          $seikeijouken = 0;

          if(strtotime($kikaku_created_at) > strtotime($seikeijouken_created_at)){
            $datetime = $kikaku_created_at;
          }else{
            $datetime = $seikeijouken_created_at;
          }
  
          if($machine_num == "-"){
            $Linename = "-";
          }else{
            $LinenameDatas = $this->Linenames->find()
            ->where(['delete_flag' => 0, 'factory_id' => $Products[0]["factory_id"], 'machine_num' => $machine_num])->toArray();
            $Linename = $LinenameDatas[0]["name"];
            }

          $arrKensahyous[] = [
            "product_code_ini" => $product_code_ini,
            "product_code" => $arrProducts[$i],
            "product_name" => $Products[0]["name"],
            "machine_num" => $Linename,
            "kikaku" => $kikaku,
            "seikeijouken" => $seikeijouken,
            "kikaku_created_at" => $kikaku_created_at,
            "seikeijouken_created_at" => $seikeijouken_created_at,
            "datetime" => $datetime
          ];
  
        }

      }

      array_multisort( array_map( "strtotime", array_column( $arrKensahyous, "datetime" ) ), SORT_DESC, $arrKensahyous);
      $this->set('arrKensahyous', $arrKensahyous);

      if($factory_id == 5){//本部の人がログインしている場合

        $Product_name_list = $this->Products->find()
        ->where(['status_kensahyou' => 0, 'delete_flag' => 0])
        ->toArray();

      }else{

        $Product_name_list = $this->Products->find()
        ->where(['status_kensahyou' => 0, 'delete_flag' => 0,
        'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
        ->toArray();

      }
      $arrProduct_name_list = array();
      for($j=0; $j<count($Product_name_list); $j++){
        array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
      }
      $arrProduct_name_list = array_unique($arrProduct_name_list);
      $arrProduct_name_list = array_values($arrProduct_name_list);
      $this->set('arrProduct_name_list', $arrProduct_name_list);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $Staffs = $this->Staffs->find()
      ->where(['id' => $staff_id])
      ->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      if($factory_id == 5){
        $Linenames = $this->Linenames->find()
        ->where(['delete_flag' => 0])->toArray();
          }else{
            $Linenames = $this->Linenames->find()
            ->where(['delete_flag' => 0, 'factory_id' => $factory_id])->toArray();
              }

      $arrGouki = array();
      for($j=0; $j<count($Linenames); $j++){
        if($j == 0){
          $array = array("a_選択なし" => "選択なし");
          $arrGouki = $arrGouki + $array;
        }
        $array = array($Linenames[$j]["factory_id"]."_".$Linenames[$j]["machine_num"] => $Linenames[$j]["name"]);
        $arrGouki = $arrGouki + $array;
    }
      $this->set('arrGouki', $arrGouki);

      echo "<pre>";
      print_r("");
      echo "</pre>";

    }

    public function kensakuichiran()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $datasession = $session->read();

      if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

        $Groups = $this->Groups->find()->contain(["Menus"])
        ->where(['Groups.group_code' => $datasession['Auth']['User']['group_code'], 'Menus.id' => 40, 'Groups.delete_flag' => 0])
        ->toArray();
 
        if(!isset($Groups[0])){//権限がない場合
          $account_check = 0;
        }else{
          $account_check = 1;
        }
        $this->set('account_check', $account_check);

      }

      $Users= $this->Users->find('all')->contain(["Staffs"])->where(['user_code' => $datasession['Auth']['User']['user_code'], 'Users.delete_flag' => 0])->toArray();
      $user_code = $datasession['Auth']['User']['user_code'];
      $staff_id = $datasession['Auth']['User']['staff_id'];
      $Staffs = $this->Staffs->find()->where(['id' => $staff_id])->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      $getdata = $this->request->getData();

      if(isset($getdata["add"])){

        return $this->redirect(['controller' => 'kensahyoukikakus',  'action' => 'addformpre']);

      }
/*
      echo "<pre>";
      print_r($getdata);
      echo "</pre>";
*/
      $arrtouroku = array_keys($getdata, '登録');
      $arrfukusei = array_keys($getdata, '複製');
      $arrdelete = array_keys($getdata, '削除');
      $arrhyouji = array_keys($getdata, '表示');

      if(isset($arrhyouji[0])){//表示の場合

        $arrcheck = explode("_",$arrhyouji[0]);
        $check_what = $arrcheck[0];

        if($check_what === "kikaku"){//規格の表示

          if(isset($arrcheck[2])){
            $product_code = $arrcheck[1]."_".$arrcheck[2];
          }else{
            $product_code = $arrcheck[1];
          }

          return $this->redirect(['controller' => 'kensahyoukikakus',  'action' => 'kensakuhyouji',
          's' => ['product_code' => $product_code]]);
  
        }else{//成形条件の表示

          $machine_num = $arrcheck[0];
          if(isset($arrcheck[2])){
            $product_code = $arrcheck[1]."_".$arrcheck[2];
          }else{
            $product_code = $arrcheck[1];
          }
          return $this->redirect(['controller' => 'kensahyougenryous',  'action' => 'kensakuhyouji',
          's' => ['product_code' => $product_code, 'machine_num' => $machine_num]]);
    
        }

      }

      if(isset($arrtouroku[0])){//登録の場合

        $arrcheck = explode("_",$arrtouroku[0]);
        $check_what = $arrcheck[0];
  
        if($check_what === "kikaku"){//規格の登録

          if(isset($arrcheck[2])){
            $product_code = $arrcheck[1]."_".$arrcheck[2];
          }else{
            $product_code = $arrcheck[1];
          }

          return $this->redirect(['controller' => 'kensahyoukikakus',  'action' => 'addimageform',
          's' => ['product_code' => $product_code, 'user_code' => $user_code]]);
  
        }else{//成形条件の登録

          $product_code = $arrtouroku[0];
          return $this->redirect(['controller' => 'kensahyougenryous',  'action' => 'addformpregouki',
          's' => ['product_code' => $product_code, 'user_code' => $user_code]]);
    
        }

      }

      if(isset($arrfukusei[0])){//複製の場合

        $arrProMachine = explode("_",$arrfukusei[0]);
        $machine_num = $arrProMachine[0];
        if(isset($arrProMachine[2])){
          $product_code = $arrProMachine[1]."_".$arrProMachine[2];
        }else{
          $product_code = $arrProMachine[1];
        }

        return $this->redirect(['action' => 'fukuseiformgouki',
        's' => ['product_code' => $product_code, 'machine_num' => $machine_num]]);

      }

      if(isset($arrdelete[0])){//削除の場合

        $arrProMachine = explode("_",$arrdelete[0]);
        $machine_num = $arrProMachine[0];
        if(isset($arrProMachine[2])){
          $product_code = $arrProMachine[1]."_".$arrProMachine[2];
        }else{
          $product_code = $arrProMachine[1];
        }

        return $this->redirect(['action' => 'deleteconfirm',
        's' => ['product_code' => $product_code, 'machine_num' => $machine_num]]);

      }
    
      $data = $this->request->query('s');
     /* 
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $product_name = $data["product_name"];
      $this->set('product_name', $product_name);
      $line_machine_num = $data["machine_num"];
      $this->set('machine_num', $line_machine_num);
      $line_factory_id = $data["line_factory_id"];

      if($line_factory_id !== "a"){
        $LinenameDatas = $this->Linenames->find()
        ->where(['delete_flag' => 0, 'factory_id' => $line_factory_id, 'machine_num' => $line_machine_num])->toArray();
        $this->set('linename', $LinenameDatas[0]["name"]);
        }else{
          $this->set('linename', "選択なし");
        }

      if($factory_id == 5){//本部の人がログインしている場合

        $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
        ->contain(['InspectionStandardSizeParents' => ["Products"]])
        ->where(['Products.name like' => '%'.$data["product_name"].'%',
        'InspectionStandardSizeParents.is_active' => 0,
        'InspectionStandardSizeParents.delete_flag' => 0,
        'InspectionStandardSizeChildren.delete_flag' => 0])
        ->order(["inspection_standard_size_parent_id"=>"ASC"])->toArray();
  
      }else{

        $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
        ->contain(['InspectionStandardSizeParents' => ["Products"]])
        ->where(['Products.name like' => '%'.$data["product_name"].'%',
        'InspectionStandardSizeParents.is_active' => 0,
        'InspectionStandardSizeParents.delete_flag' => 0,
        'InspectionStandardSizeChildren.delete_flag' => 0,
        'OR' => [['Products.factory_id' => $factory_id], ['Products.factory_id' => 5]]])
        ->order(["inspection_standard_size_parent_id"=>"ASC"])->toArray();
  
      }


      $arrProducts1 = array();
      $arrProduct_created_at = array();
      for($j=0; $j<count($InspectionStandardSizeChildren); $j++){

        $check = 0;

        if($j == 0){//最初は追加

          $arrProducts1[] = $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"];
          $product_code_ini = substr($InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"], 0, 11);
          $arrProduct_created_at[] = [
            "product_code_ini" => $product_code_ini,
            "product_code" => $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"],
            "kikaku_created_at" => $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["created_at"]->format("Y-m-d H:i:s")
          ];

        }else{//まだ配列になければ追加

          for($i=0; $i<count($arrProducts1); $i++){
            if($arrProducts1[$i] == $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"]){
              $check = $check + 1;
            }
          }

          if($check == 0){
            $arrProducts1[] = $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"];
            $product_code_ini = substr($InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"], 0, 11);
            $arrProduct_created_at[] = [
              "product_code_ini" => $product_code_ini,
              "product_code" => $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["product_code"],
              "kikaku_created_at" => $InspectionStandardSizeChildren[$j]["inspection_standard_size_parent"]["product"]["created_at"]->format("Y-m-d H:i:s")
            ];
          }

        }

      }
      
      if($factory_id == 5){//本部の人がログインしている場合

        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['Products.name like' => '%'.$data["product_name"].'%',
         'ProductConditionParents.delete_flag' => 0,'ProductMachineMaterials.delete_flag' => 0])
        ->order(["ProductMaterialMachines.id"=>"ASC"])->toArray();

        }else{

        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['Products.name like' => '%'.$data["product_name"].'%',
         'ProductConditionParents.delete_flag' => 0,'ProductMachineMaterials.delete_flag' => 0,
         'OR' => [['Products.factory_id' => $factory_id], ['Products.factory_id' => 5]]])
         ->order(["ProductMaterialMachines.id"=>"ASC"])->toArray();

      }

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
        $seikeijouken_created_at = date('Y-m-d H:i:s');
        $kikaku_created_at = date('Y-m-d H:i:s');

        $kikakucheck = array_search($arrProducts[$i], $arrProducts1);
        if(strlen($kikakucheck) > 0){
          $kikaku = "登録済み";

          for($j=0; $j<count($arrProduct_created_at); $j++){
            if($arrProduct_created_at[$j]["product_code_ini"] == $product_code_ini){
              $kikaku_created_at = $arrProduct_created_at[$j]["kikaku_created_at"];
            }
          }

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

            for($k=0; $k<count($ProductConditionParents); $k++){

              $machine_num = $ProductConditionParents[$k]["machine_num"];;
              $seikeijouken_created_at = $ProductConditionParents[$k]["created_at"]->format("Y-m-d H:i:s");
  
              if(strtotime($kikaku_created_at) > strtotime($seikeijouken_created_at)){
                $datetime = $kikaku_created_at;
              }else{
                $datetime = $seikeijouken_created_at;
              }
      
              if($machine_num == "-"){
                $Linename = "-";
              }else{
                $LinenameDatas = $this->Linenames->find()
                ->where(['delete_flag' => 0, 'factory_id' => $Products[0]["factory_id"], 'machine_num' => $machine_num])->toArray();
                $Linename = $LinenameDatas[0]["name"];
                }
    
                if($line_factory_id == "a"){

                  $arrKensahyous[] = [
                    "product_code_ini" => $product_code_ini,
                    "product_code" => $arrProducts[$i],
                    "product_name" => $Products[0]["name"],
                    "machine_num" => $machine_num,
                    "Linename" => $Linename,
                    "kikaku" => $kikaku,
                    "seikeijouken" => $seikeijouken,
                    "kikaku_created_at" => $kikaku_created_at,
                    "seikeijouken_created_at" => $seikeijouken_created_at,
                    "datetime" => $datetime
                  ];

                }elseif($line_machine_num == $machine_num){

                  $arrKensahyous[] = [
                    "product_code_ini" => $product_code_ini,
                    "product_code" => $arrProducts[$i],
                    "product_name" => $Products[0]["name"],
                    "machine_num" => $machine_num,
                    "Linename" => $Linename,
                    "kikaku" => $kikaku,
                    "seikeijouken" => $seikeijouken,
                    "kikaku_created_at" => $kikaku_created_at,
                    "seikeijouken_created_at" => $seikeijouken_created_at,
                    "datetime" => $datetime
                  ];
    
                }
  
            }
    
          }

        }else{
          $seikeijouken = 0;

          if(strtotime($kikaku_created_at) > strtotime($seikeijouken_created_at)){
            $datetime = $kikaku_created_at;
          }else{
            $datetime = $seikeijouken_created_at;
          }
  
          if($machine_num == "-"){
            $Linename = "-";
          }else{
            $LinenameDatas = $this->Linenames->find()
            ->where(['delete_flag' => 0, 'factory_id' => $Products[0]["factory_id"], 'machine_num' => $machine_num])->toArray();
            $Linename = $LinenameDatas[0]["name"];
            }

            if($line_factory_id == "a"){

              $arrKensahyous[] = [
                "product_code_ini" => $product_code_ini,
                "product_code" => $arrProducts[$i],
                "product_name" => $Products[0]["name"],
                "machine_num" => $machine_num,
                "Linename" => $Linename,
                "kikaku" => $kikaku,
                "seikeijouken" => $seikeijouken,
                "kikaku_created_at" => $kikaku_created_at,
                "seikeijouken_created_at" => $seikeijouken_created_at,
                "datetime" => $datetime
              ];

            }elseif($line_machine_num == $machine_num){

              $arrKensahyous[] = [
                "product_code_ini" => $product_code_ini,
                "product_code" => $arrProducts[$i],
                "product_name" => $Products[0]["name"],
                "machine_num" => $machine_num,
                "Linename" => $Linename,
                "kikaku" => $kikaku,
                "seikeijouken" => $seikeijouken,
                "kikaku_created_at" => $kikaku_created_at,
                "seikeijouken_created_at" => $seikeijouken_created_at,
                "datetime" => $datetime
              ];

            }

        }

      }

      array_multisort( array_map( "strtotime", array_column( $arrKensahyous, "datetime" ) ), SORT_DESC, $arrKensahyous ) ;
      $this->set('arrKensahyous', $arrKensahyous);

      if(count($arrKensahyous) > 0){
        $mes = "";
        $this->set('mes',$mes);
        $this->set('check',0);
      }else{
        $mes = "※登録済みの製品はありません。";
        $this->set('mes',$mes);
        $this->set('check',1);
      }


      echo "<pre>";
      print_r("");
      echo "</pre>";

    }

    public function deleteconfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data = $this->request->query('s');
      /*
      echo "<pre>";
      print_r($Data);
      echo "</pre>";
*/
      $product_code = $Data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $Data["machine_num"];
      $this->set('machine_num', $machine_num);

      $ProductDatas = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $LinenameDatas = $this->Linenames->find()
      ->where(['delete_flag' => 0, 'machine_num' => $machine_num, 'factory_id' => $ProductDatas[0]["factory_id"]])->toArray();

      $Products = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $product_name = $Products[0]['name'];
      $this->set('product_name', $product_name);

      if($machine_num == "-"){
        $mes = "製品名：".$product_name."　の検査表画像・規格データを削除します。";
      }else{
        $mes = "製品名：".$product_name."　ライン：".$LinenameDatas[0]["name"]."　の原料・温度条件表を削除します。";
      }
      $this->set('mes',$mes);

    }
  
    public function deletedo()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);
      
      $session = $this->request->getSession();
      $datasession = $session->read();
      $Users= $this->Users->find('all')->contain(["Staffs"])->where(['user_code' => $datasession['Auth']['User']['user_code'], 'Users.delete_flag' => 0])->toArray();
      $staff_id = $Users[0]["staff_id"];

      $data = $this->request->getData();

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $product_name = $data["product_name"];
      $this->set('product_name', $product_name);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);

      $ProductDatas = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $LinenameDatas = $this->Linenames->find()
      ->where(['delete_flag' => 0, 'machine_num' => $machine_num, 'factory_id' => $ProductDatas[0]["factory_id"]])->toArray();

      $product_code_ini = substr($product_code, 0, 11);
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
  */
      if($machine_num == "-"){//規格の削除

        $InspectionStandardSizeParent = $this->InspectionStandardSizeParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.is_active' => 0,
         'InspectionStandardSizeParents.delete_flag' => 0])
        ->toArray();

        $connection = ConnectionManager::get('default');//トランザクション1
        // トランザクション開始2
        $connection->begin();//トランザクション3
        try {//トランザクション4
          if ($this->InspectionStandardSizeParents->updateAll(
            [ 'is_active' => 1,
              'delete_flag' => 1,
              'updated_at' => date('Y-m-d H:i:s'),
              'updated_staff' => $staff_id],
            ['id'  => $InspectionStandardSizeParent[0]["id"]])) {
 
              $this->InspectionStandardSizeChildren->updateAll(
                [ 'delete_flag' => 1,
                  'updated_at' => date('Y-m-d H:i:s'),
                  'updated_staff' => $staff_id],
                ['inspection_standard_size_parent_id' => $InspectionStandardSizeParent[0]["id"]]);
  
              $mes = "※製品名：".$product_name."　の検査表画像・規格データが削除されました。";
              $this->set('mes',$mes);
              $connection->commit();// コミット5
     
          } else {
  
            $mes = "※削除されませんでした";
            $this->set('mes',$mes);
            $this->Flash->error(__('The data could not be saved. Please, try again.'));
            throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
  
          }
  
        } catch (Exception $e) {//トランザクション7
        //ロールバック8
          $connection->rollback();//トランザクション9
        }//トランザクション10

      }else{//原料・温度条件の削除

        $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%'
        , 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

        $product_condition_parent_id = $ProductConditionParents[0]["id"];

        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['product_condition_parent_id' => $product_condition_parent_id, 'machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%',
         'ProductMachineMaterials.delete_flag' => 0, 'ProductConditionParents.delete_flag' => 0])
        ->toArray();
  
        $ProductMaterialMachines = $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['product_condition_parent_id' => $product_condition_parent_id, 'product_code like' => $product_code_ini.'%',
         'ProductMaterialMachines.delete_flag' => 0, 'ProductConditionParents.delete_flag' => 0])
        ->order(["cylinder_number"=>"ASC"])->toArray();

        $connection = ConnectionManager::get('default');//トランザクション1
        // トランザクション開始2
        $connection->begin();//トランザクション3
        try {//トランザクション4
          if ($this->ProductConditionParents->updateAll(
            [ 'is_active' => 1,
              'delete_flag' => 1,
              'updated_at' => date('Y-m-d H:i:s'),
              'updated_staff' => $staff_id],
            ['id'  => $ProductConditionParents[0]["id"]])) {
 
              for($i=0; $i<count($ProductMachineMaterials); $i++){

                $this->ProductMachineMaterials->updateAll(
                  [ 'delete_flag' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_staff' => $staff_id],
                  ['id' => $ProductMachineMaterials[$i]["id"]]);
  
              }

                for($i=0; $i<count($ProductMaterialMachines); $i++){
                
                $this->ProductMaterialMachines->updateAll(
                  [ 'delete_flag' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_staff' => $staff_id],
                  ['id' => $ProductMaterialMachines[$i]["id"]]);
  
              }

              $mes = "製品名：".$product_name."　ライン：".$LinenameDatas[0]["name"]."　の原料・温度条件表が削除されました。";
              $this->set('mes',$mes);
              $connection->commit();// コミット5
     
          } else {
  
            $mes = "※削除されませんでした";
            $this->set('mes',$mes);
            $this->Flash->error(__('The data could not be saved. Please, try again.'));
            throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
  
          }
  
        } catch (Exception $e) {//トランザクション7
        //ロールバック8
          $connection->rollback();//トランザクション9
        }//トランザクション10

      }

    }

    public function fukuseiformgouki()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $datasession = $session->read();
      $Users= $this->Users->find('all')->contain(["Staffs"])->where(['user_code' => $datasession['Auth']['User']['user_code'], 'Users.delete_flag' => 0])->toArray();
      $user_code = $datasession['Auth']['User']['user_code'];
      $this->set('user_code', $user_code);

      $Data = $this->request->query('s');
/*
      echo "<pre>";
      print_r($Data);
      echo "</pre>";
*/
      $product_code = $Data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num_moto = $Data["machine_num"];
      $this->set('machine_num_moto', $machine_num_moto);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $Staffs = $this->Staffs->find()
      ->where(['id' => $staff_id])
      ->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      if($factory_id == 5){
        $Linenames = $this->Linenames->find()
        ->where(['delete_flag' => 0])->toArray();
          }else{
            $Linenames = $this->Linenames->find()
            ->where(['delete_flag' => 0, 'factory_id' => $factory_id])->toArray();
              }

      $arrGouki = array();
      for($j=0; $j<count($Linenames); $j++){
        if($Linenames[$j]["machine_num"] != $machine_num_moto){
          $array = array($Linenames[$j]["machine_num"] => $Linenames[$j]["name"]);
          $arrGouki = $arrGouki + $array;
        }
      }
      $this->set('arrGouki', $arrGouki);

    }

    public function fukuseiform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $mes = "";
      $this->set('mes', $mes);

      $data = $this->request->getData();
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);
      $machine_num_moto = $data["machine_num_moto"];
      $this->set('machine_num_moto', $machine_num_moto);

      $ProductDatas = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $LinenameDatas = $this->Linenames->find()
      ->where(['delete_flag' => 0, 'factory_id' => $ProductDatas[0]["factory_id"], 'machine_num' => $machine_num])->toArray();
      $this->set('linename', $LinenameDatas[0]["name"]);

      $session = $this->request->getSession();
      $datasession = $session->read();
      $Users= $this->Users->find('all')->contain(["Staffs"])->where(['user_code' => $datasession['Auth']['User']['user_code'], 'Users.delete_flag' => 0])->toArray();
      $user_code = $datasession['Auth']['User']['user_code'];
      $this->set('user_code', $user_code);

      $staff_id = $Users[0]["staff_id"];
      $this->set('staff_id', $staff_id);
      $staff_name= $Users[0]["staff"]["name"];
      $this->set('staff_name', $staff_name);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $this->set('user_code', $user_code);

      $Material_name_list = $this->Materials->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrMaterial_name_list = array();
      for($j=0; $j<count($Material_name_list); $j++){
        array_push($arrMaterial_name_list,$Material_name_list[$j]["name"]);
      }
      $this->set('arrMaterial_name_list', $arrMaterial_name_list);

      $Materials = $this->Materials->find()
      ->where(['delete_flag' => 0])->order(["id"=>"ASC"])->toArray();

      $Staffs = $this->Staffs->find()
      ->where(['id' => $staff_id])
      ->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      if($factory_id == 5){
        $Seikeikis = $this->Seikeikis->find()
        ->where(['delete_flag' => 0])->toArray();
          }else{
            $Seikeikis = $this->Seikeikis->find()
            ->where(['delete_flag' => 0, 'factory_id' => $factory_id])->toArray();
              }
/*
      $Seikeikis = $this->Seikeikis->find()
      ->where(['delete_flag' => 0])->toArray();
      */
      $arrSeikeikis = array();
      for($j=0; $j<count($Seikeikis); $j++){
        $arrSeikeikis[$Seikeikis[$j]["name"]] = $Seikeikis[$j]["name"];
      }
      $this->set('arrSeikeikis', $arrSeikeikis);

      if(isset($data["genryoutuika"])){//原料追加ボタン

        if(!isset($data["tuikaseikeiki"])){//成形機の追加前

          $tuikaseikeiki = 1;

        }else{//成形機の追加後

          $tuikaseikeiki = $data["tuikaseikeiki"];

        }
        $this->set('tuikaseikeiki', $tuikaseikeiki);

        ${"tuikagenryou".$tuikaseikeiki} = $data["tuikagenryou".$tuikaseikeiki] + 1;

        for($j=1; $j<=$tuikaseikeiki; $j++){

          if($j < $tuikaseikeiki){

            ${"tuikagenryou".$j} = $data["tuikagenryou".$j];

          }

          $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

          if(isset($data['cylinder_name'.$j])){
            ${"cylinder_name".$j} = $data['cylinder_name'.$j];
            $this->set('cylinder_name'.$j,${"cylinder_name".$j});
          }else{
            ${"cylinder_name".$j} = "";
            $this->set('cylinder_name'.$j,${"cylinder_name".$j});
          }

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            if(isset($data["material_name".$j.$i])){

              $Materials = $this->Materials->find()
              ->where(['name' => $data["material_name".$j.$i]])->toArray();
              if(isset($Materials[0])){
                ${"check_material".$j.$i} = 1;
                $this->set('check_material'.$j.$i,${"check_material".$j.$i});
    
                ${"material_name".$j.$i} = $data["material_name".$j.$i];
                $this->set('material_name'.$j.$i,${"material_name".$j.$i});
                }else{
                  ${"check_material".$j.$i} = 0;
                  $this->set('check_material'.$j.$i,${"check_material".$j.$i});
      
                  ${"material_name".$j.$i} = "データなし";
                  $this->set('material_name'.$j.$i,${"material_name".$j.$i});

                  $mes = "入力された原料名は存在しません。";
                  $this->set('mes', $mes);
        
                  ${"tuikagenryou".$j} = ${"tuikagenryou".$j} - 1;
                  $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});
          
                  }
            }else{

              ${"check_material".$j.$i} = 0;
              $this->set('check_material'.$j.$i,${"check_material".$j.$i});
  
              ${"material_name".$j.$i} = "データなし";
              $this->set('material_name'.$j.$i,${"material_name".$j.$i});
            }

            if(isset($data["mixing_ratio".$j.$i])){
              ${"mixing_ratio".$j.$i} = $data["mixing_ratio".$j.$i];
              $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
            }else{
              ${"mixing_ratio".$j.$i} = "";
              $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
            }

            if(isset($data["dry_temp".$j.$i])){
              ${"dry_temp".$j.$i} = $data["dry_temp".$j.$i];
              $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
            }else{
              ${"dry_temp".$j.$i} = "";
              $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
            }

            if(isset($data["dry_hour".$j.$i])){
              ${"dry_hour".$j.$i} = $data["dry_hour".$j.$i];
              $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
            }else{
              ${"dry_hour".$j.$i} = "";
              $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
            }

            if(isset($data["recycled_mixing_ratio".$j.$i])){
              ${"recycled_mixing_ratio".$j.$i} = $data["recycled_mixing_ratio".$j.$i];
              $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});
            }else{
              ${"recycled_mixing_ratio".$j.$i} = "";
              $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});
            }

          }

        }

      }elseif(isset($data["seikeikituika"])){//成形機追加ボタン

        $tuikaseikeiki = $data["tuikaseikeiki"] + 1;
        $this->set('tuikaseikeiki', $tuikaseikeiki);

        for($j=1; $j<=$tuikaseikeiki; $j++){

          if(isset($data['tuikagenryou'.$j])){
            ${"tuikagenryou".$j} = $data['tuikagenryou'.$j];
          }else{
            ${"tuikagenryou".$j} = 1;
          }

          $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

          if(isset($data['cylinder_name'.$j])){
            ${"cylinder_name".$j} = $data['cylinder_name'.$j];
            $this->set('cylinder_name'.$j,${"cylinder_name".$j});
          }else{
            ${"cylinder_name".$j} = "";
            $this->set('cylinder_name'.$j,${"cylinder_name".$j});
          }

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            if(isset($data["material_name".$j.$i])){

              $Materials = $this->Materials->find()
              ->where(['name' => $data["material_name".$j.$i]])->toArray();
              if(isset($Materials[0])){
                ${"check_material".$j.$i} = 1;
                $this->set('check_material'.$j.$i,${"check_material".$j.$i});
    
                ${"material_name".$j.$i} = $data["material_name".$j.$i];
                $this->set('material_name'.$j.$i,${"material_name".$j.$i});
                }else{
                  ${"check_material".$j.$i} = 0;
                  $this->set('check_material'.$j.$i,${"check_material".$j.$i});
      
                  ${"material_name".$j.$i} = "データなし";
                  $this->set('material_name'.$j.$i,${"material_name".$j.$i});

                  $mes = "入力された原料名は存在しません。";
                  $this->set('mes', $mes);
        
                  $tuikaseikeiki = $tuikaseikeiki - 1;
                  $this->set('tuikaseikeiki', $tuikaseikeiki);
          
                  }
            }else{

              ${"check_material".$j.$i} = 0;
              $this->set('check_material'.$j.$i,${"check_material".$j.$i});
  
              ${"material_name".$j.$i} = "データなし";
              $this->set('material_name'.$j.$i,${"material_name".$j.$i});
            }

            if(isset($data["mixing_ratio".$j.$i])){
              ${"mixing_ratio".$j.$i} = $data["mixing_ratio".$j.$i];
              $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
            }else{
              ${"mixing_ratio".$j.$i} = "";
              $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
            }

            if(isset($data["dry_temp".$j.$i])){
              ${"dry_temp".$j.$i} = $data["dry_temp".$j.$i];
              $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
            }else{
              ${"dry_temp".$j.$i} = "";
              $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
            }

            if(isset($data["dry_hour".$j.$i])){
              ${"dry_hour".$j.$i} = $data["dry_hour".$j.$i];
              $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
            }else{
              ${"dry_hour".$j.$i} = "";
              $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
            }

            if(isset($data["recycled_mixing_ratio".$j.$i])){
              ${"recycled_mixing_ratio".$j.$i} = $data["recycled_mixing_ratio".$j.$i];
              $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});
            }else{
              ${"recycled_mixing_ratio".$j.$i} = "";
              $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});
            }

          }

        }

      }elseif(isset($data["kakuninn"])){//確認ボタン

        $materila_flag = 0;
        for($j=1; $j<=$data["tuikaseikeiki"]; $j++){//原料名にミスがないかチェック

          if(isset($data['tuikagenryou'.$j])){
            ${"tuikagenryou".$j} = $data['tuikagenryou'.$j];
          }else{
            ${"tuikagenryou".$j} = 1;
          }

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            $Materials = $this->Materials->find()
            ->where(['name' => $data["material_name".$j.$i]])->toArray();
            if(!isset($Materials[0])){
              $materila_flag = 1;
            }

          }
        }

        //成形機削除と原料削除が同時に押されていたらアラート
        $double_delete_check = 0;

        for($j=1; $j<=$data["tuikaseikeiki"]; $j++){

          if(isset($data["delete_seikeiki".$j])){

            for($i=1; $i<=$data["tuikagenryou".$j]; $i++){

              if(isset($data["delete_genryou".$j.$i])){

                $double_delete_check = 1;

              }

            }

          }

        }

        if($double_delete_check == 0 && $materila_flag == 0){

          if(!isset($_SESSION)){
            session_start();
          }

          $_SESSION['fukuseikensahyougenryoudata'] = array();
          $_SESSION['fukuseikensahyougenryoudata'] = $data + array("delete_flag" => 0);

          return $this->redirect(['action' => 'fukuseitempform']);

        }else{

          if($double_delete_check == 0){
            $mes = "同じ成形機内で「成形機削除」と「原料削除」が選択されました。もう一度やり直してください。";
          }else{
            $mes = "入力された原料名は存在しません。";
          }
          $this->set('mes', $mes);

          $tuikaseikeiki = $data["tuikaseikeiki"];
          $this->set('tuikaseikeiki', $tuikaseikeiki);

          for($j=1; $j<=$tuikaseikeiki; $j++){

            if(isset($data['tuikagenryou'.$j])){
              ${"tuikagenryou".$j} = $data['tuikagenryou'.$j];
            }else{
              ${"tuikagenryou".$j} = 1;
            }

            $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

            if(isset($data['cylinder_name'.$j])){
              ${"cylinder_name".$j} = $data['cylinder_name'.$j];
              $this->set('cylinder_name'.$j,${"cylinder_name".$j});
            }else{
              ${"cylinder_name".$j} = "";
              $this->set('cylinder_name'.$j,${"cylinder_name".$j});
            }

            for($i=1; $i<=${"tuikagenryou".$j}; $i++){

              if(isset($data["material_name".$j.$i])){

                $Materials = $this->Materials->find()
                ->where(['name' => $data["material_name".$j.$i]])->toArray();
                if(isset($Materials[0])){
                  ${"check_material".$j.$i} = 1;
                  $this->set('check_material'.$j.$i,${"check_material".$j.$i});
      
                  ${"material_name".$j.$i} = $data["material_name".$j.$i];
                  $this->set('material_name'.$j.$i,${"material_name".$j.$i});
                  }else{
                    ${"check_material".$j.$i} = 0;
                    $this->set('check_material'.$j.$i,${"check_material".$j.$i});
        
                    ${"material_name".$j.$i} = "データなし";
                    $this->set('material_name'.$j.$i,${"material_name".$j.$i});
  
                    $mes = "入力された原料名は存在しません。";
                    $this->set('mes', $mes);
            
                    }
              }else{
  
                ${"check_material".$j.$i} = 0;
                $this->set('check_material'.$j.$i,${"check_material".$j.$i});
    
                ${"material_name".$j.$i} = "データなし";
                $this->set('material_name'.$j.$i,${"material_name".$j.$i});
              }
  
              if(isset($data["mixing_ratio".$j.$i])){
                ${"mixing_ratio".$j.$i} = $data["mixing_ratio".$j.$i];
                $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
              }else{
                ${"mixing_ratio".$j.$i} = "";
                $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
              }

              if(isset($data["dry_temp".$j.$i])){
                ${"dry_temp".$j.$i} = $data["dry_temp".$j.$i];
                $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
              }else{
                ${"dry_temp".$j.$i} = "";
                $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
              }

              if(isset($data["dry_hour".$j.$i])){
                ${"dry_hour".$j.$i} = $data["dry_hour".$j.$i];
                $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
              }else{
                ${"dry_hour".$j.$i} = "";
                $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
              }

              if(isset($data["recycled_mixing_ratio".$j.$i])){
                ${"recycled_mixing_ratio".$j.$i} = $data["recycled_mixing_ratio".$j.$i];
                $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});
              }else{
                ${"recycled_mixing_ratio".$j.$i} = "";
                $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});
              }

            }

          }

        }

      }elseif(isset($data["saisyo"])){//最初にこの画面に来た時

          $htmlkensahyoulogincheck = new htmlkensahyoulogincheck();//クラスを使用
          $logincheck = $htmlkensahyoulogincheck->kensahyoulogincheckprogram($user_code);//クラスを使用
    
          if($logincheck === 1){
  
          return $this->redirect(['action' => 'kensakupre',
          's' => ['mess' => "データ登録の権限がありません。"]]);
  
          }

        $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['machine_num' => $machine_num_moto, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

        if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

          $product_code_ini = substr($product_code, 0, 11);
          $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
          ->where(['machine_num' => $machine_num_moto, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
          ->order(["version"=>"DESC"])->toArray();
  
        }
  
        $version = $ProductConditionParents[0]["version"];
  
        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['machine_num' => $machine_num_moto, 'version' => $version, 'product_code' => $product_code, 'ProductMachineMaterials.delete_flag' => 0, 'ProductConditionParents.delete_flag' => 0])
        ->toArray();

        $ProductMaterialMachines = $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['machine_num' => $machine_num_moto, 'version' => $version, 'product_code' => $product_code, 'ProductMaterialMachines.delete_flag' => 0, 'ProductConditionParents.delete_flag' => 0])
        ->order(["cylinder_number"=>"ASC"])->toArray();

        if(!isset($ProductMaterialMachines[0])){//長さ違いのデータがあればそれを持ってくる

          $product_code_ini = substr($product_code, 0, 11);
          $ProductMaterialMachines= $this->ProductMaterialMachines->find()
          ->contain(['ProductConditionParents' => ["Products"]])
          ->where(['Products.product_code like' => $product_code_ini.'%',
          'ProductConditionParents.delete_flag' => 0,
          'ProductMaterialMachines.delete_flag' => 0,
          'ProductConditionParents.machine_num' => $machine_num_moto,
          'ProductConditionParents.version' => $version])
          ->order(["cylinder_number"=>"ASC"])->toArray();
    
        }
  
        $tuikaseikeiki = count($ProductMaterialMachines);
        $this->set('tuikaseikeiki', $tuikaseikeiki);

        for($j=1; $j<=$tuikaseikeiki; $j++){

          ${"cylinder_name".$j} = $ProductMaterialMachines[$j - 1]["cylinder_name"];
          $this->set('cylinder_name'.$j,${"cylinder_name".$j});

          $ProductMachineMaterials = $this->ProductMachineMaterials->find()
          ->where(['product_material_machine_id' => $ProductMaterialMachines[$j - 1]["id"], 'delete_flag' => 0])
          ->order(["material_number"=>"DESC"])->toArray();

          ${"tuikagenryou".$j} = count($ProductMachineMaterials);
          $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            $Materials = $this->Materials->find()
            ->where(['id' => $ProductMachineMaterials[$i - 1]["material_id"]])->toArray();

            ${"check_material".$j.$i} = 1;
            $this->set('check_material'.$j.$i,${"check_material".$j.$i});
            ${"material_name".$j.$i} = $Materials[0]["name"];
            $this->set('material_name'.$j.$i,${"material_name".$j.$i});
            ${"material_id".$j.$i} = $ProductMachineMaterials[$i - 1]["material_id"];
            $this->set('material_id'.$j.$i,${"material_id".$j.$i});

            ${"mixing_ratio".$j.$i} = $ProductMachineMaterials[$i - 1]["mixing_ratio"];
            $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
            ${"dry_temp".$j.$i} = $ProductMachineMaterials[$i - 1]["dry_temp"];
            $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
            ${"dry_hour".$j.$i} = $ProductMachineMaterials[$i - 1]["dry_hour"];
            $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
            ${"recycled_mixing_ratio".$j.$i} = $ProductMachineMaterials[$i - 1]["recycled_mixing_ratio"];
            $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});

          }

        }

      }else{//原料の変更をするとき
  
        $tuikaseikeiki = $data["tuikaseikeiki"];
        $this->set('tuikaseikeiki', $tuikaseikeiki);

        $materila_flag = 0;
        for($j=1; $j<=$tuikaseikeiki; $j++){//原料名にミスがないかチェック

          if(isset($data['tuikagenryou'.$j])){
            ${"tuikagenryou".$j} = $data['tuikagenryou'.$j];
          }else{
            ${"tuikagenryou".$j} = 1;
          }

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            $Materials = $this->Materials->find()
            ->where(['name' => $data["material_name".$j.$i]])->toArray();
            if(!isset($Materials[0])){
              $materila_flag = 1;
            }

          }
        }

        for($j=1; $j<=$tuikaseikeiki; $j++){

          if(isset($data['tuikagenryou'.$j])){
            ${"tuikagenryou".$j} = $data['tuikagenryou'.$j];
          }else{
            ${"tuikagenryou".$j} = 1;
          }

          $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

          if(isset($data['cylinder_name'.$j])){
            ${"cylinder_name".$j} = $data['cylinder_name'.$j];
            $this->set('cylinder_name'.$j,${"cylinder_name".$j});
          }else{
            ${"cylinder_name".$j} = "";
            $this->set('cylinder_name'.$j,${"cylinder_name".$j});
          }
    
          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            $Materials = $this->Materials->find()
            ->where(['name' => $data["material_name".$j.$i]])->toArray();
            if(isset($Materials[0])){
              ${"check_material".$j.$i} = 1;
              $this->set('check_material'.$j.$i,${"check_material".$j.$i});
  
              ${"material_name".$j.$i} = $data["material_name".$j.$i];
              $this->set('material_name'.$j.$i,${"material_name".$j.$i});

              if(isset($data['henkou'.$j.$i]) && $materila_flag == 0){//変更かつほかの入力された原料名にミスがない場合
                ${"check_material".$j.$i} = 0;
                $this->set('check_material'.$j.$i,${"check_material".$j.$i});
                ${"material_name".$j.$i} = $Materials[0]["name"];
                $this->set('material_name'.$j.$i,${"material_name".$j.$i});
                }else{
                  ${"check_material".$j.$i} = 1;
                  $this->set('check_material'.$j.$i,${"check_material".$j.$i});
                  ${"material_name".$j.$i} = $Materials[0]["name"];
                  $this->set('material_name'.$j.$i,${"material_name".$j.$i});
                  }
  
              }else{
                ${"check_material".$j.$i} = 0;
                $this->set('check_material'.$j.$i,${"check_material".$j.$i});
    
                ${"material_name".$j.$i} = "データなし";
                $this->set('material_name'.$j.$i,${"material_name".$j.$i});

                $mes = "入力された原料名は存在しません。";
                $this->set('mes', $mes);
        
                }

                if(isset($data["mixing_ratio".$j.$i])){
              ${"mixing_ratio".$j.$i} = $data["mixing_ratio".$j.$i];
              $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
            }else{
              ${"mixing_ratio".$j.$i} = "";
              $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
            }

            if(isset($data["dry_temp".$j.$i])){
              ${"dry_temp".$j.$i} = $data["dry_temp".$j.$i];
              $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
            }else{
              ${"dry_temp".$j.$i} = "";
              $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
            }

            if(isset($data["dry_hour".$j.$i])){
              ${"dry_hour".$j.$i} = $data["dry_hour".$j.$i];
              $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
            }else{
              ${"dry_hour".$j.$i} = "";
              $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
            }

            if(isset($data["recycled_mixing_ratio".$j.$i])){
              ${"recycled_mixing_ratio".$j.$i} = $data["recycled_mixing_ratio".$j.$i];
              $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});
            }else{
              ${"recycled_mixing_ratio".$j.$i} = "";
              $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});
            }

          }

        }

      }

      echo "<pre>";
      print_r("");
      echo "</pre>";

    }

    public function fukuseitempform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $arrayfukuseiKensahyougenryoudatas = $_SESSION['fukuseikensahyougenryoudata'];
//      $_SESSION['fukuseikensahyougenryoudata'] = array();

      $data = $arrayfukuseiKensahyougenryoudatas;
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $staff_id = $data["staff_id"];
      $this->set('staff_id', $staff_id);
      $staff_name = $data["staff_name"];
      $this->set('staff_name', $staff_name);
      $delete_flag = 0;
      $this->set('delete_flag', $delete_flag);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);
      $machine_num_moto = $data["machine_num_moto"];
      $this->set('machine_num_moto', $machine_num_moto);

      $ProductDatas = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $LinenameDatas = $this->Linenames->find()
      ->where(['delete_flag' => 0, 'factory_id' => $ProductDatas[0]["factory_id"], 'machine_num' => $machine_num])->toArray();
      $this->set('linename', $LinenameDatas[0]["name"]);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $tuikaseikeiki = $data["tuikaseikeiki"];
      $tuikaseikeikimax = $data["tuikaseikeiki"];
      $this->set('tuikaseikeikimax', $tuikaseikeikimax);

      $n = 0;//成形機の数

      for($j=1; $j<=$tuikaseikeiki; $j++){

        $m = 0;//原料の数
        if(isset($data['cylinder_name'.$j])){
          ${"cylinder_name_tmp".$j} = $data['cylinder_name'.$j];
          $this->set('cylinder_name_tmp'.$j,${"cylinder_name_tmp".$j});
        }else{
          ${"cylinder_name_tmp".$j} = "";
          $this->set('cylinder_name_tmp'.$j,${"cylinder_name_tmp".$j});
        }

        if(!isset($data["delete_seikeiki".$j])){

          ${"delete_seikeiki".$j} = 0;
          $this->set('delete_seikeiki'.$j,${"delete_seikeiki".$j});

          $n = $n + 1;

          $this->set('tuikaseikeiki', $n);//成形機の数をセット

          ${"tuikagenryou".$j} = $data["tuikagenryou".$j];

          if(isset($data['cylinder_name'.$j])){
            ${"cylinder_name".$j} = $data['cylinder_name'.$j];
            $this->set('cylinder_name'.$n,${"cylinder_name".$j});
          }else{
            ${"cylinder_name".$j} = "";
            $this->set('cylinder_name'.$n,${"cylinder_name".$j});
          }
  
          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            if(!isset($data["delete_genryou".$j.$i])){
              $m = $m + 1;

              $this->set('tuikagenryou'.$n, $m);//原料の数をセット

                if(isset($data["material_name".$j.$i])){

                  $Materials = $this->Materials->find()
                  ->where(['name' => $data["material_name".$j.$i]])->toArray();
    
                  ${"material_name".$j.$i} = $Materials[0]["name"];
                  $this->set('material_name'.$n.$m,${"material_name".$j.$i});
                  ${"material_id".$j.$i} = $Materials[0]["id"];
                  $this->set('material_id'.$n.$m,${"material_id".$j.$i});
  
              }else{
                ${"material_name".$j.$i} = "";
                $this->set('material_name'.$n.$i,${"material_name".$j.$i});
                ${"material_id".$j.$i} = "";
                $this->set('material_id'.$n.$m,${"material_id".$j.$i});
              }

              if(isset($data["mixing_ratio".$j.$i])){
                ${"mixing_ratio".$j.$i} = $data["mixing_ratio".$j.$i];
                $this->set('mixing_ratio'.$n.$m,${"mixing_ratio".$j.$i});
              }else{
                ${"mixing_ratio".$j.$i} = "";
                $this->set('mixing_ratio'.$n.$m,${"mixing_ratio".$j.$i});
              }

              if(isset($data["dry_temp".$j.$i])){
                ${"dry_temp".$j.$i} = $data["dry_temp".$j.$i];
                $this->set('dry_temp'.$n.$m,${"dry_temp".$j.$i});
              }else{
                ${"dry_temp".$j.$i} = "";
                $this->set('dry_temp'.$n.$m,${"dry_temp".$j.$i});
              }

              if(isset($data["dry_hour".$j.$i])){
                ${"dry_hour".$j.$i} = $data["dry_hour".$j.$i];
                $this->set('dry_hour'.$n.$m,${"dry_hour".$j.$i});
              }else{
                ${"dry_hour".$j.$i} = "";
                $this->set('dry_hour'.$n.$m,${"dry_hour".$j.$i});
              }

              if(isset($data["recycled_mixing_ratio".$j.$i])){
                ${"recycled_mixing_ratio".$j.$i} = $data["recycled_mixing_ratio".$j.$i];
                $this->set('recycled_mixing_ratio'.$n.$m,${"recycled_mixing_ratio".$j.$i});
              }else{
                ${"recycled_mixing_ratio".$j.$i} = "";
                $this->set('recycled_mixing_ratio'.$n.$m,${"recycled_mixing_ratio".$j.$i});
              }

            }

          }

          if($m < 1){//成形機削除をせずに成形機内の原料を全て削除していた場合
            $n = $n - 1;
            $this->set('tuikaseikeiki', $n);//成形機の数をセット
          }

        }else{//deleteの場合

          ${"delete_seikeiki".$j} = 1;
          $this->set('delete_seikeiki'.$j,${"delete_seikeiki".$j});

        }

      }

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['machine_num' => $machine_num_moto, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['machine_num' => $machine_num_moto, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

      }

      if(isset($ProductConditionParents[0])){

        $version = $ProductConditionParents[0]["version"];

        $ProductMaterialMachines= $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['Products.product_code' => $product_code,
        'ProductConditionParents.delete_flag' => 0,
        'ProductMaterialMachines.delete_flag' => 0,
        'ProductConditionParents.machine_num' => $machine_num_moto,
        'ProductConditionParents.version' => $version])
        ->order(["cylinder_number"=>"ASC"])->toArray();

        if(!isset($ProductMaterialMachines[0])){//長さ違いのデータがあればそれを持ってくる

          $product_code_ini = substr($product_code, 0, 11);
          $ProductMaterialMachines= $this->ProductMaterialMachines->find()
          ->contain(['ProductConditionParents' => ["Products"]])
          ->where(['Products.product_code like' => $product_code_ini.'%',
          'ProductConditionParents.delete_flag' => 0,
          'ProductMaterialMachines.delete_flag' => 0,
          'ProductConditionParents.machine_num' => $machine_num_moto,
          'ProductConditionParents.version' => $version])
          ->order(["cylinder_number"=>"ASC"])->toArray();
  
        }
        
        $countseikeiki = count($ProductMaterialMachines);
        $this->set('countseikeiki', $countseikeiki);

        for($k=0; $k<$countseikeiki; $k++){

          $j = $k + 1;
          ${"product_material_machine_id".$j} = $ProductMaterialMachines[$k]["id"];
          $this->set('product_material_machine_id'.$j, ${"product_material_machine_id".$j});

          $ProductConditonChildren = $this->ProductConditonChildren->find()
          ->where(['product_material_machine_id' => ${"product_material_machine_id".$j}, 'delete_flag' => 0])
          ->order(["id"=>"ASC"])->toArray();

          ${"idmoto".$j} = $ProductConditonChildren[0]["id"];
          $this->set('idmoto'.$j, ${"idmoto".$j});

          ${"extrude_roatation".$j} = $ProductConditonChildren[0]["extrude_roatation"];
          $this->set('extrude_roatation'.$j, ${"extrude_roatation".$j});
          ${"extrusion_load".$j} = $ProductConditonChildren[0]["extrusion_load"];
          $this->set('extrusion_load'.$j, ${"extrusion_load".$j});

          for($n=1; $n<8; $n++){
            ${"temp_".$n.$j} = $ProductConditonChildren[0]["temp_".$n];
            $this->set('temp_'.$n.$j, ${"temp_".$n.$j});
          }

          $pickup_speed = $ProductConditonChildren[0]["pickup_speed"];
          $this->set('pickup_speed', $pickup_speed);

          ${"screw_mesh_1".$j} = $ProductConditonChildren[0]['screw_mesh_1'];
          $this->set('screw_mesh_1'.$j, ${"screw_mesh_1".$j});
          ${"screw_number_1".$j} = $ProductConditonChildren[0]['screw_number_1'];
          $this->set('screw_number_1'.$j, ${"screw_number_1".$j});
          ${"screw_mesh_2".$j} = $ProductConditonChildren[0]['screw_mesh_2'];
          $this->set('screw_mesh_2'.$j, ${"screw_mesh_2".$j});
          ${"screw_number_2".$j} = $ProductConditonChildren[0]['screw_number_2'];
          $this->set('screw_number_2'.$j, ${"screw_number_2".$j});
          ${"screw_mesh_3".$j} = $ProductConditonChildren[0]['screw_mesh_3'];
          $this->set('screw_mesh_3'.$j, ${"screw_mesh_3".$j});
          ${"screw_number_3".$j} = $ProductConditonChildren[0]['screw_number_3'];
          $this->set('screw_number_3'.$j, ${"screw_number_3".$j});
          ${"screw".$j} = $ProductConditonChildren[0]['screw'];
          $this->set('screw'.$j, ${"screw".$j});

        }

        if($countseikeiki < $data["tuikaseikeiki"]){

          for($k=$countseikeiki; $k<$data["tuikaseikeiki"]; $k++){

            $j = $k + 1;
            ${"cylinder_name".$j} = $data['cylinder_name'.$j];
            $this->set('cylinder_name'.$j,${"cylinder_name".$j});
            ${"delete_seikeiki".$j} = 0;
            $this->set('delete_seikeiki'.$j,${"delete_seikeiki".$j});
  
            ${"product_material_machine_id".$j} = 0;
            $this->set('product_material_machine_id'.$j, ${"product_material_machine_id".$j});
            ${"idmoto".$j} = 0;
            $this->set('idmoto'.$j, ${"idmoto".$j});
  
            ${"extrude_roatation".$j} = "";
            $this->set('extrude_roatation'.$j, ${"extrude_roatation".$j});
            ${"extrusion_load".$j} = "";
            $this->set('extrusion_load'.$j, ${"extrusion_load".$j});
  
            for($n=1; $n<8; $n++){
              ${"temp_".$n.$j} = "";
              $this->set('temp_'.$n.$j, ${"temp_".$n.$j});
            }
  
            ${"screw_mesh_1".$j} = "";
            $this->set('screw_mesh_1'.$j, ${"screw_mesh_1".$j});
            ${"screw_number_1".$j} = "";
            $this->set('screw_number_1'.$j, ${"screw_number_1".$j});
            ${"screw_mesh_2".$j} = "";
            $this->set('screw_mesh_2'.$j, ${"screw_mesh_2".$j});
            ${"screw_number_2".$j} = "";
            $this->set('screw_number_2'.$j, ${"screw_number_2".$j});
            ${"screw_mesh_3".$j} = "";
            $this->set('screw_mesh_3'.$j, ${"screw_mesh_3".$j});
            ${"screw_number_3".$j} = "";
            $this->set('screw_number_3'.$j, ${"screw_number_3".$j});
            ${"screw".$j} = "";
            $this->set('screw'.$j, ${"screw".$j});
  
          }
  
        }

      }

      $arrScrewMesh = [
        '-' => '',
        '#30' => '#30',
        '#40' => '#40',
        '#60' => '#60',
        '#80' => '#80',
        '#100' => '#100'
              ];
      $this->set('arrScrewMesh',$arrScrewMesh);

      $arrScrewNumber = [
        '-' => '',
        '0枚(無し)' => '0枚(無し)',
        '1枚' => '1枚',
        '2枚' => '2枚',
        '3枚' => '3枚'
              ];
      $this->set('arrScrewNumber',$arrScrewNumber);

      $arrScrew = [
        'フルフライト' => 'フルフライト',
        'ミキシング' => 'ミキシング',
        'ダルメージ' => 'ダルメージ'
              ];
      $this->set('arrScrew',$arrScrew);

      $mes = "成形条件を入力してください";
      $this->set('mes', $mes);

    }

    public function fukuseitempconfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $staff_id = $data["staff_id"];
      $this->set('staff_id', $staff_id);
      $staff_name = $data["staff_name"];
      $this->set('staff_name', $staff_name);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);
      $machine_num_moto = $data["machine_num_moto"];
      $this->set('machine_num_moto', $machine_num_moto);

      $ProductDatas = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $LinenameDatas = $this->Linenames->find()
      ->where(['delete_flag' => 0, 'factory_id' => $ProductDatas[0]["factory_id"], 'machine_num' => $machine_num])->toArray();
      $this->set('linename', $LinenameDatas[0]["name"]);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $tuikaseikeiki = $data["tuikaseikeiki"];
      $this->set('tuikaseikeiki', $tuikaseikeiki);
      $tuikaseikeikimax = $data["tuikaseikeikimax"];
      $this->set('tuikaseikeikimax', $tuikaseikeikimax);

      for($j=1; $j<=$tuikaseikeiki; $j++){

          ${"tuikagenryou".$j} = $data["tuikagenryou".$j];
          $this->set('tuikagenryou'.$j,${"tuikagenryou".$j});

          ${"cylinder_name".$j} = $data['cylinder_name'.$j];
          $this->set('cylinder_name'.$j,${"cylinder_name".$j});

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            ${"material_name".$j.$i} = $data["material_name".$j.$i];
            $this->set('material_name'.$j.$i,${"material_name".$j.$i});
            ${"material_id".$j.$i} = $data["material_id".$j.$i];
            $this->set('material_id'.$j.$i,${"material_id".$j.$i});

            ${"mixing_ratio".$j.$i} = $data["mixing_ratio".$j.$i];
            $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});

            ${"dry_temp".$j.$i} = $data["dry_temp".$j.$i];
            $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});

            ${"dry_hour".$j.$i} = $data["dry_hour".$j.$i];
            $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});

            ${"recycled_mixing_ratio".$j.$i} = $data["recycled_mixing_ratio".$j.$i];
            $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});

            }

      }

      $countseikeiki = $data["countseikeiki"];
      $this->set('countseikeiki', $countseikeiki);
      $pickup_speed = $data["pickup_speed"];
      $this->set('pickup_speed', $pickup_speed);

      for($k=0; $k<$countseikeiki; $k++){

        $j = $k + 1;
        ${"cylinder_name".$j} = $data['cylinder_name'.$j];
        $this->set('cylinder_name'.$j, ${"cylinder_name".$j});

        for($i=1; $i<=7; $i++){

          if(isset($data['temp_'.$i.$j])){

            ${"temp_".$i.$j} = $data['temp_'.$i.$j];

            $dotini = substr(${"temp_".$i.$j}, 0, 1);
            $dotend = substr(${"temp_".$i.$j}, -1, 1);
  
            if($dotini == "."){
              ${"temp_".$i.$j} = "0".${"temp_".$i.$j};
            }elseif($dotend == "."){
              ${"temp_".$i.$j} = ${"temp_".$i.$j}."0";
            }
            $this->set('temp_'.$i.$j, ${"temp_".$i.$j});
  
          }

        }

        ${"extrude_roatation".$j} = $data['extrude_roatation'.$j];
        $this->set('extrude_roatation'.$j, ${"extrude_roatation".$j});
        ${"extrusion_load".$j} = $data['extrusion_load'.$j];
        $this->set('extrusion_load'.$j, ${"extrusion_load".$j});

        ${"screw_mesh_1".$j} = $data['screw_mesh_1'.$j];
        $this->set('screw_mesh_1'.$j, ${"screw_mesh_1".$j});
        ${"screw_number_1".$j} = $data['screw_number_1'.$j];
        $this->set('screw_number_1'.$j, ${"screw_number_1".$j});
        ${"screw_mesh_2".$j} = $data['screw_mesh_2'.$j];
        $this->set('screw_mesh_2'.$j, ${"screw_mesh_2".$j});
        ${"screw_number_2".$j} = $data['screw_number_2'.$j];
        $this->set('screw_number_2'.$j, ${"screw_number_2".$j});
        ${"screw_mesh_3".$j} = $data['screw_mesh_3'.$j];
        $this->set('screw_mesh_3'.$j, ${"screw_mesh_3".$j});
        ${"screw_number_3".$j} = $data['screw_number_3'.$j];
        $this->set('screw_number_3'.$j, ${"screw_number_3".$j});
        ${"screw".$j} = $data['screw'.$j];
        $this->set('screw'.$j, ${"screw".$j});

      }

      $mes = "下記のように登録します。よろしければ決定ボタンを押してください。";
      $this->set('mes', $mes);

    }

    public function fukuseitempdo()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $staff_id = $data["staff_id"];
      $this->set('staff_id', $staff_id);
      $staff_name = $data["staff_name"];
      $this->set('staff_name', $staff_name);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);

      $ProductDatas = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $LinenameDatas = $this->Linenames->find()
      ->where(['delete_flag' => 0, 'factory_id' => $ProductDatas[0]["factory_id"], 'machine_num' => $machine_num])->toArray();
      $this->set('linename', $LinenameDatas[0]["name"]);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $tuikaseikeiki = $data["tuikaseikeiki"];
      $this->set('tuikaseikeiki', $tuikaseikeiki);

      for($j=1; $j<=$tuikaseikeiki; $j++){

        ${"tuikagenryou".$j} = $data["tuikagenryou".$j];
        $this->set('tuikagenryou'.$j,${"tuikagenryou".$j});

        ${"cylinder_name".$j} = $data['cylinder_name'.$j];
        $this->set('cylinder_name'.$j,${"cylinder_name".$j});

        for($i=1; $i<=${"tuikagenryou".$j}; $i++){

          ${"material_name".$j.$i} = $data["material_name".$j.$i];
          $this->set('material_name'.$j.$i,${"material_name".$j.$i});
          ${"material_id".$j.$i} = $data["material_id".$j.$i];
          $this->set('material_id'.$j.$i,${"material_id".$j.$i});

          ${"mixing_ratio".$j.$i} = $data["mixing_ratio".$j.$i];
          $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});

          ${"dry_temp".$j.$i} = $data["dry_temp".$j.$i];
          $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});

          ${"dry_hour".$j.$i} = $data["dry_hour".$j.$i];
          $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});

          ${"recycled_mixing_ratio".$j.$i} = $data["recycled_mixing_ratio".$j.$i];
          $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});

          }

    }

      $countseikeiki = $data["countseikeiki"];
      $this->set('countseikeiki', $countseikeiki);
      $pickup_speed = $data["pickup_speed"];
      $this->set('pickup_speed', $pickup_speed);

      for($k=0; $k<$countseikeiki; $k++){

        $j = $k + 1;
        ${"cylinder_name".$j} = $data['cylinder_name'.$j];
        $this->set('cylinder_name'.$j, ${"cylinder_name".$j});

        for($i=1; $i<=7; $i++){
          ${"temp_".$i.$j} = $data['temp_'.$i.$j];
          $this->set('temp_'.$i.$j, ${"temp_".$i.$j});
        }

        ${"extrude_roatation".$j} = $data['extrude_roatation'.$j];
        $this->set('extrude_roatation'.$j, ${"extrude_roatation".$j});
        ${"extrusion_load".$j} = $data['extrusion_load'.$j];
        $this->set('extrusion_load'.$j, ${"extrusion_load".$j});

        ${"screw_mesh_1".$j} = $data['screw_mesh_1'.$j];
        $this->set('screw_mesh_1'.$j, ${"screw_mesh_1".$j});
        ${"screw_number_1".$j} = $data['screw_number_1'.$j];
        $this->set('screw_number_1'.$j, ${"screw_number_1".$j});
        ${"screw_mesh_2".$j} = $data['screw_mesh_2'.$j];
        $this->set('screw_mesh_2'.$j, ${"screw_mesh_2".$j});
        ${"screw_number_2".$j} = $data['screw_number_2'.$j];
        $this->set('screw_number_2'.$j, ${"screw_number_2".$j});
        ${"screw_mesh_3".$j} = $data['screw_mesh_3'.$j];
        $this->set('screw_mesh_3'.$j, ${"screw_mesh_3".$j});
        ${"screw_number_3".$j} = $data['screw_number_3'.$j];
        $this->set('screw_number_3'.$j, ${"screw_number_3".$j});
        ${"screw".$j} = $data['screw'.$j];
        $this->set('screw'.$j, ${"screw".$j});

      }

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $tourokuProductConditionParent = array();

      $Products = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $product_id = $Products[0]['id'];

      $ProductConditionParents = $this->ProductConditionParents->find()
      ->where(['product_id' => $product_id, 'machine_num' => $machine_num
      , 'delete_flag' => 0])->order(["version"=>"DESC"])->toArray();

      if(isset($ProductConditionParents[0])){

        $version = $ProductConditionParents[0]["version"] + 1;
        $motoid = $ProductConditionParents[0]["id"];

      }else{

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'machine_num' => $machine_num,
        'ProductConditionParents.is_active' => 0,
        'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

        if(isset($ProductConditionParents[0])){
          
          $version = $ProductConditionParents[0]["version"] + 1;
          $motoid = $ProductConditionParents[0]["id"];

        }else{

          $version = 1;
          $motoid = 0;

          }
  
      }

      $code_date = date('y').date('m').date('d');
      $ProductConditionParents = $this->ProductConditionParents->find()
      ->where(['product_id' => $product_id, 'machine_num' => $machine_num
      , 'product_condition_code like' => $code_date."%"])
      ->toArray();

      $renban = count($ProductConditionParents) + 1;
      $product_condition_code = $code_date."-".$renban;

      $tourokuProductConditionParent = [
        "product_id" => $product_id,
        "machine_num" => $machine_num,
        "version" => $version,
        "product_condition_code" => $product_condition_code,
        "start_datetime" => date("Y-m-d H:i:s"),
        "is_active" => 0,
        "delete_flag" => 0,
        'created_at' => date("Y-m-d H:i:s"),
        "created_staff" => $staff_id
      ];
/*
      echo "<pre>";
      print_r("ProductConditionParents");
      print_r($tourokuProductConditionParent);
      echo "</pre>";
*/
      //新しいデータを登録
      $ProductConditionParents = $this->ProductConditionParents
      ->patchEntity($this->ProductConditionParents->newEntity(), $tourokuProductConditionParent);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->ProductConditionParents->save($ProductConditionParents)) {

          //元のデータを削除
          if($motoid > 0){

            $this->ProductConditionParents->updateAll(
              [ 'delete_flag' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_staff' => $staff_id],
              ['id'  => $motoid]);

          }

          $ProductConditionParents = $this->ProductConditionParents->find()
          ->where(['product_id' => $product_id, 'machine_num' => $machine_num
          , 'version' => $version, 'delete_flag' => 0])->order(["id"=>"DESC"])->toArray();

          for($j=1; $j<=$tuikaseikeiki; $j++){

            $tourokuProductMaterialMachine = array();

            $tourokuProductMaterialMachine = [
              "product_condition_parent_id" => $ProductConditionParents[0]["id"],
              "cylinder_number" => $j,
              "cylinder_name" => $data['cylinder_name'.$j],
              "delete_flag" => 0,
              'created_at' => date("Y-m-d H:i:s"),
              "created_staff" => $staff_id
            ];

            $ProductMaterialMachines = $this->ProductMaterialMachines
            ->patchEntity($this->ProductMaterialMachines->newEntity(), $tourokuProductMaterialMachine);
            if ($this->ProductMaterialMachines->save($ProductMaterialMachines)) {

              $ProductMaterialMachines = $this->ProductMaterialMachines->find()
              ->where(['product_condition_parent_id' => $ProductConditionParents[0]["id"]
              , 'cylinder_number' => $j, "delete_flag" => 0])->order(["id"=>"DESC"])->toArray();

              $tourokuProductMachineMaterial = array();
              ${"tuikagenryou".$j} = $data["tuikagenryou".$j];

              for($i=1; $i<=${"tuikagenryou".$j}; $i++){

                $tourokuProductMachineMaterial[] = [
                  "product_material_machine_id" => $ProductMaterialMachines[0]["id"],
                  "material_number" => $i,
                  "material_id" => $data["material_id".$j.$i],
                  "mixing_ratio" => $data["mixing_ratio".$j.$i],
                  "dry_temp" => $data["dry_temp".$j.$i],
                  "dry_hour" => $data["dry_hour".$j.$i],
                  "recycled_mixing_ratio" => $data["recycled_mixing_ratio".$j."1"],
                  "delete_flag" => 0,
                  'created_at' => date("Y-m-d H:i:s"),
                  "created_staff" => $staff_id
                ];

              }
/*
              echo "<pre>";
              print_r("ProductMachineMaterials");
              print_r($tourokuProductMachineMaterial);
              echo "</pre>";
  */      
              $ProductMachineMaterials = $this->ProductMachineMaterials
              ->patchEntities($this->ProductMachineMaterials->newEntity(), $tourokuProductMachineMaterial);
              if ($this->ProductMachineMaterials->saveMany($ProductMachineMaterials)) {

                $tourokuProductConditonChildren[] = [
                  "product_material_machine_id" => $ProductMaterialMachines[0]['id'],
                  "cylinder_number" => $j,
                  "cylinder_name" => $data['cylinder_name'.$j],
                  "temp_1" => $data['temp_1'.$j],
                  "temp_2" => $data['temp_2'.$j],
                  "temp_3" => $data['temp_3'.$j],
                  "temp_4" => $data['temp_4'.$j],
                  "temp_5" => $data['temp_5'.$j],
                  "temp_6" => $data['temp_6'.$j],
                  "temp_7" => $data['temp_7'.$j],
                  "extrude_roatation" => $data['extrude_roatation'.$j],
                  "extrusion_load" => $data['extrusion_load'.$j],
                  "pickup_speed" => $data['pickup_speed'],
                  "screw_mesh_1" => $data['screw_mesh_1'.$j],
                  "screw_number_1" => $data['screw_number_1'.$j],
                  "screw_mesh_2" => $data['screw_mesh_2'.$j],
                  "screw_number_2" => $data['screw_number_2'.$j],
                  "screw_mesh_3" => $data['screw_mesh_3'.$j],
                  "screw_number_3" => $data['screw_number_3'.$j],
                  "screw" => $data['screw'.$j],
                  "delete_flag" => 0,
                  'created_at' => date("Y-m-d H:i:s"),
                  "created_staff" => $staff_id
                ];
        
                if($j >= $tuikaseikeiki){

                  $ProductConditonChildren = $this->ProductConditonChildren->patchEntities($this->ProductConditonChildren->newEntity(), $tourokuProductConditonChildren);
                  if ($this->ProductConditonChildren->saveMany($ProductConditonChildren)) {

                    $connection->commit();// コミット5
                    $mes = "以下のように登録されました。";
                    $this->set('mes',$mes);
          
                  } else {
          
                    $this->Flash->error(__('The data could not be saved. Please, try again.'));
                    throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
                    $mes = "※登録されませんでした";
                    $this->set('mes',$mes);
          
                  }

                }

              } else {

                $mes = "※登録されませんでした";
                $this->set('mes',$mes);
                $this->Flash->error(__('The data could not be saved. Please, try again.'));
                throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

              }

            } else {

              $mes = "※登録されませんでした";
              $this->set('mes',$mes);
              $this->Flash->error(__('The data could not be saved. Please, try again.'));
              throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

            }

          }

        } else {

          $mes = "※登録されませんでした";
          $this->set('mes',$mes);
          $this->Flash->error(__('The data could not be saved. Please, try again.'));
          throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

        }

      } catch (Exception $e) {//トランザクション7
      //ロールバック8
        $connection->rollback();//トランザクション9
      }//トランザクション10

    }

}
