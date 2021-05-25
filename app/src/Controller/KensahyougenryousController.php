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
      session_start();
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');
      $session = $this->request->session();
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
          ->where(['product_id' => $product_id, 'version' => $version])->toArray();

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

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      $version = $ProductConditionParents[0]["version"];

      $ProductMachineMaterials = $this->ProductMachineMaterials->find()
      ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
      ->where(['version' => $version, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->toArray();

      if($ProductMachineMaterials[0]){

        $ProductMaterialMachines = $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['version' => $version, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
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

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は原料登録がされていません。"]]);

      }

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

      $data = $this->request->getData();
      $user_code = $data["user_code"];
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
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
      ->where(['delete_flag' => 0])->order(["grade"=>"ASC"])->toArray();

      $arrMaterials = array();
      foreach ($Materials as $value) {
        $array = array($value->id => $value->grade." : ".$value->maker." : ".$value->name);
        $arrMaterials = $arrMaterials + $array;//array_mergeだとキーが0,1,2,…とふりなおされてしまう
      }
      $this->set('arrMaterials', $arrMaterials);

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

        if(!isset($_SESSION)){
          session_start();
        }

        $_SESSION['updatekensahyougenryoudata'] = array();
        $_SESSION['updatekensahyougenryoudata'] = $data + array("delete_flag" => 0);

        return $this->redirect(['action' => 'editcomfirm']);

      }elseif(isset($data["sakujo"])){//削除ボタン

        if(!isset($_SESSION)){
          session_start();
        }

        $_SESSION['updatekensahyougenryoudata'] = array();
        $_SESSION['updatekensahyougenryoudata'] = $data + array("delete_flag" => 1);

        return $this->redirect(['action' => 'editcomfirm']);

      }else{//最初にこの画面に来た時

        $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

        $version = $ProductConditionParents[0]["version"];

        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['version' => $version, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
        ->toArray();

        $ProductMaterialMachines = $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['version' => $version, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
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
      $_SESSION['updatekensahyougenryoudata'] = array();

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

            ${"material_hyouji".$j.$i} = $Materials[0]["grade"].":".$Materials[0]["maker"].":".$Materials[0]["name"];
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

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      $product_condition_parent_id = $ProductConditionParents[0]["id"];

      $ProductMachineMaterials = $this->ProductMachineMaterials->find()
      ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
      ->where(['product_condition_parent_id' => $product_condition_parent_id, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->toArray();

      $ProductMaterialMachines = $this->ProductMaterialMachines->find()
      ->contain(['ProductConditionParents' => ["Products"]])
      ->where(['product_condition_parent_id' => $product_condition_parent_id, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
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
