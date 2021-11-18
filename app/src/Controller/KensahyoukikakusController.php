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

class KensahyoukikakusController extends AppController
{

  	public function beforeFilter(Event $event){
  		parent::beforeFilter($event);

  		// 認証なしでアクセスできるアクションの指定
  		$this->Auth->allow(["menu"
    //  ,"kensakupre", "kensakuhyouji"
    ]);
  	}

      public function initialize()
    {
     parent::initialize();
     $this->Users = TableRegistry::get('Users');
     $this->Staffs = TableRegistry::get('Staffs');
     $this->Products = TableRegistry::get('Products');
     $this->Customers = TableRegistry::get('Customers');
     $this->Kensakigus = TableRegistry::get('Kensakigus');
     $this->InspectionStandardSizeChildren = TableRegistry::get('InspectionStandardSizeChildren');
     $this->InspectionStandardSizeParents = TableRegistry::get('InspectionStandardSizeParents');

     if(!isset($_SESSION)){//フォーム再送信の確認対策//戻りたい画面でわざとwarningを出しておけば戻れる
       session_start();
     }
     header('Expires:');
     header('Cache-Control:');
     header('Pragma:');
/*
     echo "<pre>";//フォームの再読み込みの防止
     print_r("  ");
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

           return $this->redirect(['action' => 'addimageform',
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

    public function addimageform()
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);
      $mes = "";
      $this->set('mes',$mes);
      $mess = "";
      $this->set('mess',$mess);

      $Data = $this->request->query('s');

      if(isset($Data["product_code"])){

        $product_code = $Data["product_code"];
        $this->set('product_code', $product_code);
        if(isset($Data["mess"])){
          $mess = $Data["mess"];
          $this->set('mess',$mess);
          }

      }else{

        $data = $this->request->getData();
        $product_code = $data["product_code"];
        $this->set('product_code', $product_code);

      }

      $ProductNs = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $product_name = $ProductNs[0]["name"];
      $this->set('product_name',$product_name);

      if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');
  
      print_r("");
    }

    public function addimagecomfirm()
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);

      $data = $this->request->getData();

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $ProductNs = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $product_name = $ProductNs[0]["name"];
      $this->set('product_name',$product_name);

      if(!isset($_SESSION)){
        session_start();
      }
      $_SESSION['img_product_code'] = array();
      $_SESSION['img_product_code'] = $product_code;

      $fileName = $_FILES['upfile']['tmp_name'];

      if(substr($_FILES['upfile']["name"], -4) !== ".JPG"
      && substr($_FILES['upfile']["name"], -4) !== ".jpg"
      && substr($_FILES['upfile']["name"], -5) !== ".JPEG"
      && substr($_FILES['upfile']["name"], -5) !== ".jpeg"){

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['img_product_code'] = array();
        $_SESSION['img_product_code'] = $product_code;

        return $this->redirect(['action' => 'addimageform',
        's' => ['mess' => "※拡張子が「.JPG」でないファイルが選択されました。"]]);

      }
/*
      echo "<pre>";
      print_r($_FILES);
      echo "</pre>";
*/
      if($_FILES['upfile']['error'] == 0){

        if($_FILES['upfile']['size']>2000000){

          if(!isset($_SESSION)){
            session_start();
          }
          $_SESSION['img_product_code'] = array();
          $_SESSION['img_product_code'] = $product_code;

          return $this->redirect(['action' => 'addimageform',
          's' => ['mess' => "※画像サイズが大き過ぎます（アップロードできるサイズの上限は２MBです）"]]);

        }else{

          if(move_uploaded_file($_FILES['upfile']["tmp_name"],"img/kensahyouimg/".$_FILES['upfile']["name"])){

          }else{
      
            if(!isset($_SESSION)){
              session_start();
            }
            $_SESSION['img_product_code'] = array();
            $_SESSION['img_product_code'] = $product_code;
  
            return $this->redirect(['action' => 'addimageform',
            's' => ['mess' => "※ファイルが読み込まれませんでした。"]]);
      
          }

        }

      }elseif($_FILES['upfile']['error'] == 1){

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['img_product_code'] = array();
        $_SESSION['img_product_code'] = $product_code;

        return $this->redirect(['action' => 'addimageform',
        's' => ['mess' => "※画像サイズが大き過ぎます（アップロードできるサイズの上限は２MBです）"]]);
        
      }else{

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['img_product_code'] = array();
        $_SESSION['img_product_code'] = $product_code;

        return $this->redirect(['action' => 'addimageform',
        's' => ['mess' => "※ファイルが読み込まれませんでした。"]]);

      }

      $selectfilename = $_FILES['upfile']["name"];
      $filename = str_replace(' ', '_', $selectfilename);

      if($selectfilename !== $filename){//半角スペースが含まれている場合はNG

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['img_product_code'] = array();
        $_SESSION['img_product_code'] = $product_code;

        return $this->redirect(['action' => 'addimageform',
        's' => ['mess' => "※ファイル名に半角スペースが含まれています。ファイル名に半角スペースを使用しないでください。"]]);
        }
        

        $selectfilename = $_FILES['upfile']["name"];
        $filename = str_replace('~', '_', $selectfilename);
  
        if($selectfilename !== $filename){//「~」が含まれている場合はNG
  
          if(!isset($_SESSION)){
            session_start();
          }
          $_SESSION['img_product_code'] = array();
          $_SESSION['img_product_code'] = $product_code;
  
          return $this->redirect(['action' => 'addimageform',
          's' => ['mess' => "※ファイル名に「~」が含まれています。ファイル名に「~」を使用しないでください。"]]);
          }
  
			$gif = "kensahyouimg/".$selectfilename;//ローカル
			$this->set('gif',$gif);

    }

    public function addformselect()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data = $this->request->query('s');

      if(isset($Data["product_code"])){

        $product_code = $Data["product_code"];
        $this->set('product_code', $product_code);
        $gif = $Data["gif"];
        $this->set('gif', $gif);

      }else{

        $data = $this->request->getData();
        $product_code = $data["product_code"];
        $this->set('product_code', $product_code);
        $gif = $data["gif"];
        $this->set('gif', $gif);

      }

      $arrTypes = [
        "int" => "数値",
        "judge" => "〇✕"
      ];
      $this->set('arrTypes', $arrTypes);

      $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($InspectionStandardSizeParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
       ->order(["version"=>"DESC"])->toArray();
   
      }

      $product_code_gif = $product_code."~".$gif;
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheaderpreadd($product_code_gif);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:');
      header('Cache-Control:');
      header('Pragma:');

      echo "<pre>";
      print_r("");
      echo "</pre>";
      
    }

    public function addform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $gif = $data["gif"];
      $this->set('gif', $gif);
