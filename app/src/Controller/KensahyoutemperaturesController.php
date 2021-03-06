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
use App\myClass\classprograms\htmlkensahyoulogincheck;//myClassフォルダに配置したクラスを使用
$htmlkensahyoulogincheck = new htmlkensahyoulogincheck();

class KensahyoutemperaturesController extends AppController
{

  	public function beforeFilter(Event $event){
  		parent::beforeFilter($event);

  		// 認証なしでアクセスできるアクションの指定
  		$this->Auth->allow(["menu"]);
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

    public function kensakumenu()
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

           return $this->redirect(['action' => 'addformpregouki',
           's' => ['product_code' => $product_code, 'user_code' => $user_code]]);

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

       $Data=$this->request->query('s');
       if(isset($Data["mess"])){
         $mess = $Data["mess"];
         $this->set('mess',$mess);
       }else{
         $mess = "";
         $this->set('mess',$mess);
       }

     }
     
     echo "<pre>";//フォームの再読み込みの防止
     print_r("");
     echo "</pre>";

    }

    public function addformpregouki()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data = $this->request->query('s');
      $product_code = $Data["product_code"];
      $this->set('product_code', $product_code);
      $user_code = $Data["user_code"];
      $this->set('user_code', $user_code);

      $data = $this->request->getData();

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

      }

      $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'machine_num' => $machine_num, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'machine_num' => $machine_num, 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

      }

      if(isset($ProductConditionParents[0])){

        $version = $ProductConditionParents[0]["version"];
        $machine_num = $ProductConditionParents[0]["machine_num"];
        $this->set('machine_num', $machine_num);

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
        ${"cylinder_name".$j} = $ProductMaterialMachines[$k]["cylinder_name"];
        $this->set('cylinder_name'.$j, ${"cylinder_name".$j});

      }

      $arrScrewMesh = [
        '-' => '',
        '#20' => '#20',
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
      ->where(['machine_num' => $machine_num, 'version' => $version, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->toArray();

      if(!isset($ProductMachineMaterials[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['machine_num' => $machine_num, 'version' => $version, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->toArray();
  
      }

      if(isset($ProductMachineMaterials[0])){

        $product_code_machine_num = $product_code."_".$machine_num;
        $htmlkensahyougenryouheader = new htmlkensahyouprogram();
        $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code_machine_num);
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

      echo "<pre>";//フォームの再読み込みの防止
      print_r("");
      echo "</pre>";

    }

    public function addcomfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();

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

      $product_code_machine_num = $product_code."_".$machine_num;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code_machine_num);
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

      $product_code_machine_num = $product_code."_".$machine_num;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code_machine_num);
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

     echo "<pre>";//フォームの再読み込みの防止
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
            's' => ['mess' => "「".$Products[0]["name"]."」は成形温度登録がされていません"]]);

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

      $product_code_machine_num = $product_code."_".$machine_num;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code_machine_num);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

      echo "<pre>";//フォームの再読み込みの防止
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

      echo "<pre>";//フォームの再読み込みの防止
      print_r("");
      echo "</pre>";

    }

    public function editform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
      
      $session = $this->request->getSession();
      $datasession = $session->read();
      $Users= $this->Users->find('all')->contain(["Staffs"])->where(['user_code' => $datasession['Auth']['User']['user_code'], 'Users.delete_flag' => 0])->toArray();
      $user_code = $datasession['Auth']['User']['user_code'];
      $this->set('user_code', $user_code);
      $staff_id = $Users[0]["staff_id"];
      $this->set('staff_id', $staff_id);
      $staff_name= $Users[0]["staff"]["name"];
      $this->set('staff_name', $staff_name);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

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
        '#20' => '#20',
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

      $product_code_machine_num = $product_code."_".$machine_num;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code_machine_num);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

      echo "<pre>";//フォームの再読み込みの防止
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
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);

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

      $product_code_machine_num = $product_code."_".$machine_num;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code_machine_num);
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
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $product_code_machine_num = $product_code."_".$machine_num;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code_machine_num);
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

    public function kensakurirekipre()
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

           return $this->redirect(['action' => 'kensakurirekigouki',
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

     echo "<pre>";//フォームの再読み込みの防止
     print_r("");
     echo "</pre>";

    }

    public function kensakurirekigouki()
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

      $arrGouki = array();
      for($k=0; $k<count($ProductConditionParents); $k++){
        $array = array($ProductConditionParents[$k]["machine_num"] => $ProductConditionParents[$k]["machine_num"]);
        $arrGouki = $arrGouki + $array;
      }
      $this->set('arrGouki', $arrGouki);

      if(isset($data["next"])){//「次へ」ボタンを押したとき

        $product_code = $data["product_code"];
        $machine_num = $data["machine_num"];

        return $this->redirect(['action' => 'kensakurirekiichiran',
        's' => ['product_code' => $product_code, 'machine_num' => $machine_num]]);

      }
   
    }

    public function kensakurirekiichiran()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data=$this->request->query('s');
      if(isset($Data["product_code"])){
        $product_code = $Data["product_code"];
        $this->set('product_code', $product_code);
        $machine_num = $Data["machine_num"];
        $this->set('machine_num', $machine_num);
          }else{
          $data = $this->request->getData();
          $product_code = $data["product_code"];
          $this->set('product_code', $product_code);
          $machine_num = $data["machine_num"];
          $this->set('machine_num', $machine_num);
              }

      $Products = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $product_name = $Products[0]["name"];
      $this->set('product_name', $product_name);

      $product_code_ini = substr($product_code, 0, 11);
      $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%'])
      ->order(["version"=>"DESC"])->toArray();

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
          ${"cylinder_name".$j} = $ProductMaterialMachines[$k]["cylinder_name"];
          $this->set('cylinder_name'.$j, ${"cylinder_name".$j});

          $ProductConditonChildren = $this->ProductConditonChildren->find()
          ->where(['product_material_machine_id' => ${"product_material_machine_id".$j}, 'cylinder_name' => ${"cylinder_name".$j}])
          ->order(["ProductConditonChildren.created_at"=>"DESC"])->toArray();
    
          if(isset($ProductConditonChildren[0])){

            for($l=0; $l<count($ProductConditonChildren); $l++){

              $created_at = $ProductConditonChildren[$l]["created_at"]->format('Y-m-d H:i:s');
              if(isset($ProductConditonChildren[$l]["updated_at"])){
                $updated_at = $ProductConditonChildren[$l]["updated_at"]->format('Y-m-d H:i:s');
              }else{
                $updated_at = "使用中";
              }

              $arrDates[] = [
                "created_at" => $created_at,
                "updated_at" => $updated_at
              ];

              }
      
          }else{

            $Products = $this->Products->find()
            ->where(['product_code' => $product_code])->toArray();

            return $this->redirect(['action' => 'kensakurirekipre',
            's' => ['mess' => "「".$Products[0]["name"]."」は成形温度登録がされていません。s"]]);

          }

        }

      }else{

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'kensakurirekipre',
        's' => ['mess' => "「".$Products[0]["name"]."」は成形温度登録がされていません。"]]);

      }

      foreach($arrDates as $key => $value)
      {
          $sort_keys[$key] = $value['created_at'];
      }
      array_multisort($sort_keys, SORT_DESC, $arrDates);

      $countDate = count($arrDates);
      for($i=0; $i<$countDate; $i++){

        if($i > 0 && $arrDates[$i-1]["created_at"] == $arrDates[$i]["created_at"]){
          unset($arrDates[$i-1]);
        }

      }
      $arrDates = array_values($arrDates);

      $this->set('arrDates',$arrDates);

      $mes = '＊最新の上位３つのデータです。';
      $this->set('mes', $mes);
      $checksaerch = 0;
      $this->set('checksaerch', $checksaerch);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $product_code_machine_num = $product_code."_".$machine_num;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code_machine_num);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

      if(isset($data['saerch'])){

        $startY = $data['start']['year'];
    		$startM = $data['start']['month'];
    		$startD = $data['start']['day'];
        $startYMD = $startY."-".$startM."-".$startD." 00:00";

        $endY = $data['end']['year'];
    		$endM = $data['end']['month'];
    		$endD = $data['end']['day'];
        $endYMD = $endY."-".$endM."-".$endD." 23:59";

        $countDate = count($arrDates);
        for($i=0; $i<$countDate; $i++){
  
          if($startYMD < $arrDates[$i]["created_at"] && $arrDates[$i]["created_at"] < $endYMD){
            //OK
          }else{
            unset($arrDates[$i]);
          }
  
        }
        $arrDates = array_values($arrDates);

        $this->set('arrDates',$arrDates);
  
        $mes = "検索期間： ".$startY."-".$startM."-".$startD .' ～ '.$endY."-".$endM."-".$endD;
        $this->set('mes', $mes);

        $checksaerch = 1;
        $this->set('checksaerch', $checksaerch);

      }

      echo "<pre>";//フォームの再読み込みの防止
      print_r("");
      echo "</pre>";

    }

    public function kensakurirekihyouji()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->query('s');

      $arrdata = explode("_",$data);

      $created_at = $arrdata[0];
      $machine_num = $arrdata[1];
      $this->set('machine_num', $machine_num);
      $product_code = $arrdata[2];
      $this->set('product_code', $product_code);
      $product_code_ini = substr($product_code, 0, 11);

      $ProductConditonChildren = $this->ProductConditonChildren->find()
      ->where(['created_at' => $created_at])
      ->order(["cylinder_number"=>"ASC"])->toArray();

      $countseikeiki = count($ProductConditonChildren);
      $this->set('countseikeiki', $countseikeiki);

      for($k=0; $k<$countseikeiki; $k++){

        $j = $k + 1;
        ${"extrude_roatation".$j} = $ProductConditonChildren[$k]["extrude_roatation"];
        $this->set('extrude_roatation'.$j, ${"extrude_roatation".$j});
        ${"extrusion_load".$j} = $ProductConditonChildren[$k]["extrusion_load"];
        $this->set('extrusion_load'.$j, ${"extrusion_load".$j});
        ${"cylinder_name".$j} = $ProductConditonChildren[$k]["cylinder_name"];
        $this->set('cylinder_name'.$j, ${"cylinder_name".$j});

        for($n=1; $n<8; $n++){
          ${"temp_".$n.$j} = $ProductConditonChildren[$k]["temp_".$n];
          $this->set('temp_'.$n.$j, ${"temp_".$n.$j});
        }

        $pickup_speed = $ProductConditonChildren[$k]["pickup_speed"];
        $this->set('pickup_speed', $pickup_speed);

        ${"screw_mesh_1".$j} = $ProductConditonChildren[$k]['screw_mesh_1'];
        $this->set('screw_mesh_1'.$j, ${"screw_mesh_1".$j});
        ${"screw_number_1".$j} = $ProductConditonChildren[$k]['screw_number_1'];
        $this->set('screw_number_1'.$j, ${"screw_number_1".$j});
        ${"screw_mesh_2".$j} = $ProductConditonChildren[$k]['screw_mesh_2'];
        $this->set('screw_mesh_2'.$j, ${"screw_mesh_2".$j});
        ${"screw_number_2".$j} = $ProductConditonChildren[$k]['screw_number_2'];
        $this->set('screw_number_2'.$j, ${"screw_number_2".$j});
        ${"screw_mesh_3".$j} = $ProductConditonChildren[$k]['screw_mesh_3'];
        $this->set('screw_mesh_3'.$j, ${"screw_mesh_3".$j});
        ${"screw_number_3".$j} = $ProductConditonChildren[$k]['screw_number_3'];
        $this->set('screw_number_3'.$j, ${"screw_number_3".$j});
        ${"screw".$j} = $ProductConditonChildren[$k]['screw'];
        $this->set('screw'.$j, ${"screw".$j});

      }

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();//これは最新のものを表示
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $arrcreated_at_machine_num = $created_at."_".$machine_num;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();//その時の原料情報を取得
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheaderrireki($arrcreated_at_machine_num);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

      echo "<pre>";//フォームの再読み込みの防止
      print_r("");
      echo "</pre>";

    }

}
