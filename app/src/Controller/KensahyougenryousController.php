<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Event\Event;

use App\myClass\classprograms\htmlLogin;//myClassフォルダに配置したクラスを使用
$htmlinputstaffctp = new htmlLogin();
use App\myClass\classprograms\htmlproductcheck;//myClassフォルダに配置したクラスを使用
$htmlproductcheck = new htmlproductcheck();
use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
$htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
use App\myClass\classprograms\htmlkensahyoulogincheck;//myClassフォルダに配置したクラスを使用
$htmlkensahyoulogincheck = new htmlkensahyoulogincheck();

class KensahyougenryousController extends AppController
{

  	public function beforeFilter(Event $event){
  		parent::beforeFilter($event);

  		// 認証なしでアクセスできるアクションの指定
      $this->Auth->allow(["menu"
      ,"kensakupre", "kensakuhyouji"
      ,"kensakugouki"
      , "kensakumenu", "kensakurirekipre"]);
      
/*
      session_start();//全部NG
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');
      $session = $this->request->session();
      $session->read();

      $session = $this->request->getSession();
      $session->destroy();
*/
  	}

      public function initialize()
    {
     parent::initialize();
     $this->Seikeikis = TableRegistry::get('Seikeikis');
     $this->Users = TableRegistry::get('Users');
     $this->Staffs = TableRegistry::get('Staffs');
     $this->Products = TableRegistry::get('Products');
     $this->Customers = TableRegistry::get('Customers');
     $this->Materials = TableRegistry::get('Materials');
     $this->ProductConditionParents = TableRegistry::get('ProductConditionParents');
     $this->ProductMaterialMachines = TableRegistry::get('ProductMaterialMachines');
     $this->ProductMachineMaterials = TableRegistry::get('ProductMachineMaterials');
     $this->ProductConditonChildren = TableRegistry::get('ProductConditonChildren');

     if(!isset($_SESSION)){//フォーム再送信の確認対策
       session_start();
     }
     header('Expires:');
     header('Cache-Control:');
     header('Pragma:');
/*
     echo "<pre>";
     print_r("　");
     echo "</pre>";
*/
    }

    public function menu()
    {
    }

    public function addlogin()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }
    }

    public function addformpre()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
      $mess = "";
      $this->set('mess', $mess);

      $session = $this->request->getSession();
      $datasession = $session->read();
      $Users= $this->Users->find('all')->contain(["Staffs"])->where(['user_code' => $datasession['Auth']['User']['user_code'], 'Users.delete_flag' => 0])->toArray();
      $user_code = $datasession['Auth']['User']['user_code'];
      $this->set('user_code', $user_code);
      $staff_id = $Users[0]["staff_id"];
      $this->set('staff_id', $staff_id);
      $staff_name= $Users[0]["staff"]["name"];
      $this->set('staff_name', $staff_name);

      $customer_check = 0;
      $this->set('customer_check', $customer_check);

      $Customer_name_list = $this->Customers->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrCustomer_name_list = array();
      for($j=0; $j<count($Customer_name_list); $j++){
        array_push($arrCustomer_name_list,$Customer_name_list[$j]["name"]);
      }
      $this->set('arrCustomer_name_list', $arrCustomer_name_list);

     if(isset($data["customer"])){//顧客絞り込みをしたとき

       $Product_name_list = $this->Products->find()
       ->contain(['Customers'])
       ->where(['Customers.name' => $data["customer_name"], 'Products.status_kensahyou' => 0, 'Products.delete_flag' => 0])->toArray();

       if(count($Product_name_list) < 1){//顧客名にミスがある場合

         $mess = "入力された顧客の製品は登録されていません。確認してください。";
         $this->set('mess',$mess);

         $Product_name_list = $this->Products->find()
         ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }else{

        $customer_check = 1;
        $this->set('customer_check', $customer_check);
  
         $arrProduct_names = array();
         for($j=0; $j<count($Product_name_list); $j++){
   //       array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
         $arrProduct_names[$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm"] = $Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm";
        }
         $this->set('arrProduct_names', $arrProduct_names);
  
         $arrProduct_name_list = array();
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }

     }elseif(isset($data["next"])){//「次へ」ボタンを押したとき

       if(strlen($data["product_name"]) > 0){//product_nameの入力がある

        $product_name_length = explode(";",$data["product_name"]);
        $name = $product_name_length[0];
        if(isset($product_name_length[1])){
          $length = str_replace('mm', '', $product_name_length[1]);
          $Products = $this->Products->find()
          ->where(['status_kensahyou' => 0, 'name' => $name, 'length' => $length, 'delete_flag' => 0])->toArray();
         }else{
          $length = "";
          $Products = $this->Products->find()
          ->where(['status_kensahyou' => 0, 'name' => $name, 'delete_flag' => 0])->toArray();
         }

         if(isset($Products[0])){

           $product_code = $Products[0]["product_code"];

           return $this->redirect(['action' => 'addformpregouki',
           's' => ['product_code' => $product_code, 'user_code' => $user_code]]);

         }else{

           $mess = "入力された製品名は登録されていないか、検査表非表示の製品です。確認してください。";
           $this->set('mess',$mess);

           $Product_name_list = $this->Products->find()
           ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();

           $arrProduct_name_list = array();
           for($j=0; $j<count($Product_name_list); $j++){
             array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
           }
           $this->set('arrProduct_name_list', $arrProduct_name_list);

         }

       }else{//product_nameの入力がない

         $mess = "製品名が入力されていません。";
         $this->set('mess',$mess);

         $Product_name_list = $this->Products->find()
         ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }

     }else{//はじめ

       $Product_name_list = $this->Products->find()
       ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();
       $arrProduct_name_list = array();
       for($j=0; $j<count($Product_name_list); $j++){
         array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
       }
       $this->set('arrProduct_name_list', $arrProduct_name_list);

       $Data=$this->request->query('s');
       if(isset($Data["mess"])){
         $mess = $Data["mess"];
         $this->set('mess',$mess);
       }else{
         $mess = "";
         $this->set('mess',$mess);
       }

     }

     echo "<pre>";
     print_r("");
     echo "</pre>";

    }

    public function addformpregouki()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data = $this->request->query('s');
      if(isset($Data["product_code"])){
        $product_code = $Data["product_code"];
        $this->set('product_code', $product_code);
        $user_code = $Data["user_code"];
        $this->set('user_code', $user_code);
      }else{
        $data = $this->request->getData();
    
        $product_code = $data["product_code"];
        $this->set('product_code', $product_code);
        $user_code = $data["user_code"];
        $this->set('user_code', $user_code);
      }

      $data = $this->request->getData();

      $arrGouki = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7
      ];
      $this->set('arrGouki', $arrGouki);
 
      if(isset($data["next"])){//「次へ」ボタンを押したとき

        $product_code = $data["product_code"];
        $user_code = $data["user_code"];
        $machine_num = $data["machine_num"];

        return $this->redirect(['action' => 'addform',
        's' => ['product_code' => $product_code, 'user_code' => $user_code, 'machine_num' => $machine_num]]);

      }
   
    }

    public function addform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data = $this->request->query('s');

      if(isset($Data["product_code"])){

        $product_code = $Data["product_code"];
        $this->set('product_code', $product_code);
        $user_code = $Data["user_code"];
        $this->set('user_code', $user_code);
        $machine_num = $Data["machine_num"];
        $this->set('machine_num', $machine_num);

        $Users= $this->Users->find()->contain(["Staffs"])->where(['user_code' => $user_code, 'Users.delete_flag' => 0])->toArray();
        $staff_id = $Users[0]["staff_id"];
        $this->set('staff_id', $staff_id);
        $staff_name= $Users[0]["staff"]["name"];
        $this->set('staff_name', $staff_name);

      }else{

        $data = $this->request->getData();
        $staff_id = $data["staff_id"];
        $this->set('staff_id', $staff_id);
        $staff_name = $data["staff_name"];
        $this->set('staff_name', $staff_name);
        $user_code = $data["user_code"];
        $this->set('user_code', $user_code);
        $product_code = $data["product_code"];
        $this->set('product_code', $product_code);
        $machine_num = $data["machine_num"];
        $this->set('machine_num', $machine_num);
/*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
  */
      }

      $product_code_ini = substr($product_code, 0, 11);
      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code like' => $product_code_ini.'%', 
      'ProductConditionParents.is_active' => 0,
      'ProductConditionParents.delete_flag' => 0])
      ->order(["machine_num"=>"ASC"])->toArray();

      $arrGouki = array();
      for($k=0; $k<count($ProductConditionParents); $k++){
        $array = array($ProductConditionParents[$k]["machine_num"] => $ProductConditionParents[$k]["machine_num"]);
        $arrGouki = $arrGouki + $array;
      }
      $this->set('arrGouki', $arrGouki);
