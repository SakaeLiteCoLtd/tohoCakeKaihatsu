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

class KensahyousokuteidatasController extends AppController
{

  	public function beforeFilter(Event $event){
  		parent::beforeFilter($event);

  		// 認証なしでアクセスできるアクションの指定
      $this->Auth->allow(["menu","addlogin","addformpre","addform","addcomfirm","adddo"
      ,"kensakupre", "kensakudate", "kensakuhyouji"]);
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
     $this->ProductConditionParents = TableRegistry::get('ProductConditionParents');
     $this->InspectionDataResultParents = TableRegistry::get('InspectionDataResultParents');
     $this->InspectionDataResultChildren = TableRegistry::get('InspectionDataResultChildren');
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
      $this->set('user_code', $user_code);

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

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code,
      'ProductConditionParents.is_active' => 0,
      'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(isset($ProductConditionParents[0])){

        $product_conditon_parent_id = $ProductConditionParents[0]['id'];
        $this->set('product_conditon_parent_id', $product_conditon_parent_id);

      }else{

        if(!isset($_SESSION)){
        session_start();
        }
        $_SESSION['user_code'] = array();
        $_SESSION['user_code'] = $user_code;

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は検査表親テーブルの登録がされていません。"]]);

      }

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

        if(!isset($_SESSION)){
        session_start();
        }
        $_SESSION['user_code'] = array();
        $_SESSION['user_code'] = $user_code;

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は規格登録がされていません。"]]);

      }

      $Staffs = $this->Staffs->find()
      ->where(['delete_flag' => 0])->order(["id"=>"ASC"])->toArray();

      $arrStaffs = array();
      foreach ($Staffs as $value) {
        $array = array($value->id => $value->name);
        $arrStaffs = $arrStaffs + $array;//array_mergeだとキーが0,1,2,…とふりなおされてしまう
      }
      $this->set('arrStaffs', $arrStaffs);

      $arrGaikan = ["良" => "良", "不" => "不"];
      $this->set('arrGaikan', $arrGaikan);

      $arrGouhi = ["合" => "合", "非" => "非"];
      $this->set('arrGouhi', $arrGouhi);

      if(isset($data["tuika"])){//行追加

        $gyou = $data["gyou"] + 1;
        $this->set('gyou', $gyou);

        for($j=1; $j<=$gyou; $j++){

          if(isset($data['lot_number'.$j])){
            ${"lot_number".$j} = $data['lot_number'.$j];
          }else{
            ${"lot_number".$j} = "";
          }
          $this->set('lot_number'.$j,${"lot_number".$j});

          if(isset($data['datetime'.$j])){
            ${"datetime".$j} = $data['datetime'.$j];
          }else{
            ${"datetime".$j} = date('H:i');
          }
          $this->set('datetime'.$j,${"datetime".$j});

          if(isset($data['staff_id'.$j])){
            ${"staff_id".$j} = $data['staff_id'.$j];
          }else{
            ${"staff_id".$j} = "";
          }
          $this->set('staff_id'.$j,${"staff_id".$j});

          if(isset($data['gaikan'.$j])){
            ${"gaikan".$j} = $data['gaikan'.$j];
          }else{
            ${"gaikan".$j} = "";
          }
          $this->set('gaikan'.$j,${"gaikan".$j});

          if(isset($data['weight'.$j])){
            ${"weight".$j} = $data['weight'.$j];
          }else{
            ${"weight".$j} = "";
          }
          $this->set('weight'.$j,${"weight".$j});

          if(isset($data['gouhi'.$j])){
            ${"gouhi".$j} = $data['gouhi'.$j];
          }else{
            ${"gouhi".$j} = "";
          }
          $this->set('gouhi'.$j,${"gouhi".$j});

          for($i=1; $i<=9; $i++){

            if(isset($data['result_size'.$j.$i])){
              ${"result_size".$j.$i} = $data['result_size'.$j.$i];
            }else{
              ${"result_size".$j.$i} = "";
            }
            $this->set('result_size'.$j.$i,${"result_size".$j.$i});

          }

        }

      }elseif(isset($data["kakuninn"])){

        if(!isset($_SESSION)){//sessionsyuuseituika
        session_start();
        }

        $_SESSION['kensahyouresultdata'] = array();
        $_SESSION['kensahyouresultdata'] = $data;

        return $this->redirect(['action' => 'addcomfirm']);

      }else{

        $gyou = 1;
        $this->set('gyou', $gyou);

        $j = 1;
        ${"lot_number".$j} = "";
        $this->set('lot_number'.$j,${"lot_number".$j});
        ${"datetime".$j} = date('H:i');
        $this->set('datetime'.$j,${"datetime".$j});
        ${"staff_id".$j} = "";
        $this->set('staff_id'.$j,${"staff_id".$j});
        ${"gaikan".$j} = "";
        $this->set('gaikan'.$j,${"gaikan".$j});
        ${"weight".$j} = "";
        $this->set('weight'.$j,${"weight".$j});
        ${"gouhi".$j} = "";
        $this->set('gouhi'.$j,${"gouhi".$j});

      }

    }

    public function addcomfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $arraykensahyouresultdata = $_SESSION['kensahyouresultdata'];
  //    $_SESSION['kensahyouresultdata'] = array();

      $data = $arraykensahyouresultdata;
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
      $this->set('user_code', $user_code);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);

      $htmlproductcheck = new htmlproductcheck();//クラスを使用
      $arrayproductdate = $htmlproductcheck->productcheckprogram($product_code);//クラスを使用

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      $inspection_standard_size_parent_id = $InspectionStandardSizeParents[0]['id'];
      $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code,
      'ProductConditionParents.is_active' => 0,
      'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      $product_conditon_parent_id = $ProductConditionParents[0]['id'];
      $this->set('product_conditon_parent_id', $product_conditon_parent_id);

      $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren->find()
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

      }

      $gyou = $data["gyou"];
      $this->set('gyou', $gyou);

      for($j=1; $j<=$gyou; $j++){

        if(isset($data['lot_number'.$j])){
          ${"lot_number".$j} = $data['lot_number'.$j];
        }else{
          ${"lot_number".$j} = "";
        }
        $this->set('lot_number'.$j,${"lot_number".$j});

        if(isset($data['datetime'.$j])){
          ${"datetime".$j} = $data['datetime'.$j]['hour'].":".$data['datetime'.$j]['minute'];
        }else{
          ${"datetime".$j} = "";
        }
        $this->set('datetime'.$j,${"datetime".$j});

        if(isset($data['staff_id'.$j])){
          ${"staff_id".$j} = $data['staff_id'.$j];

          $Staffs = $this->Staffs->find()
          ->where(['id' => ${"staff_id".$j}])->toArray();

          ${"staff_hyouji".$j} = $Staffs[0]['name'];
        }else{
          ${"staff_id".$j} = "";
          ${"staff_hyouji".$j} = "";
        }
        $this->set('staff_id'.$j,${"staff_id".$j});
        $this->set('staff_hyouji'.$j,${"staff_hyouji".$j});

        if(isset($data['gaikan'.$j])){
          ${"gaikan".$j} = $data['gaikan'.$j];
        }else{
          ${"gaikan".$j} = "";
        }
        $this->set('gaikan'.$j,${"gaikan".$j});

        if(isset($data['weight'.$j])){
          ${"weight".$j} = $data['weight'.$j];
        }else{
          ${"weight".$j} = "";
        }
        $this->set('weight'.$j,${"weight".$j});

        if(isset($data['gouhi'.$j])){
          ${"gouhi".$j} = $data['gouhi'.$j];
        }else{
          ${"gouhi".$j} = "";
        }
        $this->set('gouhi'.$j,${"gouhi".$j});

        for($i=1; $i<=9; $i++){

          if(isset($data['result_size'.$j.$i])){
            ${"result_size".$j.$i} = $data['result_size'.$j.$i];
          }else{
            ${"result_size".$j.$i} = "";
          }
          $this->set('result_size'.$j.$i,${"result_size".$j.$i});

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
      $user_code = $data["user_code"];
      $this->set('user_code', $user_code);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);

      $htmlproductcheck = new htmlproductcheck();//クラスを使用
      $arrayproductdate = $htmlproductcheck->productcheckprogram($product_code);//クラスを使用

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      $inspection_standard_size_parent_id = $InspectionStandardSizeParents[0]['id'];
      $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code,
      'ProductConditionParents.is_active' => 0,
      'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      $product_conditon_parent_id = $ProductConditionParents[0]['id'];
      $this->set('product_conditon_parent_id', $product_conditon_parent_id);

      $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren->find()
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

      }

      $gyou = $data["gyou"];
      $this->set('gyou', $gyou);

      for($j=1; $j<=$gyou; $j++){

        if(isset($data['lot_number'.$j])){
          ${"lot_number".$j} = $data['lot_number'.$j];
        }else{
          ${"lot_number".$j} = "";
        }
        $this->set('lot_number'.$j,${"lot_number".$j});

        if(isset($data['datetime'.$j])){
          ${"datetime".$j} = $data['datetime'.$j];
        }else{
          ${"datetime".$j} = "";
        }
        $this->set('datetime'.$j,${"datetime".$j});

        if(isset($data['staff_hyouji'.$j])){
          ${"staff_hyouji".$j} = $data['staff_hyouji'.$j];
        }else{
          ${"staff_hyouji".$j} = "";
        }
        $this->set('staff_hyouji'.$j,${"staff_hyouji".$j});

        if(isset($data['gaikan'.$j])){
          ${"gaikan".$j} = $data['gaikan'.$j];
        }else{
          ${"gaikan".$j} = "";
        }
        $this->set('gaikan'.$j,${"gaikan".$j});

        if(isset($data['weight'.$j])){
          ${"weight".$j} = $data['weight'.$j];
        }else{
          ${"weight".$j} = "";
        }
        $this->set('weight'.$j,${"weight".$j});

        if(isset($data['gouhi'.$j])){
          ${"gouhi".$j} = $data['gouhi'.$j];
        }else{
          ${"gouhi".$j} = "";
        }
        $this->set('gouhi'.$j,${"gouhi".$j});

        for($i=1; $i<=9; $i++){

          if(isset($data['result_size'.$j.$i])){
            ${"result_size".$j.$i} = $data['result_size'.$j.$i];
          }else{
            ${"result_size".$j.$i} = "";
          }
          $this->set('result_size'.$j.$i,${"result_size".$j.$i});

        }

      }

      $tourokuInspectionDataResultParents = array();
      for($j=1; $j<=$gyou; $j++){

        if(strlen($data['lot_number'.$j]) > 0){

          $tourokuInspectionDataResultParents = [
            "inspection_standard_size_parent_id" => $data['inspection_standard_size_parent_id'],
            "product_conditon_parent_id" => $data['product_conditon_parent_id'],
            'datetime' => date("Y-m-d ").$data['datetime'.$j].":00",
            'staff_id' => $data['staff_id'.$j],
            "delete_flag" => 0,
            'created_at' => date("Y-m-d H:i:s"),
            "created_staff" => $staff_id
          ];
/*
          echo "<pre>";
          print_r($tourokuInspectionDataResultParents);
          echo "</pre>";
*/
          $InspectionDataResultParents = $this->InspectionDataResultParents
          ->patchEntity($this->InspectionDataResultParents->newEntity(), $tourokuInspectionDataResultParents);
          $connection = ConnectionManager::get('default');//トランザクション1
          // トランザクション開始2
          $connection->begin();//トランザクション3
          try {//トランザクション4
            if ($this->InspectionDataResultParents->save($InspectionDataResultParents)) {

              $InspectionDataResultParentsId = $this->InspectionDataResultParents->find()
              ->where(['inspection_standard_size_parent_id' => $data['inspection_standard_size_parent_id'], 'datetime' => date("Y-m-d ").$data['datetime'.$j].":00"])
              ->order(["id"=>"DESC"])->toArray();

              $tourokuInspectionDataResultChildren = array();

              for($i=1; $i<=9; $i++){

                if(strlen($data['result_size'.$j.$i]) > 0){

                  $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren->find()
                  ->where(['inspection_standard_size_parent_id' => $data['inspection_standard_size_parent_id'], "size_number" => $i])
                  ->order(["id"=>"DESC"])->toArray();

                  $tourokuInspectionDataResultChildren[] = [
                    "inspection_data_result_parent_id" => $InspectionDataResultParentsId[0]["id"],
                    "inspection_standard_size_child_id" => $InspectionStandardSizeChildren[0]["id"],
                    'result_size' => $data['result_size'.$j.$i],
                    "delete_flag" => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    "created_staff" => $staff_id
                  ];

                }

                if($i == 9){//各jに対して一括登録

                  $InspectionDataResultChildren = $this->InspectionDataResultChildren
                  ->patchEntities($this->InspectionDataResultChildren->newEntity(), $tourokuInspectionDataResultChildren);
                  if ($this->InspectionDataResultChildren->saveMany($InspectionDataResultChildren)) {

                    if($j == $gyou){//最後まできたらコメントをセット

                      $mes = "以下のように登録されました。";
                      $this->set('mes',$mes);

                    }
                    $connection->commit();// コミット5

                  }

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

    public function kensakudate()
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

      $htmlproductcheck = new htmlproductcheck();//クラスを使用
      $arrayproductdate = $htmlproductcheck->productcheckprogram($product_code);//クラスを使用

      if($arrayproductdate[0] === "no_product"){

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は存在しません。"]]);

      }else{
        $name = $arrayproductdate[0];
        $customer = $arrayproductdate[1];
        $this->set('name', $name);
        $this->set('customer', $customer);
      }

      if(isset($data['saerch'])){

        $startY = $data['start']['year'];
    		$startM = $data['start']['month'];
    		$startD = $data['start']['day'];
        $startYMD = $startY."-".$startM."-".$startD." 00:00";

        $endY = $data['end']['year'];
    		$endM = $data['end']['month'];
    		$endD = $data['end']['day'];
        $endYMD = $endY."-".$endM."-".$endD." 23:59";

        $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()
        ->contain(['InspectionDataResultParents' => ['InspectionStandardSizeParents' => ["Products"]]])
        ->where(['product_code' => $product_code, 'InspectionDataResultChildren.delete_flag' => 0,
        'datetime >=' => $startYMD, 'datetime <=' => $endYMD])
        ->order(["InspectionDataResultParents.datetime"=>"DESC"])->toArray();

        $arrDates = array();

        for($k=0; $k<count($InspectionDataResultChildren); $k++){

          $arrDates[] = $InspectionDataResultChildren[$k]['inspection_data_result_parent']['datetime']->format('Y-m-d');

        }

        $arrDates = array_unique($arrDates);
        $arrDates = array_values($arrDates);

        $this->set('arrDates', $arrDates);

        $mes = "検索期間： ".$startY."-".$startM."-".$startD .' ～ '.$endY."-".$endM."-".$endD;
        $this->set('mes', $mes);

        $checksaerch = 1;
        $this->set('checksaerch', $checksaerch);

      }else{

        $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()
        ->contain(['InspectionDataResultParents' => ['InspectionStandardSizeParents' => ["Products"]]])
        ->where(['product_code' => $product_code, 'InspectionDataResultChildren.delete_flag' => 0])
        ->order(["InspectionDataResultParents.datetime"=>"DESC"])->toArray();

        $arrDates = array();

        for($k=0; $k<count($InspectionDataResultChildren); $k++){

          $arrDates[] = $InspectionDataResultChildren[$k]['inspection_data_result_parent']['datetime']->format('Y-m-d');

        }

        $arrDates = array_unique($arrDates);
        $arrDates = array_values($arrDates);

        $this->set('arrDates', $arrDates);

        $mes = '＊最新の上位３つの測定データです。';
        $this->set('mes', $mes);

        $checksaerch = 0;
        $this->set('checksaerch', $checksaerch);

      }

    }

    public function kensakuhyouji()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->query('s');

      $arrdata = explode("_",$data);

      $datetimesta = $arrdata[0]." 00:00";
      $datetimefin = $arrdata[0]." 23:59";
/*
      echo "<pre>";
      print_r($datetimefin);
      echo "</pre>";
*/
      $product_code = $arrdata[1];
      $this->set('product_code', $product_code);

      $htmlproductcheck = new htmlproductcheck();//クラスを使用
      $arrayproductdate = $htmlproductcheck->productcheckprogram($product_code);//クラスを使用

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren->find()
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

      }

      $InspectionDataResultParents = $this->InspectionDataResultParents->find()
      ->contain(['InspectionStandardSizeParents' => ["Products"]])
      ->where(['product_code' => $product_code, 'InspectionStandardSizeParents.delete_flag' => 0,
      'datetime >=' => $datetimesta, 'datetime <=' => $datetimefin])
      ->toArray();
/*
      echo "<pre>";
      print_r($InspectionDataResultParents);
      echo "</pre>";
*/
      $gyou = count($InspectionDataResultParents);
      $this->set('gyou', $gyou);

      $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()
      ->contain(['InspectionStandardSizeChildren', 'InspectionDataResultParents' => ['InspectionStandardSizeParents' => ["Products"]]])
      ->where(['product_code' => $product_code, 'InspectionDataResultChildren.delete_flag' => 0,
      'datetime >=' => $datetimesta, 'datetime <=' => $datetimefin])
      ->order(["InspectionDataResultParents.datetime"=>"DESC"])->toArray();
/*
      echo "<pre>";
      print_r($InspectionDataResultChildren);
      echo "</pre>";
*/
      for($j=0; $j<count($InspectionDataResultParents); $j++){

        $n = $j + 1;

        $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()
        ->contain(['InspectionStandardSizeChildren'])
        ->where(['inspection_data_result_parent_id' => $InspectionDataResultParents[$j]["id"]])
        ->toArray();

        ${"datetime".$n} = $InspectionDataResultParents[$j]['datetime'];
        $this->set('datetime'.$n,${"datetime".$n});

        for($i=1; $i<=9; $i++){

          ${"result_size".$n.$i} = "";
          $this->set('result_size'.$n.$i,${"result_size".$n.$i});

        }

        for($i=0; $i<count($InspectionDataResultChildren); $i++){

          $size_number = $InspectionDataResultChildren[$i]['inspection_standard_size_child']['size_number'];

          ${"result_size".$n.$size_number} = $InspectionDataResultChildren[$i]['result_size'];
          $this->set('result_size'.$n.$size_number,${"result_size".$n.$size_number});

        }

      }

    }

}
