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

class KensahyougenryousController extends AppController
{

  	public function beforeFilter(Event $event){
  		parent::beforeFilter($event);

  		// 認証なしでアクセスできるアクションの指定
      $this->Auth->allow(["menu","addlogin","addformpre","addform","addcomfirm","adddo"
      ,"kensakupre", "kensakuhyouji", "editlogin", "editform", "editcomfirm", "editdo"]);
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
     $this->Users = TableRegistry::get('Users');
     $this->Staffs = TableRegistry::get('Staffs');
     $this->Products = TableRegistry::get('Products');
     $this->Customers = TableRegistry::get('Customers');
     $this->Materials = TableRegistry::get('Materials');
     $this->ProductConditionParents = TableRegistry::get('ProductConditionParents');
     $this->ProductMaterialMachines = TableRegistry::get('ProductMaterialMachines');
     $this->ProductMachineMaterials = TableRegistry::get('ProductMachineMaterials');

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

      $Customer_name_list = $this->Customers->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrCustomer_name_list = array();
      for($j=0; $j<count($Customer_name_list); $j++){
        array_push($arrCustomer_name_list,$Customer_name_list[$j]["name"]);
      }
      $this->set('arrCustomer_name_list', $arrCustomer_name_list);

     if(isset($data["customer"])){//顧客絞り込みをしたとき

       $staff_id = $data["staff_id"];
       $this->set('staff_id', $staff_id);
       $staff_name = $data["staff_name"];
       $this->set('staff_name', $staff_name);
       $user_code = $data["user_code"];
       $this->set('user_code', $user_code);

       $Product_name_list = $this->Products->find()
       ->contain(['Customers'])
       ->where(['Customers.name' => $data["customer_name"], 'Products.delete_flag' => 0])->toArray();

       if(count($Product_name_list) < 1){//顧客名にミスがある場合

         $mess = "入力された顧客名は登録されていません。確認してください。";
         $this->set('mess',$mess);

         $Product_name_list = $this->Products->find()
         ->where(['delete_flag' => 0])->toArray();

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }else{

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }

     }elseif(isset($data["next"])){//「次へ」ボタンを押したとき

       $staff_id = $data["staff_id"];
       $this->set('staff_id', $staff_id);
       $staff_name = $data["staff_name"];
       $this->set('staff_name', $staff_name);
       $user_code = $data["user_code"];
       $this->set('user_code', $user_code);

       if(strlen($data["product_name"]) > 0){//product_nameの入力がある

         $Products = $this->Products->find()
         ->where(['name' => $data["product_name"], 'delete_flag' => 0])->toArray();

         if(isset($Products[0])){

           $product_code = $Products[0]["product_code"];

           return $this->redirect(['action' => 'addform',
           's' => ['product_code' => $product_code, 'user_code' => $user_code]]);

         }else{

           $mess = "入力された製品名は登録されていません。確認してください。";
           $this->set('mess',$mess);

           $Product_name_list = $this->Products->find()
           ->where(['delete_flag' => 0])->toArray();

           $arrProduct_name_list = array();
           for($j=0; $j<count($Product_name_list); $j++){
             array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
           }
           $this->set('arrProduct_name_list', $arrProduct_name_list);

         }

       }else{//product_nameの入力がない

         $mess = "製品名が入力されていません。";
         $this->set('mess',$mess);

         $Product_name_list = $this->Products->find()
         ->where(['delete_flag' => 0])->toArray();

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }

     }else{//はじめ

       $Product_name_list = $this->Products->find()
       ->where(['delete_flag' => 0])->toArray();
       $arrProduct_name_list = array();
       for($j=0; $j<count($Product_name_list); $j++){
         array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
       }
       $this->set('arrProduct_name_list', $arrProduct_name_list);

       $Data=$this->request->query('s');
       if(isset($Data["mess"])){
         $mess = $Data["mess"];
         $this->set('mess',$mess);

         $session = $this->request->getSession();
         $_SESSION = $session->read();
         $user_code = $_SESSION["user_code"];
         $_SESSION['user_code'] = array();

       }else{
         $mess = "";
         $this->set('mess',$mess);
         $user_code = $data["user_code"];
       }

       $this->set('user_code', $user_code);

       $userlogincheck = $user_code."_".$data["password"];

       $htmlinputstaff = new htmlLogin();//クラスを使用
   //    $arraylogindate = $htmlinputstaff->inputstaffprogram($user_code);//クラスを使用
       $arraylogindate = $htmlinputstaff->inputstaffprogram($userlogincheck);//クラスを使用210608更新

       if($arraylogindate[0] === "no_staff"){

         return $this->redirect(['action' => 'addlogin',
         's' => ['mess' => "社員コードまたはパスワードが間違っています。もう一度やり直してください。"]]);

       }else{

         $staff_id = $arraylogindate[0];
         $staff_name = $arraylogindate[1];
         $this->set('staff_id', $staff_id);
         $this->set('staff_name', $staff_name);

       }

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

      }
/*
      $data = $this->request->getData();

      echo "<pre>";
      print_r($data);
      echo "</pre>";

      $staff_id = $data["staff_id"];
      $this->set('staff_id', $staff_id);
      $staff_name = $data["staff_name"];
      $this->set('staff_name', $staff_name);
      $user_code = $data["user_code"];
      $this->set('user_code', $user_code);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
*/
/*
      $htmlproductcheck = new htmlproductcheck();//クラスを使用
      $arrayproductdate = $htmlproductcheck->productcheckprogram($product_code);//クラスを使用

      if($arrayproductdate[0] === "no_product"){

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['user_code'] = array();
        $_SESSION['user_code'] = $user_code;

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は存在しません。"]]);

      }
*/
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $this->set('user_code', $user_code);

      $Materialmakers = $this->Materials->find()
      ->where(['delete_flag' => 0])->order(["grade"=>"ASC"])->toArray();

      $arrMaterialmakers = array();
      foreach ($Materialmakers as $value) {
        $array = array($value->maker => $value->maker);
        $arrMaterialmakers = $arrMaterialmakers + $array;
      }
      $arrMaterialmakers = array_unique($arrMaterialmakers);
      $this->set('arrMaterialmakers', $arrMaterialmakers);
/*
      echo "<pre>";
      print_r($arrMaterialmakers);
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

        if(!isset($data["material_id".$tuikaseikeiki.$data["tuikagenryou".$tuikaseikeiki]])){//原料が選択されていない場合
          ${"tuikagenryou".$tuikaseikeiki} = $data["tuikagenryou".$tuikaseikeiki];

          $mess = "原料が選択されていません。";
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

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            if(isset($data["makercheck".$j.$i])){
              ${"makercheck".$j.$i} = $data["makercheck".$j.$i];
              $this->set('makercheck'.$j.$i,${"makercheck".$j.$i});
            }else{
              ${"makercheck".$j.$i} = 0;
              $this->set('makercheck'.$j.$i,${"makercheck".$j.$i});
            }

            if(isset($data["material_maker".$j.$i])){
              ${"material_maker".$j.$i} = $data["material_maker".$j.$i];
              $this->set('material_maker'.$j.$i,${"material_maker".$j.$i});
            }else{
              ${"material_maker".$j.$i} = "";
              $this->set('material_maker'.$j.$i,${"material_maker".$j.$i});
            }

            if(isset($data["material_id".$j.$i])){
              ${"material_id".$j.$i} = $data["material_id".$j.$i];
              $this->set('material_id'.$j.$i,${"material_id".$j.$i});

              $Materialhoujis = $this->Materials->find()
              ->where(['id' => $data["material_id".$j.$i]])->toArray();
              ${"material_houji".$j.$i} = $Materialhoujis[0]["name"]." : ".$Materialhoujis[0]["grade"]." : ".$Materialhoujis[0]["color"];
              $this->set('material_houji'.$j.$i,${"material_houji".$j.$i});
              ${"makercheck".$j.$i} = 2;
              $this->set('makercheck'.$j.$i,${"makercheck".$j.$i});
            }else{
              ${"material_id".$j.$i} = "";
              $this->set('material_id'.$j.$i,${"material_id".$j.$i});
              ${"material_houji".$j.$i} = "";
              $this->set('material_houji'.$j.$i,${"material_houji".$j.$i});
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

        $tuikaseikeiki = $data["tuikaseikeiki"];

        if(!isset($data["material_id".$tuikaseikeiki.$data["tuikagenryou".$tuikaseikeiki]])){//原料が選択されていない場合
          $tuikaseikeiki = $data["tuikaseikeiki"];

          $mess = "原料が選択されていません。";
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

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            if(isset($data["makercheck".$j.$i])){
              ${"makercheck".$j.$i} = $data["makercheck".$j.$i];
              $this->set('makercheck'.$j.$i,${"makercheck".$j.$i});
            }else{
              ${"makercheck".$j.$i} = 0;
              $this->set('makercheck'.$j.$i,${"makercheck".$j.$i});
            }

            if(isset($data["material_maker".$j.$i])){
              ${"material_maker".$j.$i} = $data["material_maker".$j.$i];
              $this->set('material_maker'.$j.$i,${"material_maker".$j.$i});
            }else{
              ${"material_maker".$j.$i} = "";
              $this->set('material_maker'.$j.$i,${"material_maker".$j.$i});
            }

            if(isset($data["material_id".$j.$i])){
              ${"material_id".$j.$i} = $data["material_id".$j.$i];
              $this->set('material_id'.$j.$i,${"material_id".$j.$i});

              $Materialhoujis = $this->Materials->find()
              ->where(['id' => $data["material_id".$j.$i]])->toArray();
              ${"material_houji".$j.$i} = $Materialhoujis[0]["name"]." : ".$Materialhoujis[0]["grade"]." : ".$Materialhoujis[0]["color"];
              $this->set('material_houji'.$j.$i,${"material_houji".$j.$i});
              ${"makercheck".$j.$i} = 2;
              $this->set('makercheck'.$j.$i,${"makercheck".$j.$i});
            }else{
              ${"material_id".$j.$i} = "";
              $this->set('material_id'.$j.$i,${"material_id".$j.$i});
              ${"material_houji".$j.$i} = "";
              $this->set('material_houji'.$j.$i,${"material_houji".$j.$i});
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

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            if(strlen($data["makercheck".$j.$i]) > 0){
              ${"makercheck".$j.$i} = $data["makercheck".$j.$i];
              $this->set('makercheck'.$j.$i,${"makercheck".$j.$i});
            }else{
              ${"makercheck".$j.$i} = 0;
              $this->set('makercheck'.$j.$i,${"makercheck".$j.$i});
            }

            if(strlen($data["material_maker".$j.$i]) > 0){
              ${"material_maker".$j.$i} = $data["material_maker".$j.$i];
              $this->set('material_maker'.$j.$i,${"material_maker".$j.$i});
            }else{
              ${"material_maker".$j.$i} = "";
              $this->set('material_maker'.$j.$i,${"material_maker".$j.$i});
              $mess = "※入力漏れがあります。";
            }

            if(strlen($data["material_id".$j.$i]) > 0){
              ${"material_id".$j.$i} = $data["material_id".$j.$i];
              $this->set('material_id'.$j.$i,${"material_id".$j.$i});

              $Materialhoujis = $this->Materials->find()
              ->where(['id' => $data["material_id".$j.$i]])->toArray();
              ${"material_houji".$j.$i} = $Materialhoujis[0]["name"]." : ".$Materialhoujis[0]["grade"]." : ".$Materialhoujis[0]["color"];
              $this->set('material_houji'.$j.$i,${"material_houji".$j.$i});
              ${"makercheck".$j.$i} = 2;
              $this->set('makercheck'.$j.$i,${"makercheck".$j.$i});
            }else{
              ${"material_id".$j.$i} = "";
              $this->set('material_id'.$j.$i,${"material_id".$j.$i});
              ${"material_houji".$j.$i} = "";
              $this->set('material_houji'.$j.$i,${"material_houji".$j.$i});
              $mess = "※入力漏れがあります。";
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

            if(strlen($data["recycled_mixing_ratio".$j.$i]) > 0){
              ${"recycled_mixing_ratio".$j.$i} = $data["recycled_mixing_ratio".$j.$i];
              $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});
            }else{
              ${"recycled_mixing_ratio".$j.$i} = "";
              $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});
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

          return $this->redirect(['action' => 'addcomfirm',
          's' => ['data' => 'addcomfirm']]);
        }

      }else{//最初にこの画面に来た時

        $i = $j = 1;
        $tuikagenryou = 1;
        $this->set('tuikagenryou'.$i, $tuikagenryou);
        $tuikaseikeiki = 1;
        $this->set('tuikaseikeiki', $tuikaseikeiki);
        ${"cylinder_name".$j} = "";
        $this->set('cylinder_name'.$j,${"cylinder_name".$j});
        ${"material_id".$j.$i} = "";
        $this->set('material_id'.$j.$i,${"material_id".$j.$i});
        ${"material_maker".$j.$i} = "";
        $this->set('material_maker'.$j.$i,${"material_maker".$j.$i});
        ${"makercheck".$j.$i} = "";
        $this->set('makercheck'.$j.$i,${"makercheck".$j.$i});
        ${"mixing_ratio".$j.$i} = "";
        $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
        ${"dry_temp".$j.$i} = "";
        $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
        ${"dry_hour".$j.$i} = "";
        $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
        ${"recycled_mixing_ratio".$j.$i} = "";
        $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});

      }

      //原料の絞り込み
      if(isset($data["siborikomi"])){

        if(!isset($data["tuikaseikeiki"])){//成形機の追加前

          $tuikaseikeiki = 1;

        }else{//成形機の追加後

          $tuikaseikeiki = $data["tuikaseikeiki"];

        }
        $this->set('tuikaseikeiki', $tuikaseikeiki);

        ${"tuikagenryou".$tuikaseikeiki} = $data["tuikagenryou".$tuikaseikeiki];

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

            if(isset($data["makercheck".$j.$i])){
              ${"makercheck".$j.$i} = $data["makercheck".$j.$i];
              $this->set('makercheck'.$j.$i,${"makercheck".$j.$i});
            }else{
              ${"makercheck".$j.$i} = 0;
              $this->set('makercheck'.$j.$i,${"makercheck".$j.$i});
            }

            if(isset($data["material_maker".$j.$i])){
              ${"material_maker".$j.$i} = $data["material_maker".$j.$i];
              $this->set('material_maker'.$j.$i,${"material_maker".$j.$i});
            }else{
              ${"material_maker".$j.$i} = "";
              $this->set('material_maker'.$j.$i,${"material_maker".$j.$i});
            }

            if(isset($data["material_id".$j.$i])){
              ${"material_id".$j.$i} = $data["material_id".$j.$i];
              $this->set('material_id'.$j.$i,${"material_id".$j.$i});

              $Materialhoujis = $this->Materials->find()
              ->where(['id' => $data["material_id".$j.$i]])->toArray();
              ${"material_houji".$j.$i} = $Materialhoujis[0]["name"]." : ".$Materialhoujis[0]["grade"]." : ".$Materialhoujis[0]["color"];
              $this->set('material_houji'.$j.$i,${"material_houji".$j.$i});
              ${"makercheck".$j.$i} = 2;
              $this->set('makercheck'.$j.$i,${"makercheck".$j.$i});
            }else{
              ${"material_id".$j.$i} = "";
              $this->set('material_id'.$j.$i,${"material_id".$j.$i});
              ${"material_houji".$j.$i} = "";
              $this->set('material_houji'.$j.$i,${"material_houji".$j.$i});
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

        $j = $data["tuikaseikeiki"];
        $i = $data["tuikagenryou".$data["tuikaseikeiki"]];
        ${"makercheck".$j.$i} = 1;
        $this->set('makercheck'.$j.$i,${"makercheck".$j.$i});

        $Materials = $this->Materials->find()
        ->where(['maker' => $data["material_maker".$j.$i], 'delete_flag' => 0])->order(["grade"=>"ASC"])->toArray();

        $arrMaterials = array();
        foreach ($Materials as $value) {
          $array = array($value->id => $value->name." : ".$value->grade." : ".$value->color);
          $arrMaterials = $arrMaterials + $array;//array_mergeだとキーが0,1,2,…とふりなおされてしまう
        }
        $this->set('arrMaterials', $arrMaterials);
      }

    }

    public function addcomfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $arrayKensahyougenryoudatas = $_SESSION['kensahyougenryoudata'];
    //  $_SESSION['kensahyougenryoudata'] = array();

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

            ${"material_hyouji".$j.$i} = $Materials[0]["maker"]." : ".$Materials[0]["name"]." : "
            .$Materials[0]["grade"]." : ".$Materials[0]["color"];
            $this->set('material_hyouji'.$j.$i,${"material_hyouji".$j.$i});
            ${"material_id".$j.$i} = $data["material_id".$j.$i];
            $this->set('material_id'.$j.$i,${"material_id".$j.$i});

          }else{
            ${"material_hyouji".$j.$i} = "";
            $this->set('material_hyouji'.$j.$i,${"material_hyouji".$j.$i});
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

    }

    public function adddo()
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

            ${"material_hyouji".$j.$i} = $Materials[0]["grade"].":".$Materials[0]["maker"].":".$Materials[0]["material_code"];
            $this->set('material_hyouji'.$j.$i,${"material_hyouji".$j.$i});
            ${"material_id".$j.$i} = $data["material_id".$j.$i];
            $this->set('material_id'.$j.$i,${"material_id".$j.$i});

          }else{
            ${"material_hyouji".$j.$i} = "";
            $this->set('material_hyouji'.$j.$i,${"material_hyouji".$j.$i});
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

      $tourokuProductConditionParent = array();

      $Products = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $product_id = $Products[0]['id'];

      $ProductConditionParents = $this->ProductConditionParents->find()
      ->where(['product_id' => $product_id, 'delete_flag' => 0])->order(["version"=>"DESC"])->toArray();

      if(isset($ProductConditionParents[0])){
        $version = $ProductConditionParents[0]["version"] + 1;
        $motoid = $ProductConditionParents[0]["id"];
      }else{
        $version = 1;
        $motoid = 0;
      }

      $tourokuProductConditionParent = [
        "product_id" => $product_id,
        "version" => $version,
        "start_datetime" => date("Y-m-d H:i:s"),
        "is_active" => 0,
        "delete_flag" => 0,
        'created_at' => date("Y-m-d H:i:s"),
        "created_staff" => $staff_id
      ];

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
          ->where(['product_id' => $product_id, 'version' => $version, 'delete_flag' => 0])->toArray();

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
              ->where(['product_condition_parent_id' => $ProductConditionParents[0]["id"], 'cylinder_number' => $j])->toArray();

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
                  "recycled_mixing_ratio" => $data["recycled_mixing_ratio".$j.$i],
                  "delete_flag" => 0,
                  'created_at' => date("Y-m-d H:i:s"),
                  "created_staff" => $staff_id
                ];

              }

              $ProductMachineMaterials = $this->ProductMachineMaterials
              ->patchEntities($this->ProductMachineMaterials->newEntity(), $tourokuProductMachineMaterial);
              if ($this->ProductMachineMaterials->saveMany($ProductMachineMaterials)) {

                if($j >= $tuikaseikeiki){

                  $connection->commit();// コミット5
                  $mes = "以下のように登録されました。";
                  $this->set('mes',$mes);

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
       ->where(['Customers.name' => $data["customer_name"], 'Products.delete_flag' => 0])->toArray();

       if(count($Product_name_list) < 1){//顧客名にミスがある場合

         $mess = "入力された顧客名は登録されていません。確認してください。";
         $this->set('mess',$mess);

         $Product_name_list = $this->Products->find()
         ->where(['delete_flag' => 0])->toArray();

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }else{

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }

     }elseif(isset($data["next"])){//「次へ」ボタンを押したとき

       if(strlen($data["product_name"]) > 0){//product_nameの入力がある

         $Products = $this->Products->find()
         ->where(['name' => $data["product_name"], 'delete_flag' => 0])->toArray();

         if(isset($Products[0])){

           $product_code = $Products[0]["product_code"];

           return $this->redirect(['action' => 'kensakuhyouji',
           's' => ['product_code' => $product_code]]);

         }else{

           $mess = "入力された製品名は登録されていません。確認してください。";
           $this->set('mess',$mess);

           $Product_name_list = $this->Products->find()
           ->where(['delete_flag' => 0])->toArray();

           $arrProduct_name_list = array();
           for($j=0; $j<count($Product_name_list); $j++){
             array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
           }
           $this->set('arrProduct_name_list', $arrProduct_name_list);

         }

       }else{//product_nameの入力がない

         $mess = "製品名が入力されていません。";
         $this->set('mess',$mess);

         $Product_name_list = $this->Products->find()
         ->where(['delete_flag' => 0])->toArray();

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }

     }else{//はじめ

       $Product_name_list = $this->Products->find()
       ->where(['delete_flag' => 0])->toArray();
       $arrProduct_name_list = array();
       for($j=0; $j<count($Product_name_list); $j++){
         array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
       }
       $this->set('arrProduct_name_list', $arrProduct_name_list);

     }

    }

    public function kensakuhyouji()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data = $this->request->query('s');
      $product_code = $Data["product_code"];
      $this->set('product_code', $product_code);

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(isset($ProductConditionParents[0])){

        $version = $ProductConditionParents[0]["version"];

      }else{

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "「".$Products[0]["name"]."」は原料登録がされていません。"]]);

      }

      $ProductMachineMaterials = $this->ProductMachineMaterials->find()
      ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
      ->where(['version' => $version, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->toArray();

      if($ProductMachineMaterials[0]){

        $ProductMaterialMachines = $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['version' => $version, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0, 'ProductMaterialMachines.delete_flag' => 0])
        ->order(["cylinder_number"=>"DESC"])->toArray();

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

            ${"material_hyouji".$j.$i} = $Materials[0]["grade"].":".$Materials[0]["maker"].":".$Materials[0]["material_code"];
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
    }

    public function editform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $mes = "";
      $this->set('mes', $mes);

      $data = $this->request->getData();
      $user_code = $data["user_code"];
/*//210608更新
      $htmlinputstaff = new htmlLogin();//クラスを使用
      $arraylogindate = $htmlinputstaff->inputstaffprogram($user_code);//クラスを使用

      if($arraylogindate[0] === "no_staff"){

        return $this->redirect(['action' => 'addlogin',
        's' => ['mess' => "社員コードかパスワードに誤りがあります。もう一度やり直してください。"]]);

      }else{

        $staff_id = $arraylogindate[0];
        $staff_name = $arraylogindate[1];
        $this->set('staff_id', $staff_id);
        $this->set('staff_name', $staff_name);

      }
*/
      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);