/*
      echo "<pre>";
      print_r($arrGouki);
      echo "</pre>";
*/
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $this->set('user_code', $user_code);

      $Material_name_list = $this->Materials->find()
      ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();
 //     ->where(['delete_flag' => 0])->toArray();
      $arrMaterial_name_list = array();
      for($j=0; $j<count($Material_name_list); $j++){
        array_push($arrMaterial_name_list,$Material_name_list[$j]["name"]);
      }
      $this->set('arrMaterial_name_list', $arrMaterial_name_list);

      $Seikeikis = $this->Seikeikis->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrSeikeikis = array();
      for($j=0; $j<count($Seikeikis); $j++){
        $arrSeikeikis[$Seikeikis[$j]["name"]] = $Seikeikis[$j]["name"];
      }
      $this->set('arrSeikeikis', $arrSeikeikis);
/*
      echo "<pre>";
      print_r($arrSeikeikis);
      echo "</pre>";
*/
      $mess = "";
      $this->set('mess', $mess);

      if(isset($data["genryoutuika"])){//原料追加ボタン

        if(!isset($data["tuikaseikeiki"])){//成形機の追加前

          $tuikaseikeiki = 1;

        }else{//成形機の追加後

          $tuikaseikeiki = $data["tuikaseikeiki"];

        }
        $this->set('tuikaseikeiki', $tuikaseikeiki);

        $Materials = $this->Materials->find()
        ->where(['name' => $data["material_name".$tuikaseikeiki.$data["tuikagenryou".$tuikaseikeiki]], 'delete_flag' => 0])->toArray();
  
        if(!isset($Materials[0])){//原料が存在しない場合
          ${"tuikagenryou".$tuikaseikeiki} = $data["tuikagenryou".$tuikaseikeiki];

          $mess = "入力された原料名に誤りがあります。";
          $this->set('mess', $mess);

        }else{
          ${"tuikagenryou".$tuikaseikeiki} = $data["tuikagenryou".$tuikaseikeiki] + 1;

        }

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
          if(isset($data["recycled_mixing_ratio".$j])){
            ${"recycled_mixing_ratio".$j} = $data["recycled_mixing_ratio".$j];
            $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});
          }else{
            ${"recycled_mixing_ratio".$j} = "";
            $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});
          }

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            if(isset($data["material_name".$j.$i])){

              $Materials = $this->Materials->find()
              ->where(['name' => $data["material_name".$j.$i], 'delete_flag' => 0])->toArray();
      
              if(isset($Materials[0])){//原料が存在する場合

                ${"material_name".$j.$i} = $data["material_name".$j.$i];
                $this->set('material_name'.$j.$i,${"material_name".$j.$i});
                ${"check_material_name".$j.$i} = 1;
                $this->set('check_material_name'.$j.$i,${"check_material_name".$j.$i});

                }else{

                  ${"material_name".$j.$i} = "";
                  $this->set('material_name'.$j.$i,${"material_name".$j.$i});
                  ${"check_material_name".$j.$i} = 0;
                  $this->set('check_material_name'.$j.$i,${"check_material_name".$j.$i});
                  
                  }

            }else{
              ${"material_name".$j.$i} = "";
              $this->set('material_name'.$j.$i,${"material_name".$j.$i});
              ${"check_material_name".$j.$i} = 0;
              $this->set('check_material_name'.$j.$i,${"check_material_name".$j.$i});
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


          }

        }

      }elseif(isset($data["seikeikituika"])){//成形機追加ボタン

        $tuikaseikeiki = $data["tuikaseikeiki"];

        $Materials = $this->Materials->find()
        ->where(['name' => $data["material_name".$tuikaseikeiki.$data["tuikagenryou".$tuikaseikeiki]], 'delete_flag' => 0])->toArray();
  
        if(!isset($Materials[0])){//原料が存在しない場合
          $tuikaseikeiki = $data["tuikaseikeiki"];

          $mess = "入力された原料名に誤りがあります。";
          $this->set('mess', $mess);

        }else{
          $tuikaseikeiki = $data["tuikaseikeiki"] + 1;
        }
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
          if(isset($data["recycled_mixing_ratio".$j])){
            ${"recycled_mixing_ratio".$j} = $data["recycled_mixing_ratio".$j];
            $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});
          }else{
            ${"recycled_mixing_ratio".$j} = "";
            $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});
          }

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            if(isset($data["material_name".$j.$i])){

              $Materials = $this->Materials->find()
              ->where(['name' => $data["material_name".$j.$i], 'delete_flag' => 0])->toArray();
      
              if(isset($Materials[0])){//原料が存在する場合

                ${"material_name".$j.$i} = $data["material_name".$j.$i];
                $this->set('material_name'.$j.$i,${"material_name".$j.$i});
                ${"check_material_name".$j.$i} = 1;
                $this->set('check_material_name'.$j.$i,${"check_material_name".$j.$i});

                }else{

                  ${"material_name".$j.$i} = "";
                  $this->set('material_name'.$j.$i,${"material_name".$j.$i});
                  ${"check_material_name".$j.$i} = 0;
                  $this->set('check_material_name'.$j.$i,${"check_material_name".$j.$i});
                  
                  }

            }else{

              ${"material_name".$j.$i} = "";
              $this->set('material_name'.$j.$i,${"material_name".$j.$i});
              ${"check_material_name".$j.$i} = 0;
              $this->set('check_material_name'.$j.$i,${"check_material_name".$j.$i});

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


          }

        }

      }elseif(isset($data["kakuninn"])){//確認ボタン

        for($j=1; $j<=$data['tuikaseikeiki']; $j++){

          $this->set('tuikaseikeiki', $data['tuikaseikeiki']);

          if(strlen($data['tuikagenryou'.$j]) > 0){
            ${"tuikagenryou".$j} = $data['tuikagenryou'.$j];
          }else{
            ${"tuikagenryou".$j} = 1;
          }
          $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

          if(strlen($data['cylinder_name'.$j]) > 0){
            ${"cylinder_name".$j} = $data['cylinder_name'.$j];
            $this->set('cylinder_name'.$j,${"cylinder_name".$j});
          }else{
            ${"cylinder_name".$j} = "";
            $this->set('cylinder_name'.$j,${"cylinder_name".$j});
            $mess = "※入力漏れがあります。";
          }

          if(strlen($data["recycled_mixing_ratio".$j]) > 0){
            ${"recycled_mixing_ratio".$j} = $data["recycled_mixing_ratio".$j];
            $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});
          }else{
            ${"recycled_mixing_ratio".$j} = "";
            $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});
            $mess = "※入力漏れがあります。";
          }

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            if(strlen($data["material_name".$j.$i]) > 0){

              $Materials = $this->Materials->find()
              ->where(['name' => $data["material_name".$j.$i], 'delete_flag' => 0])->toArray();

              if(!isset($Materials[0])){//原料が存在しない場合
                $tuikaseikeiki = $data["tuikaseikeiki"];
      
                $mess = "入力された原料名に誤りがあります。";
                $this->set('mess', $mess);
      
                ${"material_name".$j.$i} = "";
                $this->set('material_name'.$j.$i,${"material_name".$j.$i});
                ${"check_material_name".$j.$i} = 0;
                $this->set('check_material_name'.$j.$i,${"check_material_name".$j.$i});
    
              }else{

                ${"material_name".$j.$i} = $data["material_name".$j.$i];
                $this->set('material_name'.$j.$i,${"material_name".$j.$i});
                ${"check_material_name".$j.$i} = 1;
                $this->set('check_material_name'.$j.$i,${"check_material_name".$j.$i});
  
              }
      
            }else{
              ${"material_name".$j.$i} = "";
              $this->set('material_name'.$j.$i,${"material_name".$j.$i});
              $mess = "※入力漏れがあります。";
              ${"check_material_name".$j.$i} = 0;
              $this->set('check_material_name'.$j.$i,${"check_material_name".$j.$i});
            }

            if(strlen($data["mixing_ratio".$j.$i]) > 0){
              ${"mixing_ratio".$j.$i} = $data["mixing_ratio".$j.$i];
              $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
            }else{
              ${"mixing_ratio".$j.$i} = "";
              $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
              $mess = "※入力漏れがあります。";
            }

            if(strlen($data["dry_temp".$j.$i]) > 0){
              ${"dry_temp".$j.$i} = $data["dry_temp".$j.$i];
              $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
            }else{
              ${"dry_temp".$j.$i} = "";
              $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
              $mess = "※入力漏れがあります。";
            }

            if(strlen($data["dry_hour".$j.$i]) > 0){
              ${"dry_hour".$j.$i} = $data["dry_hour".$j.$i];
              $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
            }else{
              ${"dry_hour".$j.$i} = "";
              $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
              $mess = "※入力漏れがあります。";
            }

          }

        }

        $this->set('mess', $mess);