/*
      $arrkensakigu = [
        "" => "",
        "デジタルノギス" => "デジタルノギス",
        "ルーペ" => "ルーペ",
        "テーパーゲージ" => "テーパーゲージ",
        "厚みゲージ" => "厚みゲージ",
        "金尺" => "金尺",
        "デジタル計り" => "デジタル計り"
      ];
*/

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $Staffs = $this->Staffs->find()
      ->where(['id' => $staff_id])
      ->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      $Kensakigus = $this->Kensakigus->find()
      ->where(['delete_flag' => 0, 'factory_id' => $factory_id])->toArray();
      $arrkensakigu = array();
      $arrkensakigu[""] = "";
      for($j=0; $j<count($Kensakigus); $j++){
        $arrkensakigu[$Kensakigus[$j]["name"]] = $Kensakigus[$j]["name"];
      }
      $this->set('arrkensakigu', $arrkensakigu);

      $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($InspectionStandardSizeParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
       ->order(["version"=>"DESC"])->toArray();
   
      }

      $product_code_gif = $product_code."~".$gif;
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheaderpreadd($product_code_gif);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:');
      header('Cache-Control:');
      header('Pragma:');

      echo "<pre>";
      print_r("");
      echo "</pre>";
      
    }

    public function addcomfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $gif = $data["gif"];
      $this->set('gif', $gif);

      $product_code_gif = $product_code."~".$gif;
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheaderpreadd($product_code_gif);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $formcheck = 0;
      $formcheckmess = 0;

      for($i=1; $i<=11; $i++){

        ${"inputtype".$i} = $data['inputtype'.$i];
        $this->set('inputtype'.$i,${"inputtype".$i});

        if(strlen($data['size_name'.$i]) > 0 && $data['inputtype'.$i] == "int"){
          ${"size_name".$i} = $data['size_name'.$i];
          $this->set('size_name'.$i,${"size_name".$i});

          if(strlen($data['upper_limit'.$i]) > 0 && strlen($data['lower_limit'.$i]) > 0
           && strlen($data['size'.$i]) > 0 && strlen($data['measuring_instrument'.$i]) > 0){

            $formcheck = 0;

            ${"size_name".$i} = $data['size_name'.$i];
            $this->set('size_name'.$i,${"size_name".$i});
            ${"upper_limit".$i} = sprintf("%.1f", $data['upper_limit'.$i]);
            $this->set('upper_limit'.$i,${"upper_limit".$i});
            ${"lower_limit".$i} = sprintf("%.1f", $data['lower_limit'.$i]);
            $this->set('lower_limit'.$i,${"lower_limit".$i});
            ${"size".$i} = sprintf("%.1f", $data['size'.$i]);
            $this->set('size'.$i,${"size".$i});
            ${"measuring_instrument".$i} = $data['measuring_instrument'.$i];
            $this->set('measuring_instrument'.$i,${"measuring_instrument".$i});

          }else{

            $formcheck = 1;
            $formcheckmess = $i."列目に入力漏れがあります。";
            $this->set('formcheckmess', $formcheckmess);

          }

        }elseif(strlen($data['size_name'.$i]) > 0 && $data['inputtype'.$i] == "judge"){

          ${"size_name".$i} = $data['size_name'.$i];
          $this->set('size_name'.$i,${"size_name".$i});
          ${"upper_limit".$i} = $data['upper_limit'.$i];
          $this->set('upper_limit'.$i,${"upper_limit".$i});
          ${"lower_limit".$i} = $data['lower_limit'.$i];
          $this->set('lower_limit'.$i,${"lower_limit".$i});
          ${"size".$i} = $data['size'.$i];
          $this->set('size'.$i,${"size".$i});
          ${"measuring_instrument".$i} = $data['measuring_instrument'.$i];
          $this->set('measuring_instrument'.$i,${"measuring_instrument".$i});

        }else{

          ${"size_name".$i} = "";
          $this->set('size_name'.$i,${"size_name".$i});
          ${"upper_limit".$i} = "";
          $this->set('upper_limit'.$i,${"upper_limit".$i});
          ${"lower_limit".$i} = "";
          $this->set('lower_limit'.$i,${"lower_limit".$i});
          ${"size".$i} = "";
          $this->set('size'.$i,${"size".$i});
          ${"measuring_instrument".$i} = "";
          $this->set('measuring_instrument'.$i,${"measuring_instrument".$i});

        }

      }
      $this->set('formcheck', $formcheck);

    }

    public function adddo()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $datasession = $session->read();
      $Users= $this->Users->find('all')->contain(["Staffs"])->where(['user_code' => $datasession['Auth']['User']['user_code'], 'Users.delete_flag' => 0])->toArray();
      $staff_id = $Users[0]["staff_id"];
      $this->set('staff_id', $staff_id);
      $user_code = $datasession['Auth']['User']['user_code'];
      $this->set('user_code', $user_code);

      $data = $this->request->getData();
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $gif = $data["gif"];
      $this->set('gif', $gif);

      $product_code_gif = $product_code."~".$gif;
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheaderpreadd($product_code_gif);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $Products = $this->Products->find()
      ->where(['product_code' => $data['product_code']])->toArray();
      $product_id = $Products[0]['id'];
      $product_name = $Products[0]["name"];
      $this->set('product_name',$product_name);

      $product_code_ini = substr($product_code, 0, 11);

      $InspectionStandardSizeParentversion = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.is_active' => 0,
       'InspectionStandardSizeParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(isset($InspectionStandardSizeParentversion[0])){
        $version = $InspectionStandardSizeParentversion[0]["version"] + 1;
      }else{
        $version = 1;
      }

      $code_date = date('y').date('m').date('d');
      $InspectionStandardSizeParentcodes = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code like' => $product_code_ini.'%', 'inspection_standard_size_code like' => $code_date."%"])
      ->toArray();

      $renban = count($InspectionStandardSizeParentcodes) + 1;
      $inspection_standard_size_code = $code_date."-".$renban;

      $arrtourokuinspectionStandardSizeParent = array();
      $arrtourokuinspectionStandardSizeParent = [
        'product_id' => $product_id,
        'image_file_name_dir' => $data["gif"],
        'inspection_standard_size_code' => $inspection_standard_size_code,
        'version' => $version,
        'is_active' => 0,
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokuinspectionStandardSizeParent);
      echo "</pre>";
