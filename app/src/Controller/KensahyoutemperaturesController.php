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
use App\myClass\classprograms\htmlkensahyouprogram;//myClassフォルダに配置したクラスを使用
$htmlkensahyougenryouheader = new htmlkensahyouprogram();

class KensahyoutemperaturesController extends AppController
{

  	public function beforeFilter(Event $event){
  		parent::beforeFilter($event);

  		// 認証なしでアクセスできるアクションの指定
  		$this->Auth->allow(["menu","addlogin","addformpre","addform","addcomfirm","adddo"
      ,"kensakupre", "kensakuhyouji", "editlogin", "editform", "editcomfirm", "editdo"]);
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
     $this->ProductConditonChildren = TableRegistry::get('ProductConditonChildren');

     if(!isset($_SESSION)){//フォーム再送信の確認対策
       session_start();
     }
     header('Expires:');
     header('Cache-Control:');
     header('Pragma:');

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

         $mess = "入力された顧客の製品は登録されていません。確認してください。";
         $this->set('mess',$mess);

         $Product_name_list = $this->Products->find()
         ->where(['delete_flag' => 0])->toArray();

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }else{

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
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

        $product_name_length = explode(";",$data["product_name"]);
        $name = $product_name_length[0];
        $length = str_replace('mm', '', $product_name_length[1]);
  
         $Products = $this->Products->find()
         ->where(['name' => $name, 'length' => $length, 'delete_flag' => 0])->toArray();

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
             array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
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
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }

     }else{//はじめ

       $Product_name_list = $this->Products->find()
       ->where(['delete_flag' => 0])->toArray();
       $arrProduct_name_list = array();
       for($j=0; $j<count($Product_name_list); $j++){
         array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
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

         $Users= $this->Users->find('all')->contain(["Staffs"])->where(['user_code' => $user_code, 'Users.delete_flag' => 0])->toArray();
         $staff_id = $Users[0]["staff_id"];
         $this->set('staff_id', $staff_id);
         $staff_name= $Users[0]["staff"]["name"];
         $this->set('staff_name', $staff_name);

       }else{

         $mess = "";
         $this->set('mess',$mess);
         $user_code = $data["user_code"];

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

       $this->set('user_code', $user_code);

     }
     echo "<pre>";
     print_r("");
     echo "</pre>";

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

      $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

      }

      if(isset($ProductConditionParents[0])){

        $version = $ProductConditionParents[0]["version"];

      }else{

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        $session = $this->request->getSession();
        $_SESSION = $session->read();
        $_SESSION['user_code'] = array();
        $_SESSION["user_code"] = $user_code;

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "「".$Products[0]["name"]."」は原料登録がされていません。"]]);

      }

      $ProductMaterialMachines= $this->ProductMaterialMachines->find()
      ->contain(['ProductConditionParents' => ["Products"]])
      ->where(['Products.product_code' => $product_code,
      'ProductConditionParents.delete_flag' => 0,
      'ProductMaterialMachines.delete_flag' => 0,
      'ProductConditionParents.version' => $version])
      ->order(["cylinder_number"=>"ASC"])->toArray();

      if(!isset($ProductMaterialMachines[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductMaterialMachines= $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['Products.product_code like' => $product_code_ini.'%',
        'ProductConditionParents.delete_flag' => 0,
        'ProductMaterialMachines.delete_flag' => 0,
        'ProductConditionParents.version' => $version])
        ->order(["cylinder_number"=>"ASC"])->toArray();
  
      }

      $countseikeiki = count($ProductMaterialMachines);
      $this->set('countseikeiki', $countseikeiki);

      for($k=0; $k<$countseikeiki; $k++){

        $j = $k + 1;
        ${"product_material_machine_id".$j} = $ProductMaterialMachines[$k]["id"];
        $this->set('product_material_machine_id'.$j, ${"product_material_machine_id".$j});
        ${"cylinder_name".$j} = $ProductMaterialMachines[$k]["cylinder_name"];
        $this->set('cylinder_name'.$j, ${"cylinder_name".$j});

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

      $version = $ProductConditionParents[0]["version"];

      $ProductMachineMaterials = $this->ProductMachineMaterials->find()
      ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
      ->where(['version' => $version, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->toArray();

      if(!isset($ProductMachineMaterials[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['version' => $version, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->toArray();
  
      }

      if(isset($ProductMachineMaterials[0])){

        $htmlkensahyougenryouheader = new htmlkensahyouprogram();
        $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code);
      	$this->set('htmlgenryouheader',$htmlgenryouheader);

      }else{

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        $session = $this->request->getSession();
        $_SESSION = $session->read();
        $_SESSION['user_code'] = array();
        $_SESSION["user_code"] = $user_code;

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "「".$Products[0]["name"]."」は原料登録がされていません。"]]);

      }

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      echo "<pre>";
      print_r("");
      echo "</pre>";

    }

    public function addcomfirm()
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

      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

      $countseikeiki = $data["countseikeiki"];
      $this->set('countseikeiki', $countseikeiki);
      $pickup_speed = $data["pickup_speed"];
      $this->set('pickup_speed', $pickup_speed);

      for($k=0; $k<$countseikeiki; $k++){

        $j = $k + 1;
        ${"product_material_machine_id".$j} = $data['product_material_machine_id'.$j];
        $this->set('product_material_machine_id'.$j, ${"product_material_machine_id".$j});
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

      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

      $countseikeiki = $data["countseikeiki"];
      $this->set('countseikeiki', $countseikeiki);
      $pickup_speed = $data["pickup_speed"];
      $this->set('pickup_speed', $pickup_speed);

      $tourokuProductConditonChildren = array();

      for($k=0; $k<$countseikeiki; $k++){

        $j = $k + 1;
        ${"product_material_machine_id".$j} = $data['product_material_machine_id'.$j];
        $this->set('product_material_machine_id'.$j, ${"product_material_machine_id".$j});
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

        $tourokuProductConditonChildren[] = [
          "product_material_machine_id" => $data['product_material_machine_id'.$j],
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
/*
      echo "<pre>";
      print_r($tourokuProductConditonChildren);
      echo "</pre>";
*/
      //新しいデータを登録
      $ProductConditonChildren = $this->ProductConditonChildren->patchEntities($this->ProductConditonChildren->newEntity(), $tourokuProductConditonChildren);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4

        for($i=0; $i<count($tourokuProductConditonChildren); $i++){//元のデータがあればdelete

          $this->ProductConditonChildren->updateAll(
            [ 'delete_flag' => 1,
              'updated_at' => date('Y-m-d H:i:s'),
              'updated_staff' => $staff_id],
            ['product_material_machine_id' => $tourokuProductConditonChildren[$i]['product_material_machine_id']]);

        }

        if ($this->ProductConditonChildren->saveMany($ProductConditonChildren)) {

          $connection->commit();// コミット5
          $mes = "登録されました。";
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

         $mess = "入力された顧客の製品は登録されていません。確認してください。";
         $this->set('mess',$mess);

         $Product_name_list = $this->Products->find()
         ->where(['delete_flag' => 0])->toArray();

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }else{

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
         }
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
           ->where(['delete_flag' => 0])->toArray();

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
         ->where(['delete_flag' => 0])->toArray();

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }

     }else{//はじめ

       $Product_name_list = $this->Products->find()
       ->where(['delete_flag' => 0])->toArray();
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

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

      }

      if(isset($ProductConditionParents[0])){

        $version = $ProductConditionParents[0]["version"];

        $ProductMaterialMachines= $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['Products.product_code' => $product_code,
        'ProductConditionParents.delete_flag' => 0,
        'ProductMaterialMachines.delete_flag' => 0,
        'ProductConditionParents.version' => $version])
        ->order(["cylinder_number"=>"ASC"])->toArray();

        if(!isset($ProductMaterialMachines[0])){//長さ違いのデータがあればそれを持ってくる

          $product_code_ini = substr($product_code, 0, 11);
          $ProductMaterialMachines= $this->ProductMaterialMachines->find()
          ->contain(['ProductConditionParents' => ["Products"]])
          ->where(['Products.product_code like' => $product_code_ini.'%',
          'ProductConditionParents.delete_flag' => 0,
          'ProductMaterialMachines.delete_flag' => 0,
          'ProductConditionParents.version' => $version])
          ->order(["cylinder_number"=>"ASC"])->toArray();
    
        }
  
        $countseikeiki = count($ProductMaterialMachines);
        $this->set('countseikeiki', $countseikeiki);

        for($k=0; $k<$countseikeiki; $k++){

          $j = $k + 1;
          ${"product_material_machine_id".$j} = $ProductMaterialMachines[$k]["id"];
          $this->set('product_material_machine_id'.$j, ${"product_material_machine_id".$j});
          ${"cylinder_name".$j} = $ProductMaterialMachines[$k]["cylinder_name"];
          $this->set('cylinder_name'.$j, ${"cylinder_name".$j});

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

          }else{

            $Products = $this->Products->find()
            ->where(['product_code' => $product_code])->toArray();

            return $this->redirect(['action' => 'kensakupre',
            's' => ['mess' => "「".$Products[0]["name"]."」は成形温度登録がされていません。s"]]);

          }

        }

      }else{

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "「".$Products[0]["name"]."」は成形温度登録がされていません。"]]);

      }

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

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

      $data = $this->request->getData();
      $user_code = $data["user_code"];

      $userlogincheck = $user_code."_".$data["password"];

      $htmlinputstaff = new htmlLogin();//クラスを使用
  //    $arraylogindate = $htmlinputstaff->inputstaffprogram($user_code);//クラスを使用
      $arraylogindate = $htmlinputstaff->inputstaffprogram($userlogincheck);//クラスを使用210608更新

      if($arraylogindate[0] === "no_staff"){

        return $this->redirect(['action' => 'addlogin',
        's' => ['mess' => "社員コードかパスワードに誤りがあります。もう一度やり直してください。"]]);

      }else{

        $staff_id = $arraylogindate[0];
        $staff_name = $arraylogindate[1];
        $this->set('staff_id', $staff_id);
        $this->set('staff_name', $staff_name);

      }

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

      }

      if(isset($ProductConditionParents[0])){

        $version = $ProductConditionParents[0]["version"];

        $ProductMaterialMachines= $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['Products.product_code' => $product_code,
        'ProductConditionParents.delete_flag' => 0,
        'ProductMaterialMachines.delete_flag' => 0,
        'ProductConditionParents.version' => $version])
        ->order(["cylinder_number"=>"ASC"])->toArray();

        if(!isset($ProductMaterialMachines[0])){//長さ違いのデータがあればそれを持ってくる

          $product_code_ini = substr($product_code, 0, 11);
          $ProductMaterialMachines= $this->ProductMaterialMachines->find()
          ->contain(['ProductConditionParents' => ["Products"]])
          ->where(['Products.product_code like' => $product_code_ini.'%',
          'ProductConditionParents.delete_flag' => 0,
          'ProductMaterialMachines.delete_flag' => 0,
          'ProductConditionParents.version' => $version])
          ->order(["cylinder_number"=>"ASC"])->toArray();
  
        }
        
        $countseikeiki = count($ProductMaterialMachines);
        $this->set('countseikeiki', $countseikeiki);

        for($k=0; $k<$countseikeiki; $k++){

          $j = $k + 1;
          ${"product_material_machine_id".$j} = $ProductMaterialMachines[$k]["id"];
          $this->set('product_material_machine_id'.$j, ${"product_material_machine_id".$j});
          ${"cylinder_name".$j} = $ProductMaterialMachines[$k]["cylinder_name"];
          $this->set('cylinder_name'.$j, ${"cylinder_name".$j});

          $ProductConditonChildren = $this->ProductConditonChildren->find()
          ->where(['product_material_machine_id' => ${"product_material_machine_id".$j}, 'cylinder_name' => ${"cylinder_name".$j}, 'delete_flag' => 0])
          ->toArray();

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

      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

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

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $countseikeiki = $data["countseikeiki"];
      $this->set('countseikeiki', $countseikeiki);
      $pickup_speed = $data["pickup_speed"];
      $this->set('pickup_speed', $pickup_speed);

      for($k=0; $k<$countseikeiki; $k++){

        $j = $k + 1;
        ${"product_material_machine_id".$j} = $data['product_material_machine_id'.$j];
        $this->set('product_material_machine_id'.$j, ${"product_material_machine_id".$j});
        ${"cylinder_name".$j} = $data['cylinder_name'.$j];
        $this->set('cylinder_name'.$j, ${"cylinder_name".$j});

        for($i=1; $i<=7; $i++){
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

      if($data["check"] < 1){
        $mes = "上記のように更新します。よろしければ決定ボタンを押してください。";
      }else{
        $mes = "上記のデータを削除します。よろしければ決定ボタンを押してください。";
      }
      $this->set('mes', $mes);

      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

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

      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

      $countseikeiki = $data["countseikeiki"];
      $this->set('countseikeiki', $countseikeiki);
      $pickup_speed = $data["pickup_speed"];
      $this->set('pickup_speed', $pickup_speed);

      $tourokuProductConditonChildren = array();

      for($k=0; $k<$countseikeiki; $k++){

        $j = $k + 1;
        ${"product_material_machine_id".$j} = $data['product_material_machine_id'.$j];
        $this->set('product_material_machine_id'.$j, ${"product_material_machine_id".$j});
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

        $updateProductConditonChildren[] = [
          "product_material_machine_id" => $data['product_material_machine_id'.$j],
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
/*
      echo "<pre>";
      print_r($updateProductConditonChildren);
      echo "</pre>";
*/
          if($data["check"] < 1){//削除ではない場合

            $ProductConditonChildren = $this->ProductConditonChildren->patchEntities($this->ProductConditonChildren->newEntity(), $updateProductConditonChildren);
            $connection = ConnectionManager::get('default');//トランザクション1
            // トランザクション開始2
            $connection->begin();//トランザクション3
            try {//トランザクション4
              if ($this->ProductConditonChildren->saveMany($ProductConditonChildren)) {

                for($i=1; $i<=$countseikeiki; $i++){

                  $this->ProductConditonChildren->updateAll(
                    [ 'delete_flag' => 1,
                      'updated_at' => date('Y-m-d H:i:s'),
                      'updated_staff' => $staff_id],
                    ['id'  => $data['idmoto'.$i]]);

                }

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

          }else{//削除の場合

            for($i=1; $i<=$countseikeiki; $i++){

                $ProductConditonChildren = $this->ProductConditonChildren
                ->patchEntity($this->ProductConditonChildren->newEntity(), $data);
                $connection = ConnectionManager::get('default');//トランザクション1
                // トランザクション開始2
                $connection->begin();//トランザクション3
                try {//トランザクション4
                  if ($this->ProductConditonChildren->updateAll(
                    [ 'delete_flag' => 1,
                      'updated_at' => date('Y-m-d H:i:s'),
                      'updated_staff' => $staff_id],
                    ['id'  => $data['idmoto'.$i]])
                  ) {

                    $mes = "削除されました。";
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

    }

}