/*
        echo "<pre>";
        print_r($mess);
        echo "</pre>";
*/
        if(strlen($mess) < 1){
          if(!isset($_SESSION)){
            session_start();
          }

          $_SESSION['kensahyougenryoudata'] = array();
          $_SESSION['kensahyougenryoudata'] = $data;

          return $this->redirect(['action' => 'addtempform',
          's' => ['data' => 'addtempform']]);
        }

      }else{//最初にこの画面に来た時

        $i = $j = 1;
        $tuikagenryou = 1;
        $this->set('tuikagenryou'.$i, $tuikagenryou);
        $tuikaseikeiki = 1;
        $this->set('tuikaseikeiki', $tuikaseikeiki);
        ${"cylinder_name".$j} = "";
        $this->set('cylinder_name'.$j,${"cylinder_name".$j});
        ${"material_name".$j.$i} = "";
        $this->set('material_name'.$j.$i,${"material_name".$j.$i});
        ${"check_material_name".$j.$i} = 0;
        $this->set('check_material_name'.$j.$i,${"check_material_name".$j.$i});
        ${"mixing_ratio".$j.$i} = "";
        $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
        ${"dry_temp".$j.$i} = "";
        $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
        ${"dry_hour".$j.$i} = "";
        $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
        ${"recycled_mixing_ratio".$j} = "";
        $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});

      }

      echo "<pre>";
      print_r("");
      echo "</pre>";
 
    }

    public function addtempform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $arrayKensahyougenryoudatas = $_SESSION['kensahyougenryoudata'];
   //   $_SESSION['kensahyougenryoudata'] = array();

      $data = $arrayKensahyougenryoudatas;
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

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $tuikaseikeiki = $data["tuikaseikeiki"];
      $this->set('tuikaseikeiki', $tuikaseikeiki);

      for($j=1; $j<=$tuikaseikeiki; $j++){

        ${"tuikagenryou".$j} = $data["tuikagenryou".$j];
        $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

        if(isset($data['cylinder_name'.$j])){
          ${"cylinder_name".$j} = $data['cylinder_name'.$j];
          $this->set('cylinder_name'.$j,${"cylinder_name".$j});
        }else{
          ${"cylinder_name".$j} = "";
          $this->set('cylinder_name'.$j,${"cylinder_name".$j});
        }

        if(isset($data["recycled_mixing_ratio".$j])){
          ${"recycled_mixing_ratio".$j} = $data["recycled_mixing_ratio".$j];
          $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});
        }else{
          ${"recycled_mixing_ratio".$j} = "";
          $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});
        }

        for($i=1; $i<=${"tuikagenryou".$j}; $i++){

          if(isset($data["material_name".$j.$i])){
            ${"material_name".$j.$i} = $data["material_name".$j.$i];
            $this->set('material_name'.$j.$i,${"material_name".$j.$i});

            $Materials = $this->Materials->find()
            ->where(['name' => $data["material_name".$j.$i]])->toArray();

            ${"material_id".$j.$i} = $Materials[0]["id"];
            $this->set('material_id'.$j.$i,${"material_id".$j.$i});

          }else{
            ${"material_id".$j.$i} = "";
            $this->set('material_id'.$j.$i,${"material_id".$j.$i});
            ${"material_name".$j.$i} = "";
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

        }

      }

      $countseikeiki = $data["tuikaseikeiki"];
      $this->set('countseikeiki', $countseikeiki);

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

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

    }

    public function addtempcomfirm()
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

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $tuikaseikeiki = $data["tuikaseikeiki"];
      $this->set('tuikaseikeiki', $tuikaseikeiki);

      for($j=1; $j<=$tuikaseikeiki; $j++){

        ${"tuikagenryou".$j} = $data["tuikagenryou".$j];
        $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

        if(isset($data['cylinder_name'.$j])){
          ${"cylinder_name".$j} = $data['cylinder_name'.$j];
          $this->set('cylinder_name'.$j,${"cylinder_name".$j});
        }else{
          ${"cylinder_name".$j} = "";
          $this->set('cylinder_name'.$j,${"cylinder_name".$j});
        }

        if(isset($data["recycled_mixing_ratio".$j])){
          ${"recycled_mixing_ratio".$j} = $data["recycled_mixing_ratio".$j];
          $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});
        }else{
          ${"recycled_mixing_ratio".$j} = "";
          $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});
        }

        for($i=1; $i<=${"tuikagenryou".$j}; $i++){

          if(isset($data["material_name".$j.$i])){
            ${"material_name".$j.$i} = $data["material_name".$j.$i];
            $this->set('material_name'.$j.$i,${"material_name".$j.$i});

            $Materials = $this->Materials->find()
            ->where(['name' => $data["material_name".$j.$i]])->toArray();

            ${"material_id".$j.$i} = $Materials[0]["id"];
            $this->set('material_id'.$j.$i,${"material_id".$j.$i});

          }else{
            ${"material_id".$j.$i} = "";
            $this->set('material_id'.$j.$i,${"material_id".$j.$i});
            ${"material_name".$j.$i} = "";
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

    }

    public function addalldo()
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

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $tuikaseikeiki = $data["tuikaseikeiki"];
      $this->set('tuikaseikeiki', $tuikaseikeiki);

      for($j=1; $j<=$tuikaseikeiki; $j++){

        ${"tuikagenryou".$j} = $data["tuikagenryou".$j];
        $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

        if(isset($data['cylinder_name'.$j])){
          ${"cylinder_name".$j} = $data['cylinder_name'.$j];
          $this->set('cylinder_name'.$j,${"cylinder_name".$j});
        }else{
          ${"cylinder_name".$j} = "";
          $this->set('cylinder_name'.$j,${"cylinder_name".$j});
        }

        if(isset($data["recycled_mixing_ratio".$j])){
          ${"recycled_mixing_ratio".$j} = $data["recycled_mixing_ratio".$j];
          $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});
        }else{
          ${"recycled_mixing_ratio".$j} = "";
          $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});
        }

        for($i=1; $i<=${"tuikagenryou".$j}; $i++){

          if(isset($data["material_name".$j.$i])){
            ${"material_name".$j.$i} = $data["material_name".$j.$i];
            $this->set('material_name'.$j.$i,${"material_name".$j.$i});

            $Materials = $this->Materials->find()
            ->where(['name' => $data["material_name".$j.$i]])->toArray();

            ${"material_id".$j.$i} = $Materials[0]["id"];
            $this->set('material_id'.$j.$i,${"material_id".$j.$i});

          }else{
            ${"material_id".$j.$i} = "";
            $this->set('material_id'.$j.$i,${"material_id".$j.$i});
            ${"material_name".$j.$i} = "";
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
/*
            echo "<pre>";
            print_r("ProductMaterialMachines");
            print_r($tourokuProductMaterialMachine);
            echo "</pre>";
  */    
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
                  "recycled_mixing_ratio" => $data["recycled_mixing_ratio".$j],
                  "delete_flag" => 0,
                  'created_at' => date("Y-m-d H:i:s"),
                  "created_staff" => $staff_id
                ];

              }
