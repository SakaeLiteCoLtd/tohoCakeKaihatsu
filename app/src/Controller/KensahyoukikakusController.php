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

class KensahyoukikakusController extends AppController
{

  	public function beforeFilter(Event $event){
  		parent::beforeFilter($event);

  		// 認証なしでアクセスできるアクションの指定
  		$this->Auth->allow(["addlogin", "addformpre", "addform", "addcomfirm", "adddo"
      ,"kensakupre", "kensakuhyouji", "editlogin", "editform", "editcomfirm", "editdo"]);
  	}

      public function initialize()
    {
     parent::initialize();
     $this->Users = TableRegistry::get('Users');
     $this->Staffs = TableRegistry::get('Staffs');
     $this->Products = TableRegistry::get('Products');
     $this->Customers = TableRegistry::get('Customers');
     $this->InspectionStandardSizeChildren = TableRegistry::get('InspectionStandardSizeChildren');
     $this->InspectionStandardSizeParents = TableRegistry::get('InspectionStandardSizeParents');

     if(!isset($_SESSION)){//フォーム再送信の確認対策//戻りたい画面でわざとwarningを出しておけば戻れる
       session_start();
     }
     header('Expires:');
     header('Cache-Control:');
     header('Pragma:');

     echo "<pre>";//フォームの再読み込みの防止
     print_r("  ");
     echo "</pre>";

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

      $htmlinputstaff = new htmlLogin();//クラスを使用
      $arraylogindate = $htmlinputstaff->inputstaffprogram($user_code);//クラスを使用

      if($arraylogindate[0] === "no_staff"){

        return $this->redirect(['action' => 'addlogin',
        's' => ['mess' => "社員コードが存在しません。もう一度やり直してください。"]]);

      }else{

        $staff_id = $arraylogindate[0];
        $staff_name = $arraylogindate[1];
        $this->set('staff_id', $staff_id);
        $this->set('staff_name', $staff_name);

      }

    }

    public function addform()
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
      $user_code = $data["user_code"];

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

      $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(isset($InspectionStandardSizeParents[0])){

        $inspection_standard_size_parent_id = $InspectionStandardSizeParents[0]['id'];
        $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);

      }else{

        if(!isset($_SESSION)){
        session_start();
        }
        $_SESSION['user_code'] = array();
        $_SESSION['user_code'] = $user_code;

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は検査表親テーブルの登録がされていません。"]]);

      }
