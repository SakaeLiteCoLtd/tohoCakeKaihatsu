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
      ,"kensakupre", "kensakudate", "kensakuhyouji", "editlogin", "editform", "editcomfirm", "editdo"]);
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

      $mess = "";
      $this->set('mess', $mess);

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

          $arrNum[] = $num;

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
/*
      $Staffs = $this->Staffs->find()
      ->where(['delete_flag' => 0])->order(["id"=>"ASC"])->toArray();

      $arrStaffs = array();
      foreach ($Staffs as $value) {
        $array = array($value->id => $value->name);
        $arrStaffs = $arrStaffs + $array;//array_mergeだとキーが0,1,2,…とふりなおされてしまう
      }
      $this->set('arrStaffs', $arrStaffs);
*/
      $arrGaikan = ["0" => "良", "1" => "不"];
      $this->set('arrGaikan', $arrGaikan);

      $arrGouhi = ["0" => "合", "1" => "否"];
      $this->set('arrGouhi', $arrGouhi);

      if(isset($data["tuika"])){//行追加//この時点でデータの登録をする

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

          if(isset($data['user_code'.$j])){
            ${"user_code".$j} = $data['user_code'.$j];
          }else{
            ${"user_code".$j} = "";
          }
          $this->set('user_code'.$j,${"user_code".$j});

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
/*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
*/
        $j = $data["gyou"];
        ${"user_code".$j} = $data['user_code'.$j];
        $Users = $this->Users->find()->contain(["Staffs"])->where(['user_code' => ${"user_code".$j}, 'Users.delete_flag' => 0])->toArray();

        $InspectionDataResultParents = $this->InspectionDataResultParents->find()
        ->where(['inspection_standard_size_parent_id' => $data['inspection_standard_size_parent_id'],
         'product_conditon_parent_id' => $data['product_conditon_parent_id'],
         'datetime' => date("Y-m-d ").$data['datetime'.$j].":00"])
        ->order(["id"=>"DESC"])->toArray();

        if(!isset($InspectionDataResultParents[0])){//再読み込みで同じデータが登録されないようにチェック

          $tourokuInspectionDataResultParents = [
            "inspection_standard_size_parent_id" => $data['inspection_standard_size_parent_id'],
            "product_conditon_parent_id" => $data['product_conditon_parent_id'],
            'lot_number' => $data['lot_number'.$j],
            'datetime' => date("Y-m-d ").$data['datetime'.$j].":00",
            'staff_id' => $Users[0]["staff_id"],
            'appearance' => $data['gaikan'.$j],
            'result_weight' => $data['weight'.$j],
            'judge' => $data['gouhi'.$j],
            "delete_flag" => 0,
            'created_at' => date("Y-m-d H:i:s"),
            "created_staff" => $staff_id
          ];

          echo "<pre>";
          print_r($tourokuInspectionDataResultParents);
          echo "</pre>";

          $InspectionDataResultParents = $this->InspectionDataResultParents->patchEntity($this->InspectionDataResultParents->newEntity(), $tourokuInspectionDataResultParents);
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

                  echo "<pre>";
                  print_r($tourokuInspectionDataResultChildren);
                  echo "</pre>";

                  $InspectionDataResultChildren = $this->InspectionDataResultChildren
                  ->patchEntities($this->InspectionDataResultChildren->newEntity(), $tourokuInspectionDataResultChildren);
                  if ($this->InspectionDataResultChildren->saveMany($InspectionDataResultChildren)) {

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

      }elseif(isset($data["kakuninn"])){

        $checknull = 0;
        $j = $data["gyou"];

        ${"user_code".$j} = $data['user_code'.$j];
        $Users = $this->Users->find()->contain(["Staffs"])->where(['user_code' => ${"user_code".$j}, 'Users.delete_flag' => 0])->toArray();
        if(!isset($Users[0])){
          $checknull = $checknull + 1;
          $mess = $mess."※入力された社員コードは登録されていません。".'<br>';
        }else{
          $checknull = 0;
        }
        for($k=0; $k<count($arrNum); $k++){

          $i = $arrNum[$k];

          if(strlen($data['result_size'.$j.$i]) > 0){//ちゃんと入力されている場合
            $checknull = $checknull;
          }else{//入力漏れがある場合
            $checknull = $checknull + 1;
            $mess = "※測定データに入力漏れがあります。".'<br>';
          }
        }
        $this->set('mess', $mess);

        if($checknull > 0){//入力漏れがある場合

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
              ${"datetime".$j} = date('H:i');
            }
            $this->set('datetime'.$j,${"datetime".$j});

            if(isset($data['user_code'.$j])){
              ${"user_code".$j} = $data['user_code'.$j];
            }else{
              ${"user_code".$j} = "";
            }
            $this->set('user_code'.$j,${"user_code".$j});

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

        }else{//ちゃんと入力されている場合

          if(!isset($_SESSION)){//sessionsyuuseituika
          session_start();
          }

          $_SESSION['kensahyouresultdata'] = array();
          $_SESSION['kensahyouresultdata'] = $data;

          return $this->redirect(['action' => 'addcomfirm']);
        }

      }else{

        $gyou = 1;
        $this->set('gyou', $gyou);

        $j = 1;
        ${"lot_number".$j} = "";
        $this->set('lot_number'.$j,${"lot_number".$j});
        ${"datetime".$j} = date('H:i');
        $this->set('datetime'.$j,${"datetime".$j});
        ${"user_code".$j} = "";
        $this->set('user_code'.$j,${"user_code".$j});
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
          if(isset($data['datetime'.$j]['hour'])){
            ${"datetime".$j} = $data['datetime'.$j]['hour'].":".$data['datetime'.$j]['minute'];
          }else{
            ${"datetime".$j} = $data['datetime'.$j];
          }
        }else{
          ${"datetime".$j} = "";
        }
        $this->set('datetime'.$j,${"datetime".$j});

        if(isset($data['user_code'.$j])){
          ${"user_code".$j} = $data['user_code'.$j];
        }else{
          ${"user_code".$j} = "";
        }
        $this->set('user_code'.$j,${"user_code".$j});

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
/*
    public function adddo()//210525不使用になった（行ごとの登録のため）
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();

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
*/
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

      $datekensaku = $arrdata[0];
      $datetimesta = $arrdata[0]." 00:00";
      $datetimefin = $arrdata[0]." 23:59";
      $this->set('datekensaku', $datekensaku);
      $this->set('datetimesta', $datetimesta);
      $this->set('datetimefin', $datetimefin);
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
      ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();
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
      ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();
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

        ${"lot_number".$n} = $InspectionDataResultParents[$j]['lot_number'];
        $this->set('lot_number'.$n,${"lot_number".$n});
        ${"datetime".$n} = $InspectionDataResultParents[$j]['datetime']->format('y-n-j G:i');
        $this->set('datetime'.$n,${"datetime".$n});

        $Staffs = $this->Staffs->find()
        ->where(['id' => $InspectionDataResultParents[$j]['staff_id']])->order(["id"=>"ASC"])->toArray();
        ${"staff_hyouji".$n} = $Staffs[0]['name'];
        $this->set('staff_hyouji'.$n,${"staff_hyouji".$n});

        ${"appearance".$n} = $InspectionDataResultParents[$j]['appearance'];
        $this->set('appearance'.$n,${"appearance".$n});
        ${"result_weight".$n} = $InspectionDataResultParents[$j]['result_weight'];
        $this->set('result_weight'.$n,${"result_weight".$n});
        ${"judge".$n} = $InspectionDataResultParents[$j]['judge'];
        $this->set('judge'.$n,${"judge".$n});

/*
        echo "<pre>";
        print_r($InspectionDataResultParents[$j]);
        echo "</pre>";
*/
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

    public function editlogin()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

  //    $session = $this->request->getSession();
  //    $session->destroy();
  //    $_SESSION = $session->read();
  //    echo "<pre>";
  //    print_r($_SESSION);
  //    echo "</pre>";

      $data = $this->request->getData();

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $datetimesta = $data["datetimesta"];
      $datetimefin = $data["datetimefin"];

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

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      if(isset($_SESSION['editsokuteidata']['staff_id'])){
        $data = $_SESSION['editsokuteidata'];
        $_SESSION['editsokuteidata'] = array();
      }

      $user_code = $data["user_code"];
      $this->set('user_code', $user_code);

      $arrGaikan = ["0" => "良", "1" => "不"];
      $this->set('arrGaikan', $arrGaikan);

      $arrGouhi = ["0" => "合", "1" => "否"];
      $this->set('arrGouhi', $arrGouhi);

      $htmlinputstaff = new htmlLogin();//クラスを使用
      $arraylogindate = $htmlinputstaff->inputstaffprogram($user_code);//クラスを使用

      if($arraylogindate[0] === "no_staff"){

        return $this->redirect(['action' => 'editlogin',
        's' => ['mess' => "社員コードが存在しません。もう一度やり直してください。"]]);

      }else{

        $staff_id = $arraylogindate[0];
        $this->set('staff_id', $staff_id);

      }

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $datekensaku = $data["datekensaku"];
      $datetimesta = $data["datetimesta"];
      $datetimefin = $data["datetimefin"];
      $this->set('datekensaku', $datekensaku);
      $this->set('datetimesta', $datetimesta);
      $this->set('datetimefin', $datetimefin);

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
      ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();
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
      ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();
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

        ${"lot_number".$n} = $InspectionDataResultParents[$j]['lot_number'];
        $this->set('lot_number'.$n,${"lot_number".$n});
        ${"datetime".$n} = $InspectionDataResultParents[$j]['datetime']->format('y-n-j G:i');
        $this->set('datetime'.$n,${"datetime".$n});

        $Users = $this->Users->find()->where(['staff_id' => $InspectionDataResultParents[$j]['staff_id']])->toArray();
        ${"user_code".$n} = $Users[0]['user_code'];
        $this->set('user_code'.$n,${"user_code".$n});

        ${"appearance".$n} = $InspectionDataResultParents[$j]['appearance'];
        $this->set('appearance'.$n,${"appearance".$n});
        ${"result_weight".$n} = $InspectionDataResultParents[$j]['result_weight'];
        $this->set('result_weight'.$n,${"result_weight".$n});
        ${"judge".$n} = $InspectionDataResultParents[$j]['judge'];
        $this->set('judge'.$n,${"judge".$n});
/*
        echo "<pre>";
        print_r(${"result_weight".$n});
        echo "</pre>";
*/
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
      $user_code = $data["user_code"];
      $this->set('user_code', $user_code);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $datekensaku = $data["datekensaku"];
      $this->set('datekensaku', $datekensaku);

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


      $gyoumax = $data["gyoumax"];
      $this->set('gyou', 0);
      $m = 0;
      for($j=0; $j<$gyoumax; $j++){

        $n = $j + 1;

        if($data["delete_sokutei".$n] == 0){

          $m = $m + 1;
          $this->set('gyou', $m);

          ${"datetime".$n} = $data['datetime'.$n]['hour'].":".$data['datetime'.$n]['minute'];
          $this->set('datetime'.$m,${"datetime".$n});

          ${"user_code".$n} = $data['user_code'.$n];
          $Users = $this->Users->find()->contain(["Staffs"])->where(['user_code' => ${"user_code".$n}, 'Users.delete_flag' => 0])->toArray();
          if(!isset($Users[0])){
            $mess = $n."行目に入力された社員コードは登録されていません。もう一度やり直してください。";

            if(!isset($_SESSION)){
              session_start();
            }

            $_SESSION['editsokuteidata'] = array();
            $_SESSION['editsokuteidata'] = $data;

            return $this->redirect(['action' => 'editform',
            's' => ['mess' => $mess]]);

          }else{
            $this->set('user_code'.$m,${"user_code".$n});
          }

          ${"appearance".$n} = $data['appearance'.$n];
          $this->set('appearance'.$m,${"appearance".$n});
          ${"result_weight".$n} = $data['result_weight'.$n];
          $this->set('result_weight'.$m,${"result_weight".$n});
          ${"judge".$n} = $data['judge'.$n];
          $this->set('judge'.$m,${"judge".$n});

          for($i=1; $i<=9; $i++){

            if(strlen($data['result_size'.$n.$i]) > 0){
              ${"result_size".$n.$i} = $data['result_size'.$n.$i];
            }else{
              ${"result_size".$n.$i} = "";
            }
            $this->set('result_size'.$m.$i,${"result_size".$n.$i});

          }

        }

      }

      if($data["check"] < 1 && $m > 0){
        $mess = "以下のように更新します。よろしければ決定ボタンを押してください。";
        $this->set('delete_flag', 0);
      }else{
        $mess = "データを削除します。よろしければ決定ボタンを押してください。";
        $this->set('delete_flag', 1);
      }
      $this->set('mess', $mess);

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
      $user_code = $data["user_code"];
      $this->set('user_code', $user_code);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $datetimesta = $data["datetimesta"];
      $datetimefin = $data["datetimefin"];

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $datekensaku = $data["datekensaku"];
      $this->set('datekensaku', $datekensaku);

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

      $gyou = $data["gyou"];
      $this->set('gyou', $gyou);
      for($j=0; $j<$gyou; $j++){

        $n = $j + 1;

        ${"datetime".$n} = $data['datetime'.$n];
        $this->set('datetime'.$n,${"datetime".$n});

        ${"user_code".$n} = $data['user_code'.$n];
        $this->set('user_code'.$n,${"user_code".$n});

        ${"appearance".$n} = $data['appearance'.$n];
        $this->set('appearance'.$n,${"appearance".$n});
        ${"result_weight".$n} = $data['result_weight'.$n];
        $this->set('result_weight'.$n,${"result_weight".$n});
        ${"judge".$n} = $data['judge'.$n];
        $this->set('judge'.$n,${"judge".$n});

        for($i=1; $i<=9; $i++){

          if(strlen($data['result_size'.$n.$i]) > 0){
            ${"result_size".$n.$i} = $data['result_size'.$n.$i];
          }else{
            ${"result_size".$n.$i} = "";
          }
          $this->set('result_size'.$n.$i,${"result_size".$n.$i});

        }

      }

      if($data["delete_flag"] < 1){

        $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
        ->where(['product_code' => $product_code, 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

        $inspection_standard_size_parent_id = $InspectionStandardSizeParents[0]['id'];

        $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code' => $product_code,
        'ProductConditionParents.is_active' => 0,
        'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

        $product_conditon_parent_id = $ProductConditionParents[0]['id'];

        for($j=1; $j<=$gyou; $j++){

          ${"user_code".$j} = $data['user_code'.$j];
          $Users = $this->Users->find()->contain(["Staffs"])->where(['user_code' => ${"user_code".$j}, 'Users.delete_flag' => 0])->toArray();

          $updateInspectionDataResultParents = [
            "inspection_standard_size_parent_id" => $inspection_standard_size_parent_id,
            "product_conditon_parent_id" => $product_conditon_parent_id,
            'lot_number' => $data['lot_number'.$j],
            'datetime' => $data['datekensaku']." ".$data['datetime'.$j].":00",
            'staff_id' => $Users[0]["staff_id"],
            'appearance' => $data['appearance'.$j],
            'result_weight' => $data['result_weight'.$j],
            'judge' => $data['judge'.$j],
            "delete_flag" => 0,
            'created_at' => date("Y-m-d H:i:s"),
            "created_staff" => $staff_id
          ];
/*
          echo "<pre>";
          print_r($updateInspectionDataResultParents);
          echo "</pre>";
*/
    //      $InspectionDataResultParents = $this->InspectionDataResultParents->patchEntity($this->InspectionDataResultParents->newEntity(), $tourokuInspectionDataResultParents);
          $connection = ConnectionManager::get('default');//トランザクション1
          // トランザクション開始2
          $connection->begin();//トランザクション3
          try {//トランザクション4

            if($j == 1){
              $this->InspectionDataResultParents->updateAll(
                [ 'delete_flag' => 1,
                  'updated_at' => date('Y-m-d H:i:s'),
                  'updated_staff' => $staff_id],
                ['datetime >=' => $datetimesta, 'datetime <=' => $datetimefin]);

                $InspectionDataResultParentsDATA = $this->InspectionDataResultParents->find()
                ->where(['datetime >=' => $datetimesta, 'datetime <=' => $datetimefin])
                ->order(["id"=>"DESC"])->toArray();

                for($i=0; $i<count($InspectionDataResultParentsDATA); $i++){

                  $this->InspectionDataResultChildren->updateAll(
                    [ 'delete_flag' => 1,
                      'updated_at' => date('Y-m-d H:i:s'),
                      'updated_staff' => $staff_id],
                    ['inspection_data_result_parent_id' => $InspectionDataResultParentsDATA[$i]['id']]);

                }

              }

              $InspectionDataResultParents = $this->InspectionDataResultParents->patchEntity($this->InspectionDataResultParents->newEntity(), $updateInspectionDataResultParents);
              if ($this->InspectionDataResultParents->save($InspectionDataResultParents)) {

                $InspectionDataResultParentsId = $this->InspectionDataResultParents->find()
                ->where(['delete_flag' => 0, 'inspection_standard_size_parent_id' => $inspection_standard_size_parent_id,
                 'datetime' => $data['datekensaku']." ".$data['datetime'.$j].":00"])
                ->order(["id"=>"DESC"])->toArray();

                $tourokuInspectionDataResultChildren = array();

                for($i=1; $i<=9; $i++){

                  if(strlen($data['result_size'.$j.$i]) > 0){

                    $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren->find()
                    ->where(['inspection_standard_size_parent_id' => $inspection_standard_size_parent_id, "size_number" => $i])
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
/*
                    echo "<pre>";
                    print_r($tourokuInspectionDataResultChildren);
                    echo "</pre>";
*/
                    $InspectionDataResultChildren = $this->InspectionDataResultChildren
                    ->patchEntities($this->InspectionDataResultChildren->newEntity(), $tourokuInspectionDataResultChildren);
                    if ($this->InspectionDataResultChildren->saveMany($InspectionDataResultChildren)) {

                      $mes = "※更新されました";
                      $this->set('mes',$mes);
                      $connection->commit();// コミット5

                    }

                  }

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

        }

      }else{

        $connection = ConnectionManager::get('default');//トランザクション1
        // トランザクション開始2
        $connection->begin();//トランザクション3
        try {//トランザクション4

          if ($this->InspectionDataResultParents->updateAll(
            [ 'delete_flag' => 1,
              'updated_at' => date('Y-m-d H:i:s'),
              'updated_staff' => $staff_id],
            ['datetime >=' => $datetimesta, 'datetime <=' => $datetimefin])
          ) {

            $connection->commit();// コミット5
            $mes = "※削除されました";
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

}