/*
              echo "<pre>";
              print_r("ProductMachineMaterial");
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
/*
                  echo "<pre>";
                  print_r("ProductConditonChildren");
                  print_r($tourokuProductConditonChildren);
                  echo "</pre>";
  */  
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

    public function kensakupre()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }

      $customer_check = 0;
      $this->set('customer_check', $customer_check);

      $Customer_name_list = $this->Customers->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrCustomer_name_list = array();
      for($j=0; $j<count($Customer_name_list); $j++){
        array_push($arrCustomer_name_list,$Customer_name_list[$j]["name"]);
      }
      $this->set('arrCustomer_name_list', $arrCustomer_name_list);

     if(isset($data["customer"])){//顧客絞り込みをしたとき

       $Product_name_list = $this->Products->find()
       ->contain(['Customers'])
       ->where(['Customers.name' => $data["customer_name"], 'Products.status_kensahyou' => 0, 'Products.delete_flag' => 0])->toArray();

       if(count($Product_name_list) < 1){//顧客名にミスがある場合

         $mess = "入力された顧客の製品は登録されていません。確認してください。";
         $this->set('mess',$mess);

         $Product_name_list = $this->Products->find()
         ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }else{

        $customer_check = 1;
        $this->set('customer_check', $customer_check);
  
         $arrProduct_names = array();
         for($j=0; $j<count($Product_name_list); $j++){
   //       array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
         $arrProduct_names[$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm"] = $Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm";
        }
         $this->set('arrProduct_names', $arrProduct_names);

         $arrProduct_name_list = array();
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }

     }elseif(isset($data["next"])){//「次へ」ボタンを押したとき

       if(strlen($data["product_name"]) > 0){//product_nameの入力がある

        $product_name_length = explode(";",$data["product_name"]);
        $name = $product_name_length[0];
        $length = str_replace('mm', '', $product_name_length[1]);
  
         $Products = $this->Products->find()
         ->where(['name' => $name, 'length' => $length, 'delete_flag' => 0])->toArray();

         if(isset($Products[0])){

           $product_code = $Products[0]["product_code"];

           return $this->redirect(['action' => 'kensakugouki',
           's' => ['product_code' => $product_code]]);

         }else{

           $mess = "入力された製品名は登録されていません。確認してください。";
           $this->set('mess',$mess);

           $Product_name_list = $this->Products->find()
           ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();

           $arrProduct_name_list = array();
           for($j=0; $j<count($Product_name_list); $j++){
             array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
           }
           $this->set('arrProduct_name_list', $arrProduct_name_list);

         }

       }else{//product_nameの入力がない

         $mess = "製品名が入力されていません。";
         $this->set('mess',$mess);

         $Product_name_list = $this->Products->find()
         ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }

     }else{//はじめ

       $Product_name_list = $this->Products->find()
       ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();
       $arrProduct_name_list = array();
       for($j=0; $j<count($Product_name_list); $j++){
         array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
       }
       $this->set('arrProduct_name_list', $arrProduct_name_list);

     }

     echo "<pre>";
     print_r("");
     echo "</pre>";

    }

    public function kensakugouki()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data = $this->request->query('s');
      $product_code = $Data["product_code"];
      $this->set('product_code', $product_code);

      $data = $this->request->getData();

      $product_code_ini = substr($product_code, 0, 11);
      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code like' => $product_code_ini.'%', 
      'ProductConditionParents.is_active' => 0,
      'ProductConditionParents.delete_flag' => 0])
      ->order(["machine_num"=>"ASC"])->toArray();
