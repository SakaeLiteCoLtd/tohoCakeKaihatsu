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

class KensahyougenryousController extends AppController
{

  	public function beforeFilter(Event $event){
  		parent::beforeFilter($event);

  		// 認証なしでアクセスできるアクションの指定
  		$this->Auth->allow(["addlogin","addformpre","addform","addcomfirm","adddo"]);
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

      $user_code = $data["user_code"];
/*
      //以下はクラスに設定
      $Users= $this->Users->find()->contain(["Staffs"])->where(['user_code' => $user_code, 'Users.delete_flag' => 0])->toArray();

      if(isset($Users[0])){

        $staff_id = $Users[0]["staff_id"];
        $this->set('staff_id', $staff_id);
        $staff_name= $Users[0]["staff"]["name"];
        $this->set('staff_name', $staff_name);

      }else{

        return $this->redirect(['action' => 'addlogin',
        's' => ['mess' => "社員コードが存在しません。もう一度やり直してください。"]]);

      }
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

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }

    }

    public function addform()
    {
      session_start();
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');

      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $today = date('Y年n月j日');
      $this->set('today', $today);

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

      $Products= $this->Products->find()->contain(["Customers"])->where(['product_code' => $product_code])->toArray();

      if(isset($Products[0])){

        $name = $Products[0]["name"];
        $this->set('name', $name);
        $customer= $Products[0]["customer"]["name"];
        $this->set('customer', $customer);

      }else{

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は存在しません。"]]);

      }

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

        if(!isset($_SESSION)){//sessionsyuuseituika
        session_start();
        }

        $_SESSION['kensahyougenryoudata'] = array();
        $_SESSION['kensahyougenryoudata'] = $data;

        return $this->redirect(['action' => 'addcomfirm']);

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
        ${"mixing_ratio".$j.$i} = "";
        $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
        ${"dry_temp".$j.$i} = "";
        $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
        ${"dry_hour".$j.$i} = "";
        $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
        ${"recycled_mixing_ratio".$j.$i} = "";
        $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});

      }

    }

    public function addcomfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $arrayKensahyougenryoudatas = $_SESSION['kensahyougenryoudata'];
      $_SESSION['kensahyougenryoudata'] = array();

      $data = $arrayKensahyougenryoudatas;
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $today = date('Y年n月j日');
      $this->set('today', $today);

      $staff_id = $data["staff_id"];
      $this->set('staff_id', $staff_id);
      $staff_name = $data["staff_name"];
      $this->set('staff_name', $staff_name);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);

      $Products= $this->Products->find()->contain(["Customers"])->where(['product_code' => $product_code])->toArray();
      $name = $Products[0]["name"];
      $this->set('name', $name);
      $customer= $Products[0]["customer"]["name"];
      $this->set('customer', $customer);

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

    }

    public function adddo()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $today = date('Y年n月j日');
      $this->set('today', $today);

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

      $Products= $this->Products->find()->contain(["Customers"])->where(['product_code' => $product_code])->toArray();
      $product_id = $Products[0]["id"];
      $name = $Products[0]["name"];
      $this->set('name', $name);
      $customer= $Products[0]["customer"]["name"];
      $this->set('customer', $customer);

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

      $ProductConditionParents = $this->ProductConditionParents->find()
      ->where(['product_id' => $product_id])->order(["version"=>"DESC"])->toArray();

      if(isset($ProductConditionParents[0])){
        $version = $ProductConditionParents[0]["version"] + 1;
      }else{
        $version = 1;
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

          $ProductConditionParents = $this->ProductConditionParents->find()
          ->where(['product_id' => $product_id, 'version' => $version])->toArray();

          for($j=1; $j<=$tuikaseikeiki; $j++){

            $tourokuProductMaterialMachine = array();

            $tourokuProductMaterialMachine = [
              "product_condition_parent_id" => $ProductConditionParents[0]["id"],
              "cylinder_numer" => $j,
              "cylinder_name" => $data['cylinder_name'.$j],
              "delete_flag" => 0,
              'created_at' => date("Y-m-d H:i:s"),
              "created_staff" => $staff_id
            ];

            $ProductMaterialMachines = $this->ProductMaterialMachines
            ->patchEntity($this->ProductMaterialMachines->newEntity(), $tourokuProductMaterialMachine);
            if ($this->ProductMaterialMachines->save($ProductMaterialMachines)) {

              $ProductMaterialMachines = $this->ProductMaterialMachines->find()
              ->where(['product_condition_parent_id' => $ProductConditionParents[0]["id"], 'cylinder_numer' => $j])->toArray();

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

/*
      echo "<pre>";
      print_r($tourokuProductMaterialMachine);
      echo "</pre>";
      echo "<pre>";
      print_r($tourokuProductMachineMaterial);
      echo "</pre>";
*/

    }


}