      $htmlproductcheck = new htmlproductcheck();//クラスを使用
      $arrayproductdate = $htmlproductcheck->productcheckprogram($product_code);//クラスを使用

      if($arrayproductdate[0] === "no_product"){

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['user_code'] = array();
        $_SESSION['user_code'] = $user_code;

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は存在しません。"]]);

      }

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $this->set('user_code', $user_code);

      $Materials = $this->Materials->find()
      ->where(['delete_flag' => 0])->order(["maker"=>"ASC"])->toArray();

      $arrMaterials = array();
      foreach ($Materials as $value) {
        $array = array($value->id => $value->maker." : ".$value->name." : ".$value->grade." : ".$value->color);
        $arrMaterials = $arrMaterials + $array;//array_mergeだとキーが0,1,2,…とふりなおされてしまう
      }
      $this->set('arrMaterials', $arrMaterials);

      if(isset($data["genryoutuika"])){//原料追加ボタン

        $staff_id = $data["staff_id"];
        $staff_name = $data["staff_name"];
        $this->set('staff_id', $staff_id);
        $this->set('staff_name', $staff_name);

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

            if(isset($data["material_id".$j.$i])){
              ${"material_id".$j.$i} = $data["material_id".$j.$i];
              $this->set('material_id'.$j.$i,${"material_id".$j.$i});
            }else{
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

      }elseif(isset($data["seikeikituika"])){//成形機追加ボタン

        $staff_id = $data["staff_id"];
        $staff_name = $data["staff_name"];
        $this->set('staff_id', $staff_id);
        $this->set('staff_name', $staff_name);

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

            if(isset($data["material_id".$j.$i])){
              ${"material_id".$j.$i} = $data["material_id".$j.$i];
              $this->set('material_id'.$j.$i,${"material_id".$j.$i});
            }else{
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

      }elseif(isset($data["kakuninn"])){//確認ボタン

        $staff_id = $data["staff_id"];
        $staff_name = $data["staff_name"];
        $this->set('staff_id', $staff_id);
        $this->set('staff_name', $staff_name);

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

        if($double_delete_check == 0){

          if(!isset($_SESSION)){
            session_start();
          }

          $_SESSION['updatekensahyougenryoudata'] = array();
          $_SESSION['updatekensahyougenryoudata'] = $data + array("delete_flag" => 0);

          return $this->redirect(['action' => 'editcomfirm']);

        }else{

          $mes = "同じ成形機内で「成形機削除」と「原料削除」が選択されました。もう一度やり直してください。";
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

              if(isset($data["material_id".$j.$i])){
                ${"material_id".$j.$i} = $data["material_id".$j.$i];
                $this->set('material_id'.$j.$i,${"material_id".$j.$i});
              }else{
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

        }

      }elseif(isset($data["sakujo"])){//削除ボタン

        $staff_id = $data["staff_id"];
        $staff_name = $data["staff_name"];
        $this->set('staff_id', $staff_id);
        $this->set('staff_name', $staff_name);

        if(!isset($_SESSION)){
          session_start();
        }

        $_SESSION['updatekensahyougenryoudata'] = array();
        $_SESSION['updatekensahyougenryoudata'] = $data + array("delete_flag" => 1);

        return $this->redirect(['action' => 'editcomfirm']);

      }else{//最初にこの画面に来た時

        $userlogincheck = $user_code."_".$data["password"];

        $htmlinputstaff = new htmlLogin();//クラスを使用
    //    $arraylogindate = $htmlinputstaff->inputstaffprogram($user_code);//クラスを使用
        $arraylogindate = $htmlinputstaff->inputstaffprogram($userlogincheck);//クラスを使用210608更新

        if($arraylogindate[0] === "no_staff"){

          return $this->redirect(['action' => 'addlogin',
          's' => ['mess' => "社員コードまたはパスワードが間違っています。もう一度やり直してください。"]]);

        }else{

          $staff_id = $arraylogindate[0];
          $staff_name = $arraylogindate[1];
          $this->set('staff_id', $staff_id);
          $this->set('staff_name', $staff_name);

        }

        $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

        $version = $ProductConditionParents[0]["version"];

        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['version' => $version, 'product_code' => $product_code, 'ProductMachineMaterials.delete_flag' => 0, 'ProductConditionParents.delete_flag' => 0])
        ->toArray();

        $ProductMaterialMachines = $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['version' => $version, 'product_code' => $product_code, 'ProductMaterialMachines.delete_flag' => 0, 'ProductConditionParents.delete_flag' => 0])
        ->order(["cylinder_number"=>"DESC"])->toArray();

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

            ${"material_hyouji".$j.$i} = $Materials[0]["grade"].":".$Materials[0]["maker"].":".$Materials[0]["material_code"];
            $this->set('material_hyouji'.$j.$i,${"material_hyouji".$j.$i});
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

      }
/*
      echo "<pre>";
      print_r("aaa");
      echo "</pre>";
  */
    }