*/
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4

        $InspectionStandardSizeParentversion = $this->InspectionStandardSizeParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.is_active' => 0,
         'InspectionStandardSizeParents.delete_flag' => 0])
        ->toArray();
  
        if(isset($InspectionStandardSizeParentversion[0])){
          $this->InspectionStandardSizeParents->updateAll(
            [ 'is_active' => 1,
              'delete_flag' => 1,
              'updated_at' => date('Y-m-d H:i:s'),
              'updated_staff' => $staff_id],
            ['id'  => $InspectionStandardSizeParentversion[0]["id"]]);
  
          }

        //新しいデータを登録
        $InspectionStandardSizeParents = $this->InspectionStandardSizeParents
        ->patchEntity($this->InspectionStandardSizeParents->newEntity(), $arrtourokuinspectionStandardSizeParent);

        if ($this->InspectionStandardSizeParents->save($InspectionStandardSizeParents)) {

          if(isset($InspectionStandardSizeParentversion[0])){
            $this->InspectionStandardSizeChildren->updateAll(
              [ 'delete_flag' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_staff' => $staff_id],
              ['inspection_standard_size_parent_id' => $InspectionStandardSizeParentversion[0]["id"]]);
            }
    
            $InspectionStandardSizeParentversion = $this->InspectionStandardSizeParents->find()->contain(["Products"])
            ->where(['product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.is_active' => 0,
             'InspectionStandardSizeParents.delete_flag' => 0])
            ->toArray();
    
          $tourokuInspectionStandardSizeChildren = array();
          $num_max = 0;
    
          for($i=1; $i<=11; $i++){
    
            if(strlen($data['size_name'.$i]) > 0 && $data['inputtype'.$i] == "int"){
    
              $tourokuInspectionStandardSizeChildren[] = [
                "inspection_standard_size_parent_id" => $InspectionStandardSizeParentversion[0]['id'],
                "input_type" => $data['inputtype'.$i],
                "size_name" => $data['size_name'.$i],
                "size_number" => $i,
                "size" => sprintf("%.1f", $data['size'.$i]),
                "upper_limit" => sprintf("%.1f", $data['upper_limit'.$i]),
                "lower_limit" => sprintf("%.1f", $data['lower_limit'.$i]),
                "measuring_instrument" => $data['measuring_instrument'.$i],
                "delete_flag" => 0,
                'created_at' => date("Y-m-d H:i:s"),
                "created_staff" => $staff_id
              ];
    
            }elseif(strlen($data['size_name'.$i]) > 0 && $data['inputtype'.$i] == "judge"){
    
              $tourokuInspectionStandardSizeChildren[] = [
                "inspection_standard_size_parent_id" => $InspectionStandardSizeParentversion[0]['id'],
                "input_type" => $data['inputtype'.$i],
                "size_name" => $data['size_name'.$i],
                "size_number" => $i,
                "size" => $data['size'.$i],
                "upper_limit" => $data['upper_limit'.$i],
                "lower_limit" => $data['lower_limit'.$i],
                "measuring_instrument" => $data['measuring_instrument'.$i],
                "delete_flag" => 0,
                'created_at' => date("Y-m-d H:i:s"),
                "created_staff" => $staff_id
              ];
    
            }elseif($num_max == 0){
              $num_max = $i;
            }
    
          }
    
          //長さの列を追加（idが必要なため取得しておく）
          $tourokuInspectionStandardSizeChildren[] = [
            "inspection_standard_size_parent_id" => $InspectionStandardSizeParentversion[0]['id'],
            "input_type" => "int",
            "size_name" => "長さ",
            "size_number" => $num_max,
            "size" => 0,
            "upper_limit" => 0,
            "lower_limit" => 0,
            "measuring_instrument" => "-",
            "delete_flag" => 0,
            'created_at' => date("Y-m-d H:i:s"),
            "created_staff" => $staff_id
          ];
    /*
          echo "<pre>";
          print_r($tourokuInspectionStandardSizeChildren);
          echo "</pre>";
    */
          $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren
          ->patchEntities($this->InspectionStandardSizeChildren->newEntity(), $tourokuInspectionStandardSizeChildren);
          if ($this->InspectionStandardSizeChildren->saveMany($InspectionStandardSizeChildren)) {

            $connection->commit();// コミット5
            $mes = "登録されました。";
            $this->set('mes',$mes);
  
          } else {
  
            $this->Flash->error(__('The data could not be saved. Please, try again.'));
            throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
            $mes = "※登録されませんでした";
            $this->set('mes',$mes);
  
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
       ->where(['Customers.name' => $data["customer_name"], 'Products.delete_flag' => 0])->toArray();

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

           return $this->redirect(['action' => 'kensakuhyouji',
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

    public function kensakuhyouji()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data = $this->request->query('s');
      $product_code = $Data["product_code"];
      $this->set('product_code', $product_code);

      $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($InspectionStandardSizeParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();
  
      }
      
      if(isset($InspectionStandardSizeParents[0])){

        $inspection_standard_size_parent_id = $InspectionStandardSizeParents[0]['id'];
        $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);

      }else{

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "「".$Products[0]["name"]."」は検査表親テーブルの登録がされていません。"]]);

      }

      $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
      ->contain(['InspectionStandardSizeParents' => ["Products"]])
      ->where(['product_code' => $product_code,
      'InspectionStandardSizeParents.is_active' => 0,
      'InspectionStandardSizeParents.delete_flag' => 0,
      'InspectionStandardSizeChildren.delete_flag' => 0])
      ->order(["InspectionStandardSizeParents.version"=>"DESC"])->toArray();

      if(!isset($InspectionStandardSizeChildren[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
        ->contain(['InspectionStandardSizeParents' => ["Products"]])
        ->where(['product_code like' => $product_code_ini.'%',
        'InspectionStandardSizeParents.is_active' => 0,
        'InspectionStandardSizeParents.delete_flag' => 0,
        'InspectionStandardSizeChildren.delete_flag' => 0])
        ->order(["InspectionStandardSizeParents.version"=>"DESC"])->toArray();
  
      }

      if(isset($InspectionStandardSizeChildren[0])){

        for($i=1; $i<=11; $i++){

          ${"size_name".$i} = "";
          $this->set('size_name'.$i,${"size_name".$i});
          ${"upper_limit".$i} = "";
          $this->set('upper_limit'.$i,${"upper_limit".$i});
          ${"lower_limit".$i} = "";
          $this->set('lower_limit'.$i,${"lower_limit".$i});
          ${"size".$i} = "";
          $this->set('size'.$i,${"size".$i});
          ${"measuring_instrument".$i} = "";
          $this->set('measuring_instrument'.$i,${"measuring_instrument".$i});
          ${"input_type".$i} = "int";
          $this->set('input_type'.$i,${"input_type".$i});

        }

        for($i=0; $i<count($InspectionStandardSizeChildren) - 1; $i++){

          $num = $InspectionStandardSizeChildren[$i]["size_number"];
          ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
          $this->set('size_name'.$num,${"size_name".$num});
          ${"input_type".$num} = $InspectionStandardSizeChildren[$i]["input_type"];
          $this->set('input_type'.$num,${"input_type".$num});
          ${"upper_limit".$num} = $InspectionStandardSizeChildren[$i]["upper_limit"];
          $this->set('upper_limit'.$num,${"upper_limit".$num});
          ${"lower_limit".$num} = $InspectionStandardSizeChildren[$i]["lower_limit"];
          $this->set('lower_limit'.$num,${"lower_limit".$num});
          ${"size".$num} = $InspectionStandardSizeChildren[$i]["size"];
          $this->set('size'.$num,${"size".$num});
          ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
          $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});

        }

        $this->set('InspectionStandardSizeChildren', $InspectionStandardSizeChildren);

      }else{

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "「".$Products[0]["name"]."」は規格登録がされていません。"]]);

      }

      echo "<pre>";
      print_r("");
      echo "</pre>";
 
    }

    public function editpre()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
      
      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $inspection_standard_size_parent_id = $data["inspection_standard_size_parent_id"];
      $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);

      if(isset($data["change"])){

        return $this->redirect(['action' => 'editimageform',
        's' => ['change_flag' => 1, 'product_code' => $product_code,
         'inspection_standard_size_parent_id' => $inspection_standard_size_parent_id]]);

      }elseif(isset($data["nochange"])){

        return $this->redirect(['action' => 'editformselect',
        's' => ['change_flag' => 0, 'product_code' => $product_code,
         'inspection_standard_size_parent_id' => $inspection_standard_size_parent_id]]);

      }
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      echo "<pre>";
      print_r("");
      echo "</pre>";
 
    }

    public function editimageform()
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);

      $Data = $this->request->query('s');
      $mes = "";
      $this->set('mes',$mes);
      $mess = "";
      $this->set('mess',$mess);
