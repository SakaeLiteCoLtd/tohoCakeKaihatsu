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

      $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();
      $version = $ProductConditionParents[0]["version"];

      $ProductMaterialMachines= $this->ProductMaterialMachines->find()
      ->contain(['ProductConditionParents' => ["Products"]])
      ->where(['Products.product_code' => $product_code,
      'ProductConditionParents.delete_flag' => 0,
      'ProductMaterialMachines.delete_flag' => 0,
      'ProductConditionParents.version' => $version])
      ->order(["cylinder_number"=>"ASC"])->toArray();

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
        '-' => '',
        'フルフライト' => 'フルフライト',
        'ミキシング' => 'ミキシング',
        'ダルメージ' => 'ダルメージ'
              ];
      $this->set('arrScrew',$arrScrew);

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
        ${"screw_1".$j} = $data['screw_1'.$j];
        $this->set('screw_1'.$j, ${"screw_1".$j});
        ${"screw_mesh_2".$j} = $data['screw_mesh_2'.$j];
        $this->set('screw_mesh_2'.$j, ${"screw_mesh_2".$j});
        ${"screw_number_2".$j} = $data['screw_number_2'.$j];
        $this->set('screw_number_2'.$j, ${"screw_number_2".$j});
        ${"screw_2".$j} = $data['screw_2'.$j];
        $this->set('screw_2'.$j, ${"screw_2".$j});
        ${"screw_mesh_3".$j} = $data['screw_mesh_3'.$j];
        $this->set('screw_mesh_3'.$j, ${"screw_mesh_3".$j});
        ${"screw_number_3".$j} = $data['screw_number_3'.$j];
        $this->set('screw_number_3'.$j, ${"screw_number_3".$j});
        ${"screw_3".$j} = $data['screw_3'.$j];
        $this->set('screw_3'.$j, ${"screw_3".$j});

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
        ${"screw_1".$j} = $data['screw_1'.$j];
        $this->set('screw_1'.$j, ${"screw_1".$j});
        ${"screw_mesh_2".$j} = $data['screw_mesh_2'.$j];
        $this->set('screw_mesh_2'.$j, ${"screw_mesh_2".$j});
        ${"screw_number_2".$j} = $data['screw_number_2'.$j];
        $this->set('screw_number_2'.$j, ${"screw_number_2".$j});
        ${"screw_2".$j} = $data['screw_2'.$j];
        $this->set('screw_2'.$j, ${"screw_2".$j});
        ${"screw_mesh_3".$j} = $data['screw_mesh_3'.$j];
        $this->set('screw_mesh_3'.$j, ${"screw_mesh_3".$j});
        ${"screw_number_3".$j} = $data['screw_number_3'.$j];
        $this->set('screw_number_3'.$j, ${"screw_number_3".$j});
        ${"screw_3".$j} = $data['screw_3'.$j];
        $this->set('screw_3'.$j, ${"screw_3".$j});

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
          "screw_1" => $data['screw_1'.$j],
          "screw_mesh_2" => $data['screw_mesh_2'.$j],
          "screw_number_2" => $data['screw_number_2'.$j],
          "screw_2" => $data['screw_2'.$j],
          "screw_mesh_3" => $data['screw_mesh_3'.$j],
          "screw_number_3" => $data['screw_number_3'.$j],
          "screw_3" => $data['screw_3'.$j],
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

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if($ProductConditionParents[0]){

        $version = $ProductConditionParents[0]["version"];

        $ProductMaterialMachines= $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['Products.product_code' => $product_code,
        'ProductConditionParents.delete_flag' => 0,
        'ProductMaterialMachines.delete_flag' => 0,
        'ProductConditionParents.version' => $version])
        ->order(["cylinder_number"=>"ASC"])->toArray();

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
            ${"screw_1".$j} = $ProductConditonChildren[0]['screw_1'];
            $this->set('screw_1'.$j, ${"screw_1".$j});
            ${"screw_mesh_2".$j} = $ProductConditonChildren[0]['screw_mesh_2'];
            $this->set('screw_mesh_2'.$j, ${"screw_mesh_2".$j});
            ${"screw_number_2".$j} = $ProductConditonChildren[0]['screw_number_2'];
            $this->set('screw_number_2'.$j, ${"screw_number_2".$j});
            ${"screw_2".$j} = $ProductConditonChildren[0]['screw_2'];
            $this->set('screw_2'.$j, ${"screw_2".$j});
            ${"screw_mesh_3".$j} = $ProductConditonChildren[0]['screw_mesh_3'];
            $this->set('screw_mesh_3'.$j, ${"screw_mesh_3".$j});
            ${"screw_number_3".$j} = $ProductConditonChildren[0]['screw_number_3'];
            $this->set('screw_number_3'.$j, ${"screw_number_3".$j});
            ${"screw_3".$j} = $ProductConditonChildren[0]['screw_3'];
            $this->set('screw_3'.$j, ${"screw_3".$j});

          }else{

            return $this->redirect(['action' => 'kensakupre',
            's' => ['mess' => "管理No.「".$product_code."」の製品は成形温度登録がされていません。"]]);

          }

        }

      }else{

        return $this->redirect(['action' => 'kensakupre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は成形温度登録がされていません。"]]);

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

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if($ProductConditionParents[0]){

        $version = $ProductConditionParents[0]["version"];

        $ProductMaterialMachines= $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['Products.product_code' => $product_code,
        'ProductConditionParents.delete_flag' => 0,
        'ProductMaterialMachines.delete_flag' => 0,
        'ProductConditionParents.version' => $version])
        ->order(["cylinder_number"=>"ASC"])->toArray();

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
          ${"screw_1".$j} = $ProductConditonChildren[0]['screw_1'];
          $this->set('screw_1'.$j, ${"screw_1".$j});
          ${"screw_mesh_2".$j} = $ProductConditonChildren[0]['screw_mesh_2'];
          $this->set('screw_mesh_2'.$j, ${"screw_mesh_2".$j});
          ${"screw_number_2".$j} = $ProductConditonChildren[0]['screw_number_2'];
          $this->set('screw_number_2'.$j, ${"screw_number_2".$j});
          ${"screw_2".$j} = $ProductConditonChildren[0]['screw_2'];
          $this->set('screw_2'.$j, ${"screw_2".$j});
          ${"screw_mesh_3".$j} = $ProductConditonChildren[0]['screw_mesh_3'];
          $this->set('screw_mesh_3'.$j, ${"screw_mesh_3".$j});
          ${"screw_number_3".$j} = $ProductConditonChildren[0]['screw_number_3'];
          $this->set('screw_number_3'.$j, ${"screw_number_3".$j});
          ${"screw_3".$j} = $ProductConditonChildren[0]['screw_3'];
          $this->set('screw_3'.$j, ${"screw_3".$j});

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
        '-' => '',
        'フルフライト' => 'フルフライト',
        'ミキシング' => 'ミキシング',
        'ダルメージ' => 'ダルメージ'
              ];
      $this->set('arrScrew',$arrScrew);

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
        ${"screw_1".$j} = $data['screw_1'.$j];
        $this->set('screw_1'.$j, ${"screw_1".$j});
        ${"screw_mesh_2".$j} = $data['screw_mesh_2'.$j];
        $this->set('screw_mesh_2'.$j, ${"screw_mesh_2".$j});
        ${"screw_number_2".$j} = $data['screw_number_2'.$j];
        $this->set('screw_number_2'.$j, ${"screw_number_2".$j});
        ${"screw_2".$j} = $data['screw_2'.$j];
        $this->set('screw_2'.$j, ${"screw_2".$j});
        ${"screw_mesh_3".$j} = $data['screw_mesh_3'.$j];
        $this->set('screw_mesh_3'.$j, ${"screw_mesh_3".$j});
        ${"screw_number_3".$j} = $data['screw_number_3'.$j];
        $this->set('screw_number_3'.$j, ${"screw_number_3".$j});
        ${"screw_3".$j} = $data['screw_3'.$j];
        $this->set('screw_3'.$j, ${"screw_3".$j});

      }

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

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

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
        ${"screw_1".$j} = $data['screw_1'.$j];
        $this->set('screw_1'.$j, ${"screw_1".$j});
        ${"screw_mesh_2".$j} = $data['screw_mesh_2'.$j];
        $this->set('screw_mesh_2'.$j, ${"screw_mesh_2".$j});
        ${"screw_number_2".$j} = $data['screw_number_2'.$j];
        $this->set('screw_number_2'.$j, ${"screw_number_2".$j});
        ${"screw_2".$j} = $data['screw_2'.$j];
        $this->set('screw_2'.$j, ${"screw_2".$j});
        ${"screw_mesh_3".$j} = $data['screw_mesh_3'.$j];
        $this->set('screw_mesh_3'.$j, ${"screw_mesh_3".$j});
        ${"screw_number_3".$j} = $data['screw_number_3'.$j];
        $this->set('screw_number_3'.$j, ${"screw_number_3".$j});
        ${"screw_3".$j} = $data['screw_3'.$j];
        $this->set('screw_3'.$j, ${"screw_3".$j});

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
          "screw_1" => $data['screw_1'.$j],
          "screw_mesh_2" => $data['screw_mesh_2'.$j],
          "screw_number_2" => $data['screw_number_2'.$j],
          "screw_2" => $data['screw_2'.$j],
          "screw_mesh_3" => $data['screw_mesh_3'.$j],
          "screw_number_3" => $data['screw_number_3'.$j],
          "screw_3" => $data['screw_3'.$j],
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