    public function editcomfirm()
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

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $tuikaseikeiki = $data["tuikaseikeiki"];

      $n = 0;//成形機の数

      for($j=1; $j<=$tuikaseikeiki; $j++){

        $m = 0;//原料の数

        if(!isset($data["delete_seikeiki".$j])){
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
/*
              echo "<pre>";
              print_r("genryou".$j.$i);
              echo "</pre>";
*/
              if(isset($data["material_id".$j.$i])){
                $Materials = $this->Materials->find()
                ->where(['id' => $data["material_id".$j.$i]])->toArray();

                ${"material_hyouji".$j.$i} = $Materials[0]["maker"].":".$Materials[0]["name"].":".$Materials[0]["grade"].":".$Materials[0]["color"];
                $this->set('material_hyouji'.$n.$m,${"material_hyouji".$j.$i});
                ${"material_id".$j.$i} = $data["material_id".$j.$i];
                $this->set('material_id'.$n.$m,${"material_id".$j.$i});

              }else{
                ${"material_hyouji".$j.$i} = "";
                $this->set('material_hyouji'.$n.$i,${"material_hyouji".$j.$i});
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

        }

      }

      if($data["delete_flag"] < 1){
        $this->set('delete_flag', 0);
        $mes = "以下のように更新します。よろしければ決定ボタンを押してください。";
      }else{
        $this->set('delete_flag', 1);
        $mes = "以下のデータを削除します。よろしければ決定ボタンを押してください。";
      }
      $this->set('mes', $mes);

    }