/*
      echo "<pre>";
      print_r($Data);
      echo "</pre>";
*/
      $change_flag = $Data["change_flag"];
      $this->set('change_flag', $change_flag);
      $product_code = $Data["product_code"];
      $this->set('product_code', $product_code);
      $inspection_standard_size_parent_id = $Data["inspection_standard_size_parent_id"];
      $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);

      $ProductNs = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $product_name = $ProductNs[0]["name"];
      $this->set('product_name',$product_name);

      echo "<pre>";
      print_r("");
      echo "</pre>";
 
    }

    public function editimagecomfirm()
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);

      $data = $this->request->getData();

      $change_flag = $data["change_flag"];
      $this->set('change_flag', $change_flag);
      $inspection_standard_size_parent_id = $data["inspection_standard_size_parent_id"];
      $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);
      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      
      $ProductNs = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $product_name = $ProductNs[0]["name"];
      $this->set('product_name',$product_name);

      if(!isset($_SESSION)){
        session_start();
      }
      $_SESSION['img_product_code'] = array();
      $_SESSION['img_product_code'] = $product_code;

      $fileName = $_FILES['upfile']['tmp_name'];

      if(substr($_FILES['upfile']["name"], -4) !== ".JPG"
      && substr($_FILES['upfile']["name"], -4) !== ".jpg"
      && substr($_FILES['upfile']["name"], -5) !== ".JPEG"
      && substr($_FILES['upfile']["name"], -5) !== ".jpeg"){

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['img_product_code'] = array();
        $_SESSION['img_product_code'] = $product_code;

        return $this->redirect(['action' => 'editimageform',
        's' => ['mess' => "※拡張子が「.JPG」でないファイルが選択されました。"]]);

      }