/*
      $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren->find()
       ->where(['delete_flag' => 0])->toArray();

       if(isset($InspectionStandardSizeChildren[0])){

         if(!isset($_SESSION)){
         session_start();
         }
         $_SESSION['user_code'] = array();
         $_SESSION['user_code'] = $user_code;

         return $this->redirect(['action' => 'addformpre',
         's' => ['mess' => "管理No.「".$product_code."」の製品は登録済みです。内容を確認する場合は規格検索から確認してください。"]]);
       }
*/
    }

    public function addcomfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:');
      header('Cache-Control:');
      header('Pragma:');

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
      $inspection_standard_size_parent_id = $data["inspection_standard_size_parent_id"];
      $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $formcheck = 0;
      $formcheckmess = 0;

      for($i=1; $i<=9; $i++){

        if(strlen($data['size_name'.$i]) > 0){
          ${"size_name".$i} = $data['size_name'.$i];

          if(strlen($data['upper_limit'.$i]) > 0 && strlen($data['lower_limit'.$i]) > 0
           && strlen($data['size'.$i]) > 0 && strlen($data['measuring_instrument'.$i]) > 0){

            $formcheck = 0;

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


            $formcheck = 1;
            $formcheckmess = $i."番目に入力漏れがあります。";
            $this->set('formcheckmess', $formcheckmess);

          }

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

  //    $Products= $this->Products->find()->contain(["Customers"])->where(['product_code' => $product_code])->toArray();
  //    $product_id = $Products[0]["id"];

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $tourokuInspectionStandardSizeChildren = array();

      for($i=1; $i<=9; $i++){

        if(strlen($data['size_name'.$i]) > 0){

          $tourokuInspectionStandardSizeChildren[] = [
            "inspection_standard_size_parent_id" => $data['inspection_standard_size_parent_id'],
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

        }

      }
/*
      echo "<pre>";
      print_r($tourokuInspectionStandardSizeChildren);
      echo "</pre>";
*/
      //新しいデータを登録
      $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren
      ->patchEntities($this->InspectionStandardSizeChildren->newEntity(), $tourokuInspectionStandardSizeChildren);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->InspectionStandardSizeChildren->saveMany($InspectionStandardSizeChildren)) {

          $connection->commit();// コミット5
          $mes = "以下のように登録されました。";
          $this->set('mes',$mes);

        } else {

          $this->Flash->error(__('The data could not be saved. Please, try again.'));
          throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
          $mes = "※登録されませんでした";
          $this->set('mes',$mes);

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

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }

    }

    public function kensakuhyouji()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);

      $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(isset($InspectionStandardSizeParents[0])){

        $inspection_standard_size_parent_id = $InspectionStandardSizeParents[0]['id'];
        $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);

      }else{

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は検査表親テーブルの登録がされていません。"]]);

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

      if(isset($InspectionStandardSizeChildren[0])){

        for($i=1; $i<=9; $i++){

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

        for($i=0; $i<count($InspectionStandardSizeChildren); $i++){

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

        $this->set('InspectionStandardSizeChildren', $InspectionStandardSizeChildren);

      }else{

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は規格登録がされていません。"]]);

      }

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
    }

    public function editform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
      $user_code = $data["user_code"];

      $htmlinputstaff = new htmlLogin();//クラスを使用
      $arraylogindate = $htmlinputstaff->inputstaffprogram($user_code);//クラスを使用

      if($arraylogindate[0] === "no_staff"){

        return $this->redirect(['action' => 'addlogin',
        's' => ['mess' => "社員コードが存在しません。もう一度やり直してください。"]]);

      }else{

        $staff_id = $arraylogindate[0];
        $staff_name = $arraylogindate[1];
        $this->set('staff_id', $staff_id);
        $this->set('staff_name', $staff_name);

      }

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $inspection_standard_size_parent_id = $data["inspection_standard_size_parent_id"];
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

        for($i=1; $i<=9; $i++){

          ${"id".$i} = "";
          $this->set('id'.$i,${"id".$i});
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

        for($i=0; $i<count($InspectionStandardSizeChildren); $i++){

          $num = $InspectionStandardSizeChildren[$i]["size_number"];

          ${"id".$num} = $InspectionStandardSizeChildren[$i]["id"];
          $this->set('id'.$num,${"id".$num});

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

        $this->set('InspectionStandardSizeChildren', $InspectionStandardSizeChildren);

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

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $inspection_standard_size_parent_id = $data["inspection_standard_size_parent_id"];
      $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $formcheck = 0;
      $formcheckmess = 0;

      for($i=1; $i<=9; $i++){

        if(strlen($data['size_name'.$i]) > 0){
          ${"size_name".$i} = $data['size_name'.$i];

          if(strlen($data['upper_limit'.$i]) > 0 && strlen($data['lower_limit'.$i]) > 0
           && strlen($data['size'.$i]) > 0 && strlen($data['measuring_instrument'.$i]) > 0){

            $formcheck = 0;

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


            $formcheck = 1;
            $formcheckmess = $i."番目に入力漏れがあります。";
            $this->set('formcheckmess', $formcheckmess);

          }

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

      if($data["check"] < 1){
        $mes = "以下のように更新します。よろしければ決定ボタンを押してください。";
      }else{
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

  //    $Products= $this->Products->find()->contain(["Customers"])->where(['product_code' => $product_code])->toArray();
  //    $product_id = $Products[0]["id"];

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      if($data["check"] < 1){//削除ではない場合

        for($i=1; $i<=9; $i++){

          $updateInspectionStandardSizeChildren = array();

          if(strlen($data['size_name'.$i]) > 0){

            $updateInspectionStandardSizeChildren = [
              "inspection_standard_size_parent_id" => $data['inspection_standard_size_parent_id'],
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
/*
            echo "<pre>";
            print_r($updateInspectionStandardSizeChildren);
            echo "</pre>";
*/
            //新しいデータを登録
            $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren
            ->patchEntity($this->InspectionStandardSizeChildren->newEntity(), $updateInspectionStandardSizeChildren);
            $connection = ConnectionManager::get('default');//トランザクション1
            // トランザクション開始2
            $connection->begin();//トランザクション3
            try {//トランザクション4
              if ($this->InspectionStandardSizeChildren->save($InspectionStandardSizeChildren)) {

                $this->InspectionStandardSizeChildren->updateAll(
                  [ 'delete_flag' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_staff' => $staff_id],
                  ['id'  => $data['id'.$i]]);

                $connection->commit();// コミット5
                $mes = "以下のように更新されました。";
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

          }

        }


      }else{//削除の場合

        for($i=1; $i<=9; $i++){

          if(strlen($data['size_name'.$i]) > 0){

            $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren
            ->patchEntity($this->InspectionStandardSizeChildren->newEntity(), $data);
            $connection = ConnectionManager::get('default');//トランザクション1
            // トランザクション開始2
            $connection->begin();//トランザクション3
            try {//トランザクション4
              if ($this->InspectionStandardSizeChildren->updateAll(
                [ 'delete_flag' => 1,
                  'updated_at' => date('Y-m-d H:i:s'),
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