    public function editdo()
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

            ${"material_hyouji".$j.$i} = $Materials[0]["maker"].":".$Materials[0]["name"].":".$Materials[0]["grade"].":".$Materials[0]["color"];
            $this->set('material_hyouji'.$j.$i,${"material_hyouji".$j.$i});
            ${"material_id".$j.$i} = $data["material_id".$j.$i];
            $this->set('material_id'.$j.$i,${"material_id".$j.$i});

          }else{
            ${"material_hyouji".$j.$i} = "";
            $this->set('material_hyouji'.$j.$i,${"material_hyouji".$j.$i});
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

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      $product_condition_parent_id = $ProductConditionParents[0]["id"];

      $ProductMachineMaterials = $this->ProductMachineMaterials->find()
      ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
      ->where(['product_condition_parent_id' => $product_condition_parent_id, 'product_code' => $product_code,
       'ProductMachineMaterials.delete_flag' => 0, 'ProductConditionParents.delete_flag' => 0])
      ->toArray();

      $ProductMaterialMachines = $this->ProductMaterialMachines->find()
      ->contain(['ProductConditionParents' => ["Products"]])
      ->where(['product_condition_parent_id' => $product_condition_parent_id, 'product_code' => $product_code,
       'ProductMaterialMachines.delete_flag' => 0, 'ProductConditionParents.delete_flag' => 0])
      ->order(["cylinder_number"=>"DESC"])->toArray();

      if($data["delete_flag"] < 1){//削除ではない場合

        $ProductMachineMaterials = $this->ProductMachineMaterials->patchEntities($this->ProductMachineMaterials->newEntity(), $data);
        $connection = ConnectionManager::get('default');//トランザクション1
        // トランザクション開始2
        $connection->begin();//トランザクション3
        try {//トランザクション4
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
                  "recycled_mixing_ratio" => $data["recycled_mixing_ratio".$j.$i],
                  "delete_flag" => 0,
                  'created_at' => date("Y-m-d H:i:s"),
                  "created_staff" => $staff_id
                ];

              }

              $ProductMachineMaterials = $this->ProductMachineMaterials
              ->patchEntities($this->ProductMachineMaterials->newEntity(), $updateProductMachineMaterial);
              if ($this->ProductMachineMaterials->saveMany($ProductMachineMaterials)) {

                if($j >= $tuikaseikeiki){

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

      }else{

        $ProductMachineMaterials = $this->ProductMachineMaterials->patchEntities($this->ProductMachineMaterials->newEntity(), $data);
        $connection = ConnectionManager::get('default');//トランザクション1
        // トランザクション開始2
        $connection->begin();//トランザクション3
        try {//トランザクション4
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

                      //最後のデータが更新されたとき
                      if($j >= count($ProductMaterialMachines) && $i >= count($ProductMachineMaterials)){

                        $connection->commit();// コミット5
                        $mes = "以下のように更新されました。";
                        $this->set('mes',$mes);

                      }

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

        } catch (Exception $e) {//トランザクション7
        //ロールバック8
          $connection->rollback();//トランザクション9
        }//トランザクション10

      }

    }

}