/*
      echo "<pre>";
      print_r($_FILES);
      echo "</pre>";
*/
      if($_FILES['upfile']['error'] == 0){

        if($_FILES['upfile']['size']>2000000){

          if(!isset($_SESSION)){
            session_start();
          }
          $_SESSION['img_product_code'] = array();
          $_SESSION['img_product_code'] = $product_code;

          return $this->redirect(['action' => 'editimageform',
          's' => ['mess' => "※画像サイズが大き過ぎます（アップロードできるサイズの上限は２MBです）"]]);

        }else{

          if(move_uploaded_file($_FILES['upfile']["tmp_name"],"img/kensahyouimg/".$_FILES['upfile']["name"])){

          }else{
      
            if(!isset($_SESSION)){
              session_start();
            }
            $_SESSION['img_product_code'] = array();
            $_SESSION['img_product_code'] = $product_code;
  
            return $this->redirect(['action' => 'editimageform',
            's' => ['mess' => "※ファイルが読み込まれませんでした。"]]);
      
          }

        }

      }elseif($_FILES['upfile']['error'] == 1){

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['img_product_code'] = array();
        $_SESSION['img_product_code'] = $product_code;

        return $this->redirect(['action' => 'editimageform',
        's' => ['mess' => "※画像サイズが大き過ぎます（アップロードできるサイズの上限は２MBです）"]]);
        
      }else{

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['img_product_code'] = array();
        $_SESSION['img_product_code'] = $product_code;

        return $this->redirect(['action' => 'editimageform',
        's' => ['mess' => "※ファイルが読み込まれませんでした。"]]);

      }

      $selectfilename = $_FILES['upfile']["name"];
      $filename = str_replace(' ', '_', $selectfilename);

      if($selectfilename !== $filename){//半角スペースが含まれている場合はNG

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['img_product_code'] = array();
        $_SESSION['img_product_code'] = $product_code;

        return $this->redirect(['action' => 'editimageform',
        's' => ['mess' => "※ファイル名に半角スペースが含まれています。ファイル名に半角スペースを使用しないでください。"]]);
        }
        
			$gif = "kensahyouimg/".$selectfilename;//ローカル
			$this->set('gif',$gif);

    }

    public function editlogin()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $inspection_standard_size_parent_id = $data["inspection_standard_size_parent_id"];
      $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);

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

    public function editformselect()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data = $this->request->query('s');

      if(isset($Data["change_flag"])){

        $change_flag = $Data["change_flag"];
        $this->set('change_flag', $change_flag);
        $product_code = $Data["product_code"];
        $this->set('product_code', $product_code);
        $inspection_standard_size_parent_id = $Data["inspection_standard_size_parent_id"];
        $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);

        $InspectionStandardSizeParentversion = $this->InspectionStandardSizeParents->find()
        ->where(['id' => $inspection_standard_size_parent_id])
        ->toArray();

        $gif = $InspectionStandardSizeParentversion[0]["image_file_name_dir"];
        $this->set('gif', $gif);

      }else{
        $data = $this->request->getData();

        $change_flag = $data["change_flag"];
        $this->set('change_flag', $change_flag);
        $product_code = $data["product_code"];
        $this->set('product_code', $product_code);
        $gif = $data["gif"];
        $this->set('gif', $gif);
        $inspection_standard_size_parent_id = $data["inspection_standard_size_parent_id"];
        $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);
      }

      $session = $this->request->getSession();
      $datasession = $session->read();
      $Users= $this->Users->find('all')->contain(["Staffs"])->where(['user_code' => $datasession['Auth']['User']['user_code'], 'Users.delete_flag' => 0])->toArray();
      $user_code = $datasession['Auth']['User']['user_code'];
      $this->set('user_code', $user_code);
      $staff_id = $Users[0]["staff_id"];
      $this->set('staff_id', $staff_id);
      $staff_name= $Users[0]["staff"]["name"];
      $this->set('staff_name', $staff_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $Staffs = $this->Staffs->find()
      ->where(['id' => $staff_id])
      ->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      $Kensakigus = $this->Kensakigus->find()
      ->where(['delete_flag' => 0, 'factory_id' => $factory_id])->toArray();
      $arrkensakigu = array();
      $arrkensakigu[""] = "";
      for($j=0; $j<count($Kensakigus); $j++){
        $arrkensakigu[$Kensakigus[$j]["name"]] = $Kensakigus[$j]["name"];
      }
      $this->set('arrkensakigu', $arrkensakigu);

      $arrTypes = [
        "int" => "数値",
        "judge" => "〇✕"
      ];
      $this->set('arrTypes', $arrTypes);

      $product_code_gif = $product_code."~".$gif;
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheaderpreadd($product_code_gif);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
      ->contain(['InspectionStandardSizeParents' => ["Products"]])
      ->where(['product_code' => $product_code,
      'InspectionStandardSizeParents.is_active' => 0,
      'InspectionStandardSizeParents.delete_flag' => 0,
      'InspectionStandardSizeChildren.delete_flag' => 0])
      ->order(["InspectionStandardSizeParents.version"=>"DESC"])->toArray();

      if(!isset($InspectionStandardSizeChildren[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
        ->contain(['InspectionStandardSizeParents' => ["Products"]])
        ->where(['product_code like' => $product_code_ini.'%',
        'InspectionStandardSizeParents.is_active' => 0,
        'InspectionStandardSizeParents.delete_flag' => 0,
        'InspectionStandardSizeChildren.delete_flag' => 0])
        ->order(["InspectionStandardSizeParents.version"=>"DESC"])->toArray();
  
      }

        for($i=1; $i<=11; $i++){

          ${"id".$i} = "";
          $this->set('id'.$i,${"id".$i});
          ${"input_type".$i} = "";
          $this->set('input_type'.$i,${"input_type".$i});
          ${"size_name".$i} = "";
          $this->set('size_name'.$i,${"size_name".$i});
          ${"upper_limit".$i} = "";
          $this->set('upper_limit'.$i,${"upper_limit".$i});
          ${"lower_limit".$i} = "";
          $this->set('lower_limit'.$i,${"lower_limit".$i});
          ${"size".$i} = "";
          $this->set('size'.$i,${"size".$i});
          ${"measuring_instrument".$i} = "";
          $this->set('measuring_instrument'.$i,${"measuring_instrument".$i});
          ${"input_type".$i} = "int";
          $this->set('input_type'.$i,${"input_type".$i});

        }

        for($i=0; $i<count($InspectionStandardSizeChildren) - 1; $i++){//長さのデータを表示させないため

          $num = $InspectionStandardSizeChildren[$i]["size_number"];

          ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
          $this->set('size_name'.$num,${"size_name".$num});
          ${"input_type".$num} = $InspectionStandardSizeChildren[$i]["input_type"];
          $this->set('input_type'.$num,${"input_type".$num});
          ${"input_type".$num} = $InspectionStandardSizeChildren[$i]["input_type"];
          $this->set('input_type'.$num,${"input_type".$num});
          ${"upper_limit".$num} = $InspectionStandardSizeChildren[$i]["upper_limit"];
          $this->set('upper_limit'.$num,${"upper_limit".$num});
          ${"lower_limit".$num} = $InspectionStandardSizeChildren[$i]["lower_limit"];
          $this->set('lower_limit'.$num,${"lower_limit".$num});
          ${"size".$num} = $InspectionStandardSizeChildren[$i]["size"];
          $this->set('size'.$num,${"size".$num});
          ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
          $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});

        }

        for($i=0; $i<count($InspectionStandardSizeChildren); $i++){

          $num = $InspectionStandardSizeChildren[$i]["size_number"];

          ${"id".$num} = $InspectionStandardSizeChildren[$i]["id"];//長さの行も更新するため
          $this->set('id'.$num,${"id".$num});

        }

        $this->set('InspectionStandardSizeChildren', $InspectionStandardSizeChildren);

        echo "<pre>";
        print_r("");
        echo "</pre>";
   
    }

    public function editform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $change_flag = $data["change_flag"];
      $this->set('change_flag', $change_flag);
      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $gif = $data["gif"];
      $this->set('gif', $gif);
      $inspection_standard_size_parent_id = $data["inspection_standard_size_parent_id"];
      $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);

      $session = $this->request->getSession();
      $datasession = $session->read();
      $Users= $this->Users->find('all')->contain(["Staffs"])->where(['user_code' => $datasession['Auth']['User']['user_code'], 'Users.delete_flag' => 0])->toArray();
      $user_code = $datasession['Auth']['User']['user_code'];
      $this->set('user_code', $user_code);
      $staff_id = $Users[0]["staff_id"];
      $this->set('staff_id', $staff_id);
      $staff_name= $Users[0]["staff"]["name"];
      $this->set('staff_name', $staff_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $Staffs = $this->Staffs->find()
      ->where(['id' => $staff_id])
      ->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      $Kensakigus = $this->Kensakigus->find()
      ->where(['delete_flag' => 0, 'factory_id' => $factory_id])->toArray();
      $arrkensakigu = array();
      $arrkensakigu[""] = "";
      for($j=0; $j<count($Kensakigus); $j++){
        $arrkensakigu[$Kensakigus[$j]["name"]] = $Kensakigus[$j]["name"];
      }
      $this->set('arrkensakigu', $arrkensakigu);

      $product_code_gif = $product_code."~".$gif;
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheaderpreadd($product_code_gif);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
      ->contain(['InspectionStandardSizeParents' => ["Products"]])
      ->where(['product_code' => $product_code,
      'InspectionStandardSizeParents.is_active' => 0,
      'InspectionStandardSizeParents.delete_flag' => 0,
      'InspectionStandardSizeChildren.delete_flag' => 0])
      ->order(["InspectionStandardSizeParents.version"=>"DESC"])->toArray();

      if(!isset($InspectionStandardSizeChildren[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
        ->contain(['InspectionStandardSizeParents' => ["Products"]])
        ->where(['product_code like' => $product_code_ini.'%',
        'InspectionStandardSizeParents.is_active' => 0,
        'InspectionStandardSizeParents.delete_flag' => 0,
        'InspectionStandardSizeChildren.delete_flag' => 0])
        ->order(["InspectionStandardSizeParents.version"=>"DESC"])->toArray();
  
      }

        for($i=1; $i<=11; $i++){

          ${"id".$i} = "";
          $this->set('id'.$i,${"id".$i});
          ${"input_type".$i} = "";
          $this->set('input_type'.$i,${"input_type".$i});
          ${"size_name".$i} = "";
          $this->set('size_name'.$i,${"size_name".$i});
          ${"upper_limit".$i} = "";
          $this->set('upper_limit'.$i,${"upper_limit".$i});
          ${"lower_limit".$i} = "";
          $this->set('lower_limit'.$i,${"lower_limit".$i});
          ${"size".$i} = "";
          $this->set('size'.$i,${"size".$i});
          ${"measuring_instrument".$i} = "";
          $this->set('measuring_instrument'.$i,${"measuring_instrument".$i});
          ${"input_type".$i} = "";
          $this->set('input_type'.$i,${"input_type".$i});

        }

        for($i=0; $i<count($InspectionStandardSizeChildren) - 1; $i++){//長さのデータを表示させないため

          $num = $InspectionStandardSizeChildren[$i]["size_number"];

          ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
          $this->set('size_name'.$num,${"size_name".$num});
          ${"upper_limit".$num} = $InspectionStandardSizeChildren[$i]["upper_limit"];
          $this->set('upper_limit'.$num,${"upper_limit".$num});
          ${"lower_limit".$num} = $InspectionStandardSizeChildren[$i]["lower_limit"];
          $this->set('lower_limit'.$num,${"lower_limit".$num});
          ${"size".$num} = $InspectionStandardSizeChildren[$i]["size"];
          $this->set('size'.$num,${"size".$num});
          ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
          $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});

        }

        for($i=0; $i<count($InspectionStandardSizeChildren); $i++){

          $num = $InspectionStandardSizeChildren[$i]["size_number"];

          ${"id".$num} = $InspectionStandardSizeChildren[$i]["id"];//長さの行も更新するため
          $this->set('id'.$num,${"id".$num});

        }

        $this->set('InspectionStandardSizeChildren', $InspectionStandardSizeChildren);

        for($i=1; $i<=11; $i++){
          ${"inputtype".$i} = $data['inputtype'.$i];
          $this->set('inputtype'.$i,${"inputtype".$i});
        }

        echo "<pre>";
        print_r("");
        echo "</pre>";
   
    }

    public function editcomfirm()
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

      $change_flag = $data["change_flag"];
      $this->set('change_flag', $change_flag);
      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $gif = $data["gif"];
      $this->set('gif', $gif);
      $inspection_standard_size_parent_id = $data["inspection_standard_size_parent_id"];
      $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);

      $product_code_gif = $product_code."~".$gif;
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheaderpreadd($product_code_gif);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $formcheck = 0;
      $formcheckmess = 0;

      for($i=1; $i<=11; $i++){

        if(strlen($data['size_name'.$i]) > 0 && $data['inputtype'.$i] == "int"){
          ${"size_name".$i} = $data['size_name'.$i];

          if(strlen($data['upper_limit'.$i]) > 0 && strlen($data['lower_limit'.$i]) > 0
           && strlen($data['size'.$i]) > 0 && strlen($data['measuring_instrument'.$i]) > 0){

            $formcheck = 0;

            ${"size_name".$i} = $data['size_name'.$i];
            $this->set('size_name'.$i,${"size_name".$i});
            ${"upper_limit".$i} = sprintf("%.1f", $data['upper_limit'.$i]);
            $this->set('upper_limit'.$i,${"upper_limit".$i});
            ${"lower_limit".$i} = sprintf("%.1f", $data['lower_limit'.$i]);
            $this->set('lower_limit'.$i,${"lower_limit".$i});
            ${"size".$i} = sprintf("%.1f", $data['size'.$i]);
            $this->set('size'.$i,${"size".$i});
            ${"measuring_instrument".$i} = $data['measuring_instrument'.$i];
            $this->set('measuring_instrument'.$i,${"measuring_instrument".$i});

          }else{

            $formcheck = 1;
            $formcheckmess = $i."番目に入力漏れがあります。";
            $this->set('formcheckmess', $formcheckmess);

          }

        }elseif(strlen($data['size_name'.$i]) > 0 && $data['inputtype'.$i] == "judge"){

          ${"size_name".$i} = $data['size_name'.$i];
          $this->set('size_name'.$i,${"size_name".$i});
          ${"upper_limit".$i} = $data['upper_limit'.$i];
          $this->set('upper_limit'.$i,${"upper_limit".$i});
          ${"lower_limit".$i} = $data['lower_limit'.$i];
          $this->set('lower_limit'.$i,${"lower_limit".$i});
          ${"size".$i} = $data['size'.$i];
          $this->set('size'.$i,${"size".$i});
          ${"measuring_instrument".$i} = $data['measuring_instrument'.$i];
          $this->set('measuring_instrument'.$i,${"measuring_instrument".$i});

        }else{

          ${"size_name".$i} = "";
          $this->set('size_name'.$i,${"size_name".$i});
          ${"upper_limit".$i} = "";
          $this->set('upper_limit'.$i,${"upper_limit".$i});
          ${"lower_limit".$i} = "";
          $this->set('lower_limit'.$i,${"lower_limit".$i});
          ${"size".$i} = "";
          $this->set('size'.$i,${"size".$i});
          ${"measuring_instrument".$i} = "";
          $this->set('measuring_instrument'.$i,${"measuring_instrument".$i});

        }

      }
      $this->set('formcheck', $formcheck);

      $mes = "上記のように更新します。よろしければ決定ボタンを押してください。";
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
      $change_flag = $data["change_flag"];
      $this->set('change_flag', $change_flag);
      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $inspection_standard_size_parent_id = $data["inspection_standard_size_parent_id"];
      $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);
      $gif = $data["gif"];
      $this->set('gif', $gif);

      $Products= $this->Products->find()->contain(["Customers"])->where(['product_code' => $product_code])->toArray();
      $product_id = $Products[0]["id"];

      $product_code_gif = $product_code."~".$gif;
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheaderpreadd($product_code_gif);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $datetimenow = date('Y-m-d H:i:s');

      if($change_flag == 100){//画像を変更しないない場合//このときも更新した方が履歴をおいやすい
/*
        echo "<pre>";
        print_r("no");
        echo "</pre>";
  */
        //新しいデータを登録
            $connection = ConnectionManager::get('default');//トランザクション1
            // トランザクション開始2
            $connection->begin();//トランザクション3
            try {//トランザクション4

              $num_max = 0;

              for($i=1; $i<=11; $i++){

                $updateInspectionStandardSizeChildren = array();
      
                if(strlen($data['size_name'.$i]) > 0 && $data['inputtype'.$i] == "int"){
    
                  $num_max = $num_max + 1;

                  $updateInspectionStandardSizeChildren = [
                    "inspection_standard_size_parent_id" => $InspectionStandardSizeParentversion[0]['id'],
                    "input_type" => $data['inputtype'.$i],
                    "size_name" => $data['size_name'.$i],
                    "size_number" => $i,
                    "size" => sprintf("%.1f", $data['size'.$i]),
                    "upper_limit" => sprintf("%.1f", $data['upper_limit'.$i]),
                    "lower_limit" => sprintf("%.1f", $data['lower_limit'.$i]),
                    "measuring_instrument" => $data['measuring_instrument'.$i],
                    "delete_flag" => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    "created_staff" => $staff_id
                  ];
        
                  $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren
                  ->patchEntity($this->InspectionStandardSizeChildren->newEntity(), $updateInspectionStandardSizeChildren);
                  $this->InspectionStandardSizeChildren->save($InspectionStandardSizeChildren);
    
                  $updateInspectionStandardSizeChildren = array();
  
                }elseif(strlen($data['size_name'.$i]) > 0 && $data['inputtype'.$i] == "judge"){
        
                  $num_max = $num_max + 1;

                  $updateInspectionStandardSizeChildren = [
                    "inspection_standard_size_parent_id" => $InspectionStandardSizeParentversion[0]['id'],
                    "input_type" => $data['inputtype'.$i],
                    "size_name" => $data['size_name'.$i],
                    "size_number" => $i,
                    "size" => $data['size'.$i],
                    "upper_limit" => $data['upper_limit'.$i],
                    "lower_limit" => $data['lower_limit'.$i],
                    "measuring_instrument" => $data['measuring_instrument'.$i],
                    "delete_flag" => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    "created_staff" => $staff_id
                  ];
        
                  $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren
                  ->patchEntity($this->InspectionStandardSizeChildren->newEntity(), $updateInspectionStandardSizeChildren);
                  $this->InspectionStandardSizeChildren->save($InspectionStandardSizeChildren);
    
                  $updateInspectionStandardSizeChildren = array();
  
                }elseif($num_max == 0){
                  $num_max = $i;
                }
/*                
                if(strlen($data['size_name'.$i]) > 0){

                  $num_max = $num_max + 1;

                  $updateInspectionStandardSizeChildren = [
                    "inspection_standard_size_parent_id" => $data['inspection_standard_size_parent_id'],
                    "input_type" => $data['inputtype'.$i],
                    "size_name" => $data['size_name'.$i],
                    "size_number" => $i,
                    "size" => $data['size'.$i],
                    "upper_limit" => $data['upper_limit'.$i],
                    "lower_limit" => $data['lower_limit'.$i],
                    "measuring_instrument" => $data['measuring_instrument'.$i],
                    "delete_flag" => 0,
                    'created_at' => $datetimenow,
                    "created_staff" => $staff_id
                  ];
      
                $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren
                ->patchEntity($this->InspectionStandardSizeChildren->newEntity(), $updateInspectionStandardSizeChildren);
                $this->InspectionStandardSizeChildren->save($InspectionStandardSizeChildren);
  
                $updateInspectionStandardSizeChildren = array();

                }elseif($num_max == 0){
                  $num_max = $i;
                }
  */
                if(strlen($data['id'.$i]) > 0){

                  $this->InspectionStandardSizeChildren->updateAll(
                    [ 'delete_flag' => 1,
                      'updated_at' => $datetimenow,
                      'updated_staff' => $staff_id],
                    ['id'  => $data['id'.$i]]);

                  }

            }

            //長さの登録
            $updateInspectionStandardSizeChildren = [
              "inspection_standard_size_parent_id" => $data['inspection_standard_size_parent_id'],
              "input_type" => "int",
              "size_name" => "長さ",
              "size_number" => $num_max,
              "size" => 0,
              "upper_limit" => 0,
              "lower_limit" => 0,
              "measuring_instrument" => "-",
              "delete_flag" => 0,
              'created_at' => $datetimenow,
              "created_staff" => $staff_id
            ];

          $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren
          ->patchEntity($this->InspectionStandardSizeChildren->newEntity(), $updateInspectionStandardSizeChildren);
          if($this->InspectionStandardSizeChildren->save($InspectionStandardSizeChildren)){

                $connection->commit();// コミット5
                $mes = "更新されました。";
                $this->set('mes',$mes);

              } else {

                $this->Flash->error(__('The data could not be saved. Please, try again.'));
                throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
                $mes = "※更新されませんでした";
                $this->set('mes',$mes);

              }

            } catch (Exception $e) {//トランザクション7
            //ロールバック8
              $connection->rollback();//トランザクション9
            }//トランザクション10

      }elseif($change_flag < 2){////画像変更の場合

        //新しいデータを登録
        $connection = ConnectionManager::get('default');//トランザクション1
        // トランザクション開始2
        $connection->begin();//トランザクション3
        try {//トランザクション4

          $product_code_ini = substr($product_code, 0, 11);
          $InspectionStandardSizeParentversion = $this->InspectionStandardSizeParents->find()->contain(["Products"])
          ->where(['product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
          ->order(["version"=>"DESC"])->toArray();
    
          if(isset($InspectionStandardSizeParentversion[0])){
            $version = $InspectionStandardSizeParentversion[0]["version"] + 1;
          }else{
            $version = 1;
          }
    
          $InspectionStandardSizeParentsmoto = $this->InspectionStandardSizeParents->find()
          ->where(['id' => $data['inspection_standard_size_parent_id']])->toArray();
    
          $arrupdateInspectionStandardSizeParentsmoto = [
            'product_id' => $InspectionStandardSizeParentsmoto[0]["product_id"],
            'image_file_name_dir' => $InspectionStandardSizeParentsmoto[0]["image_file_name_dir"],
            'inspection_standard_size_code' => $InspectionStandardSizeParentsmoto[0]["inspection_standard_size_code"],
            'version' => $InspectionStandardSizeParentsmoto[0]["version"],
            'is_active' => 1,
            'delete_flag' => 1,
            'created_at' => $InspectionStandardSizeParentsmoto[0]["created_at"]->format("Y-m-d H:i:s"),
            'created_staff' => $InspectionStandardSizeParentsmoto[0]["created_staff"],
            'updated_at' => $datetimenow,
            'updated_staff' => $staff_id
              ];
    
          $code_date = date('y').date('m').date('d');
          $InspectionStandardSizeParentcodes = $this->InspectionStandardSizeParents->find()->contain(["Products"])
          ->where(['product_code like' => $product_code_ini.'%', 'inspection_standard_size_code like' => $code_date."%"])
          ->toArray();
  
          $renban = count($InspectionStandardSizeParentcodes) + 1;
          $inspection_standard_size_code = $code_date."-".$renban;

          $arrtourokuinspectionStandardSizeParent = array();
          $arrtourokuinspectionStandardSizeParent = [
            'product_id' => $product_id,
            'image_file_name_dir' => $data["gif"],
            'inspection_standard_size_code' => $inspection_standard_size_code,
            'version' => $version,
            'is_active' => 0,
            'delete_flag' => 0,
            'created_at' => $datetimenow,
            'created_staff' => $staff_id
          ];
              
          if ($this->InspectionStandardSizeParents->updateAll(
            [ 'image_file_name_dir' => $arrtourokuinspectionStandardSizeParent["image_file_name_dir"],
            'inspection_standard_size_code' => $arrtourokuinspectionStandardSizeParent["inspection_standard_size_code"],
            'version' => $arrtourokuinspectionStandardSizeParent["version"],
            'updated_at' => $datetimenow,
            'updated_staff' => $staff_id],
            ['id'  => $inspection_standard_size_parent_id])){
        
            //新しいデータを登録
            $InspectionStandardSizeParents = $this->InspectionStandardSizeParents
            ->patchEntity($this->InspectionStandardSizeParents->newEntity(), $arrupdateInspectionStandardSizeParentsmoto);
            if ($this->InspectionStandardSizeParents->save($InspectionStandardSizeParents)){
          
              $num_max = 0;

              for($i=1; $i<=11; $i++){

                $updateInspectionStandardSizeChildren = array();
      
                if(strlen($data['size_name'.$i]) > 0 && $data['inputtype'.$i] == "int"){
    
        //          $num_max = $num_max + 1;

                  $updateInspectionStandardSizeChildren = [
                    "inspection_standard_size_parent_id" => $InspectionStandardSizeParentversion[0]['id'],
                    "input_type" => $data['inputtype'.$i],
                    "size_name" => $data['size_name'.$i],
                    "size_number" => $i,
                    "size" => sprintf("%.1f", $data['size'.$i]),
                    "upper_limit" => sprintf("%.1f", $data['upper_limit'.$i]),
                    "lower_limit" => sprintf("%.1f", $data['lower_limit'.$i]),
                    "measuring_instrument" => $data['measuring_instrument'.$i],
                    "delete_flag" => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    "created_staff" => $staff_id
                  ];
          
                  $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren
                  ->patchEntity($this->InspectionStandardSizeChildren->newEntity(), $updateInspectionStandardSizeChildren);
                  $this->InspectionStandardSizeChildren->save($InspectionStandardSizeChildren);
    
                  $updateInspectionStandardSizeChildren = array();
  
                }elseif(strlen($data['size_name'.$i]) > 0 && $data['inputtype'.$i] == "judge"){
        
        //          $num_max = $num_max + 1;

                  $updateInspectionStandardSizeChildren = [
                    "inspection_standard_size_parent_id" => $InspectionStandardSizeParentversion[0]['id'],
                    "input_type" => $data['inputtype'.$i],
                    "size_name" => $data['size_name'.$i],
                    "size_number" => $i,
                    "size" => $data['size'.$i],
                    "upper_limit" => $data['upper_limit'.$i],
                    "lower_limit" => $data['lower_limit'.$i],
                    "measuring_instrument" => $data['measuring_instrument'.$i],
                    "delete_flag" => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    "created_staff" => $staff_id
                  ];
        
                  $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren
                  ->patchEntity($this->InspectionStandardSizeChildren->newEntity(), $updateInspectionStandardSizeChildren);
                  $this->InspectionStandardSizeChildren->save($InspectionStandardSizeChildren);
    
                  $updateInspectionStandardSizeChildren = array();
  
                }elseif($num_max == 0){
                  $num_max = $i;
                }

                if(strlen($data['id'.$i]) > 0){
    
                  $this->InspectionStandardSizeChildren->updateAll(
                    [ 'delete_flag' => 1,
                      'updated_at' => $datetimenow,
                      'updated_staff' => $staff_id],
                    ['id'  => $data['id'.$i]]);
    
                  }
    
            }
    
            //長さの登録
            $updateInspectionStandardSizeChildren = [
              "inspection_standard_size_parent_id" => $data['inspection_standard_size_parent_id'],
              "input_type" => "int",
              "size_name" => "長さ",
              "size_number" => $num_max,
              "size" => 0,
              "upper_limit" => 0,
              "lower_limit" => 0,
              "measuring_instrument" => "-",
              "delete_flag" => 0,
              'created_at' => $datetimenow,
              "created_staff" => $staff_id
            ];
    
          } else {
  
            $mes = "※更新されませんでした";
            $this->set('mes',$mes);
            $this->Flash->error(__('The data could not be saved. Please, try again.'));
            throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
  
          }

          $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren
          ->patchEntity($this->InspectionStandardSizeChildren->newEntity(), $updateInspectionStandardSizeChildren);
          if($this->InspectionStandardSizeChildren->save($InspectionStandardSizeChildren)){
    
                $connection->commit();// コミット5
                $mes = "更新されました。";
                $this->set('mes',$mes);
    
              } else {
    
                $this->Flash->error(__('The data could not be saved. Please, try again.'));
                throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
                $mes = "※更新されませんでした";
                $this->set('mes',$mes);
    
              }
      
          } else {
  
            $mes = "※更新されませんでした";
            $this->set('mes',$mes);
            $this->Flash->error(__('The data could not be saved. Please, try again.'));
            throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
  
          }

        } catch (Exception $e) {//トランザクション7
          //ロールバック8
            $connection->rollback();//トランザクション9
          }//トランザクション10

      }else{////削除

        for($i=1; $i<=11; $i++){

          if(strlen($data['id'.$i]) > 0){

            $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren
            ->patchEntity($this->InspectionStandardSizeChildren->newEntity(), $data);
            $connection = ConnectionManager::get('default');//トランザクション1
            // トランザクション開始2
            $connection->begin();//トランザクション3
            try {//トランザクション4
              if ($this->InspectionStandardSizeChildren->updateAll(
                [ 'delete_flag' => 1,
                  'updated_at' => $datetimenow,
                  'updated_staff' => $staff_id],
                ['id'  => $data['id'.$i]])
              ) {

                $connection->commit();// コミット5
                $mes = "削除されました。";
                $this->set('mes',$mes);

              } else {

                $this->Flash->error(__('The data could not be saved. Please, try again.'));
                throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
                $mes = "※削除されませんでした";
                $this->set('mes',$mes);

              }

            } catch (Exception $e) {//トランザクション7
            //ロールバック8
              $connection->rollback();//トランザクション9
            }//トランザクション10

          }

      }

    }

  }


}