/*
      echo "<pre>";
      print_r("data");
      print_r($data);
      echo "</pre>";
*/
      $arrGouki = array();
      for($k=0; $k<count($ProductConditionParents); $k++){
        $array = array($ProductConditionParents[$k]["machine_num"] => $ProductConditionParents[$k]["machine_num"]);
        $arrGouki = $arrGouki + $array;
      }
      $this->set('arrGouki', $arrGouki);

      if(isset($data["next"])){//「次へ」ボタンを押したとき

        $product_code = $data["product_code"];
        $machine_num = $data["machine_num"];

        return $this->redirect(['action' => 'kensakuhyouji',
        's' => ['product_code' => $product_code, 'machine_num' => $machine_num]]);

      }
   
    }

    public function kensakuhyouji()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data = $this->request->query('s');
      $product_code = $Data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $Data["machine_num"];
      $this->set('machine_num', $machine_num);

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['machine_num' => $machine_num, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

      }

      if(isset($ProductConditionParents[0])){

        $version = $ProductConditionParents[0]["version"];
        $machine_num = $ProductConditionParents[0]["machine_num"];
        $this->set('machine_num', $machine_num);

      }else{

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "「".$Products[0]["name"]."」は原料登録がされていません。"]]);

      }

      $ProductMachineMaterials = $this->ProductMachineMaterials->find()
      ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
      ->where(['machine_num' => $machine_num, 'version' => $version, 'product_code' => $product_code
      , 'ProductConditionParents.delete_flag' => 0, 'ProductMachineMaterials.delete_flag' => 0])
      ->order(["ProductMaterialMachines.id"=>"ASC"])->toArray();

      if(!isset($ProductMachineMaterials[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['machine_num' => $machine_num, 'version' => $version, 'Products.product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->order(["ProductMaterialMachines.id"=>"ASC"])->toArray();
  
      }

      if($ProductMachineMaterials[0]){

        $ProductMaterialMachines = $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['machine_num' => $machine_num, 'version' => $version, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0, 'ProductMaterialMachines.delete_flag' => 0])
        ->order(["cylinder_number"=>"ASC"])->toArray();

        if(!isset($ProductMaterialMachines[0])){//長さ違いのデータがあればそれを持ってくる

          $product_code_ini = substr($product_code, 0, 11);
          $ProductMaterialMachines= $this->ProductMaterialMachines->find()
          ->contain(['ProductConditionParents' => ["Products"]])
          ->where(['Products.product_code like' => $product_code_ini.'%',
          'ProductConditionParents.delete_flag' => 0,
          'ProductMaterialMachines.delete_flag' => 0,
          'ProductConditionParents.machine_num' => $machine_num,
          'ProductConditionParents.version' => $version])
          ->order(["cylinder_number"=>"ASC"])->toArray();
    
        }
          
        $tuikaseikeiki = count($ProductMaterialMachines);
        $this->set('tuikaseikeiki', $tuikaseikeiki);
        $countseikeiki = count($ProductMaterialMachines);
        $this->set('countseikeiki', $countseikeiki);

        for($j=1; $j<=$tuikaseikeiki; $j++){

          ${"product_material_machine_id".$j} = $ProductMaterialMachines[$j - 1]["id"];
          $this->set('product_material_machine_id'.$j, ${"product_material_machine_id".$j});
          ${"cylinder_name".$j} = $ProductMaterialMachines[$j - 1]["cylinder_name"];
          $this->set('cylinder_name'.$j,${"cylinder_name".$j});

          $ProductConditonChildren = $this->ProductConditonChildren->find()
          ->where(['product_material_machine_id' => ${"product_material_machine_id".$j}, 'cylinder_name' => ${"cylinder_name".$j}, 'delete_flag' => 0])
          ->toArray();
    
          if(isset($ProductConditonChildren[0])){

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

          $ProductMachineMaterials = $this->ProductMachineMaterials->find()
          ->where(['product_material_machine_id' => $ProductMaterialMachines[$j - 1]["id"], 'delete_flag' => 0])
          ->order(["material_number"=>"DESC"])->toArray();

          ${"tuikagenryou".$j} = count($ProductMachineMaterials);
          $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            $Materials = $this->Materials->find()
            ->where(['id' => $ProductMachineMaterials[$i - 1]["material_id"]])->toArray();

            ${"material_hyouji".$j.$i} = $Materials[0]["name"];
            $this->set('material_hyouji'.$j.$i,${"material_hyouji".$j.$i});

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

      }else{

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "「".$Products[0]["name"]."」は原料登録がされていません。"]]);

      }

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      echo "<pre>";
      print_r("");
      echo "</pre>";
 
    }

    public function editlogin()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }

      echo "<pre>";
      print_r("");
      echo "</pre>";

    }

    public function editform()
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
      ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();
 //     ->where(['delete_flag' => 0])->toArray();
      $arrMaterial_name_list = array();
      for($j=0; $j<count($Material_name_list); $j++){
        array_push($arrMaterial_name_list,$Material_name_list[$j]["name"]);
      }
      $this->set('arrMaterial_name_list', $arrMaterial_name_list);

      $Materials = $this->Materials->find()
      ->where(['delete_flag' => 0])->order(["id"=>"ASC"])->toArray();

      $Seikeikis = $this->Seikeikis->find()
      ->where(['delete_flag' => 0])->toArray();
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

          $_SESSION['updatekensahyougenryoudata'] = array();
          $_SESSION['updatekensahyougenryoudata'] = $data + array("delete_flag" => 0);

          return $this->redirect(['action' => 'edittempform']);

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

      }elseif(isset($data["sakujo"])){//削除ボタン

        if(!isset($_SESSION)){
          session_start();
        }

        $_SESSION['updatekensahyougenryoudata'] = array();
        $_SESSION['updatekensahyougenryoudata'] = $data + array("delete_flag" => 1);

        return $this->redirect(['action' => 'editdeleteconfirm']);

      }elseif(isset($data["saisyo"])){//最初にこの画面に来た時

          $htmlkensahyoulogincheck = new htmlkensahyoulogincheck();//クラスを使用
          $logincheck = $htmlkensahyoulogincheck->kensahyoulogincheckprogram($user_code);//クラスを使用
    
          if($logincheck === 1){
  
          return $this->redirect(['action' => 'kensakupre',
          's' => ['mess' => "データ登録の権限がありません。"]]);
  
          }

        $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['machine_num' => $machine_num, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

        if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

          $product_code_ini = substr($product_code, 0, 11);
          $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
          ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
          ->order(["version"=>"DESC"])->toArray();
  
        }
  
        $version = $ProductConditionParents[0]["version"];
        $machine_num = $ProductConditionParents[0]["machine_num"];
        $this->set('machine_num', $machine_num);
  
        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['machine_num' => $machine_num, 'version' => $version, 'product_code' => $product_code, 'ProductMachineMaterials.delete_flag' => 0, 'ProductConditionParents.delete_flag' => 0])
        ->toArray();

        $ProductMaterialMachines = $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['machine_num' => $machine_num, 'version' => $version, 'product_code' => $product_code, 'ProductMaterialMachines.delete_flag' => 0, 'ProductConditionParents.delete_flag' => 0])
        ->order(["cylinder_number"=>"ASC"])->toArray();

        if(!isset($ProductMaterialMachines[0])){//長さ違いのデータがあればそれを持ってくる

          $product_code_ini = substr($product_code, 0, 11);
          $ProductMaterialMachines= $this->ProductMaterialMachines->find()
          ->contain(['ProductConditionParents' => ["Products"]])
          ->where(['Products.product_code like' => $product_code_ini.'%',
          'ProductConditionParents.delete_flag' => 0,
          'ProductMaterialMachines.delete_flag' => 0,
          'ProductConditionParents.machine_num' => $machine_num,
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
/*
            ${"material_hyouji".$j.$i} = $Materials[0]["material_code"].":".$Materials[0]["maker"].":".$Materials[0]["material_code"];
            $this->set('material_hyouji'.$j.$i,${"material_hyouji".$j.$i});
*/
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

      $arrGouki = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7
      ];
      $this->set('arrGouki', $arrGouki);

      echo "<pre>";
      print_r("");
      echo "</pre>";

    }

    public function edittempform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $arrayKensahyougenryoudatas = $_SESSION['updatekensahyougenryoudata'];
//      $_SESSION['updatekensahyougenryoudata'] = array();

      $data = $arrayKensahyougenryoudatas;
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
/*
          echo "<pre>";
          print_r("seikeiki".$j);
          echo "</pre>";
*/
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
      ->where(['machine_num' => $machine_num, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

      }

      if(isset($ProductConditionParents[0])){

        $version = $ProductConditionParents[0]["version"];

        $ProductMaterialMachines= $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['Products.product_code' => $product_code,
        'ProductConditionParents.delete_flag' => 0,
        'ProductMaterialMachines.delete_flag' => 0,
        'ProductConditionParents.machine_num' => $machine_num,
        'ProductConditionParents.version' => $version])
        ->order(["cylinder_number"=>"ASC"])->toArray();

        if(!isset($ProductMaterialMachines[0])){//長さ違いのデータがあればそれを持ってくる

          $product_code_ini = substr($product_code, 0, 11);
          $ProductMaterialMachines= $this->ProductMaterialMachines->find()
          ->contain(['ProductConditionParents' => ["Products"]])
          ->where(['Products.product_code like' => $product_code_ini.'%',
          'ProductConditionParents.delete_flag' => 0,
          'ProductMaterialMachines.delete_flag' => 0,
          'ProductConditionParents.machine_num' => $machine_num,
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

      }else{

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は成形温度登録がされていません。"]]);

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

    public function edittempconfirm()
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
      $delete_flag = 0;
      $this->set('delete_flag', $delete_flag);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);
      $machine_num_moto = $data["machine_num_moto"];
      $this->set('machine_num_moto', $machine_num_moto);

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

      $mes = "下記のように更新します。よろしければ決定ボタンを押してください。";
      $this->set('mes', $mes);

    }

    public function editalldo()
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

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $tuikaseikeiki = $data["tuikaseikeiki"];
      $this->set('tuikaseikeiki', $tuikaseikeiki);

      for($j=1; $j<=$tuikaseikeiki; $j++){

        ${"tuikagenryou".$j} = $data["tuikagenryou".$j];
        $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

        if(isset($data['cylinder_name'.$j])){
          ${"cylinder_name".$j} = $data['cylinder_name'.$j];
          $this->set('cylinder_name'.$j,${"cylinder_name".$j});
        }else{
          ${"cylinder_name".$j} = "";
          $this->set('cylinder_name'.$j,${"cylinder_name".$j});
        }

        for($i=1; $i<=${"tuikagenryou".$j}; $i++){

          if(isset($data["material_id".$j.$i])){
            $Materials = $this->Materials->find()
            ->where(['id' => $data["material_id".$j.$i]])->toArray();

            ${"material_name".$j.$i} = $Materials[0]["name"];
            $this->set('material_name'.$j.$i,${"material_name".$j.$i});
            ${"material_id".$j.$i} = $data["material_id".$j.$i];
            $this->set('material_id'.$j.$i,${"material_id".$j.$i});

          }else{
            ${"material_name".$j.$i} = "";
            $this->set('material_name'.$j.$i,${"material_name".$j.$i});
            ${"material_id".$j.$i} = "";
            $this->set('material_id'.$j.$i,${"material_id".$j.$i});
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

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['machine_num' => $machine_num_moto, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['machine_num' => $machine_num_moto, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

      }

      $product_condition_parent_id = $ProductConditionParents[0]["id"];

      $ProductMachineMaterials = $this->ProductMachineMaterials->find()
      ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
      ->where(['product_condition_parent_id' => $product_condition_parent_id, 'machine_num' => $machine_num_moto, 'product_code' => $product_code,
       'ProductMachineMaterials.delete_flag' => 0, 'ProductConditionParents.delete_flag' => 0])
      ->toArray();

      if(!isset($ProductMachineMaterials[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['product_condition_parent_id' => $product_condition_parent_id, 'product_code like' => $product_code_ini.'%',
         'ProductMachineMaterials.delete_flag' => 0, 'ProductConditionParents.delete_flag' => 0])
        ->toArray();
  
      }

      $ProductMaterialMachines = $this->ProductMaterialMachines->find()
      ->contain(['ProductConditionParents' => ["Products"]])
      ->where(['product_condition_parent_id' => $product_condition_parent_id, 'product_code' => $product_code,
       'ProductMaterialMachines.delete_flag' => 0, 'ProductConditionParents.delete_flag' => 0])
      ->order(["cylinder_number"=>"ASC"])->toArray();

      if(!isset($ProductMaterialMachines[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductMaterialMachines = $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['product_condition_parent_id' => $product_condition_parent_id, 'product_code like' => $product_code_ini.'%',
         'ProductMaterialMachines.delete_flag' => 0, 'ProductConditionParents.delete_flag' => 0])
        ->order(["cylinder_number"=>"ASC"])->toArray();
    
      }

        $ProductMachineMaterials = $this->ProductMachineMaterials->patchEntities($this->ProductMachineMaterials->newEntity(), $data);
        $connection = ConnectionManager::get('default');//トランザクション1
        // トランザクション開始2
        $connection->begin();//トランザクション3
        try {//トランザクション4

          if($machine_num != $machine_num_moto){//号機の変更がある場合

            $ProductConditionParentmoto= $this->ProductConditionParents->find()
            ->where(['id' => $product_condition_parent_id])->toArray();
    
            $arrProductConditionParents_moto = array();
            $arrProductConditionParents_moto = [
              'product_id' => $ProductConditionParentmoto[0]["product_id"],
              'machine_num' => $ProductConditionParentmoto[0]["machine_num"],
              'product_condition_code' => $ProductConditionParentmoto[0]["product_condition_code"],
              'version' => $ProductConditionParentmoto[0]["version"],
              'start_datetime' => $ProductConditionParentmoto[0]["start_datetime"]->format("Y-m-d H:i:s"),
              'finish_datetime' => date("Y-m-d H:i:s"),
              'is_active' => 1,
              'delete_flag' => 1,
              'created_at' => $ProductConditionParentmoto[0]["created_at"]->format("Y-m-d H:i:s"),
              'created_staff' => $ProductConditionParentmoto[0]["created_staff"],
              'updated_at' => date("Y-m-d H:i:s"),
              'updated_staff' => $staff_id
            ];
      
            $ProductConditionParents = $this->ProductConditionParents->patchEntity($this->ProductConditionParents->newEntity(), $arrProductConditionParents_moto);
            $this->ProductConditionParents->save($ProductConditionParents);
 
            $this->ProductConditionParents->updateAll(
              [ 
               'machine_num' => $machine_num,
               'updated_at' => date('Y-m-d H:i:s'),
               'updated_staff' => $staff_id],
              ['id'  => $product_condition_parent_id]);
              
          }

          //元データを削除
          for($j=1; $j<=count($ProductMaterialMachines); $j++){

              if ($this->ProductMaterialMachines->updateAll(
                [ 'delete_flag' => 1,
                  'updated_at' => date('Y-m-d H:i:s'),
                  'updated_staff' => $staff_id],
                ['id'  => $ProductMaterialMachines[$j - 1]["id"]])
              ) {

                $ProductMachineMaterials = $this->ProductMachineMaterials->find()
                ->where(['product_material_machine_id' => $ProductMaterialMachines[$j - 1]["id"], 'delete_flag' => 0])
                ->order(["material_number"=>"DESC"])->toArray();

                for($i=1; $i<=count($ProductMachineMaterials); $i++){

                    if ($this->ProductMachineMaterials->updateAll(
                      [ 'delete_flag' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_staff' => $staff_id],
                      ['id'  => $ProductMachineMaterials[$i - 1]["id"]])
                    ) {

                    } else {

                      $mes = "※更新されませんでした";
                      $this->set('mes',$mes);
                      $this->Flash->error(__('The data could not be saved. Please, try again.'));
                      throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

                    }

                  }

              } else {

                $mes = "※更新されませんでした";
                $this->set('mes',$mes);
                $this->Flash->error(__('The data could not be saved. Please, try again.'));
                throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

              }

            }

            //データ新規登録
          for($j=1; $j<=$tuikaseikeiki; $j++){

            $updateProductMaterialMachine = array();
            $updateProductMaterialMachine = [
              "product_condition_parent_id" => $product_condition_parent_id,
              "cylinder_number" => $j,
              "cylinder_name" => $data['cylinder_name'.$j],
              "delete_flag" => 0,
              'created_at' => date("Y-m-d H:i:s"),
              "created_staff" => $staff_id
            ];

            $ProductMaterialMachines = $this->ProductMaterialMachines
            ->patchEntity($this->ProductMaterialMachines->newEntity(), $updateProductMaterialMachine);
            $this->ProductMaterialMachines->save($ProductMaterialMachines);
            if ($this->ProductMaterialMachines->saveMany($ProductMaterialMachines)) {

              $ProductMaterialMachines = $this->ProductMaterialMachines->find()
              ->where(['product_condition_parent_id' => $product_condition_parent_id, 'cylinder_number' => $j, 'delete_flag' => 0])->toArray();

              $updateProductMachineMaterial = array();
              ${"tuikagenryou".$j} = $data["tuikagenryou".$j];

              for($i=1; $i<=${"tuikagenryou".$j}; $i++){

                $updateProductMachineMaterial[] = [
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

              $ProductMachineMaterials = $this->ProductMachineMaterials
              ->patchEntities($this->ProductMachineMaterials->newEntity(), $updateProductMachineMaterial);
              if ($this->ProductMachineMaterials->saveMany($ProductMachineMaterials)) {

                if($j >= $tuikaseikeiki){

                  //ここから温度条件
                  $product_code_ini = substr($product_code, 0, 11);
                  $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
                  ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
                  ->order(["version"=>"DESC"])->toArray();
                  $version = $ProductConditionParents[0]["version"];
          
                  $product_code_ini = substr($product_code, 0, 11);
                  $ProductMaterialMachines= $this->ProductMaterialMachines->find()
                  ->contain(['ProductConditionParents' => ["Products"]])
                  ->where(['Products.product_code like' => $product_code_ini.'%',
                  'ProductConditionParents.delete_flag' => 0,
                  'ProductMaterialMachines.delete_flag' => 0,
                  'ProductConditionParents.machine_num' => $machine_num,
                  'ProductConditionParents.version' => $version])
                  ->order(["cylinder_number"=>"ASC"])->toArray();
                
                  $countProductMaterialMachines = count($ProductMaterialMachines);

                    $ProductConditonChildren = $this->ProductConditonChildren->find()
                    ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
                    ->where(['Products.product_code like' => $product_code_ini.'%'
                    , 'ProductConditionParents.machine_num' => $machine_num
                    , 'ProductConditonChildren.delete_flag' => 0])
                    ->toArray();
          
                    for($k=0; $k<count($ProductConditonChildren); $k++){

                    $this->ProductConditonChildren->updateAll(//もともとあったデータは削除
                      [ 'delete_flag' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_staff' => $staff_id],
                      ['id'  => $ProductConditonChildren[$k]["id"]]);

                  }

                  $tourokuProductConditonChildren = array();
            
                  for($k=0; $k<$countProductMaterialMachines; $k++){
            
                    $j = $k + 1;
                    $updateProductConditonChildren[] = [
                      "product_material_machine_id" => $ProductMaterialMachines[$k]["id"],
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
            
                  }
                              
                  $ProductConditonChildren = $this->ProductConditonChildren->patchEntities($this->ProductConditonChildren->newEntity(), $updateProductConditonChildren);
                  if ($this->ProductConditonChildren->saveMany($ProductConditonChildren)) {
        
                    $connection->commit();// コミット5
                    $mes = "以下のように更新されました。";
                    $this->set('mes',$mes);
  
                  } else {
        
                    $this->Flash->error(__('The data could not be saved. Please, try again.'));
                    throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
                    $mes = "※更新されませんでした";
                    $this->set('mes',$mes);
        
                  }
        
                  $connection->commit();// コミット5
                  $mes = "以下のように更新されました。";
                  $this->set('mes',$mes);

                }

              }else{

                $mes = "※更新されませんでした";
                $this->set('mes',$mes);
                $this->Flash->error(__('The data could not be saved. Please, try again.'));
                throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

              }

            }else{

              $mes = "※更新されませんでした";
              $this->set('mes',$mes);
              $this->Flash->error(__('The data could not be saved. Please, try again.'));
              throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

            }

          }

        } catch (Exception $e) {//トランザクション7
        //ロールバック8
          $connection->rollback();//トランザクション9
        }//トランザクション10

    }

    public function editdeleteconfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $arrayKensahyougenryoudatas = $_SESSION['updatekensahyougenryoudata'];
//      $_SESSION['updatekensahyougenryoudata'] = array();

      $data = $arrayKensahyougenryoudatas;
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['machine_num' => $machine_num, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

      }

      if(isset($ProductConditionParents[0])){

        $version = $ProductConditionParents[0]["version"];
        $machine_num = $ProductConditionParents[0]["machine_num"];
        $this->set('machine_num', $machine_num);

      }else{

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "「".$Products[0]["name"]."」は原料登録がされていません。"]]);

      }

      $ProductMachineMaterials = $this->ProductMachineMaterials->find()
      ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
      ->where(['machine_num' => $machine_num, 'version' => $version, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["ProductMaterialMachines.id"=>"ASC"])->toArray();

      if(!isset($ProductMachineMaterials[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['machine_num' => $machine_num, 'version' => $version, 'Products.product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->order(["ProductMaterialMachines.id"=>"ASC"])->toArray();
  
      }

      if($ProductMachineMaterials[0]){

        $ProductMaterialMachines = $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['machine_num' => $machine_num, 'version' => $version, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0, 'ProductMaterialMachines.delete_flag' => 0])
        ->order(["cylinder_number"=>"ASC"])->toArray();

        if(!isset($ProductMaterialMachines[0])){//長さ違いのデータがあればそれを持ってくる

          $product_code_ini = substr($product_code, 0, 11);
          $ProductMaterialMachines= $this->ProductMaterialMachines->find()
          ->contain(['ProductConditionParents' => ["Products"]])
          ->where(['Products.product_code like' => $product_code_ini.'%',
          'ProductConditionParents.delete_flag' => 0,
          'ProductMaterialMachines.delete_flag' => 0,
          'ProductConditionParents.machine_num' => $machine_num,
          'ProductConditionParents.version' => $version])
          ->order(["cylinder_number"=>"ASC"])->toArray();
    
        }
          
        $tuikaseikeiki = count($ProductMaterialMachines);
        $this->set('tuikaseikeiki', $tuikaseikeiki);
        $countseikeiki = count($ProductMaterialMachines);
        $this->set('countseikeiki', $countseikeiki);

        for($j=1; $j<=$tuikaseikeiki; $j++){

          ${"product_material_machine_id".$j} = $ProductMaterialMachines[$j - 1]["id"];
          $this->set('product_material_machine_id'.$j, ${"product_material_machine_id".$j});
          ${"cylinder_name".$j} = $ProductMaterialMachines[$j - 1]["cylinder_name"];
          $this->set('cylinder_name'.$j,${"cylinder_name".$j});

          $ProductConditonChildren = $this->ProductConditonChildren->find()
          ->where(['product_material_machine_id' => ${"product_material_machine_id".$j}, 'cylinder_name' => ${"cylinder_name".$j}, 'delete_flag' => 0])
          ->toArray();
    
          if(isset($ProductConditonChildren[0])){

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

          $ProductMachineMaterials = $this->ProductMachineMaterials->find()
          ->where(['product_material_machine_id' => $ProductMaterialMachines[$j - 1]["id"], 'delete_flag' => 0])
          ->order(["material_number"=>"DESC"])->toArray();

          ${"tuikagenryou".$j} = count($ProductMachineMaterials);
          $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            $Materials = $this->Materials->find()
            ->where(['id' => $ProductMachineMaterials[$i - 1]["material_id"]])->toArray();

            ${"material_hyouji".$j.$i} = $Materials[0]["name"];
            $this->set('material_hyouji'.$j.$i,${"material_hyouji".$j.$i});

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

      }else{

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "「".$Products[0]["name"]."」は原料登録がされていません。"]]);

      }

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

    }
    
    public function editdeletedo()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $_SESSION = $session->read();
      $Users= $this->Users->find('all')->contain(["Staffs"])->where(['user_code' => $_SESSION['Auth']['User']['user_code'], 'Users.delete_flag' => 0])->toArray();
      $user_code = $_SESSION['Auth']['User']['user_code'];
      $this->set('user_code', $user_code);

      $staff_id = $Users[0]["staff_id"];
      $this->set('staff_id', $staff_id);

      $arrayKensahyougenryoudatas = $_SESSION['updatekensahyougenryoudata'];
//      $_SESSION['updatekensahyougenryoudata'] = array();

      $data = $arrayKensahyougenryoudatas;
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['machine_num' => $machine_num, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

      }

      if(isset($ProductConditionParents[0])){

        $version = $ProductConditionParents[0]["version"];
        $machine_num = $ProductConditionParents[0]["machine_num"];
        $this->set('machine_num', $machine_num);

      }else{

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "「".$Products[0]["name"]."」は原料登録がされていません。"]]);

      }

      $ProductMachineMaterials = $this->ProductMachineMaterials->find()
      ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
      ->where(['machine_num' => $machine_num, 'version' => $version, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["ProductMaterialMachines.id"=>"ASC"])->toArray();

      if(!isset($ProductMachineMaterials[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['machine_num' => $machine_num, 'version' => $version, 'Products.product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->order(["ProductMaterialMachines.id"=>"ASC"])->toArray();
  
      }

      if($ProductMachineMaterials[0]){

        $ProductMaterialMachines = $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['machine_num' => $machine_num, 'version' => $version, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0, 'ProductMaterialMachines.delete_flag' => 0])
        ->order(["cylinder_number"=>"ASC"])->toArray();

        if(!isset($ProductMaterialMachines[0])){//長さ違いのデータがあればそれを持ってくる

          $product_code_ini = substr($product_code, 0, 11);
          $ProductMaterialMachines= $this->ProductMaterialMachines->find()
          ->contain(['ProductConditionParents' => ["Products"]])
          ->where(['Products.product_code like' => $product_code_ini.'%',
          'ProductConditionParents.delete_flag' => 0,
          'ProductMaterialMachines.delete_flag' => 0,
          'ProductConditionParents.machine_num' => $machine_num,
          'ProductConditionParents.version' => $version])
          ->order(["cylinder_number"=>"ASC"])->toArray();
    
        }
          
        $tuikaseikeiki = count($ProductMaterialMachines);
        $this->set('tuikaseikeiki', $tuikaseikeiki);
        $countseikeiki = count($ProductMaterialMachines);
        $this->set('countseikeiki', $countseikeiki);

        for($j=1; $j<=$tuikaseikeiki; $j++){

          ${"product_material_machine_id".$j} = $ProductMaterialMachines[$j - 1]["id"];
          $this->set('product_material_machine_id'.$j, ${"product_material_machine_id".$j});
          ${"cylinder_name".$j} = $ProductMaterialMachines[$j - 1]["cylinder_name"];
          $this->set('cylinder_name'.$j,${"cylinder_name".$j});

          $ProductConditonChildren = $this->ProductConditonChildren->find()
          ->where(['product_material_machine_id' => ${"product_material_machine_id".$j}, 'cylinder_name' => ${"cylinder_name".$j}, 'delete_flag' => 0])
          ->toArray();
    
          if(isset($ProductConditonChildren[0])){

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

          $ProductMachineMaterials = $this->ProductMachineMaterials->find()
          ->where(['product_material_machine_id' => $ProductMaterialMachines[$j - 1]["id"], 'delete_flag' => 0])
          ->order(["material_number"=>"DESC"])->toArray();

          ${"tuikagenryou".$j} = count($ProductMachineMaterials);
          $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            $Materials = $this->Materials->find()
            ->where(['id' => $ProductMachineMaterials[$i - 1]["material_id"]])->toArray();

            ${"material_hyouji".$j.$i} = $Materials[0]["name"];
            $this->set('material_hyouji'.$j.$i,${"material_hyouji".$j.$i});

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

      }else{

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "「".$Products[0]["name"]."」は原料登録がされていません。"]]);

      }

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

        if ($this->ProductConditionParents->updateAll(
          ['is_active' => 1, 'delete_flag' => 1,
          'updated_at' => date('Y-m-d H:i:s'),
          'updated_staff' => $staff_id],
          ['id'  => $ProductConditionParents[0]["id"]])){

            $ProductConditonChildren = $this->ProductConditonChildren->find()
            ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
            ->where(['Products.product_code like' => $product_code_ini.'%'
            , 'ProductConditionParents.machine_num' => $machine_num
            , 'ProductConditonChildren.delete_flag' => 0])
            ->toArray();
  
            for($k=0; $k<count($ProductConditonChildren); $k++){

            $this->ProductConditonChildren->updateAll(
              [ 'delete_flag' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_staff' => $staff_id],
              ['id'  => $ProductConditonChildren[$k]["id"]]);

            }
          
            $ProductMaterialMachines = $this->ProductMaterialMachines->find()
            ->contain(['ProductConditionParents' => ["Products"]])
            ->where(['product_code like' => $product_code_ini.'%'
            , 'ProductConditionParents.machine_num' => $machine_num
            , 'ProductMaterialMachines.delete_flag' => 0])
            ->order(["cylinder_number"=>"ASC"])->toArray();
    
            for($k=0; $k<count($ProductMaterialMachines); $k++){

              $this->ProductMachineMaterials->updateAll(
                [ 'delete_flag' => 1,
                  'updated_at' => date('Y-m-d H:i:s'),
                  'updated_staff' => $staff_id],
                ['product_material_machine_id'  => $ProductMaterialMachines[$k]["id"]]);
  
                $this->ProductMaterialMachines->updateAll(
                  [ 'delete_flag' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_staff' => $staff_id],
                  ['id'  => $ProductMaterialMachines[$k]["id"]]);

            }
 /*   
            echo "<pre>";
            print_r($ProductMaterialMachines);
            echo "</pre>";
*/
              $connection->commit();// コミット5
              $mes = "削除されました。";
              $this->set('mes',$mes);
    
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
