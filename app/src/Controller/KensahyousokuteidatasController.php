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

class KensahyousokuteidatasController extends AppController
{

  	public function beforeFilter(Event $event){
  		parent::beforeFilter($event);

  		// 認証なしでアクセスできるアクションの指定
      $this->Auth->allow([//"menu",
      "addform", "addfinishform", "addfinishconfirm", "addfinishdo"
//      , "kensakumenu", "kensakupre", "kensakuikkatsupre", "kensakudate", "kensakugouki"
 //     , "kensakuhyouji", "kensatyuproducts", "kensatyuichiran", "kensakuikkatsujouken"
  //    , "kensakuikkatsugouki"
    //  , "kensakuikkatsudate", "kensakuikkatsuichiran"
      ]);
  	}

      public function initialize()
    {
     parent::initialize();
     $this->Users = TableRegistry::get('Users');
     $this->Staffs = TableRegistry::get('Staffs');
     $this->Products = TableRegistry::get('Products');
     $this->Customers = TableRegistry::get('Customers');
     $this->Materials = TableRegistry::get('Materials');
     $this->InspectionStandardSizeChildren = TableRegistry::get('InspectionStandardSizeChildren');
     $this->InspectionStandardSizeParents = TableRegistry::get('InspectionStandardSizeParents');
     $this->ProductConditionParents = TableRegistry::get('ProductConditionParents');
     $this->ProductMaterialMachines = TableRegistry::get('ProductMaterialMachines');
     $this->ProductMachineMaterials = TableRegistry::get('ProductMachineMaterials');
     $this->ProductConditonChildren = TableRegistry::get('ProductConditonChildren');
     $this->InspectionDataResultParents = TableRegistry::get('InspectionDataResultParents');
     $this->InspectionDataResultChildren = TableRegistry::get('InspectionDataResultChildren');
     $this->InspectionDataConditonChildren = TableRegistry::get('InspectionDataConditonChildren');
     $this->InspectionDataConditonParents = TableRegistry::get('InspectionDataConditonParents');
     $this->DailyReports = TableRegistry::get('DailyReports');
     $this->Linenames = TableRegistry::get('Linenames');

     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');

     if(!isset($_SESSION)){//フォーム再送信の確認対策
       session_start();
     }
     header('Expires:');
     header('Cache-Control:');
     header('Pragma:');
/*
     $Product_name_list = $this->Products->find()
     ->where(['delete_flag' => 0])->toArray();

     $arrProduct_name_list = array();
     for($j=0; $j<count($Product_name_list); $j++){
       array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
     }

    $this->set('arrProduct_name_list', $arrProduct_name_list);
*/
    }

    public function menu()
    {
      /*
      echo "<pre>";
      print_r(phpinfo());
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

      $session = $this->request->getSession();
      $datasession = $session->read();
      $staff_id = $datasession['Auth']['User']['staff_id'];
      $Staffs = $this->Staffs->find()->where(['id' => $staff_id])->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      $datetimesta = date('Y-m-d 00:00:00');//今日の0時が境目

      //検査中一覧のクラス
      $datetimesta_factory = $datetimesta."_".$factory_id;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $arrInspectionDataResultParents = $htmlkensahyougenryouheader->toujitsuichiran($datetimesta_factory);
      $this->set('arrInspectionDataResultParents', $arrInspectionDataResultParents);

      //未検査一覧のクラス
      $datetimesta_factory = $datetimesta."_".$factory_id;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $arrInspectionDataResultParentnotfin = $htmlkensahyougenryouheader->mikanryouichiran($datetimesta_factory);
      $this->set('arrInspectionDataResultParentnotfin', $arrInspectionDataResultParentnotfin);

    }

    public function kensakumenu()
    {
/*
 //     $cmd = 'dir';
      $cmd = "wkhtmltopdf -V";
      exec($cmd, $opt, $return_ver);
      echo '実行結果：'.$return_ver;//0成功1失敗

 //     exec("wkhtmltopdf https://google.com google2.pdf 2> result.txt");
*/
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
      $staff_id = $datasession['Auth']['User']['staff_id'];
      $Staffs = $this->Staffs->find()->where(['id' => $staff_id])->toArray();
      $factory_id = $Staffs[0]["factory_id"];

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

       $staff_id = $data["staff_id"];
       $this->set('staff_id', $staff_id);
       $staff_name = $data["staff_name"];
       $this->set('staff_name', $staff_name);
       $user_code = $data["user_code"];
       $this->set('user_code', $user_code);

       if($factory_id == 5){//本部の人がログインしている場合

        $Product_name_list = $this->Products->find()
        ->contain(['Customers'])
        ->where(['Customers.name' => $data["customer_name"], 'Products.status_kensahyou' => 0, 'Products.delete_flag' => 0])
        ->toArray();
 
      }else{

        $Product_name_list = $this->Products->find()
        ->contain(['Customers'])
        ->where(['Customers.name' => $data["customer_name"], 'Products.status_kensahyou' => 0, 'Products.delete_flag' => 0,
        'OR' => [['Products.factory_id' => $factory_id], ['Products.factory_id' => 5]]])
        ->toArray();

      }

       if(count($Product_name_list) < 1){//顧客名にミスがある場合

         $mess = "入力された顧客の製品は登録されていません。確認してください。";
         $this->set('mess',$mess);

         if($factory_id == 5){//本部の人がログインしている場合

          $Product_name_list = $this->Products->find()
          ->where(['status_kensahyou' => 0, 'delete_flag' => 0])
          ->toArray();
  
        }else{
  
          $Product_name_list = $this->Products->find()
          ->where(['status_kensahyou' => 0, 'delete_flag' => 0,
          'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
          ->toArray();
  
        }

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
        if(isset($product_name_length[1])){

          $length = str_replace('mm', '', $product_name_length[1]);

          if($factory_id == 5){//本部の人がログインしている場合

            $Products = $this->Products->find()
            ->where(['status_kensahyou' => 0, 'name' => $name, 'length' => $length, 'delete_flag' => 0])
            ->toArray();
    
          }else{
    
            $Products = $this->Products->find()
            ->where(['status_kensahyou' => 0, 'name' => $name, 'length' => $length, 'delete_flag' => 0,
            'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
            ->toArray();
    
          }

         }else{

          $length = "";

          if($factory_id == 5){//本部の人がログインしている場合

            $Products = $this->Products->find()
            ->where(['status_kensahyou' => 0, 'name' => $name, 'delete_flag' => 0])
            ->toArray();
    
          }else{
    
            $Products = $this->Products->find()
            ->where(['status_kensahyou' => 0, 'name' => $name, 'delete_flag' => 0,
            'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
            ->toArray();
    
          }

         }

         if(isset($Products[0])){

           $product_code = $Products[0]["product_code"];
           $product_code_ini = substr($product_code, 0, 11);

          //検査表の登録ができているかチェック
          $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren->find()
          ->contain(['InspectionStandardSizeParents' => ["Products"]])
          ->where(['Products.product_code like' => $product_code_ini.'%',
          'InspectionStandardSizeParents.is_active' => 0,
          'InspectionStandardSizeParents.delete_flag' => 0,
          'InspectionStandardSizeChildren.delete_flag' => 0])
          ->order(["inspection_standard_size_parent_id"=>"ASC"])->toArray();
  
          $ProductMachineMaterials = $this->ProductMachineMaterials->find()
          ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
          ->where(['Products.product_code like' => $product_code_ini.'%',
          'ProductConditionParents.delete_flag' => 0,'ProductMachineMaterials.delete_flag' => 0])
          ->order(["ProductMaterialMachines.id"=>"ASC"])->toArray();
  
          if(isset($InspectionStandardSizeChildren[0]) && isset($ProductMachineMaterials[0])){

            return $this->redirect(['action' => 'addformpregouki',
            's' => ['product_code' => $product_code, 'user_code' => $user_code]]);
 
          }else{

            $mess = "入力された製品は検査表の登録が完了していません。";
            $this->set('mess',$mess);
 
            if($factory_id == 5){//本部の人がログインしている場合

              $Product_name_list = $this->Products->find()
              ->where(['status_kensahyou' => 0, 'delete_flag' => 0])
              ->toArray();
      
            }else{
      
              $Product_name_list = $this->Products->find()
              ->where(['status_kensahyou' => 0, 'delete_flag' => 0,
              'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
              ->toArray();
      
            }
    
             $arrProduct_name_list = array();
             for($j=0; $j<count($Product_name_list); $j++){
               array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
             }
             $this->set('arrProduct_name_list', $arrProduct_name_list);
  
          }

         }else{

           $mess = "入力された製品名は登録されていません。確認してください。";
           $this->set('mess',$mess);

           if($factory_id == 5){//本部の人がログインしている場合

            $Product_name_list = $this->Products->find()
            ->where(['status_kensahyou' => 0, 'delete_flag' => 0])
            ->toArray();
    
          }else{
    
            $Product_name_list = $this->Products->find()
            ->where(['status_kensahyou' => 0, 'delete_flag' => 0,
            'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
            ->toArray();
    
          }
  
           $arrProduct_name_list = array();
           for($j=0; $j<count($Product_name_list); $j++){
             array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
           }
           $this->set('arrProduct_name_list', $arrProduct_name_list);

         }

       }else{//product_nameの入力がない

         $mess = "製品名が入力されていません。";
         $this->set('mess',$mess);

         if($factory_id == 5){//本部の人がログインしている場合

          $Product_name_list = $this->Products->find()
          ->where(['status_kensahyou' => 0, 'delete_flag' => 0])
          ->toArray();
  
        }else{
  
          $Product_name_list = $this->Products->find()
          ->where(['status_kensahyou' => 0, 'delete_flag' => 0,
          'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
          ->toArray();
  
        }
         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }

     }else{//はじめ

      if($factory_id == 5){//本部の人がログインしている場合

        $Product_name_list = $this->Products->find()
        ->where(['status_kensahyou' => 0, 'delete_flag' => 0])
        ->toArray();

      }else{

        $Product_name_list = $this->Products->find()
        ->where(['status_kensahyou' => 0, 'delete_flag' => 0,
        'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
        ->toArray();

      }
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

      if(isset($data["next"])){//「次へ」ボタンを押したとき

        $product_code = $data["product_code"];
        $user_code = $data["user_code"];
        $machine_num = $data["machine_num"];

        return $this->redirect(['action' => 'addconditionform',
        's' => ['product_code' => $product_code, 'user_code' => $user_code, 'machine_num' => $machine_num]]);

      }
   
      $arrGouki = array();
      for($k=0; $k<count($ProductConditionParents); $k++){

        $ProductDatas = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();
        $LinenameDatas = $this->Linenames->find()
        ->where(['delete_flag' => 0, 'factory_id' => $ProductDatas[0]["factory_id"], 'machine_num' => $ProductConditionParents[$k]["machine_num"]])->toArray();

        $array = array($ProductConditionParents[$k]["machine_num"] => $LinenameDatas[0]["name"]);
        $arrGouki = $arrGouki + $array;
      }
      $this->set('arrGouki', $arrGouki);

    }

    public function addconditionform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');

      $Data = $this->request->query('s');
/*
      echo "<pre>";
      print_r($Data);
      echo "</pre>";
*/
/*
      //未完了の製品・ラインではないかチェック
      $InspectionDataResultParentsnotfin = $this->InspectionDataResultParents->find()
      ->contain(['InspectionStandardSizeParents', 'ProductConditionParents', 'Products'])
      ->where([
        'InspectionStandardSizeParents.delete_flag' => 0,
        'InspectionDataResultParents.delete_flag' => 0,
        'kanryou_flag IS' => NULL,
        'Products.product_code' => $Data["product_code"],
        'machine_num' => $Data["machine_num"]
      ])
      ->order(["InspectionDataResultParents.datetime"=>"DESC"])->limit('1')->toArray();

      if(isset($InspectionDataResultParentsnotfin[0])){

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => $Data["machine_num"]."号ライン、「".$Data["product_code"]."」は未完了の測定があります。検査中一覧画面から測定を完了させてください。"]]);

       }
 */
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

      //原料の表示
      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'machine_num' => $machine_num, 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'machine_num' => $machine_num,
        'ProductConditionParents.is_active' => 0,
        'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();
  
      }

      if(isset($ProductConditionParents[0])){

        $version = $ProductConditionParents[0]["version"];

      }else{

        if(!isset($_SESSION)){
        session_start();
        }
        $_SESSION['user_code'] = array();
        $_SESSION['user_code'] = $user_code;

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "「".$Products[0]["name"]."」は原料登録がされていません。"]]);

      }

      $ProductMachineMaterials = $this->ProductMachineMaterials->find()
      ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
      ->where(['version' => $version, 'machine_num' => $machine_num, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
      ->toArray();

      if(!isset($ProductMachineMaterials[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductMachineMaterials = $this->ProductMachineMaterials->find()
        ->contain(['ProductMaterialMachines' => ['ProductConditionParents' => ["Products"]]])
        ->where(['version' => $version, 'machine_num' => $machine_num, 'Products.product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->toArray();

      }

      if(isset($ProductMachineMaterials[0])){

        $ProductMaterialMachines = $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['version' => $version, 'machine_num' => $machine_num, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0, 'ProductMaterialMachines.delete_flag' => 0])
        ->order(["cylinder_number"=>"ASC"])->toArray();

        if(!isset($ProductMaterialMachines[0])){//長さ違いのデータがあればそれを持ってくる

          $product_code_ini = substr($product_code, 0, 11);
          $ProductMaterialMachines = $this->ProductMaterialMachines->find()
          ->contain(['ProductConditionParents' => ["Products"]])
          ->where(['version' => $version, 'machine_num' => $machine_num, 'Products.product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0, 'ProductMaterialMachines.delete_flag' => 0])
          ->order(["cylinder_number"=>"ASC"])->toArray();
      
        }
  
        $tuikaseikeiki = count($ProductMaterialMachines);
        $this->set('tuikaseikeiki', $tuikaseikeiki);

        for($j=1; $j<=$tuikaseikeiki; $j++){

          ${"cylinder_name".$j} = $ProductMaterialMachines[$j - 1]["cylinder_name"];
          $this->set('cylinder_name'.$j,${"cylinder_name".$j});

          $ProductMachineMaterials = $this->ProductMachineMaterials->find()
          ->where(['product_material_machine_id' => $ProductMaterialMachines[$j - 1]["id"], 'delete_flag' => 0])
          ->order(["material_number"=>"ASC"])->toArray();

          ${"tuikagenryou".$j} = count($ProductMachineMaterials);
          $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            $Materials = $this->Materials->find()
            ->where(['id' => $ProductMachineMaterials[$i - 1]["material_id"]])->toArray();

            ${"material_hyouji".$j.$i} = $Materials[0]["name"];
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

      }

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $product_code_machine_num = $product_code."_".$machine_num;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code_machine_num);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

      //規格登録されているかチェック
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

      if(!isset($InspectionStandardSizeChildren[0])){

        if(!isset($_SESSION)){
        session_start();
        }
        $_SESSION['user_code'] = array();
        $_SESSION['user_code'] = $user_code;

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "「".$Products[0]["name"]."」は規格登録がされていません。"]]);

      }

//温度の表示
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

        if(!isset($_SESSION)){
        session_start();
        }
        $_SESSION['user_code'] = array();
        $_SESSION['user_code'] = $user_code;

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "「".$Products[0]["name"]."」は検査表画像登録がされていません。管理者に報告してください。"]]);

      }

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'machine_num' => $machine_num,
      'ProductConditionParents.is_active' => 0,
      'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'machine_num' => $machine_num,
        'ProductConditionParents.is_active' => 0,
        'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();
  
      }

      if(isset($ProductConditionParents[0])){

        $product_condition_parent_id = $ProductConditionParents[0]['id'];
        $this->set('product_condition_parent_id', $product_condition_parent_id);

      }else{

        if(!isset($_SESSION)){
        session_start();
        }
        $_SESSION['user_code'] = array();
        $_SESSION['user_code'] = $user_code;

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "「".$Products[0]["name"]."」は原料登録がされていません。1"]]);

      }

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
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

            ${"extrude_roatation".$j} = sprintf("%.1f", $ProductConditonChildren[0]["extrude_roatation"]);
            $this->set('extrude_roatation'.$j, ${"extrude_roatation".$j});
            ${"extrusion_load".$j} = sprintf("%.1f", $ProductConditonChildren[0]["extrusion_load"]);
            $this->set('extrusion_load'.$j, ${"extrusion_load".$j});

            for($n=1; $n<8; $n++){
              ${"temp_".$n.$j} = sprintf("%.0f", $ProductConditonChildren[0]["temp_".$n]);
              $this->set('temp_'.$n.$j, ${"temp_".$n.$j});
            }

            $pickup_speed = sprintf("%.1f", $ProductConditonChildren[0]["pickup_speed"]);
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

            if(!isset($_SESSION)){
              session_start();
              }
              $_SESSION['user_code'] = array();
              $_SESSION['user_code'] = $user_code;
      
            $Products = $this->Products->find()
            ->where(['product_code' => $product_code])->toArray();

            return $this->redirect(['action' => 'addformpre',
            's' => ['mess' => "「".$Products[0]["name"]."」は成形温度登録がされていません。"]]);

          }

        }

      }else{

        if(!isset($_SESSION)){
          session_start();
          }
          $_SESSION['user_code'] = array();
          $_SESSION['user_code'] = $user_code;

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "「".$Products[0]["name"]."」は成形温度登録がされていません"]]);

      }

      $ProductDatas = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $LinenameDatas = $this->Linenames->find()
      ->where(['delete_flag' => 0, 'factory_id' => $ProductDatas[0]["factory_id"], 'machine_num' => $machine_num])->toArray();
      $this->set('linename', $LinenameDatas[0]["name"]);

      //規格表示
      $ProductParent = $this->Products->find()
      ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();
      $ProductLength = $this->Products->find()
      ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();
      $Length = $ProductLength[0]['length'];
      $this->set('Length',$Length);
      $ig_bank_modes = $ProductParent[0]['ig_bank_modes'];
      if($ig_bank_modes == 0){
        $mode = "0（X-Y）";
      }else{
        $mode = "0（Y-Y）";
      }
      $this->set('mode',$mode);

      $product_code_ini = substr($product_code, 0, 11);
      $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
      ->contain(['InspectionStandardSizeParents' => ["Products"]])
      ->where(['product_code like' => $product_code_ini.'%',
      'InspectionStandardSizeParents.is_active' => 0,
      'InspectionStandardSizeParents.delete_flag' => 0,
      'InspectionStandardSizeChildren.delete_flag' => 0])
      ->order(["InspectionStandardSizeParents.version"=>"DESC"])->toArray();

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

        for($i=0; $i<count($InspectionStandardSizeChildren); $i++){

          $num = $InspectionStandardSizeChildren[$i]["size_number"];
          ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
          ${"input_type".$num} = $InspectionStandardSizeChildren[$i]["input_type"];
          $this->set('input_type'.$num,${"input_type".$num});

          if(${"size_name".$num} === "長さ"){

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = "-";
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = "-";
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = "-";
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = "※製品規格参照";
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
    
          }elseif($InspectionStandardSizeChildren[$i]["input_type"] === "judge"){

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
  
          }else{

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["upper_limit"]);
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["lower_limit"]);
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["size"]);
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
  
          }
    
        }
        
      }
      
      echo "<pre>";
      print_r("");
      echo "</pre>";

    }

    public function addconditionconfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $staff_id = $data["staff_id"];
      $this->set('staff_id', $staff_id);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);
      $Staffs = $this->Staffs->find()
      ->where(['id' => $staff_id])
      ->toArray();
      $factory_id = $Staffs[0]["factory_id"];
      $this->set('factory_id', $factory_id);
/*
      echo "<pre>";
      print_r($factory_id."_".$machine_num);
      echo "</pre>";
*/
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $product_code_machine_num = $product_code."_".$machine_num;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code_machine_num);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

//温度の表示
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

        if(!isset($_SESSION)){
        session_start();
        }
        $_SESSION['user_code'] = array();
        $_SESSION['user_code'] = $data["user_code"];

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は検査表親テーブルの登録がされていません。"]]);

      }

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'machine_num' => $machine_num,
      'ProductConditionParents.is_active' => 0,
      'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'machine_num' => $machine_num
        , 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

      }

      if(isset($ProductConditionParents[0])){

        $product_condition_parent_id = $ProductConditionParents[0]['id'];
        $this->set('product_condition_parent_id', $product_condition_parent_id);

      }else{

        if(!isset($_SESSION)){
        session_start();
        }
        $_SESSION['user_code'] = array();
        $_SESSION['user_code'] = $data["user_code"];

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は検査表親テーブルの登録がされていません。"]]);

      }

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'machine_num' => $machine_num
      , 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'machine_num' => $machine_num
        , 'ProductConditionParents.delete_flag' => 0])
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

            ${"extrude_roatation".$j} = sprintf("%.1f", $ProductConditonChildren[0]["extrude_roatation"]);
            $this->set('extrude_roatation'.$j, ${"extrude_roatation".$j});
            ${"extrusion_load".$j} = sprintf("%.1f", $ProductConditonChildren[0]["extrusion_load"]);
            $this->set('extrusion_load'.$j, ${"extrusion_load".$j});
            ${"extrusion_upper_limit".$j} = $ProductConditonChildren[0]["extrusion_upper_limit"];
            $this->set('extrusion_upper_limit'.$j, ${"extrusion_upper_limit".$j});
            ${"extrusion_lower_limit".$j} = $ProductConditonChildren[0]["extrusion_lower_limit"];
            $this->set('extrusion_lower_limit'.$j, ${"extrusion_lower_limit".$j});

            for($n=1; $n<8; $n++){
              ${"temp_".$n.$j} = sprintf("%.0f", $ProductConditonChildren[0]["temp_".$n]);
              $this->set('temp_'.$n.$j, ${"temp_".$n.$j});
              ${"temp_".$n."_upper_limit".$j} = sprintf("%.1f", $ProductConditonChildren[0]["temp_".$n."_upper_limit"]);
              $this->set('temp_'.$n."_upper_limit".$j, ${"temp_".$n."_upper_limit".$j});
              ${"temp_".$n."_lower_limit".$j} = sprintf("%.1f", $ProductConditonChildren[0]["temp_".$n."_lower_limit"]);
              $this->set('temp_'.$n."_lower_limit".$j, ${"temp_".$n."_lower_limit".$j});
            }

            $pickup_speed = sprintf("%.1f", $ProductConditonChildren[0]["pickup_speed"]);
            $this->set('pickup_speed', $pickup_speed);
            $pickup_speed_upper_limit = $ProductConditonChildren[0]["pickup_speed_upper_limit"];
            $this->set('pickup_speed_upper_limit', $pickup_speed_upper_limit);
            $pickup_speed_lower_limit = $ProductConditonChildren[0]["pickup_speed_lower_limit"];
            $this->set('pickup_speed_lower_limit', $pickup_speed_lower_limit);

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

        }

      }

      for($j=1; $j<=$countseikeiki; $j++){

        ${"inspection_extrude_roatation".$j} = sprintf("%.1f", $data['inspection_extrude_roatation'.$j]);
        $this->set('inspection_extrude_roatation'.$j, ${"inspection_extrude_roatation".$j});
        ${"inspection_extrusion_load".$j} = sprintf("%.1f", $data['inspection_extrusion_load'.$j]);
        $this->set('inspection_extrusion_load'.$j, ${"inspection_extrusion_load".$j});

        for($n=1; $n<8; $n++){

          ${"inspection_temp_".$n.$j} = $data["inspection_temp_".$n.$j];
          $dotini = substr(${"inspection_temp_".$n.$j}, 0, 1);
          $dotend = substr(${"inspection_temp_".$n.$j}, -1, 1);

          if($dotini == "."){
            ${"inspection_temp_".$n.$j} = "0".${"inspection_temp_".$n.$j};
          }elseif($dotend == "."){
            ${"inspection_temp_".$n.$j} = ${"inspection_temp_".$n.$j}."0";
          }

          ${"inspection_temp_".$n.$j} = sprintf("%.2f", ${"inspection_temp_".$n.$j});
          $this->set('inspection_temp_'.$n.$j, ${"inspection_temp_".$n.$j});

        }

      }

      $inspection_pickup_speed = sprintf("%.1f", $data['inspection_pickup_speed']);
      $this->set('inspection_pickup_speed', $inspection_pickup_speed);

      //規格表示
      $ProductParent = $this->Products->find()
      ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();
      $ProductLength = $this->Products->find()
      ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();
      $Length = $ProductLength[0]['length'];
      $this->set('Length',$Length);
      $ig_bank_modes = $ProductParent[0]['ig_bank_modes'];
      if($ig_bank_modes == 0){
        $mode = "0（X-Y）";
      }else{
        $mode = "0（Y-Y）";
      }
      $this->set('mode',$mode);

      $product_code_ini = substr($product_code, 0, 11);
      $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
      ->contain(['InspectionStandardSizeParents' => ["Products"]])
      ->where(['product_code like' => $product_code_ini.'%',
      'InspectionStandardSizeParents.is_active' => 0,
      'InspectionStandardSizeParents.delete_flag' => 0,
      'InspectionStandardSizeChildren.delete_flag' => 0])
      ->order(["InspectionStandardSizeParents.version"=>"DESC"])->toArray();

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

        for($i=0; $i<count($InspectionStandardSizeChildren); $i++){

          $num = $InspectionStandardSizeChildren[$i]["size_number"];
          ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
          ${"input_type".$num} = $InspectionStandardSizeChildren[$i]["input_type"];
          $this->set('input_type'.$num,${"input_type".$num});

          if(${"size_name".$num} === "長さ"){

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = "-";
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = "-";
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = "-";
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = "※製品規格参照";
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
    
          }elseif($InspectionStandardSizeChildren[$i]["input_type"] === "judge"){

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
  
          }else{

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["upper_limit"]);
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["lower_limit"]);
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["size"]);
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
  
          }
    
        }
        
      }

    }

    public function addconditiondo()
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
      $staff_id = $data["staff_id"];
      $this->set('staff_id', $staff_id);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);
      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $product_code_machine_num = $product_code."_".$machine_num;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code_machine_num);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

//温度の表示
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

        if(!isset($_SESSION)){
        session_start();
        }
        $_SESSION['user_code'] = array();
        $_SESSION['user_code'] = $user_code;

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は検査表親テーブルの登録がされていません。"]]);

      }

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'machine_num' => $machine_num,
      'ProductConditionParents.is_active' => 0,
      'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'machine_num' => $machine_num
        , 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

      }

      if(isset($ProductConditionParents[0])){

        $product_condition_parent_id = $ProductConditionParents[0]['id'];
        $this->set('product_condition_parent_id', $product_condition_parent_id);

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
      ->where(['product_code' => $product_code, 'machine_num' => $machine_num
      , 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'machine_num' => $machine_num
        , 'ProductConditionParents.delete_flag' => 0])
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

            ${"extrude_roatation".$j} = sprintf("%.1f", $ProductConditonChildren[0]["extrude_roatation"]);
            $this->set('extrude_roatation'.$j, ${"extrude_roatation".$j});
            ${"extrusion_load".$j} = sprintf("%.1f", $ProductConditonChildren[0]["extrusion_load"]);
            $this->set('extrusion_load'.$j, ${"extrusion_load".$j});

            for($n=1; $n<8; $n++){
              ${"temp_".$n.$j} = sprintf("%.0f", $ProductConditonChildren[0]["temp_".$n]);
              $this->set('temp_'.$n.$j, ${"temp_".$n.$j});
            }

            $pickup_speed = sprintf("%.1f", $ProductConditonChildren[0]["pickup_speed"]);
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

        }

      }

      for($j=1; $j<=$countseikeiki; $j++){

        $ProductConditonChildren = $this->ProductConditonChildren->find()
        ->where(['product_material_machine_id' => ${"product_material_machine_id".$j}, 'cylinder_name' => ${"cylinder_name".$j}, 'delete_flag' => 0])
        ->toArray();

        ${"inspection_extrude_roatation".$j} = sprintf("%.1f", $data['inspection_extrude_roatation'.$j]);
        $this->set('inspection_extrude_roatation'.$j, ${"inspection_extrude_roatation".$j});
        ${"inspection_extrusion_load".$j} = sprintf("%.1f", $data['inspection_extrusion_load'.$j]);
        $this->set('inspection_extrusion_load'.$j, ${"inspection_extrusion_load".$j});

        for($n=1; $n<8; $n++){

          ${"inspection_temp_".$n.$j} = sprintf("%.1f", $data["inspection_temp_".$n.$j]);
          $this->set('inspection_temp_'.$n.$j, ${"inspection_temp_".$n.$j});

        }

      }

      $inspection_pickup_speed = sprintf("%.1f", $data['inspection_pickup_speed']);
      $this->set('inspection_pickup_speed', $inspection_pickup_speed);

      $tourokuInspectionDataConditonParents = [
        'product_code' => $product_code,
        'datetime' => date("Y-m-d H:i:s"),
        "delete_flag" => 0,
        'created_at' => date("Y-m-d H:i:s"),
        "created_staff" => $staff_id
      ];
/*
      echo "<pre>";
      print_r($tourokuInspectionDataConditonParents);
      echo "</pre>";
*/
      //新しいデータを登録
      $InspectionDataConditonParents = $this->InspectionDataConditonParents->patchEntity($this->InspectionDataConditonParents->newEntity(), $tourokuInspectionDataConditonParents);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->InspectionDataConditonParents->save($InspectionDataConditonParents)) {

          $InspectionDataConditonParents = $this->InspectionDataConditonParents->find()
          ->where(['product_code' => $product_code, 'delete_flag' => 0])->order(["id"=>"DESC"])->toArray();

          $inspection_data_conditon_parent_id = $InspectionDataConditonParents[0]['id'];
          $this->set('inspection_data_conditon_parent_id', $inspection_data_conditon_parent_id);

          for($j=1; $j<=$countseikeiki; $j++){

            $ProductConditonChildren = $this->ProductConditonChildren->find()
            ->where(['product_material_machine_id' => ${"product_material_machine_id".$j}, 'cylinder_name' => ${"cylinder_name".$j}, 'delete_flag' => 0])
            ->toArray();

            $tourokuInspectionDataConditonChildren[] = [
              "inspection_data_conditon_parent_id" => $InspectionDataConditonParents[0]["id"],
              "product_conditon_child_id" => $ProductConditonChildren[0]["id"],
              "inspection_temp_1" => $data['inspection_temp_1'.$j],
              "inspection_temp_2" => $data['inspection_temp_2'.$j],
              "inspection_temp_3" => $data['inspection_temp_3'.$j],
              "inspection_temp_4" => $data['inspection_temp_4'.$j],
              "inspection_temp_5" => $data['inspection_temp_5'.$j],
              "inspection_temp_6" => $data['inspection_temp_6'.$j],
              "inspection_temp_7" => $data['inspection_temp_7'.$j],
              "inspection_extrude_roatation" => $data['inspection_extrude_roatation'.$j],
              "inspection_extrusion_load" => $data['inspection_extrusion_load'.$j],
              "inspection_pickup_speed" => $data['inspection_pickup_speed'],
              "delete_flag" => 0,
              'created_at' => date("Y-m-d H:i:s"),
              "created_staff" => $staff_id
            ];

          }
/*
          echo "<pre>";
          print_r($tourokuInspectionDataConditonChildren);
          echo "</pre>";
  */  
          $InspectionDataConditonChildren = $this->InspectionDataConditonChildren->patchEntities
          ($this->InspectionDataConditonChildren->newEntity(), $tourokuInspectionDataConditonChildren);
          $this->InspectionDataConditonChildren->saveMany($InspectionDataConditonChildren);

          $connection->commit();// コミット5
          $mes = "";
          $this->set('mes',$mes);
          
          if(!isset($_SESSION)){
            session_start();
            }
            $session = $this->request->getSession();
    
            $hanbetu_code = $product_code.$machine_num.$factory_id;
            $_SESSION['productmachinefactory'] = array();
            $_SESSION['InspectionDataConditons'.$hanbetu_code] = array();

            $_SESSION['productmachinefactory'] = array(
              'chenk' => 1,
              'hanbetu_code' => $hanbetu_code,
            );

            $_SESSION['InspectionDataConditons'.$hanbetu_code] = array(
              'chenk' => 1,
              'hanbetu_code' => $hanbetu_code,
              'product_code' => $product_code,
              'machine_num' => $machine_num,
              'inspection_standard_size_parent_id' => $inspection_standard_size_parent_id,
              'product_condition_parent_id' => $product_condition_parent_id,
              'inspection_data_conditon_parent_id' => $inspection_data_conditon_parent_id,
              'inspection_pickup_speed' => $inspection_pickup_speed,
            );

            for($j=1; $j<=$countseikeiki; $j++){
              
              $_SESSION['InspectionDataConditons'.$hanbetu_code] = $_SESSION['InspectionDataConditons'.$hanbetu_code] + array('inspection_extrude_roatation'.$j => ${"inspection_extrude_roatation".$j});
              $_SESSION['InspectionDataConditons'.$hanbetu_code] = $_SESSION['InspectionDataConditons'.$hanbetu_code] + array('inspection_extrusion_load'.$j => ${"inspection_extrusion_load".$j});

              for($n=1; $n<=7; $n++){
                $_SESSION['InspectionDataConditons'.$hanbetu_code] = $_SESSION['InspectionDataConditons'.$hanbetu_code] + array('inspection_temp_'.$n.$j => ${"inspection_temp_".$n.$j});
              }

              }

              //検査日をキープしておく
              $date1 = strtotime(date("Y-m-d"));
              $date_sta = date("Y-m-d")." 00:00:00";
              $date_fin = date('Y-m-d', strtotime('+1 day', $date1))." 00:00:00";
              $_SESSION['kensa_datetime']["date_sta"] = $date_sta;
              $_SESSION['kensa_datetime']["date_fin"] = $date_fin;

              return $this->redirect(['action' => 'addform']);

        } else {

          $this->Flash->error(__('The data could not be saved. Please, try again.'));
          throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
          $mes = "※登録されませんでした。管理者に問い合わせてください。";
          $this->set('mes',$mes);
          return $this->redirect(['action' => 'addlogin',
          's' => ['mess' => "セッションが切れました。この画面からやり直してください。"]]);

        }

      } catch (Exception $e) {//トランザクション7
      //ロールバック8
        $connection->rollback();//トランザクション9
      }//トランザクション10
      
    }

    public function addform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $mes = "";
      $this->set('mes',$mes);

      $kaishi_loss_flag = 0;
      $this->set('kaishi_loss_flag',$kaishi_loss_flag);
      $kaishi_loss_input = 0;
      $this->set('kaishi_loss_input', $kaishi_loss_input);

      if(!isset($_SESSION)){
        session_start();
        }
        $session = $this->request->getSession();
        $sessiondata = $session->read();
  
      if(isset($_SESSION['productmachinefactory']["chenk"])){//最初

        $hanbetu_code = $_SESSION['productmachinefactory']["hanbetu_code"];
        
        $data = $_SESSION['InspectionDataConditons'.$hanbetu_code];
        $_SESSION['productmachinefactory'] = array();
        $_SESSION['InspectionDataConditons'.$hanbetu_code] = array();
  
        //検査日をキープしておく
        $date_sta = $_SESSION['kensa_datetime']["date_sta"];
        $date_fin = $_SESSION['kensa_datetime']["date_fin"];
        $_SESSION['kensa_datetime'] = array();
  
      }elseif(isset($_SESSION['tuzukikara']["check"])){//続きからの場合

        $data = $_SESSION['tuzukikara']["data"];
        $date_sta = $data["date_sta"];
        $date_fin = $data["date_fin"];
        $_SESSION['tuzukikara'] = array();

      }else{

        $data = $this->request->getData();
        $date_sta = $data["date_sta"];
        $date_fin = $data["date_fin"];
  
        if(!isset($data["product_code"])){

          return $this->redirect(['action' => 'addlogin',
          's' => ['mess' => "セッションが切れました。この画面からやり直してください。"]]);
  
        }

      }
      
      $this->set('date_sta',$date_sta);
      $this->set('date_fin',$date_fin);

      if(!isset($data["mikan_check"])){
        $mikan_check = 0;
      }else{
        $mikan_check = $data["mikan_check"];
      }
      $this->set('mikan_check',$mikan_check);

      $end_ok_check = 0;
      if(!isset($data["lot_number2"])){
        $end_ok_check = 1;
      }
      $this->set('end_ok_check', $end_ok_check);
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);

      $inspection_data_conditon_parent_id = $data['inspection_data_conditon_parent_id'];
      $this->set('inspection_data_conditon_parent_id', $inspection_data_conditon_parent_id);

      if(isset($data['inspection_data_conditon_parent_id_moto'])){
        $inspection_data_conditon_parent_id_moto = $data['inspection_data_conditon_parent_id_moto'];
        $this->set('inspection_data_conditon_parent_id_moto', $inspection_data_conditon_parent_id_moto);
      }else{
        $inspection_data_conditon_parent_id_moto = $data['inspection_data_conditon_parent_id'];
        $this->set('inspection_data_conditon_parent_id_moto', $inspection_data_conditon_parent_id_moto);
      }

      $mess = "";
      $this->set('mess', $mess);

      $arrJudge = [
        "0.0" => "〇",
        "1.0" => "✕"
      ];
      $this->set('arrJudge', $arrJudge);

      $check_seikeijouken = 0;
      $this->set('check_seikeijouken', $check_seikeijouken);

      $htmlproductcheck = new htmlproductcheck();//クラスを使用
      $arrayproductdate = $htmlproductcheck->productcheckprogram($product_code);//クラスを使用

      if($arrayproductdate[0] === "no_product"){

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は存在しません。"]]);

      }

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $product_code_ini = substr($product_code, 0, 11);
      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%',
      'ProductConditionParents.is_active' => 0,
      'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(isset($ProductConditionParents[0])){

        $product_condition_parent_id = $ProductConditionParents[0]['id'];
        $this->set('product_condition_parent_id', $product_condition_parent_id);
  
      }else{

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は原料登録がされていません。"]]);

      }

      $product_code_ini = substr($product_code, 0, 11);
      $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
      ->contain(['InspectionStandardSizeParents' => ["Products"]])
      ->where(['product_code like' => $product_code_ini.'%',
      'InspectionStandardSizeParents.is_active' => 0,
      'InspectionStandardSizeParents.delete_flag' => 0,
      'InspectionStandardSizeChildren.delete_flag' => 0])
      ->order(["InspectionStandardSizeParents.version"=>"DESC"])->toArray();

      if(isset($InspectionStandardSizeChildren[0])){

        $arrNum = array();
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

        for($i=0; $i<count($InspectionStandardSizeChildren); $i++){

          $num = $InspectionStandardSizeChildren[$i]["size_number"];
          ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
          ${"input_type".$num} = $InspectionStandardSizeChildren[$i]["input_type"];
          $this->set('input_type'.$num,${"input_type".$num});

          if(${"size_name".$num} === "長さ"){

            $num_length = $num - 1;
            $this->set('num_length',$num_length);
    
          }elseif($InspectionStandardSizeChildren[$i]["input_type"] === "judge"){

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
  
          }else{

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["upper_limit"]);
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["lower_limit"]);
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["size"]);
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
  
            $arrNum[] = $num;
  
          }

        }

        $this->set('InspectionStandardSizeChildren', $InspectionStandardSizeChildren);

      }else{

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は規格登録がされていません。"]]);

      }

      if(isset($data["finish"])){//完了した場合

        $InspectionDataResultParentData = $this->InspectionDataResultParents->find()
        ->contain(['InspectionStandardSizeParents', 'ProductConditionParents', 'Products'])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
        'InspectionStandardSizeParents.delete_flag' => 0,
        'InspectionDataResultParents.delete_flag' => 0])
        ->order(["InspectionDataResultParents.created_at"=>"DESC"])->limit('1')->toArray();

        if(strlen($InspectionDataResultParentData[0]["loss_amount"]) > 0){

          return $this->redirect(['action' => 'addfinishform',
          's' => ['product_code' => $product_code, 'machine_num' => $machine_num, 'date_sta' => $date_sta, 'date_fin' => $date_fin]]);

        }else{

          $mes = "※検査完了前に終了ロスを登録してください。";
          $this->set('mes',$mes);

          $count_seikeijouken = $data["count_seikeijouken"];
          $this->set('count_seikeijouken', $count_seikeijouken);
    
          $gyou = $data["gyou"];
          $this->set('gyou', $gyou);
  
          for($j=1; $j<=$gyou; $j++){
  
            if(isset($data['lot_number'.$j])){
              ${"lot_number".$j} = $data['lot_number'.$j];
            }else{
              ${"lot_number".$j} = "";
            }
            $this->set('lot_number'.$j,${"lot_number".$j});
  
            if(isset($data['datetime'.$j]["hour"])){
              ${"datetime".$j} = $data['datetime'.$j]["hour"].":".$data['datetime'.$j]["minute"];
            }elseif(isset($data['datetime'.$j])){
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
  
            if(isset($data['product_id'.$j])){
              ${"product_id".$j} = $data['product_id'.$j];
              $Products = $this->Products->find()
              ->where(['id' => ${"product_id".$j}])->toArray();
              ${"lengthhyouji".$j} = $Products[0]["length"];
            }else{
              ${"product_id".$j} = "";
              ${"lengthhyouji".$j} = "";
            }
            $this->set('product_id'.$j,${"product_id".$j});
            $this->set('lengthhyouji'.$j,${"lengthhyouji".$j});
  
            if(isset($data['gaikan'.$j])){
              ${"gaikan".$j} = $data['gaikan'.$j];
            }else{
              ${"gaikan".$j} = "";
            }
            $this->set('gaikan'.$j,${"gaikan".$j});
  
            if(isset($data['weight'.$j])){
              ${"weight".$j} = $data['weight'.$j];
              ${"weight".$j} = sprintf("%.1f", ${"weight".$j});
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
  
            $gouhi_check = 0;
  
            for($i=1; $i<=11; $i++){
  
              if(strlen($data["result_size".$j."_".$i]) > 0){
                ${"result_size".$j."_".$i} = $data["result_size".$j."_".$i];
  
                $dotini = substr(${"result_size".$j."_".$i}, 0, 1);
                $dotend = substr(${"result_size".$j."_".$i}, -1, 1);
    
                if($dotini == "."){
                  ${"result_size".$j."_".$i} = "0".${"result_size".$j."_".$i};
                }elseif($dotend == "."){
                  ${"result_size".$j."_".$i} = ${"result_size".$j."_".$i}."0";
                }
                if(${"result_size".$j."_".$i} !== "〇" && ${"result_size".$j."_".$i} !== "✕"){
                  ${"result_size".$j."_".$i} = sprintf("%.1f", ${"result_size".$j."_".$i});
                }
  
              }else{
                ${"result_size".$j."_".$i} = "";
              }
              $this->set("result_size".$j."_".$i,${"result_size".$j."_".$i});
              
              $Productlengthcheck = $this->Products->find()
              ->where(['product_code like' => $product_code_ini.'%'
              , 'status_kensahyou' => 0, 'status_length' => 0, 'delete_flag' => 0])->order(["id"=>"ASC"])->toArray();
              if($i == $num_length + 1 && count($Productlengthcheck) > 0){//長さ列の場合
  
                $Products= $this->Products->find()->where(['product_code like' => $product_code_ini.'%', 'length' => ${"lengthhyouji".$j}, 'delete_flag' => 0])->toArray();
                ${"size".$i} = $Products[0]["length_cut"];
                ${"upper_limit".$i} = $Products[0]["length_upper_limit"];
                ${"lower_limit".$i} = $Products[0]["length_lower_limit"];
          
              }
  
              if(${"input_type".$i} !== "judge" && ${"result_size".$j."_".$i} <= (float)${"size".$i} + (float)${"upper_limit".$i}
              && ${"result_size".$j."_".$i} >= (float)${"size".$i} + (float)${"lower_limit".$i}){
                $gouhi_check = $gouhi_check;
              } elseif(${"input_type".$i} == "judge" && ${"result_size".$j."_".$i} < 1) {
                $gouhi_check = $gouhi_check;
              } else {
                $gouhi_check = $gouhi_check + 1;
              }
  
            }

            if($data['gaikan'.$j] > 0){
              $gouhi_check = $gouhi_check + 1;
            }

            if($gouhi_check > 0){
              ${"gouhi".$j} = 1;
              ${"gouhihyouji".$j} = "否";
            } else {
              ${"gouhi".$j} = 0;
              ${"gouhihyouji".$j} = "合";
            }
            $this->set('gouhi'.$j,${"gouhi".$j});
            $this->set('gouhihyouji'.$j,${"gouhihyouji".$j});
    
          }
  
        }

      }

      $arrGaikan = ["0" => "〇", "1" => "✕"];
      $this->set('arrGaikan', $arrGaikan);

      $arrGouhi = ["0" => "合", "1" => "否"];
      $this->set('arrGouhi', $arrGouhi);

      $product_code_ini = substr($product_code, 0, 11);
      $this->set('product_code_ini', $product_code_ini);

      $ProductParent = $this->Products->find()
      ->where(['product_code' => $product_code, 'status_kensahyou' => 0
      , 'status_length' => 0, 'delete_flag' => 0])->toArray();
      if(!isset($ProductParent[0])){
        $ProductParent = $this->Products->find()
        ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();
      }
      $Products = $this->Products->find()
      ->where(['product_code IS NOT' => $product_code, 'product_code like' => $product_code_ini.'%'
      , 'status_kensahyou' => 0, 'status_length' => 0, 'delete_flag' => 0])->order(["id"=>"ASC"])->toArray();

      $Products = array_merge($ProductParent, $Products);

      $arrLength = array();
      foreach ($Products as $value) {
        $array = array($value->id => sprintf("%.1f", $value->length));
        $arrLength = $arrLength + $array;
      }
      $this->set('arrLength', $arrLength);

      $ig_bank_modes = $ProductParent[0]['ig_bank_modes'];
      if($ig_bank_modes == 0){
        $mode = "0（X-Y）";
      }else{
        $mode = "0（Y-Y）";
      }
      $this->set('mode',$mode);
      
      for($n=0; $n<count($Products); $n++){

        if(strlen($Products[$n]["length_measuring_instrument"]) > 0){
          $length_measuring_instrument = $Products[$n]["length_measuring_instrument"];
        }else{
          $length_measuring_instrument = "-";
        }
        ${"arrLength_size".$n} = array();

        if(sprintf("%.1f", $Products[$n]["length_lower_limit"]) <= 0){
          $lower = sprintf("%.1f", $Products[$n]["length_lower_limit"]);
        }else{
          $lower = "+".sprintf("%.1f", $Products[$n]["length_lower_limit"]);
        }
        ${"arrLength_size".$n} = [
          "product_id" => $Products[$n]["id"],
          "size" => sprintf("%.1f", $Products[$n]["length_cut"]),
          "upper" => "+".sprintf("%.1f", $Products[$n]["length_upper_limit"]),
          "lower" => $lower,
          "length_measuring_instrument" => $length_measuring_instrument
        ];
  
        $this->set('arrLength_size'.$n,${"arrLength_size".$n});
  
      }

      $count_length = count($Products);
      $this->set('count_length',$count_length);

      if(count($Products) < 1){

        $count_length = 1;
        $this->set('count_length',$count_length);
  
        $Productnolength = $this->Products->find()
        ->where(['product_code' => $product_code, 'status_kensahyou' => 0
      ,'delete_flag' => 0])->toArray();

        $array = array($Productnolength[0]["id"] => "長さなし");
        $arrLength = $arrLength + $array;
        $this->set('arrLength', $arrLength);
        $n = 0;

        ${"arrLength_size".$n} = array();
        ${"arrLength_size".$n} = [
          "product_id" => $Productnolength[0]["id"],
          "size" => "-",
          "upper" => "-",
          "lower" => "-",
          "length_measuring_instrument" => "-"
        ];
  
        $this->set('arrLength_size'.$n,${"arrLength_size".$n});
  
      }

      $nagasa = "長さ";
      $this->set('nagasa',$nagasa);
      $haihun = "-";
      $this->set('haihun',$haihun);

      $checkedit = 0;
      $this->set('checkedit', $checkedit);
      $checkloss = 0;
      $this->set('checkloss', $checkloss);

      if(isset($data["gyou"])){
        $gyou = $data["gyou"];
      }else{
        $gyou = 1;
      }

      for($j=1; $j<=$gyou; $j++){
        if(isset($data['edit'.$j])){
          $checkedit = $j;
        }
      }
      
      for($j=1; $j<=$gyou; $j++){
        if(isset($data['loss'.$j])){
          $checkloss = $j;
          ${"lossflag".$j} = 1;
        }elseif(isset($data['lossflag'.$j])){
          ${"lossflag".$j} = $data['lossflag'.$j];
        }else{
          ${"lossflag".$j} = 0;
        }
        $this->set('lossflag'.$j, ${"lossflag".$j});
        }

      if(isset($data["tuzukikara"])){

        $product_code_ini = substr($product_code, 0, 11);
        $InspectionDataResultParentData = $this->InspectionDataResultParents->find()
        ->contain(['InspectionStandardSizeParents', 'ProductConditionParents', 'Products'])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
        'InspectionStandardSizeParents.delete_flag' => 0,
        'InspectionDataResultParents.datetime >=' => $data["date_sta"],
        'InspectionDataResultParents.datetime <' => $data["date_fin"],
        'InspectionDataResultParents.delete_flag' => 0])
        ->order(["InspectionDataResultParents.datetime"=>"DESC"])->limit('1')->toArray();

        $InspectionDataResultParents = $this->InspectionDataResultParents->patchEntity
        ($this->InspectionDataResultParents->newEntity(), $data);
        $connection = ConnectionManager::get('default');//トランザクション1
         // トランザクション開始2
         $connection->begin();//トランザクション3
         try {//トランザクション4
          if ($this->InspectionDataResultParents->updateAll(
            ['kanryou_flag' => ""],
            ['id' => $InspectionDataResultParentData[0]['id']])){
  
              $InspectionDataResultParentData3 = $this->InspectionDataResultParents->find()
              ->contain(['InspectionStandardSizeParents', 'ProductConditionParents', 'Products'])
              ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
              'InspectionStandardSizeParents.delete_flag' => 0,
              'InspectionDataResultParents.datetime >=' => $data["date_sta"],
              'InspectionDataResultParents.datetime <' => $data["date_fin"],
              'InspectionDataResultParents.delete_flag' => 0])
              ->order(["InspectionDataResultParents.datetime"=>"DESC"])->toArray();

              for($j=0; $j<count($InspectionDataResultParentData3); $j++){
                $this->InspectionDataResultParents->updateAll(
                  ['kanryou_flag' => ""],
                  ['id' => $InspectionDataResultParentData3[$j]['id']]);
              }

           $connection->commit();// コミット5
  
         } else {
  
           $this->Flash->error(__('The data could not be saved. Please, try again.'));
           throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
  
         }
  
       } catch (Exception $e) {//トランザクション7
       //ロールバック8
         $connection->rollback();//トランザクション9
       }//トランザクション10
  
      }

      if(isset($data["tuika"])){//行追加//この時点でデータの登録をする

        $checknull = 0;
        $j = $data["gyou"];

        $count_seikeijouken = $data["count_seikeijouken"];
        $this->set('count_seikeijouken', $count_seikeijouken);
  
        ${"user_code".$j} = $data['user_code'.$j];
        $Users = $this->Users->find()->contain(["Staffs"])->where(['user_code' => ${"user_code".$j}, 'Users.delete_flag' => 0])->toArray();
        if(!isset($Users[0])){
          $checknull = $checknull + 1;
          $mess = $mess."※入力されたユーザーIDは登録されていません。".'<br>';
        }else{
          $checknull = 0;
        }
        for($k=0; $k<count($arrNum); $k++){

          $i = $arrNum[$k];

          if(strlen($data["result_size".$j."_".$i]) > 0){//ちゃんと入力されている場合
            $checknull = $checknull;
          }else{//入力漏れがある場合
            $checknull = $checknull + 1;
            $mess = "※測定データに入力漏れがあります。".'<br>';
          }

          if(strlen($data['weight'.$j]) > 0){//ちゃんと入力されている場合
            $checknull = $checknull;
          }else{//入力漏れがある場合
            $checknull = $checknull + 1;
            $mess = "※重量データに入力漏れがあります。".'<br>';
          }

          if($data['gaikan'.$j] == "-"){//入力漏れがある場合
            $checknull = $checknull + 1;
            $mess = "※外観を選択してください。".'<br>';
          }else{//入力漏れがある場合
            $checknull = $checknull;
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

            if(isset($data['product_id'.$j])){
              ${"product_id".$j} = $data['product_id'.$j];
              $Products = $this->Products->find()
              ->where(['id' => ${"product_id".$j}])->toArray();
              ${"lengthhyouji".$j} = $Products[0]["length"];
            }else{
              ${"product_id".$j} = "";
              ${"lengthhyouji".$j} = "";
            }
            $this->set('product_id'.$j,${"product_id".$j});
            $this->set('lengthhyouji'.$j,${"lengthhyouji".$j});

            if(isset($data['gaikan'.$j])){
              ${"gaikan".$j} = $data['gaikan'.$j];
            }else{
              ${"gaikan".$j} = "";
            }
            $this->set('gaikan'.$j,${"gaikan".$j});
  
            if(isset($data['weight'.$j])){
              ${"weight".$j} = $data['weight'.$j];
              ${"weight".$j} = sprintf("%.1f", ${"weight".$j});
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
            
            if(${"gouhi".$j} == 1){
              ${"gouhihyouji".$j} = "否";
            } else {
              ${"gouhihyouji".$j} = "合";
            }
            $this->set('gouhihyouji'.$j,${"gouhihyouji".$j});
  
            for($i=1; $i<=11; $i++){

              if(isset($data["result_size".$j."_".$i])){
                if(strlen($data["result_size".$j."_".$i]) > 0){
                  ${"result_size".$j."_".$i} = sprintf("%.1f", $data["result_size".$j."_".$i]);
                }else{
                  ${"result_size".$j."_".$i} = "";
                }
              }else{
                ${"result_size".$j."_".$i} = "";
              }
              $this->set("result_size".$j."_".$i,${"result_size".$j."_".$i});

            }

          }

        }else{//ちゃんと入力されている場合

        for($j=1; $j<=$gyou; $j++){

          if(isset($data['lot_number'.$j])){
            ${"lot_number".$j} = $data['lot_number'.$j];
          }else{
            ${"lot_number".$j} = "";
          }
          $this->set('lot_number'.$j,${"lot_number".$j});

          if(isset($data['datetimenow'.$j])){
            ${"datetime".$j} = date('H:i');
          }elseif(isset($data['datetime'.$j])){
            ${"datetime".$j} = $data['datetime'.$j];
          }elseif(isset($data['datetime_h_i'.$j])){
            ${"datetime".$j} = $data['datetime_h_i'.$j];
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

          if(isset($data['product_id'.$j])){
            ${"product_id".$j} = $data['product_id'.$j];
            $Products = $this->Products->find()
            ->where(['id' => ${"product_id".$j}])->toArray();
            ${"lengthhyouji".$j} = $Products[0]["length"];
          }else{
            ${"product_id".$j} = "";
            ${"lengthhyouji".$j} = "";
          }
          $this->set('product_id'.$j,${"product_id".$j});
          $this->set('lengthhyouji'.$j,${"lengthhyouji".$j});

          if(isset($data['gaikan'.$j])){
            ${"gaikan".$j} = $data['gaikan'.$j];
          }else{
            ${"gaikan".$j} = "";
          }
          $this->set('gaikan'.$j,${"gaikan".$j});

          if(isset($data['weight'.$j])){
            ${"weight".$j} = $data['weight'.$j];
            ${"weight".$j} = sprintf("%.1f", ${"weight".$j});
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

          $gouhi_check = 0;

          for($i=1; $i<=11; $i++){

            if(strlen($data["result_size".$j."_".$i]) > 0){
              ${"result_size".$j."_".$i} = $data["result_size".$j."_".$i];
  
              $dotini = substr(${"result_size".$j."_".$i}, 0, 1);
              $dotend = substr(${"result_size".$j."_".$i}, -1, 1);
  
              if($dotini == "."){
                ${"result_size".$j."_".$i} = "0".${"result_size".$j."_".$i};
              }elseif($dotend == "."){
                ${"result_size".$j."_".$i} = ${"result_size".$j."_".$i}."0";
              }
              ${"result_size".$j."_".$i} = sprintf("%.1f", ${"result_size".$j."_".$i});
  
            }else{
              ${"result_size".$j."_".$i} = "";
            }
            $this->set("result_size".$j."_".$i,${"result_size".$j."_".$i});

            $Productlengthcheck = $this->Products->find()
            ->where(['product_code like' => $product_code_ini.'%'
            , 'status_kensahyou' => 0, 'status_length' => 0, 'delete_flag' => 0])->order(["id"=>"ASC"])->toArray();
            if($i == $num_length + 1 && count($Productlengthcheck) > 0){//長さ列の場合

              $Products= $this->Products->find()->where(['product_code like' => $product_code_ini.'%', 'length' => ${"lengthhyouji".$j}, 'delete_flag' => 0])->toArray();
              ${"size".$i} = $Products[0]["length_cut"];
              ${"upper_limit".$i} = $Products[0]["length_upper_limit"];
              ${"lower_limit".$i} = $Products[0]["length_lower_limit"];
        
            }
    
            if(${"input_type".$i} !== "judge" && ${"result_size".$j."_".$i} <= (float)${"size".$i} + (float)${"upper_limit".$i}
            && ${"result_size".$j."_".$i} >= (float)${"size".$i} + (float)${"lower_limit".$i}){
      
              $gouhi_check = $gouhi_check;

            } elseif(${"input_type".$i} == "judge" && ${"result_size".$j."_".$i} < 1) {

              $gouhi_check = $gouhi_check;

            } else {

              $gouhi_check = $gouhi_check + 1;

            }

            if($data['gaikan'.$j] > 0){
              $gouhi_check = $gouhi_check + 1;
            }

          }
          if($gouhi_check > 0){
            ${"gouhi".$j} = 1;
            ${"gouhihyouji".$j} = "否";
          } else {
            ${"gouhi".$j} = 0;
            ${"gouhihyouji".$j} = "合";
          }
          $this->set('gouhi'.$j,${"gouhi".$j});
          $this->set('gouhihyouji'.$j,${"gouhihyouji".$j});
  
        }

        $gyou = $data["gyou"] + 1;
        $this->set('gyou', $gyou);

        $j = $data["gyou"];
        ${"user_code".$j} = $data['user_code'.$j];
        $Users = $this->Users->find()->contain(["Staffs"])->where(['user_code' => ${"user_code".$j}, 'Users.delete_flag' => 0])->toArray();

        if(isset($data['datetimenow'.$j])){

          $InspectionDataResultParents = $this->InspectionDataResultParents->find()
          ->where(['inspection_standard_size_parent_id' => $data['inspection_standard_size_parent_id'],
          'product_condition_parent_id' => $data['product_condition_parent_id'],
          'inspection_data_conditon_parent_id' => $data['inspection_data_conditon_parent_id'],
          'lot_number' => $data['lot_number'.$j], 'delete_flag' => 0])
          ->order(["id"=>"DESC"])->toArray();

          }

          if(!isset($data['tuzukikara']) && $data["gyou"] > 1){
            $jm = $j - 1;
      
            if(date("Y-m-d H:i:s") > $date_fin){//測定中に日付をまたいではいけない
              $check_over = 1;
            }else{
              $check_over = 0;
            }
          }elseif($data["mikan_check"] == 1){
            $check_over = 1;
          }else{//最初にデータを登録した時間をdate_staとする
            
            $date1 = strtotime(date("Y-m-d"));
            $date_sta = date("Y-m-d")." 00:00:00";
            $date_fin = date('Y-m-d', strtotime('+1 day', $date1))." 00:00:00";
            $this->set('date_sta',$date_sta);
            $this->set('date_fin',$date_fin);
    
            $check_over = 0;
          }

          if($data["mikan_check"] == 0 && $check_over == 0 && !isset($InspectionDataResultParents[0])
           && isset($data['datetimenow'.$j])){//再読み込みで同じデータが登録されないようにチェック

          $tourokuInspectionDataResultParents = array();

          if(isset($data["kaishi_loss_amount"])){

            $tourokuInspectionDataResultParents = [
              'inspection_data_conditon_parent_id' => (int)$data['inspection_data_conditon_parent_id'],
              "inspection_standard_size_parent_id" => $data['inspection_standard_size_parent_id'],
              "product_condition_parent_id" => $data['product_condition_parent_id'],
              'product_id' => $data['product_id'.$j],
              'lot_number' => $data['lot_number'.$j],
              'datetime' => date("Y-m-d H:i").":22",
              'staff_id' => $Users[0]["staff_id"],
              'appearance' => $data['gaikan'.$j],
              'result_weight' => $data['weight'.$j],
              'judge' => ${"gouhi".$j},
              "loss_amount" => $data["kaishi_loss_amount"],
              "bik" => $data["kaishi_bik"],
              "delete_flag" => 0,
              'created_at' => date("Y-m-d H:i:s"),
              "created_staff" => $Users[0]["staff_id"]//ログインは不要
            ];

            $lossflag1 = 1;
            $this->set('lossflag1',$lossflag1);

          }else{

            $tourokuInspectionDataResultParents = [
              'inspection_data_conditon_parent_id' => (int)$data['inspection_data_conditon_parent_id'],
              "inspection_standard_size_parent_id" => $data['inspection_standard_size_parent_id'],
              "product_condition_parent_id" => $data['product_condition_parent_id'],
              'product_id' => $data['product_id'.$j],
              'lot_number' => $data['lot_number'.$j],
              'datetime' => date("Y-m-d H:i").":22",
              'staff_id' => $Users[0]["staff_id"],
              'appearance' => $data['gaikan'.$j],
              'result_weight' => $data['weight'.$j],
              'judge' => ${"gouhi".$j},
              "delete_flag" => 0,
              'created_at' => date("Y-m-d H:i:s"),
              "created_staff" => $Users[0]["staff_id"]//ログインは不要
            ];

          }
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

            if($this->InspectionDataResultParents->save($InspectionDataResultParents)){

              $InspectionDataResultParentsId = $this->InspectionDataResultParents->find()
              ->where(['inspection_standard_size_parent_id' => $data['inspection_standard_size_parent_id'],
              "delete_flag" => 0, 'lot_number' => $data['lot_number'.$j]])
              ->order(["id"=>"DESC"])->toArray();

              $tourokuInspectionDataResultChildren = array();

              for($i=1; $i<=11; $i++){

                if(strlen($data["result_size".$j."_".$i]) > 0){

                  $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren->find()
                  ->where(['inspection_standard_size_parent_id' => $data['inspection_standard_size_parent_id'], "size_number" => $i])
                  ->order(["id"=>"DESC"])->toArray();

                  $tourokuInspectionDataResultChildren[] = [
                    "inspection_data_result_parent_id" => $InspectionDataResultParentsId[0]["id"],
                    "inspection_standard_size_child_id" => $InspectionStandardSizeChildren[0]["id"],
                    "result_size" => sprintf("%.2f", $data["result_size".$j."_".$i]),
                    "delete_flag" => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    "created_staff" => $Users[0]["staff_id"]//ログインは不要
                  ];

                }

                if($i == 11){//各jに対して一括登録

                  $InspectionDataResultChildren = $this->InspectionDataResultChildren
                  ->patchEntities($this->InspectionDataResultChildren->newEntity(), $tourokuInspectionDataResultChildren);
                  if ($this->InspectionDataResultChildren->saveMany($InspectionDataResultChildren)) {

                    $mes = "";
                    $this->set('mes',$mes);
                    $connection->commit();// コミット5

                  }

                }

              }

            } else {

              $gyou = $data["gyou"];
              $this->set('gyou', $gyou);
      
              $mes = "※登録されませんでした。管理者に報告してください。";
              $this->set('mes',$mes);
              $this->Flash->error(__('The data could not be saved. Please, try again.'));
              throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

            }

          } catch (Exception $e) {//トランザクション7
          //ロールバック8
            $connection->rollback();//トランザクション9
          }//トランザクション10

        }else{

          if($data["mikan_check"] == 1 || $check_over == 1){

            $mes = "※午前0時をまたぐ登録はできません。終了ロスを登録後、検査完了へ進んでください。";
            $this->set('mes',$mes);

            $end_ok_check = 0;
            $this->set('end_ok_check', $end_ok_check);
            
            if($data["mikan_check"] == 1 && isset($data["tuzukikara"])){
              $gyou = $data["gyou"] + 1;
            }else{
              $gyou = $data["gyou"];
            }
            $this->set('gyou', $gyou);

          }elseif(isset($data['datetimenow'.$j])){

            $mes = "※再読み込みされました。同じデータは登録されません。";
            $this->set('mes',$mes);

            if($data["gyou"] == 1){//再読み込みのときと同じ時間で登録しようとした時でgyouがちがう
              $gyou = 2;
            }else{

              $InspectionDataResultParentDatas = $this->InspectionDataResultParents->find()
              ->contain(['InspectionStandardSizeParents', 'ProductConditionParents', 'Products'])
              ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
              'InspectionDataResultParents.datetime >=' => date("Y-m-d ").$data['datetime1'],
              'InspectionDataResultParents.datetime <' => date("Y-m-d H:i:s"),
              'InspectionStandardSizeParents.delete_flag' => 0,
              'InspectionDataResultParents.delete_flag' => 0])
              ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();

              $gyou = count($InspectionDataResultParentDatas) + 1;
            }

            for($j=0; $j<count($InspectionDataResultParentDatas); $j++){

              $datetimenum = $j + 1;
              ${"datetime".$datetimenum} = $InspectionDataResultParentDatas[$j]['datetime']->format("H:i");;
              $this->set('datetime'.$datetimenum,${"datetime".$datetimenum});
    
            }
/*
            echo "<pre>";
            print_r($gyou);
            echo "</pre>";
  */
            $this->set('gyou', $gyou);

          }else{

            $mes = "※続きから登録できます。";
            $this->set('mes',$mes);
  
            if($data["gyou"] == 1){
              $gyou = $data["gyou"];
            }else{
              $gyou = $data["gyou"] + 1;
            }
            $this->set('gyou', $gyou);
  
          }
  
        }

        }

      }elseif($checkedit > 0){//修正のとき

        $this->set('checkedit', $checkedit);

        $count_seikeijouken = $data["count_seikeijouken"];
        $this->set('count_seikeijouken', $count_seikeijouken);
  
        $gyou = $data["gyou"];
        $this->set('gyou', $gyou);

        for($j=1; $j<=$gyou; $j++){

          if(isset($data['lot_number'.$j])){
            ${"lot_number".$j} = $data['lot_number'.$j];
          }else{
            ${"lot_number".$j} = "";
          }
          $this->set('lot_number'.$j,${"lot_number".$j});

          if(isset($data['datetime'.$j]["hour"])){
            ${"datetime".$j} = $data['datetime'.$j]["hour"].":".$data['datetime'.$j]["minute"];
          }elseif(isset($data['datetime'.$j])){
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

          if(isset($data['product_id'.$j])){
            ${"product_id".$j} = $data['product_id'.$j];
            $Products = $this->Products->find()
            ->where(['id' => ${"product_id".$j}])->toArray();
            ${"lengthhyouji".$j} = $Products[0]["length"];
          }else{
            ${"product_id".$j} = "";
            ${"lengthhyouji".$j} = "";
          }
          $this->set('product_id'.$j,${"product_id".$j});
          $this->set('lengthhyouji'.$j,${"lengthhyouji".$j});

          if(isset($data['gaikan'.$j])){
            ${"gaikan".$j} = $data['gaikan'.$j];
          }else{
            ${"gaikan".$j} = "";
          }
          $this->set('gaikan'.$j,${"gaikan".$j});

          if(isset($data['weight'.$j])){
            ${"weight".$j} = $data['weight'.$j];
            ${"weight".$j} = sprintf("%.1f", ${"weight".$j});
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

          $gouhi_check = 0;

          for($i=1; $i<=11; $i++){

            if(strlen($data["result_size".$j."_".$i]) > 0){
              ${"result_size".$j."_".$i} = $data["result_size".$j."_".$i];
              /*
              echo "<pre>";
              print_r(${"result_size".$j."_".$i});
              echo "</pre>";
        */
              $dotini = substr(${"result_size".$j."_".$i}, 0, 1);
              $dotend = substr(${"result_size".$j."_".$i}, -1, 1);
  
              if($dotini == "."){
                ${"result_size".$j."_".$i} = "0".${"result_size".$j."_".$i};
              }elseif($dotend == "."){
                ${"result_size".$j."_".$i} = ${"result_size".$j."_".$i}."0";
              }
              if(${"result_size".$j."_".$i} !== "〇" && ${"result_size".$j."_".$i} !== "✕"){
                ${"result_size".$j."_".$i} = sprintf("%.1f", ${"result_size".$j."_".$i});
              }

            }else{
              ${"result_size".$j."_".$i} = "";
            }
            $this->set("result_size".$j."_".$i,${"result_size".$j."_".$i});
            
            $Productlengthcheck = $this->Products->find()
            ->where(['product_code like' => $product_code_ini.'%'
            , 'status_kensahyou' => 0, 'status_length' => 0, 'delete_flag' => 0])->order(["id"=>"ASC"])->toArray();
            if($i == $num_length + 1 && count($Productlengthcheck) > 0){//長さ列の場合

              $Products= $this->Products->find()->where(['product_code like' => $product_code_ini.'%', 'length' => ${"lengthhyouji".$j}, 'delete_flag' => 0])->toArray();
              ${"size".$i} = $Products[0]["length_cut"];
              ${"upper_limit".$i} = $Products[0]["length_upper_limit"];
              ${"lower_limit".$i} = $Products[0]["length_lower_limit"];
        
            }

            if(${"input_type".$i} !== "judge" && ${"result_size".$j."_".$i} <= (float)${"size".$i} + (float)${"upper_limit".$i}
            && ${"result_size".$j."_".$i} >= (float)${"size".$i} + (float)${"lower_limit".$i}){
              $gouhi_check = $gouhi_check;
            } elseif(${"input_type".$i} == "judge" && ${"result_size".$j."_".$i} < 1) {
              $gouhi_check = $gouhi_check;
            } else {
              $gouhi_check = $gouhi_check + 1;
            }

          }
          if($gouhi_check > 0){
            ${"gouhi".$j} = 1;
            ${"gouhihyouji".$j} = "否";
          } else {
            ${"gouhi".$j} = 0;
            ${"gouhihyouji".$j} = "合";
          }
          $this->set('gouhi'.$j,${"gouhi".$j});
          $this->set('gouhihyouji'.$j,${"gouhihyouji".$j});
  
        }

      }elseif($checkloss > 0){//ロスのとき

        $this->set('checkloss', $checkloss);
  
        $InspectionDataResultParents = $this->InspectionDataResultParents->find()
        ->contain(['ProductConditionParents', 'Products'])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
         'InspectionDataResultParents.delete_flag' => 0,
         'lot_number' => $data['lot_number'.$checkloss], 'datetime >=' => $date_sta, 'datetime <' => $date_fin])
        ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();
/*
        echo "<pre>";
        print_r($InspectionDataResultParents);
        echo "</pre>";
*/
        $loss_amount_moto = $InspectionDataResultParents[0]["loss_amount"];
        $this->set('loss_amount_moto', $loss_amount_moto);
        $bik_moto = $InspectionDataResultParents[0]["bik"];
        $this->set('bik_moto', $bik_moto);

        $count_seikeijouken = $data["count_seikeijouken"];
        $this->set('count_seikeijouken', $count_seikeijouken);
  
        $gyou = $data["gyou"];
        $this->set('gyou', $gyou);

        for($j=1; $j<=$gyou; $j++){

          if(isset($data['lot_number'.$j])){
            ${"lot_number".$j} = $data['lot_number'.$j];
          }else{
            ${"lot_number".$j} = "";
          }
          $this->set('lot_number'.$j,${"lot_number".$j});

          if(isset($data['datetime'.$j]["hour"])){
            ${"datetime".$j} = $data['datetime'.$j]["hour"].":".$data['datetime'.$j]["minute"];
          }elseif(isset($data['datetime'.$j])){
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

          if(isset($data['product_id'.$j])){
            ${"product_id".$j} = $data['product_id'.$j];
            $Products = $this->Products->find()
            ->where(['id' => ${"product_id".$j}])->toArray();
            ${"lengthhyouji".$j} = $Products[0]["length"];
          }else{
            ${"product_id".$j} = "";
            ${"lengthhyouji".$j} = "";
          }
          $this->set('product_id'.$j,${"product_id".$j});
          $this->set('lengthhyouji'.$j,${"lengthhyouji".$j});

          if(isset($data['gaikan'.$j])){
            ${"gaikan".$j} = $data['gaikan'.$j];
          }else{
            ${"gaikan".$j} = "";
          }
          $this->set('gaikan'.$j,${"gaikan".$j});

          if(isset($data['weight'.$j])){
            ${"weight".$j} = $data['weight'.$j];
            ${"weight".$j} = sprintf("%.1f", ${"weight".$j});
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

          $gouhi_check = 0;

          for($i=1; $i<=11; $i++){

            if(strlen($data["result_size".$j."_".$i]) > 0){
              ${"result_size".$j."_".$i} = $data["result_size".$j."_".$i];

              $dotini = substr(${"result_size".$j."_".$i}, 0, 1);
              $dotend = substr(${"result_size".$j."_".$i}, -1, 1);
  
              if($dotini == "."){
                ${"result_size".$j."_".$i} = "0".${"result_size".$j."_".$i};
              }elseif($dotend == "."){
                ${"result_size".$j."_".$i} = ${"result_size".$j."_".$i}."0";
              }
              if(${"result_size".$j."_".$i} !== "〇" && ${"result_size".$j."_".$i} !== "✕"){
                ${"result_size".$j."_".$i} = sprintf("%.1f", ${"result_size".$j."_".$i});
              }

            }else{
              ${"result_size".$j."_".$i} = "";
            }
            $this->set("result_size".$j."_".$i,${"result_size".$j."_".$i});
            
            $Productlengthcheck = $this->Products->find()
            ->where(['product_code like' => $product_code_ini.'%'
            , 'status_kensahyou' => 0, 'status_length' => 0, 'delete_flag' => 0])->order(["id"=>"ASC"])->toArray();
            if($i == $num_length + 1 && count($Productlengthcheck) > 0){//長さ列の場合

              $Products= $this->Products->find()->where(['product_code like' => $product_code_ini.'%', 'length' => ${"lengthhyouji".$j}, 'delete_flag' => 0])->toArray();
              ${"size".$i} = $Products[0]["length_cut"];
              ${"upper_limit".$i} = $Products[0]["length_upper_limit"];
              ${"lower_limit".$i} = $Products[0]["length_lower_limit"];
        
            }

            if(${"input_type".$i} !== "judge" && ${"result_size".$j."_".$i} <= (float)${"size".$i} + (float)${"upper_limit".$i}
            && ${"result_size".$j."_".$i} >= (float)${"size".$i} + (float)${"lower_limit".$i}){
              $gouhi_check = $gouhi_check;
            } elseif(${"input_type".$i} == "judge" && ${"result_size".$j."_".$i} < 1) {
              $gouhi_check = $gouhi_check;
            } else {
              $gouhi_check = $gouhi_check + 1;
            }

          }
          if($gouhi_check > 0){
            ${"gouhi".$j} = 1;
            ${"gouhihyouji".$j} = "否";
          } else {
            ${"gouhi".$j} = 0;
            ${"gouhihyouji".$j} = "合";
          }
          $this->set('gouhi'.$j,${"gouhi".$j});
          $this->set('gouhihyouji'.$j,${"gouhihyouji".$j});
  
        }

      }elseif(isset($data["losstouroku"])){

        $loss_num = $data["checkloss"];

        if(strlen($data["loss_amount"]) == 0 && strlen($data["bik"]) == 0){
          ${"lossflag".$loss_num} = 0;
          $this->set('lossflag'.$loss_num, ${"lossflag".$loss_num});
        }

        $count_seikeijouken = $data["count_seikeijouken"];
        $this->set('count_seikeijouken', $count_seikeijouken);
  
        $gyou = $data["gyou"] - 1;
        
        for($j=1; $j<=$gyou; $j++){

          if(isset($data['lot_number'.$j])){
            ${"lot_number".$j} = $data['lot_number'.$j];
          }else{
            ${"lot_number".$j} = "";
          }
          $this->set('lot_number'.$j,${"lot_number".$j});

          if(isset($data['datetime'.$j]["hour"])){
            ${"datetime".$j} = $data['datetime'.$j]["hour"].":".$data['datetime'.$j]["minute"];
          }elseif(isset($data['datetime'.$j])){
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

          if(isset($data['product_id'.$j])){
            ${"product_id".$j} = $data['product_id'.$j];
            $Products = $this->Products->find()
            ->where(['id' => ${"product_id".$j}])->toArray();
            ${"lengthhyouji".$j} = $Products[0]["length"];
          }else{
            ${"product_id".$j} = "";
            ${"lengthhyouji".$j} = "";
          }
          $this->set('product_id'.$j,${"product_id".$j});
          $this->set('lengthhyouji'.$j,${"lengthhyouji".$j});

          if(isset($data['gaikan'.$j])){
            ${"gaikan".$j} = $data['gaikan'.$j];
          }else{
            ${"gaikan".$j} = "";
          }
          $this->set('gaikan'.$j,${"gaikan".$j});

          if(isset($data['weight'.$j])){
            ${"weight".$j} = $data['weight'.$j];
            ${"weight".$j} = sprintf("%.1f", ${"weight".$j});
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

          $gouhi_check = 0;

          for($i=1; $i<=11; $i++){

            if(strlen($data["result_size".$j."_".$i]) > 0){
              ${"result_size".$j."_".$i} = $data["result_size".$j."_".$i];
  
              $dotini = substr(${"result_size".$j."_".$i}, 0, 1);
              $dotend = substr(${"result_size".$j."_".$i}, -1, 1);
  
              if($dotini == "."){
                ${"result_size".$j."_".$i} = "0".${"result_size".$j."_".$i};
              }elseif($dotend == "."){
                ${"result_size".$j."_".$i} = ${"result_size".$j."_".$i}."0";
              }
              ${"result_size".$j."_".$i} = sprintf("%.1f", ${"result_size".$j."_".$i});

            }else{
              ${"result_size".$j."_".$i} = "";
            }
            $this->set("result_size".$j."_".$i,${"result_size".$j."_".$i});
            
            $Productlengthcheck = $this->Products->find()
            ->where(['product_code like' => $product_code_ini.'%'
            , 'status_kensahyou' => 0, 'status_length' => 0, 'delete_flag' => 0])->order(["id"=>"ASC"])->toArray();
            if($i == $num_length + 1 && count($Productlengthcheck) > 0){//長さ列の場合

              $Products= $this->Products->find()->where(['product_code like' => $product_code_ini.'%', 'length' => ${"lengthhyouji".$j}, 'delete_flag' => 0])->toArray();
              ${"size".$i} = $Products[0]["length_cut"];
              ${"upper_limit".$i} = $Products[0]["length_upper_limit"];
              ${"lower_limit".$i} = $Products[0]["length_lower_limit"];
        
            }

            if(${"input_type".$i} !== "judge" && ${"result_size".$j."_".$i} <= (float)${"size".$i} + (float)${"upper_limit".$i}
            && ${"result_size".$j."_".$i} >= (float)${"size".$i} + (float)${"lower_limit".$i}){
              $gouhi_check = $gouhi_check;
            } elseif(${"input_type".$i} == "judge" && ${"result_size".$j."_".$i} < 1) {
              $gouhi_check = $gouhi_check;
            } else {
              $gouhi_check = $gouhi_check + 1;
            }

          }
          if($gouhi_check > 0){
            ${"gouhi".$j} = 1;
            ${"gouhihyouji".$j} = "否";
          } else {
            ${"gouhi".$j} = 0;
            ${"gouhihyouji".$j} = "合";
          }
          $this->set('gouhi'.$j,${"gouhi".$j});
          $this->set('gouhihyouji'.$j,${"gouhihyouji".$j});

        }

        $gyou = $data["gyou"];
        $this->set('gyou', $gyou);

        $j = $loss_num;//ロスを登録する行のデータ
        ${"user_code".$j} = $data['user_code'.$j];
        $Users = $this->Users->find()->contain(["Staffs"])->where(['user_code' => ${"user_code".$j}, 'Users.delete_flag' => 0])->toArray();

        $connection = ConnectionManager::get('default');//トランザクション1
        // トランザクション開始2
        $connection->begin();//トランザクション3
        try {//トランザクション4

          if($this->InspectionDataResultParents->updateAll(
            ['loss_amount' => $data["loss_amount"],
            'bik' => $data["bik"],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_staff' => $Users[0]["staff_id"]],
          ['inspection_data_conditon_parent_id' => $data["inspection_data_conditon_parent_id"], 'lot_number' => $data['lot_number'.$loss_num]])){

            $mes = "";
            $this->set('mes',$mes);
            $connection->commit();// コミット5

          } else {

            $mes = "※登録されませんでした。管理者に報告してください。";
            $this->set('mes',$mes);
            $this->Flash->error(__('The data could not be saved. Please, try again.'));
            throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

          }

        } catch (Exception $e) {//トランザクション7
        //ロールバック8
          $connection->rollback();//トランザクション9
        }//トランザクション10

      }elseif(isset($data["edittouroku"])){

        $edit_num = $data["checkedit"];

        $count_seikeijouken = $data["count_seikeijouken"];
        $this->set('count_seikeijouken', $count_seikeijouken);
  
        $gyou = $data["gyou"] - 1;
        
        for($j=1; $j<=$gyou; $j++){

          if(isset($data['lot_number'.$j])){
            ${"lot_number".$j} = $data['lot_number'.$j];
          }else{
            ${"lot_number".$j} = "";
          }
          $this->set('lot_number'.$j,${"lot_number".$j});

          if(isset($data['datetime'.$j]["hour"])){
            ${"datetime".$j} = $data['datetime'.$j]["hour"].":".$data['datetime'.$j]["minute"];
          }elseif(isset($data['datetime'.$j])){
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

          if(isset($data['product_id'.$j])){
            ${"product_id".$j} = $data['product_id'.$j];
            $Products = $this->Products->find()
            ->where(['id' => ${"product_id".$j}])->toArray();
            ${"lengthhyouji".$j} = $Products[0]["length"];
          }else{
            ${"product_id".$j} = "";
            ${"lengthhyouji".$j} = "";
          }
          $this->set('product_id'.$j,${"product_id".$j});
          $this->set('lengthhyouji'.$j,${"lengthhyouji".$j});

          if(isset($data['gaikan'.$j])){
            ${"gaikan".$j} = $data['gaikan'.$j];
          }else{
            ${"gaikan".$j} = "";
          }
          $this->set('gaikan'.$j,${"gaikan".$j});

          if(isset($data['weight'.$j])){
            ${"weight".$j} = $data['weight'.$j];
            ${"weight".$j} = sprintf("%.1f", ${"weight".$j});
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

          $gouhi_check = 0;

          for($i=1; $i<=11; $i++){

            if(strlen($data["result_size".$j."_".$i]) > 0){
              ${"result_size".$j."_".$i} = $data["result_size".$j."_".$i];
  
              $dotini = substr(${"result_size".$j."_".$i}, 0, 1);
              $dotend = substr(${"result_size".$j."_".$i}, -1, 1);
  
              if($dotini == "."){
                ${"result_size".$j."_".$i} = "0".${"result_size".$j."_".$i};
              }elseif($dotend == "."){
                ${"result_size".$j."_".$i} = ${"result_size".$j."_".$i}."0";
              }
              ${"result_size".$j."_".$i} = sprintf("%.1f", ${"result_size".$j."_".$i});

            }else{
              ${"result_size".$j."_".$i} = "";
            }
            $this->set("result_size".$j."_".$i,${"result_size".$j."_".$i});
            
            $Productlengthcheck = $this->Products->find()
            ->where(['product_code like' => $product_code_ini.'%'
            , 'status_kensahyou' => 0, 'status_length' => 0, 'delete_flag' => 0])->order(["id"=>"ASC"])->toArray();
            if($i == $num_length + 1 && count($Productlengthcheck) > 0){//長さ列の場合

              $Products= $this->Products->find()->where(['product_code like' => $product_code_ini.'%', 'length' => ${"lengthhyouji".$j}, 'delete_flag' => 0])->toArray();
              ${"size".$i} = $Products[0]["length_cut"];
              ${"upper_limit".$i} = $Products[0]["length_upper_limit"];
              ${"lower_limit".$i} = $Products[0]["length_lower_limit"];
        
            }

            if(${"input_type".$i} !== "judge" && ${"result_size".$j."_".$i} <= (float)${"size".$i} + (float)${"upper_limit".$i}
            && ${"result_size".$j."_".$i} >= (float)${"size".$i} + (float)${"lower_limit".$i}){
              $gouhi_check = $gouhi_check;
            } elseif(${"input_type".$i} == "judge" && ${"result_size".$j."_".$i} < 1) {
              $gouhi_check = $gouhi_check;
            } else {
              $gouhi_check = $gouhi_check + 1;
            }

          }

          if($data['gaikan'.$j] > 0){
            $gouhi_check = $gouhi_check + 1;
          }

          if($gouhi_check > 0){
            ${"gouhi".$j} = 1;
            ${"gouhihyouji".$j} = "否";
          } else {
            ${"gouhi".$j} = 0;
            ${"gouhihyouji".$j} = "合";
          }
          $this->set('gouhi'.$j,${"gouhi".$j});
          $this->set('gouhihyouji'.$j,${"gouhihyouji".$j});

        }

        $gyou = $data["gyou"];
        $this->set('gyou', $gyou);

        //ここから登録できるかデータチェック
        $checknull = 0;
        $j = $edit_num;

        ${"user_code".$j} = $data['user_code'.$j];
        $Users = $this->Users->find()->contain(["Staffs"])->where(['user_code' => ${"user_code".$j}, 'Users.delete_flag' => 0])->toArray();
        if(!isset($Users[0])){
          $checknull = $checknull + 1;
          $mess = $mess."※入力されたユーザーIDは登録されていません。".'<br>';
        }else{
          $checknull = 0;
        }
        for($k=0; $k<count($arrNum); $k++){

          $i = $arrNum[$k];

          if(strlen($data["result_size".$j."_".$i]) > 0){//ちゃんと入力されている場合
            $checknull = $checknull;
          }else{//入力漏れがある場合
            $checknull = $checknull + 1;
            $mess = "※測定データに入力漏れがあります。".'<br>';
          }

          if(strlen($data['weight'.$j]) > 0){//ちゃんと入力されている場合
            $checknull = $checknull;
          }else{//入力漏れがある場合
            $checknull = $checknull + 1;
            $mess = "※重量データに入力漏れがあります。".'<br>';
          }

          if($data['gaikan'.$j] == "-"){//入力漏れがある場合
            $checknull = $checknull + 1;
            $mess = "※外観を選択してください。".'<br>';
          }else{
            $checknull = $checknull;
          }

        }
        $this->set('mess', $mess);

        if($checknull > 0){//入力漏れがある場合

          $gyou = $data["gyou"];
          $this->set('gyou', $gyou);
          $checkedit = $data["checkedit"];//入力に不備がある場合
          $this->set('checkedit', $checkedit);

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

            if(isset($data['product_id'.$j])){
              ${"product_id".$j} = $data['product_id'.$j];
              $Products = $this->Products->find()
              ->where(['id' => ${"product_id".$j}])->toArray();
              ${"lengthhyouji".$j} = $Products[0]["length"];
            }else{
              ${"product_id".$j} = "";
              ${"lengthhyouji".$j} = "";
            }
            $this->set('product_id'.$j,${"product_id".$j});
            $this->set('lengthhyouji'.$j,${"lengthhyouji".$j});

            if(isset($data['gaikan'.$j])){
              ${"gaikan".$j} = $data['gaikan'.$j];
            }else{
              ${"gaikan".$j} = "";
            }
            $this->set('gaikan'.$j,${"gaikan".$j});
  
            if(isset($data['weight'.$j])){
              ${"weight".$j} = $data['weight'.$j];
              ${"weight".$j} = sprintf("%.1f", ${"weight".$j});
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

            for($i=1; $i<=11; $i++){

              if(isset($data["result_size".$j."_".$i])){
                if(strlen($data["result_size".$j."_".$i]) > 0){
                  ${"result_size".$j."_".$i} = sprintf("%.1f", $data["result_size".$j."_".$i]);
                }else{
                  ${"result_size".$j."_".$i} = "";
                }
              }else{
                ${"result_size".$j."_".$i} = "";
              }
              $this->set("result_size".$j."_".$i,${"result_size".$j."_".$i});

            }

          }

        }else{//ちゃんと入力されている場合
        //入力に不備がない場合
        $j = $edit_num;//修正する行のデータ
        ${"user_code".$j} = $data['user_code'.$j];
        $Users = $this->Users->find()->contain(["Staffs"])->where(['user_code' => ${"user_code".$j}, 'Users.delete_flag' => 0])->toArray();

        $updateInspectionDataResultParents = array();

        if($data['datetime'.$j]["hour"] > 5){
          $datetime = substr($date_sta, 0, 10)." ".$data['datetime'.$j]["hour"].":".$data['datetime'.$j]["minute"].":00";
        }else{
          $datetime = substr($date_fin, 0, 10)." ".$data['datetime'.$j]["hour"].":".$data['datetime'.$j]["minute"].":00";
        }
        $updateInspectionDataResultParents = [
          'product_id' => $data['product_id'.$j],
          'datetime' => $datetime,
          'staff_id' => $Users[0]["staff_id"],
          'appearance' => $data['gaikan'.$j],
          'result_weight' => $data['weight'.$j],
          'judge' => ${"gouhi".$j},
        ];
      /*
        echo "<pre>";
        print_r($updateInspectionDataResultParents);
        echo "</pre>";
*/
        $InspectionDataResultParents = $this->InspectionDataResultParents->find()->contain(['Products', 'ProductConditionParents'])
        ->where([
          'product_code like' => $product_code_ini.'%',
          'machine_num' => $machine_num,
     //     'inspection_standard_size_parent_id' => $data['inspection_standard_size_parent_id'],
          'datetime >=' => $date_sta, 'datetime <' => $date_fin,
     //    'product_condition_parent_id' => $data['product_condition_parent_id'],
         'lot_number' => $data['lot_number'.$edit_num]])->order(["datetime"=>"DESC"])->toArray();

        $motoInspectionDataResultParentId = $InspectionDataResultParents[0]["id"];
 
        $updateInspectionDataResultChildren = array();

        for($i=1; $i<=11; $i++){

          if(strlen($data["result_size".$j."_".$i]) > 0){

            $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren->find()
            ->where(['inspection_standard_size_parent_id' => $data['inspection_standard_size_parent_id'], "size_number" => $i])
            ->order(["id"=>"DESC"])->toArray();

            $updateInspectionDataResultChildren[] = [
              "inspection_data_result_parent_id" => $motoInspectionDataResultParentId,
              "inspection_standard_size_child_id" => $InspectionStandardSizeChildren[0]["id"],
              "result_size" => $data["result_size".$j."_".$i],
              "delete_flag" => 0,
              'created_at' => date("Y-m-d H:i:s"),
              "created_staff" => $Users[0]["staff_id"]//ログインは不要
            ];

          }

        }
        /*
        echo "<pre>";
        print_r($updateInspectionDataResultChildren);
        echo "</pre>";
*/
        $InspectionDataResultParents = $this->InspectionDataResultParents
        ->patchEntity($this->InspectionDataResultParents->newEntity(), $updateInspectionDataResultParents);
        $connection = ConnectionManager::get('default');//トランザクション1
        // トランザクション開始2
        $connection->begin();//トランザクション3
        try {//トランザクション4

          $this->InspectionDataResultParents->updateAll(
            [
              'product_id' => $updateInspectionDataResultParents["product_id"],
              'datetime' => $updateInspectionDataResultParents["datetime"],
              'staff_id' => $updateInspectionDataResultParents["staff_id"],
              'appearance' => $updateInspectionDataResultParents["appearance"],
              'result_weight' => $updateInspectionDataResultParents["result_weight"],
              'judge' => $updateInspectionDataResultParents["judge"],
              'updated_at' => date('Y-m-d H:i:s'),
              'updated_staff' => $Users[0]["staff_id"]],
            ['inspection_data_conditon_parent_id' => $data["inspection_data_conditon_parent_id"], 'lot_number' => $data['lot_number'.$edit_num]]);

          $InspectionDataResultChildren = $this->InspectionDataResultChildren
          ->patchEntities($this->InspectionDataResultChildren->newEntity(), $updateInspectionDataResultChildren);

          if($this->InspectionDataResultChildren->saveMany($InspectionDataResultChildren)){

            $mes = "";
            $this->set('mes',$mes);
            $connection->commit();// コミット5
            
          } else {

            $mes = "※更新されませんでした。管理者に報告してください。";
            $this->set('mes',$mes);
            $this->Flash->error(__('The data could not be saved. Please, try again.'));
            throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

          }

        } catch (Exception $e) {//トランザクション7
        //ロールバック8
          $connection->rollback();//トランザクション9
        }//トランザクション10
      
      }

    }elseif(isset($data["seikei"])){//成形条件調整

      $gyou = $data["gyou"];
      $this->set('gyou', $gyou);

      $count_seikeijouken = $data["count_seikeijouken"] + 1;
      $this->set('count_seikeijouken', $count_seikeijouken);

      $check_seikeijouken = 1;
      $this->set('check_seikeijouken', $check_seikeijouken);

      for($j=1; $j<=$gyou; $j++){

        if(isset($data['lot_number'.$j])){
          ${"lot_number".$j} = $data['lot_number'.$j];
        }else{
          ${"lot_number".$j} = "";
        }
        $this->set('lot_number'.$j,${"lot_number".$j});

        if(isset($data['datetime'.$j]["hour"])){
          ${"datetime".$j} = $data['datetime'.$j]["hour"].":".$data['datetime'.$j]["minute"];
        }elseif(isset($data['datetime'.$j])){
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

        if(isset($data['product_id'.$j])){
          ${"product_id".$j} = $data['product_id'.$j];
          $Products = $this->Products->find()
          ->where(['id' => ${"product_id".$j}])->toArray();
          ${"lengthhyouji".$j} = $Products[0]["length"];
        }else{
          ${"product_id".$j} = "";
          ${"lengthhyouji".$j} = "";
        }
        $this->set('product_id'.$j,${"product_id".$j});
        $this->set('lengthhyouji'.$j,${"lengthhyouji".$j});

        if(isset($data['gaikan'.$j])){
          ${"gaikan".$j} = $data['gaikan'.$j];
        }else{
          ${"gaikan".$j} = "";
        }
        $this->set('gaikan'.$j,${"gaikan".$j});

        if(isset($data['weight'.$j])){
          ${"weight".$j} = $data['weight'.$j];
          ${"weight".$j} = sprintf("%.1f", ${"weight".$j});
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

        $gouhi_check = 0;

        for($i=1; $i<=11; $i++){

          if(strlen($data["result_size".$j."_".$i]) > 0){
            ${"result_size".$j."_".$i} = $data["result_size".$j."_".$i];

            $dotini = substr(${"result_size".$j."_".$i}, 0, 1);
            $dotend = substr(${"result_size".$j."_".$i}, -1, 1);

            if($dotini == "."){
              ${"result_size".$j."_".$i} = "0".${"result_size".$j."_".$i};
            }elseif($dotend == "."){
              ${"result_size".$j."_".$i} = ${"result_size".$j."_".$i}."0";
            }
            ${"result_size".$j."_".$i} = sprintf("%.1f", ${"result_size".$j."_".$i});

          }else{
            ${"result_size".$j."_".$i} = "";
          }
          $this->set("result_size".$j."_".$i,${"result_size".$j."_".$i});
          
          $Productlengthcheck = $this->Products->find()
          ->where(['product_code like' => $product_code_ini.'%'
          , 'status_kensahyou' => 0, 'status_length' => 0, 'delete_flag' => 0])->order(["id"=>"ASC"])->toArray();
          if($i == $num_length + 1 && count($Productlengthcheck) > 0){//長さ列の場合

            $Products= $this->Products->find()->where(['product_code like' => $product_code_ini.'%', 'length' => ${"lengthhyouji".$j}, 'delete_flag' => 0])->toArray();
            ${"size".$i} = $Products[0]["length_cut"];
            ${"upper_limit".$i} = $Products[0]["length_upper_limit"];
            ${"lower_limit".$i} = $Products[0]["length_lower_limit"];
      
          }
    
          if(${"input_type".$i} !== "judge" && ${"result_size".$j."_".$i} <= (float)${"size".$i} + (float)${"upper_limit".$i}
          && ${"result_size".$j."_".$i} >= (float)${"size".$i} + (float)${"lower_limit".$i}){
            $gouhi_check = $gouhi_check;
          } elseif(${"input_type".$i} == "judge" && ${"result_size".$j."_".$i} < 1) {
            $gouhi_check = $gouhi_check;
          } else {
            $gouhi_check = $gouhi_check + 1;
          }

        }

        if($data['gaikan'.$j] > 0){
          $gouhi_check = $gouhi_check + 1;
        }

        if($gouhi_check > 0){
          ${"gouhi".$j} = 1;
          ${"gouhihyouji".$j} = "否";
        } else {
          ${"gouhi".$j} = 0;
          ${"gouhihyouji".$j} = "合";
        }
        $this->set('gouhi'.$j,${"gouhi".$j});
        $this->set('gouhihyouji'.$j,${"gouhihyouji".$j});

      }

    }elseif(isset($data["seikeikakutei"])){//成形条件調整の登録

      $gyou = $data["gyou"];
      $this->set('gyou', $gyou);

      $j = 1;

      $Users = $this->Users->find()->contain(["Staffs"])->where(['user_code' => $data["user_code_henkou"], 'Users.delete_flag' => 0])->toArray();
      if(!isset($Users[0])){
        $mess = '<br>'."※入力されたユーザーIDは登録されていません。";
        $this->set('mess', $mess);

        $count_seikeijouken = $data["count_seikeijouken"] - 1;
        $this->set('count_seikeijouken', $count_seikeijouken);

      }else{

        $count_seikeijouken = $data["count_seikeijouken"];
        $this->set('count_seikeijouken', $count_seikeijouken);
  
        $tourokuInspectionDataConditonParents = array();
        $tourokuInspectionDataConditonParents = [
          'product_code' => $product_code,
          'datetime' => date("Y-m-d H:i:s"),
          "delete_flag" => 0,
          'created_at' => date("Y-m-d H:i:s"),
          "created_staff" => $Users[0]["staff_id"]
        ];
  /*
        echo "<pre>";
        print_r($tourokuInspectionDataConditonParents);
        echo "</pre>";
*/
        $InspectionDataConditonParents = $this->InspectionDataConditonParents
        ->patchEntity($this->InspectionDataConditonParents->newEntity(), $tourokuInspectionDataConditonParents);
        $connection = ConnectionManager::get('default');//トランザクション1
        // トランザクション開始2
        $connection->begin();//トランザクション3
        try {//トランザクション4
          if ($this->InspectionDataConditonParents->save($InspectionDataConditonParents)) {

            $InspectionDataConditonParents = $this->InspectionDataConditonParents->find()
            ->where(['product_code' => $product_code, 'delete_flag' => 0])->order(["id"=>"DESC"])->toArray();
  
            $inspection_data_conditon_parent_id = $InspectionDataConditonParents[0]['id'];
            $this->set('inspection_data_conditon_parent_id', $inspection_data_conditon_parent_id);
      
            $tourokuInspectionDataConditonChildren = array();
            $countseikeiki = $data["countseikeiki"];
            for($j=1; $j<=$countseikeiki; $j++){
      
              $tourokuInspectionDataConditonChildren[] = [
                "inspection_data_conditon_parent_id" => $inspection_data_conditon_parent_id,
                "product_conditon_child_id" => $data["product_conditon_child_id".$j],
                "inspection_temp_1" => $data['inspection_temp_1'.$j],
                "inspection_temp_2" => $data['inspection_temp_2'.$j],
                "inspection_temp_3" => $data['inspection_temp_3'.$j],
                "inspection_temp_4" => $data['inspection_temp_4'.$j],
                "inspection_temp_5" => $data['inspection_temp_5'.$j],
                "inspection_temp_6" => $data['inspection_temp_6'.$j],
                "inspection_temp_7" => $data['inspection_temp_7'.$j],
                "inspection_extrude_roatation" => $data['inspection_extrude_roatation'.$j],
                "inspection_extrusion_load" => $data['inspection_extrusion_load'.$j],
                "inspection_pickup_speed" => $data['inspection_pickup_speed'],
                "delete_flag" => 0,
                'created_at' => date("Y-m-d H:i:s"),
                "created_staff" => $Users[0]["staff_id"]
              ];
      
            }
      
            $InspectionDataConditonChildren = $this->InspectionDataConditonChildren->patchEntities($this->InspectionDataConditonChildren->newEntity(), $tourokuInspectionDataConditonChildren);
            $this->InspectionDataConditonChildren->saveMany($InspectionDataConditonChildren);
    
            $mes = "";
            $this->set('mes',$mes);
            $connection->commit();// コミット5
  
          } else {
  
            $count_seikeijouken = $data["count_seikeijouken"] - 1;
            $this->set('count_seikeijouken', $count_seikeijouken);
      
            $this->Flash->error(__('The data could not be saved. Please, try again.'));
            throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
            $mes = "※登録されませんでした。管理者に問い合わせてください。";
            $this->set('mes',$mes);
  
          }
  
        } catch (Exception $e) {//トランザクション7
        //ロールバック8
          $connection->rollback();//トランザクション9
        }//トランザクション10
  
      }

      $check_seikeijouken = 0;
      $this->set('check_seikeijouken', $check_seikeijouken);

      for($j=1; $j<$gyou; $j++){

        if(isset($data['lot_number'.$j])){
          ${"lot_number".$j} = $data['lot_number'.$j];
        }else{
          ${"lot_number".$j} = "";
        }
        $this->set('lot_number'.$j,${"lot_number".$j});

        if(isset($data['datetime'.$j]["hour"])){
          ${"datetime".$j} = $data['datetime'.$j]["hour"].":".$data['datetime'.$j]["minute"];
        }elseif(isset($data['datetime'.$j])){
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

        if(isset($data['product_id'.$j])){
          ${"product_id".$j} = $data['product_id'.$j];
          $Products = $this->Products->find()
          ->where(['id' => ${"product_id".$j}])->toArray();
          ${"lengthhyouji".$j} = $Products[0]["length"];
        }else{
          ${"product_id".$j} = "";
          ${"lengthhyouji".$j} = "";
        }
        $this->set('product_id'.$j,${"product_id".$j});
        $this->set('lengthhyouji'.$j,${"lengthhyouji".$j});

        if(isset($data['gaikan'.$j])){
          ${"gaikan".$j} = $data['gaikan'.$j];
        }else{
          ${"gaikan".$j} = "";
        }
        $this->set('gaikan'.$j,${"gaikan".$j});

        if(isset($data['weight'.$j])){
          ${"weight".$j} = $data['weight'.$j];
          ${"weight".$j} = sprintf("%.1f", ${"weight".$j});
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

        $gouhi_check = 0;

        for($i=1; $i<=11; $i++){

          if(strlen($data["result_size".$j."_".$i]) > 0){
            ${"result_size".$j."_".$i} = $data["result_size".$j."_".$i];

            $dotini = substr(${"result_size".$j."_".$i}, 0, 1);
            $dotend = substr(${"result_size".$j."_".$i}, -1, 1);

            if($dotini == "."){
              ${"result_size".$j."_".$i} = "0".${"result_size".$j."_".$i};
            }elseif($dotend == "."){
              ${"result_size".$j."_".$i} = ${"result_size".$j."_".$i}."0";
            }
            ${"result_size".$j."_".$i} = sprintf("%.1f", ${"result_size".$j."_".$i});

          }else{
            ${"result_size".$j."_".$i} = "";
          }
          $this->set("result_size".$j."_".$i,${"result_size".$j."_".$i});
          
          $Productlengthcheck = $this->Products->find()
          ->where(['product_code like' => $product_code_ini.'%'
          , 'status_kensahyou' => 0, 'status_length' => 0, 'delete_flag' => 0])->order(["id"=>"ASC"])->toArray();
          if($i == $num_length + 1 && count($Productlengthcheck) > 0){//長さ列の場合

            $Products= $this->Products->find()->where(['product_code like' => $product_code_ini.'%', 'length' => ${"lengthhyouji".$j}, 'delete_flag' => 0])->toArray();
            ${"size".$i} = $Products[0]["length_cut"];
            ${"upper_limit".$i} = $Products[0]["length_upper_limit"];
            ${"lower_limit".$i} = $Products[0]["length_lower_limit"];
      
          }
    
          if(${"input_type".$i} !== "judge" && ${"result_size".$j."_".$i} <= (float)${"size".$i} + (float)${"upper_limit".$i}
          && ${"result_size".$j."_".$i} >= (float)${"size".$i} + (float)${"lower_limit".$i}){
            $gouhi_check = $gouhi_check;
          } elseif(${"input_type".$i} == "judge" && ${"result_size".$j."_".$i} < 1) {
            $gouhi_check = $gouhi_check;
          } else {
            $gouhi_check = $gouhi_check + 1;
          }

        }

        if($data['gaikan'.$j] > 0){
          $gouhi_check = $gouhi_check + 1;
        }

        if($gouhi_check > 0){
          ${"gouhi".$j} = 1;
          ${"gouhihyouji".$j} = "否";
        } else {
          ${"gouhi".$j} = 0;
          ${"gouhihyouji".$j} = "合";
        }
        $this->set('gouhi'.$j,${"gouhi".$j});
        $this->set('gouhihyouji'.$j,${"gouhihyouji".$j});

      }

    }elseif(isset($data["kaishi_loss"]) || isset($data["kaishilosstouroku"]) || !isset($data["gyou"])){//最初にここに来た時

      $kaishi_loss_flag = 1;
      $this->set('kaishi_loss_flag', $kaishi_loss_flag);

      if(isset($data["kaishi_loss"])){//開始ロスの登録

        $checkloss = 1;
        $this->set('checkloss', $checkloss);
        $kaishi_loss_input = 1;
        $this->set('kaishi_loss_input', $kaishi_loss_input);
        $kaishi_loss_flag = 0;
        $this->set('kaishi_loss_flag', $kaishi_loss_flag);

      }elseif(isset($data["kaishilosstouroku"])){//開始ロスの登録後

        $kaishi_loss_amount = $data["kaishi_loss_amount"];
        $this->set('kaishi_loss_amount', $kaishi_loss_amount);
        $kaishi_bik = $data["kaishi_bik"];
        $this->set('kaishi_bik', $kaishi_bik);
        $kaishi_loss_flag = 0;
        $this->set('kaishi_loss_flag', $kaishi_loss_flag);

      }elseif(isset($data["gyou"])){//開始ロスの登録後

        $kaishi_loss_flag = 0;
        $this->set('kaishi_loss_flag', $kaishi_loss_flag);

      }

      $gyou = 1;
      $this->set('gyou', $gyou);
      $count_seikeijouken = 1;
      $this->set('count_seikeijouken', $count_seikeijouken);

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
      ${"product_id".$j} = "";
      ${"lengthhyouji".$j} = "";
      $this->set('product_id'.$j,${"product_id".$j});
      $this->set('lengthhyouji'.$j,${"lengthhyouji".$j});

    }

    if(isset($data["tuzukikara"]) && $data["gyou"] == 1){
      $gyou = $data["gyou"] + 1;
    }
    $this->set('gyou', $gyou);

    $product_code_machine_num = $product_code."_".$machine_num;
    $htmlkensahyougenryouheader = new htmlkensahyouprogram();
    $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheader($product_code_machine_num);
    $this->set('htmlgenryouheader',$htmlgenryouheader);

      //温度の表示
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

        if(!isset($_SESSION)){
        session_start();
        }
        $_SESSION['user_code'] = array();
        $_SESSION['user_code'] = $user_code;

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は原料登録がされていません。"]]);

      }

      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['machine_num' => $machine_num, 'product_code' => $product_code,
      'ProductConditionParents.is_active' => 0,
      'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%',
        'ProductConditionParents.is_active' => 0,
        'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();
  
      }

      if(isset($ProductConditionParents[0])){

        $product_condition_parent_id = $ProductConditionParents[0]['id'];
        $this->set('product_condition_parent_id', $product_condition_parent_id);

      }else{

        if(!isset($_SESSION)){
        session_start();
        }
        $_SESSION['user_code'] = array();
        $_SESSION['user_code'] = $user_code;

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は原料登録がされていません。"]]);

      }

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

        for($k=0; $k<$countseikeiki; $k++){//基準値の呼び出し

          $j = $k + 1;
          ${"product_material_machine_id".$j} = $ProductMaterialMachines[$k]["id"];
          $this->set('product_material_machine_id'.$j, ${"product_material_machine_id".$j});
          ${"cylinder_name".$j} = $ProductMaterialMachines[$k]["cylinder_name"];
          $this->set('cylinder_name'.$j, ${"cylinder_name".$j});

          $ProductConditonChildren = $this->ProductConditonChildren->find()
          ->where(['product_material_machine_id' => ${"product_material_machine_id".$j},
           'cylinder_name' => ${"cylinder_name".$j}, 'delete_flag' => 0])
          ->toArray();

          if(isset($ProductConditonChildren[0])){

            ${"extrude_roatation".$j} = sprintf("%.1f", $ProductConditonChildren[0]["extrude_roatation"]);
            $this->set('extrude_roatation'.$j, ${"extrude_roatation".$j});
            ${"extrusion_load".$j} = sprintf("%.1f", $ProductConditonChildren[0]["extrusion_load"]);
            $this->set('extrusion_load'.$j, ${"extrusion_load".$j});
            ${"product_conditon_child_id".$j} = $ProductConditonChildren[0]["id"];
            $this->set('product_conditon_child_id'.$j, ${"product_conditon_child_id".$j});

            for($n=1; $n<8; $n++){
              ${"temp_".$n.$j} = sprintf("%.0f", $ProductConditonChildren[0]["temp_".$n]);
              $this->set('temp_'.$n.$j, ${"temp_".$n.$j});
            }

            $pickup_speed = sprintf("%.1f", $ProductConditonChildren[0]["pickup_speed"]);
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

        }

      }

      if(isset($data['inspection_data_conditon_parent_id_moto'])){
        $inspection_data_conditon_parent_id = $data['inspection_data_conditon_parent_id_moto'];
      }else{
        $inspection_data_conditon_parent_id = $data['inspection_data_conditon_parent_id'];
      }

      //ここから当日成形条件の表示用
      $InspectionDataConditonParentsall = $this->InspectionDataConditonParents->find()
      ->where(['id' => $inspection_data_conditon_parent_id])
      ->order(["id"=>"DESC"])->toArray();
      $inspection_data_conditon_parent_created = $InspectionDataConditonParentsall[0]["created_at"]->format("Y-m-d H:i:s");
    /*
      echo "<pre>";
      print_r($InspectionDataConditonChildren);
      echo "</pre>";
*/
      $product_code_ini = substr($product_code, 0, 11);
      $InspectionDataConditonParents = $this->InspectionDataConditonParents->find()
      ->where(['product_code like' => $product_code_ini.'%', 'created_at >=' => $inspection_data_conditon_parent_created, 'InspectionDataConditonParents.delete_flag' => 0])
      ->order(["created_at"=>"DESC"])->toArray();

      for($i=1; $i<=count($InspectionDataConditonParents); $i++){//成形条件があるだけ取り出し

        for($k=0; $k<$countseikeiki; $k++){//各成型機の基準値の呼び出し
    
          $cylinder_name = $ProductMaterialMachines[$k]["cylinder_name"];

          //成形機毎に取り出し
          $InspectionDataConditonChildren = $this->InspectionDataConditonChildren->find()
          ->contain(['ProductConditonChildren'])
          ->where(['inspection_data_conditon_parent_id' => $InspectionDataConditonParents[$i-1]['id'],
          'ProductConditonChildren.cylinder_name' => $cylinder_name, 'InspectionDataConditonChildren.delete_flag' => 0])
          ->order(["InspectionDataConditonChildren.id"=>"DESC"])->toArray();
    
            $j = $k + 1;
  
            ${"inspection_extrude_roatation".$j.$i} = sprintf("%.1f", $InspectionDataConditonChildren[0]['inspection_extrude_roatation']);
            $this->set('inspection_extrude_roatation'.$j.$i, ${"inspection_extrude_roatation".$j.$i});
            ${"inspection_extrusion_load".$j.$i} = sprintf("%.1f", $InspectionDataConditonChildren[0]['inspection_extrusion_load']);
            $this->set('inspection_extrusion_load'.$j.$i, ${"inspection_extrusion_load".$j.$i});
            ${"inspection_pickup_speed".$j.$i} = sprintf("%.1f", $InspectionDataConditonChildren[0]['inspection_pickup_speed']);
            $this->set('inspection_pickup_speed'.$j.$i, ${"inspection_pickup_speed".$j.$i});

            for($n=1; $n<8; $n++){
    
              ${"inspection_temp_".$n.$j.$i} = sprintf("%.0f", $InspectionDataConditonChildren[0]['inspection_temp_'.$n]);
              $this->set('inspection_temp_'.$n.$j.$i, ${"inspection_temp_".$n.$j.$i});
  
            }

        }
  
      }

      echo "<pre>";//フォームの再読み込みの防止
      print_r("");
      echo "</pre>";

    }

    public function addfinishform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data = $this->request->query('s');

      $product_code = $Data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $Data["machine_num"];
      $this->set('machine_num', $machine_num);
      $date_sta = $Data["date_sta"];
      $this->set('date_sta', $date_sta);
      $date_fin = $Data["date_fin"];
      $this->set('date_fin', $date_fin);

      $product_code_ini = substr($product_code, 0, 11);
      $InspectionDataResultParentData = $this->InspectionDataResultParents->find()
      ->contain(['InspectionStandardSizeParents', 'ProductConditionParents', 'Products'])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
      'InspectionDataResultParents.datetime >=' => $date_sta, 'InspectionDataResultParents.datetime <' => $date_fin,
      'InspectionStandardSizeParents.delete_flag' => 0,
      'InspectionDataResultParents.delete_flag' => 0])
      ->order(["InspectionDataResultParents.datetime"=>"DESC"])->toArray();

      $arrProducts = array();
      for($j=0; $j<count($InspectionDataResultParentData); $j++){
        $arrProducts[] = $InspectionDataResultParentData[$j]["product"]["length"];
      }

      $arrProducts = array_unique($arrProducts);
      $arrProducts = array_values($arrProducts);
      $this->set('arrProducts', $arrProducts);

    }
    
    public function addfinishconfirm()
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
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);
      $date_sta = $data["date_sta"];
      $this->set('date_sta', $date_sta);
      $date_fin = $data["date_fin"];
      $this->set('date_fin', $date_fin);
      $product_code_ini = substr($product_code, 0, 11);
      $this->set('num', $data["num"]);

      for($j=0; $j<$data["num"]; $j++){

        $InspectionDataResultParentData = $this->InspectionDataResultParents->find()
        ->contain(['InspectionStandardSizeParents', 'ProductConditionParents', 'Products'])
        ->where(['machine_num' => $machine_num, 'Products.length' => $data['length'.$j], 'product_code like' => $product_code_ini.'%', 
        'InspectionDataResultParents.datetime >=' => $date_sta, 'InspectionDataResultParents.datetime <' => $date_fin,
        'InspectionStandardSizeParents.delete_flag' => 0,
        'InspectionDataResultParents.delete_flag' => 0])
        ->order(["InspectionDataResultParents.datetime"=>"DESC"])->toArray();

        $total_weight = 0;
        $total_loss_weight = 0;
        for($k=0; $k<count($InspectionDataResultParentData); $k++){
          $total_weight = $total_weight + $InspectionDataResultParentData[$k]["result_weight"];
          $total_loss_weight = $total_loss_weight + $InspectionDataResultParentData[$k]["loss_amount"];
        }

        ${"total_loss_weight".$j} = $total_loss_weight;
        ${"total_loss_weight".$j} = sprintf("%.1f", ${"total_loss_weight".$j});
        $this->set('total_loss_weight'.$j, ${"total_loss_weight".$j});

        $weight_ave = $total_weight/(count($InspectionDataResultParentData) * 1000);
        ${"sum_weight".$j} = $weight_ave * $data["amount".$j];
        ${"sum_weight".$j} = sprintf("%.1f", ${"sum_weight".$j});
        $this->set('sum_weight'.$j, ${"sum_weight".$j});
/*
        ${"tasseiritsu".$j} = ${"sum_weight".$j} * 100 / (${"sum_weight".$j} + ${"total_loss_weight".$j});
        ${"tasseiritsu".$j} = sprintf("%.1f", ${"tasseiritsu".$j});
        $this->set('tasseiritsu'.$j, ${"tasseiritsu".$j});
  
        ${"lossritsu".$j} = ${"total_loss_weight".$j} * 100 / (${"sum_weight".$j} + ${"total_loss_weight".$j});
        ${"lossritsu".$j} = sprintf("%.1f", ${"lossritsu".$j});
        $this->set('lossritsu'.$j, ${"lossritsu".$j});
*/
        }

        $total_sum_weight = 0;
        $total_loss_weight = 0;
        for($j=0; $j<$data["num"]; $j++){
          $total_sum_weight = $total_sum_weight + ${"sum_weight".$j};
          $total_loss_weight = $total_loss_weight + ${"total_loss_weight".$j};
        }

        $tasseiritsu = $total_sum_weight * 100 / ($total_sum_weight + $total_loss_weight);
        $tasseiritsu = sprintf("%.1f", $tasseiritsu);
        $this->set('tasseiritsu', $tasseiritsu);
        $lossritsu = $total_loss_weight * 100 / ($total_sum_weight + $total_loss_weight);
        $lossritsu = sprintf("%.1f", $lossritsu);
        $this->set('lossritsu', $lossritsu);
  
    }

    public function addfinishdo()//日報の登録
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);
      $date_sta = $data["date_sta"];
      $this->set('date_sta', $date_sta);
      $date_fin = $data["date_fin"];
      $this->set('date_fin', $date_fin);
      $this->set('num', $data["num"]);
      $this->set('lossritsu', $data["lossritsu"]);
      $this->set('tasseiritsu', $data["tasseiritsu"]);

/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $product_code_ini = substr($product_code, 0, 11);
      $InspectionDataResultParentData = $this->InspectionDataResultParents->find()
      ->contain(['InspectionStandardSizeParents', 'ProductConditionParents', 'Products'])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
      'InspectionDataResultParents.datetime >=' => $date_sta, 'InspectionDataResultParents.datetime <' => $date_fin,
      'InspectionStandardSizeParents.delete_flag' => 0,
      'InspectionDataResultParents.delete_flag' => 0])
      ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();

      $tourokuDailyreport = array();
      for($j=0; $j<$data["num"]; $j++){

        $Products = $this->Products->find()
        ->where(['product_code like' => $product_code_ini.'%', 'length' => $data["length".$j], 'delete_flag' => 0])
        ->toArray();

        $tourokuDailyreport[] = [
          'product_id' => $Products[0]["id"],
          'machine_num' => $data["machine_num"],
          'start_datetime' => $InspectionDataResultParentData[0]["datetime"]->format('Y-m-d H:i:s'),
          'finish_datetime' => $InspectionDataResultParentData[count($InspectionDataResultParentData)-1]["datetime"]->format('Y-m-d H:i:s'),
          'amount' => $data["amount".$j],
          'sum_weight' => $data["sum_weight".$j],
          'total_loss_weight' => $data["total_loss_weight".$j],
          'bik' => $data["bik"],
          "delete_flag" => 0,
          'created_at' => date("Y-m-d H:i:s"),
          "created_staff" => $InspectionDataResultParentData[count($InspectionDataResultParentData)-1]["created_staff"]
        ];

        }
/*
        echo "<pre>";
        print_r($tourokuDailyreport);
        echo "</pre>";
  */
      $DailyReports = $this->DailyReports->patchEntities($this->DailyReports->newEntity(), $tourokuDailyreport);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4

        $DailyReportsData = $this->DailyReports->find()
        ->contain(['Products'])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
        'start_datetime >=' => $date_sta, 'start_datetime <' => $date_fin,
        'DailyReports.delete_flag' => 0])->toArray();
  
        for($j=0; $j<count($DailyReportsData); $j++){
          $this->DailyReports->updateAll(
            ['updated_at' => date('Y-m-d H:i:s'),
            'updated_staff' => $InspectionDataResultParentData[count($InspectionDataResultParentData)-1]["created_staff"],
            'delete_flag' => 1],
            ['id' => $DailyReportsData[$j]['id']]);
        }

        if ($this->DailyReports->saveMany($DailyReports)) {

          for($j=0; $j<count($InspectionDataResultParentData); $j++){

            $DailyReportId = $this->DailyReports->find()
            ->where(['product_id' => $InspectionDataResultParentData[$j]["product_id"], 'delete_flag' => 0])
            ->order(["id"=>"DESC"])->toArray();
  
            if(isset($DailyReportId[0]['id'])){
              $this->InspectionDataResultParents->updateAll(
                ['daily_report_id' => $DailyReportId[0]['id'], 'kanryou_flag' => 1],
                ['id' => $InspectionDataResultParentData[$j]['id']]);
            }

          }

          $connection->commit();// コミット5
          $mes = "登録を完了しました。";
          $this->set('mes',$mes);

        } else {

         $mes = "※登録を完了できませんでした";
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

      $session = $this->request->getSession();
      $datasession = $session->read();
      $staff_id = $datasession['Auth']['User']['staff_id'];
      $Staffs = $this->Staffs->find()->where(['id' => $staff_id])->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      $data = $this->request->getData();

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

      if($factory_id == 5){//本部の人がログインしている場合

        $Product_name_list = $this->Products->find()
        ->contain(['Customers'])
        ->where(['Customers.name' => $data["customer_name"], 'Products.status_kensahyou' => 0, 'Products.delete_flag' => 0])
        ->toArray();
 
      }else{

        $Product_name_list = $this->Products->find()
        ->contain(['Customers'])
        ->where(['Customers.name' => $data["customer_name"], 'Products.status_kensahyou' => 0, 'Products.delete_flag' => 0,
        'OR' => [['Products.factory_id' => $factory_id], ['Products.factory_id' => 5]]])
        ->toArray();

      }

       if(count($Product_name_list) < 1){//顧客名にミスがある場合

         $mess = "入力された顧客の製品は登録されていません。確認してください。";
         $this->set('mess',$mess);

         if($factory_id == 5){//本部の人がログインしている場合

          $Product_name_list = $this->Products->find()
          ->where(['status_kensahyou' => 0, 'delete_flag' => 0])
          ->toArray();
  
        }else{
  
          $Product_name_list = $this->Products->find()
          ->where(['status_kensahyou' => 0, 'delete_flag' => 0,
          'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
          ->toArray();
  
        }

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
  
         if($factory_id == 5){//本部の人がログインしている場合

          $Products = $this->Products->find()
          ->where(['name' => $name, 'length' => $length, 'delete_flag' => 0])->toArray();
    
        }else{
  
          $Products = $this->Products->find()
          ->where(['name' => $name, 'length' => $length, 'delete_flag' => 0,
           'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
          ->toArray();
  
        }
  
         if(isset($Products[0])){

           $product_code = $Products[0]["product_code"];

           return $this->redirect(['action' => 'kensakugouki',
           's' => ['product_code' => $product_code]]);

         }else{

           $mess = "入力された製品名は登録されていません。確認してください。";
           $this->set('mess',$mess);

           if($factory_id == 5){//本部の人がログインしている場合

            $Product_name_list = $this->Products->find()
            ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();
       
          }else{
    
            $Product_name_list = $this->Products->find()
            ->where(['status_kensahyou' => 0, 'delete_flag' => 0,
             'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
            ->toArray();
    
          }
  
           $arrProduct_name_list = array();
           for($j=0; $j<count($Product_name_list); $j++){
             array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
           }
           $this->set('arrProduct_name_list', $arrProduct_name_list);

         }

       }else{//product_nameの入力がない

         $mess = "製品名が入力されていません。";
         $this->set('mess',$mess);

         if($factory_id == 5){//本部の人がログインしている場合

          $Product_name_list = $this->Products->find()
          ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();
      
        }else{
  
          $Product_name_list = $this->Products->find()
          ->where(['status_kensahyou' => 0, 'delete_flag' => 0,
           'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
          ->toArray();
  
        }

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }

     }else{//はじめ

      if($factory_id == 5){//本部の人がログインしている場合

        $Product_name_list = $this->Products->find()
        ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();
     
      }else{

        $Product_name_list = $this->Products->find()
        ->where(['status_kensahyou' => 0, 'delete_flag' => 0,
         'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
        ->toArray();

      }

       $arrProduct_name_list = array();
       for($j=0; $j<count($Product_name_list); $j++){
         array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
       }
       $this->set('arrProduct_name_list', $arrProduct_name_list);

     }

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
/*
      echo "<pre>";
      print_r("data");
      print_r($data);
      echo "</pre>";
*/

      if(isset($data["next"])){//「次へ」ボタンを押したとき

        $product_code = $data["product_code"];
        $machine_num = $data["machine_num"];

        return $this->redirect(['action' => 'kensakudate',
        's' => ['product_code' => $product_code, 'machine_num' => $machine_num]]);

      }
   
      $arrGouki = array();
      for($k=0; $k<count($ProductConditionParents); $k++){
        $ProductDatas = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();
        $LinenameDatas = $this->Linenames->find()
        ->where(['delete_flag' => 0, 'factory_id' => $ProductDatas[0]["factory_id"], 'machine_num' => $ProductConditionParents[$k]["machine_num"]])->toArray();

        $array = array($ProductConditionParents[$k]["machine_num"] => $LinenameDatas[0]["name"]);
        $arrGouki = $arrGouki + $array;
      }
      $this->set('arrGouki', $arrGouki);

    }

    public function kensakudate()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();

      if(isset($data['saerch'])){

        $data = $this->request->getData();
 /*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
*/
        $Data = $this->request->query('s');
        $product_code = $data["product_code"];
        $this->set('product_code', $product_code);
        $machine_num = $data["machine_num"];
        $this->set('machine_num', $machine_num);

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();
        $product_name = $Products[0]["name"];
        $this->set('product_name', $product_name);

        $startY = $data['start']['year'];
    		$startM = $data['start']['month'];
    		$startD = $data['start']['day'];
        $startYMD = $startY."-".$startM."-".$startD." 00:00";

        $endY = $data['end']['year'];
    		$endM = $data['end']['month'];
    		$endD = $data['end']['day'] + 1;
        $endYMD = $endY."-".$endM."-".$endD." 00:00";
  
        $product_code_ini = substr($product_code, 0, 11);
        $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()
        ->contain(['InspectionDataResultParents' => ['ProductConditionParents','Products']])//測定データのうち、長さ違いも含め呼出
        ->where(['product_code like' => $product_code_ini.'%', 'machine_num' => $machine_num,
        'InspectionDataResultParents.daily_report_id >' => 0,
         'InspectionDataResultChildren.delete_flag' => 0,
        'datetime >=' => $startYMD, 'datetime <' => $endYMD])
        ->order(["InspectionDataResultParents.datetime"=>"DESC"])->toArray();

        $arrDates = array();

        for($k=0; $k<count($InspectionDataResultChildren); $k++){

          if($InspectionDataResultChildren[$k]['inspection_data_result_parent']['datetime']->format('H') < 6){//前日の測定

            $date1 = strtotime($InspectionDataResultChildren[$k]['inspection_data_result_parent']['datetime']->format('Y-m-d'));
            $datetimeyes = date('Y-m-d', strtotime('-1 day', $date1));
      
            $arrDates[] = $datetimeyes;

          }else{

            $arrDates[] = $InspectionDataResultChildren[$k]['inspection_data_result_parent']['datetime']->format('Y-m-d');

          }

        }

        $arrDates = array_unique($arrDates);
        $arrDates = array_values($arrDates);

        $this->set('arrDates', $arrDates);

        $mes = "検索期間： ".$startY."-".$startM."-".$startD .' ～ '.$endY."-".$endM."-".$endD;
        $this->set('mes', $mes);

        $checksaerch = 1;
        $this->set('checksaerch', $checksaerch);

      }else{

        $Data = $this->request->query('s');
        $product_code = $Data["product_code"];
        $this->set('product_code', $product_code);
        $machine_num = $Data["machine_num"];
        $this->set('machine_num', $machine_num);

        $Products = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();
        $product_name = $Products[0]["name"];
        $this->set('product_name', $product_name);
  
        $startday = date("Y-m-d 00:00:00");
        $product_code_ini = substr($product_code, 0, 11);
        $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()
        ->contain(['InspectionDataResultParents' => ['ProductConditionParents','Products']])//測定データのうち、長さ違いも含め呼出
        ->where(['product_code like' => $product_code_ini.'%', 'machine_num' => $machine_num,
          'InspectionDataResultParents.daily_report_id >' => 0,
          'InspectionDataResultParents.datetime <' => $startday,
         'InspectionDataResultChildren.delete_flag' => 0])
        ->order(["InspectionDataResultParents.datetime"=>"DESC"])->toArray();

        $arrDates = array();

        for($k=0; $k<count($InspectionDataResultChildren); $k++){

          if($InspectionDataResultChildren[$k]['inspection_data_result_parent']['datetime']->format('H') < 6){//前日の測定

            $date1 = strtotime($InspectionDataResultChildren[$k]['inspection_data_result_parent']['datetime']->format('Y-m-d'));
            $datetimeyes = date('Y-m-d', strtotime('-1 day', $date1));
      
            $arrDates[] = $datetimeyes;

          }else{

            $arrDates[] = $InspectionDataResultChildren[$k]['inspection_data_result_parent']['datetime']->format('Y-m-d');

          }

        }

        $arrDates = array_unique($arrDates);
        $arrDates = array_values($arrDates);

        $this->set('arrDates', $arrDates);

        $mes = '＊最新の上位３つの測定データです。';
        $this->set('mes', $mes);

        $checksaerch = 0;
        $this->set('checksaerch', $checksaerch);

      }

      echo "<pre>";//フォームの再読み込みの防止
      print_r("");
      echo "</pre>";

    }

    public function kensakuhyouji()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $datasession = $session->read();
      if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

        $Groups = $this->Groups->find()->contain(["Menus"])
        ->where(['Groups.group_name_id' => $datasession['Auth']['User']['group_name_id'], 'Menus.id' => 40, 'Groups.delete_flag' => 0])
        ->toArray();
 
        if(!isset($Groups[0])){//権限がない場合
          $account_check = 0;
        }else{
          $account_check = 1;
        }
        $this->set('account_check', $account_check);

      }

      $data = $this->request->query('s');

      $arrdata = explode("_",$data);
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $datekensaku = $arrdata[0];
      $datetimesta = $arrdata[0]." 00:00";
			$date1 = strtotime($arrdata[0]);
			$datetimefin = date('Y-m-d', strtotime('+1 day', $date1))." 00:00";
      $this->set('datekensaku', $datekensaku);
      $this->set('datetimesta', $datetimesta);
      $this->set('datetimefin', $datetimefin);

      $machine_num = $arrdata[1];
      $this->set('machine_num', $machine_num);

      if(isset($arrdata[3])){
        $product_code = $arrdata[2]."_".$arrdata[3];
      }else{
        $product_code = $arrdata[2];
      }
      $this->set('product_code', $product_code);

      $product_code_ini = substr($product_code, 0, 11);
      $this->set('product_code_ini', $product_code_ini);

      $DailyReportsData = $this->DailyReports->find()
      ->contain(['Products'])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
      'start_datetime >=' => $datetimesta, 'start_datetime <' => $datetimefin,
      'DailyReports.delete_flag' => 0])->toArray();

      $arrProducts = array();
      for($j=0; $j<count($DailyReportsData); $j++){

        $tasseiritsu = $DailyReportsData[$j]["sum_weight"] * 100 / ($DailyReportsData[$j]["sum_weight"] + $DailyReportsData[$j]["total_loss_weight"]);
        $tasseiritsu = sprintf("%.1f", $tasseiritsu);
        $lossritsu = $DailyReportsData[$j]["total_loss_weight"] * 100 / ($DailyReportsData[$j]["sum_weight"] + $DailyReportsData[$j]["total_loss_weight"]);
        $lossritsu = sprintf("%.1f", $lossritsu);

        $arrProducts[] = [
          "length" => $DailyReportsData[$j]["product"]["length"],
          "amount" => $DailyReportsData[$j]["amount"],
          "sum_weight" => $DailyReportsData[$j]["sum_weight"],
          "total_loss_weight" => $DailyReportsData[$j]["total_loss_weight"],
 //         "lossritsu" => $lossritsu,
  //        "tasseiritsu" => $tasseiritsu,
          "bik" => $DailyReportsData[$j]["bik"],
        ];
      }
      $this->set('arrProducts',$arrProducts);

      $total_sum_weight = 0;
      $total_loss_weight = 0;
      for($j=0; $j<count($arrProducts); $j++){
        $total_sum_weight = $total_sum_weight + $arrProducts[$j]["sum_weight"];
        $total_loss_weight = $total_loss_weight + $arrProducts[$j]["total_loss_weight"];
      }

      $tasseiritsu = $total_sum_weight * 100 / ($total_sum_weight + $total_loss_weight);
      $tasseiritsu = sprintf("%.1f", $tasseiritsu);
      $this->set('tasseiritsu', $tasseiritsu);
      $lossritsu = $total_loss_weight * 100 / ($total_sum_weight + $total_loss_weight);
      $lossritsu = sprintf("%.1f", $lossritsu);
      $this->set('lossritsu', $lossritsu);

/*
      echo "<pre>";
      print_r($arrProducts);
      echo "</pre>";
*/
      $ProductParent = $this->Products->find()
      ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();
      $ProductLength = $this->Products->find()
      ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();
      $Length = $ProductLength[0]['length'];
      $this->set('Length',$Length);

      $ig_bank_modes = $ProductParent[0]['ig_bank_modes'];
      if($ig_bank_modes == 0){
        $mode = "0（X-Y）";
      }else{
        $mode = "0（Y-Y）";
      }
      $this->set('mode',$mode);

      $htmlproductcheck = new htmlproductcheck();//クラスを使用
      $arrayproductdate = $htmlproductcheck->productcheckprogram($product_code);//クラスを使用

      $product_code_datetime = $product_code
      ."_".substr($DailyReportsData[0]["finish_datetime"], 0, 10)
      ."_".substr($DailyReportsData[0]["finish_datetime"], 11, 5);
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheaderkensaku($product_code_datetime);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $product_code_ini = substr($product_code, 0, 11);
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code like' => $product_code_ini.'%',
       'InspectionStandardSizeParents.created_at <=' => $DailyReportsData[0]["finish_datetime"]->format("Y-m-d H:i:s")])
      ->order(["InspectionStandardSizeParents.created_at"=>"DESC"])->toArray();
  
      $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
      ->where(['inspection_standard_size_parent_id' => $inspectionStandardSizeParents[0]["id"]])->toArray();
/*
      echo "<pre>";
      print_r($InspectionStandardSizeChildren);
      echo "</pre>";
*/
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
          ${"input_type".$i} = "";
          $this->set('input_type'.$i,${"input_type".$i});

        }

        for($i=0; $i<count($InspectionStandardSizeChildren); $i++){

          $num = $InspectionStandardSizeChildren[$i]["size_number"];
          ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
          ${"input_type".$num} = $InspectionStandardSizeChildren[$i]["input_type"];
          $this->set('input_type'.$num,${"input_type".$num});

          if(${"size_name".$num} === "長さ"){

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = "-";
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = "-";
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = "-";
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = "※製品規格参照";
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
    
          }elseif($InspectionStandardSizeChildren[$i]["input_type"] === "judge"){

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
  
          }else{

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["upper_limit"]);
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["lower_limit"]);
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["size"]);
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
  
          }

        }
        $this->set('InspectionStandardSizeChildren', $InspectionStandardSizeChildren);

      }

      $product_code_ini = substr($product_code, 0, 11);
      $InspectionDataResultParents = $this->InspectionDataResultParents->find()
      ->contain(['InspectionStandardSizeParents', 'ProductConditionParents', 'Products'])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
      'InspectionStandardSizeParents.delete_flag' => 0,
      'InspectionDataResultParents.delete_flag' => 0,
      'datetime >=' => $datetimesta, 'datetime <=' => $datetimefin])
      ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();

      $gyou = count($InspectionDataResultParents);
      $this->set('gyou', $gyou);

      $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()
      ->contain(['InspectionDataResultParents' => ['Products']])//測定データのうち、管理ナンバーが一致するものを検索
      ->where(['product_code like' => $product_code_ini.'%', 'InspectionDataResultChildren.delete_flag' => 0,
      'datetime >=' => $datetimesta, 'datetime <=' => $datetimefin])
      ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();

      $total_amount = "";
      $bik = "";
      $this->set('total_amount', $total_amount);
      $this->set('bik', $bik);

      for($j=0; $j<count($InspectionDataResultParents); $j++){

        if($InspectionDataResultParents[$j]["kanryou_flag"] == 1){
          $total_amount = $InspectionDataResultParents[$j]["total_amount"];
          $bik = $InspectionDataResultParents[$j]["bik"];
          $this->set('total_amount', $total_amount);
          $this->set('bik', $bik);
        }

        $n = $j + 1;

        $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()
        ->contain(['InspectionStandardSizeChildren'])
        ->where(['inspection_data_result_parent_id' => $InspectionDataResultParents[$j]["id"],
         'InspectionDataResultChildren.delete_flag' => 0])
        ->toArray();

        ${"length".$n} = $InspectionDataResultParents[$j]["product"]['length'];
        $this->set('length'.$n,${"length".$n});

        ${"lot_number".$n} = $InspectionDataResultParents[$j]['lot_number'];
        $this->set('lot_number'.$n,${"lot_number".$n});
        ${"datetime".$n} = $InspectionDataResultParents[$j]['datetime']->format('Y-n-j G:i');
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

        for($i=1; $i<=11; $i++){

          ${"result_size".$n.$i} = "";
          $this->set("result_size".$n."_".$i,${"result_size".$n.$i});

        }

        for($i=0; $i<count($InspectionDataResultChildren); $i++){

          $size_number = $InspectionDataResultChildren[$i]['inspection_standard_size_child']['size_number'];

          ${"result_size".$n.$size_number} = sprintf("%.1f", $InspectionDataResultChildren[$i]["result_size"]);
          $this->set("result_size".$n."_".$size_number,${"result_size".$n.$size_number});

        }

      }

      $machine_datetime_product = $machine_num."_".$datekensaku."_00:00:00_".$product_code;

      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheaderkensaku($machine_datetime_product);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

      $machine_product_datetime = $machine_num."_".$product_code."_".$datetimefin;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();//クラスを使用
      $htmlseikeijouken = $htmlkensahyougenryouheader->seikeijouken($machine_product_datetime);//クラスを使用
      $this->set('htmlseikeijouken', $htmlseikeijouken);

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
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $this->set('loss_flag', 0);

      if(isset($data["kakuninn"])){

        if(!isset($_SESSION)){
          session_start();
          }
          $session = $this->request->getSession();
  
          $_SESSION['editformdata'] = array();
          $_SESSION['editformdata']["check"] = 1;
          $_SESSION['editformdata']["data"] = $data;

          return $this->redirect(['action' => 'editcomfirm']);

      }elseif(isset($data["loss"])){
        $this->set('loss_flag', 1);

        $product_code = $data["product_code"];
        $this->set('product_code', $product_code);
        $machine_num = $data["machine_num"];
        $this->set('machine_num', $machine_num);
        $staff_id = $data["staff_id"];
        $this->set('staff_id', $staff_id);
        $user_code = $data["user_code"];
        $this->set('user_code', $user_code);
        $datekensaku = $data["datekensaku"];
        $this->set('datekensaku', $datekensaku);
        $datetimesta = $data["datetimesta"];
        $this->set('datetimesta', $datetimesta);
        $datetimefin = $data["datetimefin"];
        $this->set('datetimefin', $datetimefin);
        $gyoumax = $data["gyoumax"];
        $this->set('gyoumax', $gyoumax);
        $gyou = $data["gyoumax"];
        $this->set('gyou', $gyou);
        
        for($j=1; $j<=$gyoumax; $j++){

          ${"inspection_data_conditon_parent_id".$j} = $data['inspection_data_conditon_parent_id'.$j];
          $this->set('inspection_data_conditon_parent_id'.$j,${"inspection_data_conditon_parent_id".$j});
          ${"inspection_standard_size_parent_id".$j} = $data['inspection_standard_size_parent_id'.$j];
          $this->set('inspection_standard_size_parent_id'.$j,${"inspection_standard_size_parent_id".$j});
          ${"product_condition_parent_id".$j} = $data['product_condition_parent_id'.$j];
          $this->set('product_condition_parent_id'.$j,${"product_condition_parent_id".$j});
          ${"lot_number".$j} = $data['lot_number'.$j];
          $this->set('lot_number'.$j,${"lot_number".$j});

          ${"datetime".$j} = $datekensaku." ".$data['datetime'.$j]["hour"].":".$data['datetime'.$j]["minute"].":00";
          $this->set('datetime'.$j,${"datetime".$j});
          ${"product_id".$j} = $data['product_id'.$j];
          $this->set('product_id'.$j,${"product_id".$j});
          ${"length".$j} = $data['length'.$j];
          $this->set('length'.$j,${"length".$j});
          ${"user_code".$j} = $data['user_code'.$j];
          $this->set('user_code'.$j,${"user_code".$j});
  
          for($i=1; $i<=11; $i++){

            ${"result_size".$j."_".$i} = $data["result_size".$j."_".$i];
            $this->set("result_size".$j."_".$i,${"result_size".$j."_".$i});
  
          }
  
          ${"appearance".$j} = $data['appearance'.$j];
          $this->set('appearance'.$j,${"appearance".$j});
          ${"result_weight".$j} = $data['result_weight'.$j];
          $this->set('result_weight'.$j,${"result_weight".$j});
          ${"delete_sokutei".$j} = $data['delete_sokutei'.$j];
          $this->set('delete_sokutei'.$j,${"delete_sokutei".$j});
          ${"lossflag".$j} = $data['lossflag'.$j];
          $this->set('lossflag'.$j,${"lossflag".$j});

        }

        $countlength = $data["countlength"];
        $this->set('countlength', $countlength);

        for($j=0; $j<$countlength; $j++){

          ${"amount".$j} = $data['amount'.$j];
          $this->set('amount'.$j,${"amount".$j});

        }

        $bik = $data["bik"];
        $this->set('bik', $bik);

        $ijou_nums = array_keys($data, '異');
        $check_num = $ijou_nums[0];
        $this->set('check_num', $check_num);

        $product_code_ini = substr($product_code, 0, 11);
        $InspectionDataResultParents = $this->InspectionDataResultParents->find()
        ->contain(['ProductConditionParents', 'Products'])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
         'InspectionDataResultParents.delete_flag' => 0,
         'lot_number' => $data['lot_number'.$check_num], 'datetime >=' => $datetimesta, 'datetime <' => $datetimefin])
        ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();

        $loss_amount_moto = $InspectionDataResultParents[0]["loss_amount"];
        $this->set('loss_amount_moto', $loss_amount_moto);
        $bik_moto = $InspectionDataResultParents[0]["bik"];
        $this->set('bik_moto', $bik_moto);
        
      }elseif(isset($data["losstouroku"])){

        $product_code = $data["product_code"];
        $this->set('product_code', $product_code);

        $machine_num = $data["machine_num"];
        $this->set('machine_num', $machine_num);
        $staff_id = $data["staff_id"];
        $this->set('staff_id', $staff_id);
        $user_code = $data["user_code"];
        $this->set('user_code', $user_code);
        $datekensaku = $data["datekensaku"];
        $this->set('datekensaku', $datekensaku);
        $datetimesta = $data["datetimesta"];
        $this->set('datetimesta', $datetimesta);
        $datetimefin = $data["datetimefin"];
        $this->set('datetimefin', $datetimefin);
        $gyoumax = $data["gyoumax"];
        $this->set('gyoumax', $gyoumax);
        $gyou = $data["gyoumax"];
        $this->set('gyou', $gyou);
        $product_code_ini = substr($product_code, 0, 11);

        $DailyReportsData = $this->DailyReports->find()
        ->contain(['Products'])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
        'start_datetime >=' => $datetimesta, 'start_datetime <' => $datetimefin,
        'DailyReports.delete_flag' => 0])->toArray();
  
        $product_code_datetime = $product_code
        ."_".substr($DailyReportsData[0]["finish_datetime"], 0, 10)
        ."_".substr($DailyReportsData[0]["finish_datetime"], 11, 5);
        $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
        $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheaderkensaku($product_code_datetime);
        $this->set('htmlkensahyouheader',$htmlkensahyouheader);

        $ProductParent = $this->Products->find()
        ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();
        $product_length = $ProductParent[0]["length"];
        $this->set('product_length', $product_length);
        $product_id = $ProductParent[0]["id"];
        $this->set('product_id', $product_id);
  
        $ig_bank_modes = $ProductParent[0]['ig_bank_modes'];
        if($ig_bank_modes == 0){
          $mode = "0（X-Y）";
        }else{
          $mode = "0（Y-Y）";
        }
        $this->set('mode',$mode);
  
        $machine_datetime_product = $machine_num."_".$datekensaku."_00:00:00_".$product_code;

        $htmlkensahyougenryouheader = new htmlkensahyouprogram();
        $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheaderkensaku($machine_datetime_product);
        $this->set('htmlgenryouheader',$htmlgenryouheader);
  
        $machine_product_datetime = $machine_num."_".$product_code."_".$datetimefin;
        $htmlkensahyougenryouheader = new htmlkensahyouprogram();//クラスを使用
        $htmlseikeijouken = $htmlkensahyougenryouheader->seikeijouken($machine_product_datetime);//クラスを使用
        $this->set('htmlseikeijouken', $htmlseikeijouken);

        for($j=1; $j<=$gyoumax; $j++){

          ${"inspection_data_conditon_parent_id".$j} = $data['inspection_data_conditon_parent_id'.$j];
          $this->set('inspection_data_conditon_parent_id'.$j,${"inspection_data_conditon_parent_id".$j});
          ${"inspection_standard_size_parent_id".$j} = $data['inspection_standard_size_parent_id'.$j];
          $this->set('inspection_standard_size_parent_id'.$j,${"inspection_standard_size_parent_id".$j});
          ${"product_condition_parent_id".$j} = $data['product_condition_parent_id'.$j];
          $this->set('product_condition_parent_id'.$j,${"product_condition_parent_id".$j});
          ${"lot_number".$j} = $data['lot_number'.$j];
          $this->set('lot_number'.$j,${"lot_number".$j});
/*
          ${"datetime".$j} = [
            "hour" => $data['datetime'.$j]["hour"],
            "minute" => $data['datetime'.$j]["minute"],
          ];
*/
          ${"datetime".$j} = $data['datetime'.$j];
          $this->set('datetime'.$j,${"datetime".$j});
          ${"product_id".$j} = $data['product_id'.$j];
          $this->set('product_id'.$j,${"product_id".$j});
          ${"length".$j} = $data['length'.$j];
          $this->set('length'.$j,${"length".$j});
          ${"user_code".$j} = $data['user_code'.$j];
          $this->set('user_code'.$j,${"user_code".$j});
    
          for($i=1; $i<=11; $i++){

            ${"result_size".$j."_".$i} = $data["result_size".$j."_".$i];
            $this->set("result_size".$j."_".$i,${"result_size".$j."_".$i});
  
          }
  
          ${"appearance".$j} = $data['appearance'.$j];
          $this->set('appearance'.$j,${"appearance".$j});
          ${"result_weight".$j} = $data['result_weight'.$j];
          $this->set('result_weight'.$j,${"result_weight".$j});
          ${"delete_sokutei".$j} = $data['delete_sokutei'.$j];
          $this->set('delete_sokutei'.$j,${"delete_sokutei".$j});

          if($j == $data["loss_touroku_num"]){
            ${"lossflag".$j} = 1;
          }else{
            ${"lossflag".$j} = $data['lossflag'.$j];
          }
          $this->set('lossflag'.$j,${"lossflag".$j});

        }

        $countlength = $data["countlength"];
        $this->set('countlength', $countlength);

        for($j=0; $j<$countlength; $j++){

          ${"amount".$j} = $data['amount'.$j];
          $this->set('amount'.$j,${"amount".$j});

        }

        $bik = $data["bik"];
        $this->set('bik', $bik);

        $product_code_ini = substr($product_code, 0, 11);
        $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%',
         'InspectionStandardSizeParents.created_at <=' => $DailyReportsData[0]["finish_datetime"]->format("Y-m-d H:i:s")])
        ->order(["InspectionStandardSizeParents.created_at"=>"DESC"])->toArray();
    
        $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
        ->where(['inspection_standard_size_parent_id' => $inspectionStandardSizeParents[0]["id"]])->toArray();

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
  
          for($i=0; $i<count($InspectionStandardSizeChildren); $i++){
  
            $num = $InspectionStandardSizeChildren[$i]["size_number"];
            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            ${"input_type".$num} = $InspectionStandardSizeChildren[$i]["input_type"];
            $this->set('input_type'.$num,${"input_type".$num});
  
            if(${"size_name".$num} === "長さ"){
  
              ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
              $this->set('size_name'.$num,${"size_name".$num});
              ${"upper_limit".$num} = "-";
              $this->set('upper_limit'.$num,${"upper_limit".$num});
              ${"lower_limit".$num} = "-";
              $this->set('lower_limit'.$num,${"lower_limit".$num});
              ${"size".$num} = "-";
              $this->set('size'.$num,${"size".$num});
              ${"measuring_instrument".$num} = "※製品規格参照";
              $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
      
            }elseif($InspectionStandardSizeChildren[$i]["input_type"] === "judge"){
  
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
    
            }else{
  
              ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
              $this->set('size_name'.$num,${"size_name".$num});
              ${"upper_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["upper_limit"]);
              $this->set('upper_limit'.$num,${"upper_limit".$num});
              ${"lower_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["lower_limit"]);
              $this->set('lower_limit'.$num,${"lower_limit".$num});
              ${"size".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["size"]);
              $this->set('size'.$num,${"size".$num});
              ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
              $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
    
            }
      
          }
  
          $this->set('InspectionStandardSizeChildren', $InspectionStandardSizeChildren);
  
        }
        
        $arrGaikan = ["0" => "〇", "1" => "✕"];
        $this->set('arrGaikan', $arrGaikan);
  
        $arrGouhi = ["0" => "合", "1" => "否"];
        $this->set('arrGouhi', $arrGouhi);
  
        $arrJudge = [
          "0" => "〇",
          "1" => "✕"
        ];
        $this->set('arrJudge', $arrJudge);
  
        $DailyReportsData = $this->DailyReports->find()
        ->contain(['Products'])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
        'start_datetime >=' => $datetimesta, 'start_datetime <' => $datetimefin,
        'DailyReports.delete_flag' => 0])->toArray();
  
        $arrProducts = array();
        for($j=0; $j<count($DailyReportsData); $j++){
          $arrProducts[] = [
            "length" => $DailyReportsData[$j]["product"]["length"],
            "amount" => ${"amount".$j},
            "sum_weight" => $DailyReportsData[$j]["sum_weight"],
            "total_loss_weight" => $DailyReportsData[$j]["total_loss_weight"],
            "bik" => $bik,
          ];
        }
        $this->set('arrProducts',$arrProducts);
  
        $mess = "";
        $this->set('mess',$mess);

        //ロスの更新
        $InspectionDataResultParentId = $this->InspectionDataResultParents->find()
        ->contain(['ProductConditionParents', 'Products'])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
         'InspectionDataResultParents.delete_flag' => 0,
         'lot_number' => $data['lot_number'.$data["loss_touroku_num"]], 'datetime >=' => $datetimesta, 'datetime <' => $datetimefin])
        ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();

        $session = $this->request->getSession();
        $datasession = $session->read();
        $Users= $this->Users->find('all')->contain(["Staffs"])->where(['user_code' => $datasession['Auth']['User']['user_code'], 'Users.delete_flag' => 0])->toArray();
  
        $connection = ConnectionManager::get('default');//トランザクション1
        // トランザクション開始2
        $connection->begin();//トランザクション3
        try {//トランザクション4

          if($this->InspectionDataResultParents->updateAll(
            ['loss_amount' => $data["loss_amount"],
            'bik' => $data["loss_bik"],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_staff' => $Users[0]["staff_id"]],
          ['id' => $InspectionDataResultParentId[0]["id"]])){

            $mes = "";
            $this->set('mes',$mes);
            $connection->commit();// コミット5

          } else {

            $mes = "※登録されませんでした。管理者に報告してください。";
            $this->set('mes',$mes);
            $this->Flash->error(__('The data could not be saved. Please, try again.'));
            throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

          }

        } catch (Exception $e) {//トランザクション7
        //ロールバック8
          $connection->rollback();//トランザクション9
        }//トランザクション10

      }elseif(isset($data["hensyuu"])){//最初に来た時

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

      $datasession = $session->read();
      $Users= $this->Users->find('all')->contain(["Staffs"])->where(['user_code' => $datasession['Auth']['User']['user_code'], 'Users.delete_flag' => 0])->toArray();
      $user_code = $datasession['Auth']['User']['user_code'];
      $this->set('user_code', $user_code);
      $staff_id = $Users[0]["staff_id"];
      $this->set('staff_id', $staff_id);
      $staff_name= $Users[0]["staff"]["name"];
      $this->set('staff_name', $staff_name);

      $arrGaikan = ["0" => "〇", "1" => "✕"];
      $this->set('arrGaikan', $arrGaikan);

      $arrGouhi = ["0" => "合", "1" => "否"];
      $this->set('arrGouhi', $arrGouhi);

      $arrJudge = [
        "0" => "〇",
        "1" => "✕"
      ];
      $this->set('arrJudge', $arrJudge);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);
      
      $datekensaku = $data["datekensaku"];
      $datetimesta = $data["datetimesta"];
      $datetimefin = $data["datetimefin"];
      $this->set('datekensaku', $datekensaku);
      $this->set('datetimesta', $datetimesta);
      $this->set('datetimefin', $datetimefin);

      $product_code_ini = substr($product_code, 0, 11);
      $DailyReportsData = $this->DailyReports->find()
      ->contain(['Products'])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
      'start_datetime >=' => $datetimesta, 'start_datetime <' => $datetimefin,
      'DailyReports.delete_flag' => 0])->toArray();

      $arrProducts = array();
      for($j=0; $j<count($DailyReportsData); $j++){
        $arrProducts[] = [
          "length" => $DailyReportsData[$j]["product"]["length"],
          "amount" => $DailyReportsData[$j]["amount"],
          "sum_weight" => $DailyReportsData[$j]["sum_weight"],
          "total_loss_weight" => $DailyReportsData[$j]["total_loss_weight"],
          "bik" => $DailyReportsData[$j]["bik"],
        ];
      }
      $this->set('arrProducts',$arrProducts);

      $ProductParent = $this->Products->find()
      ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();
      $product_length = $ProductParent[0]["length"];
      $this->set('product_length', $product_length);
      $product_id = $ProductParent[0]["id"];
      $this->set('product_id', $product_id);

      $ig_bank_modes = $ProductParent[0]['ig_bank_modes'];
      if($ig_bank_modes == 0){
        $mode = "0（X-Y）";
      }else{
        $mode = "0（Y-Y）";
      }
      $this->set('mode',$mode);

      $DailyReportsData = $this->DailyReports->find()
      ->contain(['Products'])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
      'start_datetime >=' => $datetimesta, 'start_datetime <' => $datetimefin,
      'DailyReports.delete_flag' => 0])->toArray();

      $product_code_datetime = $product_code
      ."_".substr($DailyReportsData[0]["finish_datetime"], 0, 10)
      ."_".substr($DailyReportsData[0]["finish_datetime"], 11, 5);
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheaderkensaku($product_code_datetime);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $product_code_ini = substr($product_code, 0, 11);
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code like' => $product_code_ini.'%',
      'InspectionStandardSizeParents.created_at <=' => $DailyReportsData[0]["finish_datetime"]->format("Y-m-d H:i:s")])
      ->order(["InspectionStandardSizeParents.created_at"=>"DESC"])->toArray();

      $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
      ->where(['inspection_standard_size_parent_id' => $inspectionStandardSizeParents[0]["id"]])->toArray();

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

        for($i=0; $i<count($InspectionStandardSizeChildren); $i++){

          $num = $InspectionStandardSizeChildren[$i]["size_number"];
          ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
          ${"input_type".$num} = $InspectionStandardSizeChildren[$i]["input_type"];
          $this->set('input_type'.$num,${"input_type".$num});

          if(${"size_name".$num} === "長さ"){

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = "-";
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = "-";
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = "-";
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = "※製品規格参照";
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
    
          }elseif($InspectionStandardSizeChildren[$i]["input_type"] === "judge"){

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
  
          }else{

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["upper_limit"]);
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["lower_limit"]);
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["size"]);
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
  
          }
    
        }

        $this->set('InspectionStandardSizeChildren', $InspectionStandardSizeChildren);

      }

      $InspectionDataResultParents = $this->InspectionDataResultParents->find()
      ->contain(['InspectionStandardSizeParents', 'ProductConditionParents', 'Products'])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.delete_flag' => 0,
      'InspectionDataResultParents.delete_flag' => 0,
      'datetime >=' => $datetimesta, 'datetime <=' => $datetimefin])
      ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();

      $gyou = count($InspectionDataResultParents);
      $this->set('gyou', $gyou);

      $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()
      ->contain(['InspectionDataResultParents' => ['Products']])//測定データのうち、管理ナンバーが一致するものを検索
      ->where(['product_code like' => $product_code_ini.'%', 'InspectionDataResultChildren.delete_flag' => 0,
      'datetime >=' => $datetimesta, 'datetime <=' => $datetimefin])
      ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();

      $total_amount = "";
      $bik = "";
      $this->set('total_amount', $total_amount);
      $this->set('bik', $bik);

      for($j=0; $j<count($InspectionDataResultParents); $j++){

        $n = $j + 1;

        $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()
        ->contain(['InspectionStandardSizeChildren'])
        ->where(['inspection_data_result_parent_id' => $InspectionDataResultParents[$j]["id"],
         'InspectionDataResultChildren.delete_flag' => 0])
        ->toArray();

        ${"inspection_data_conditon_parent_id".$n} = $InspectionDataResultParents[$j]['inspection_data_conditon_parent_id'];
        $this->set('inspection_data_conditon_parent_id'.$n,${"inspection_data_conditon_parent_id".$n});
        ${"inspection_standard_size_parent_id".$n} = $InspectionDataResultParents[$j]['inspection_standard_size_parent_id'];
        $this->set('inspection_standard_size_parent_id'.$n,${"inspection_standard_size_parent_id".$n});
        ${"product_condition_parent_id".$n} = $InspectionDataResultParents[$j]['product_condition_parent_id'];
        $this->set('product_condition_parent_id'.$n,${"product_condition_parent_id".$n});

        ${"product_id".$n} = $InspectionDataResultParents[$j]["product"]['id'];
        $this->set('product_id'.$n,${"product_id".$n});

        ${"length".$n} = $InspectionDataResultParents[$j]["product"]['length'];
        $this->set('length'.$n,${"length".$n});

        $loss_amount = $InspectionDataResultParents[$j]["loss_amount"];
        if($loss_amount > 0){
          ${"lossflag".$n} = 1;
        }else{
          ${"lossflag".$n} = 0;
        }
        $this->set('lossflag'.$n, ${"lossflag".$n});

        ${"lot_number".$n} = $InspectionDataResultParents[$j]['lot_number'];
        $this->set('lot_number'.$n,${"lot_number".$n});
        ${"datetime".$n} = $InspectionDataResultParents[$j]['datetime']->format('Y-n-j G:i');
        $this->set('datetime'.$n,${"datetime".$n});

        $Staffs = $this->Staffs->find()
        ->where(['id' => $InspectionDataResultParents[$j]['staff_id']])->order(["id"=>"ASC"])->toArray();
        ${"staff_hyouji".$n} = $Staffs[0]['name'];
        $this->set('staff_hyouji'.$n,${"staff_hyouji".$n});

        $Users = $this->Users->find()->where(['id' => $InspectionDataResultParents[$j]['staff_id']])->toArray();
        ${"user_code".$n} = $Users[0]["user_code"];
        $this->set('user_code'.$n,${"user_code".$n});

        ${"appearance".$n} = $InspectionDataResultParents[$j]['appearance'];
        $this->set('appearance'.$n,${"appearance".$n});
        ${"result_weight".$n} = $InspectionDataResultParents[$j]['result_weight'];
        $this->set('result_weight'.$n,${"result_weight".$n});
        ${"judge".$n} = $InspectionDataResultParents[$j]['judge'];
        $this->set('judge'.$n,${"judge".$n});

        for($i=1; $i<=11; $i++){

          ${"result_size".$n.$i} = "";
          $this->set("result_size".$n."_".$i,${"result_size".$n.$i});

        }

        for($i=0; $i<count($InspectionDataResultChildren); $i++){

          $size_number = $InspectionDataResultChildren[$i]['inspection_standard_size_child']['size_number'];

          ${"result_size".$n.$size_number} = $InspectionDataResultChildren[$i]["result_size"];
          $this->set("result_size".$n."_".$size_number,${"result_size".$n.$size_number});

        }

      }

      $machine_datetime_product = $machine_num."_".$datekensaku."_00:00:00_".$product_code;

      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheaderkensaku($machine_datetime_product);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

      $machine_product_datetime = $machine_num."_".$product_code."_".$datetimefin;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();//クラスを使用
      $htmlseikeijouken = $htmlkensahyougenryouheader->seikeijouken($machine_product_datetime);//クラスを使用
      $this->set('htmlseikeijouken', $htmlseikeijouken);

    }

      echo "<pre>";//フォームの再読み込みの防止
      print_r("");
      echo "</pre>";

    }

    public function editcomfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      if(!isset($_SESSION)){
        session_start();
        }
        $session = $this->request->getSession();
        $sessiondata = $session->read();

        $data = $_SESSION['editformdata']["data"];
  //      $_SESSION['editformdata'] = array();

     // $data = $this->request->getData();
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $staff_id = $data["staff_id"];
      $this->set('staff_id', $staff_id);
      $user_code = $data["user_code"];
      $this->set('user_code', $user_code);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);
      $datetimesta = $data["datetimesta"];
      $datetimefin = $data["datetimefin"];
      $this->set('datetimesta', $datetimesta);
      $this->set('datetimefin', $datetimefin);
      $gyoumax = $data["gyoumax"];
      $this->set('gyoumax', $gyoumax);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $product_code_ini = substr($product_code, 0, 11);

      $ProductParent = $this->Products->find()
      ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();

      $ig_bank_modes = $ProductParent[0]['ig_bank_modes'];
      if($ig_bank_modes == 0){
        $mode = "0（X-Y）";
      }else{
        $mode = "0（Y-Y）";
      }
      $this->set('mode',$mode);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $datekensaku = $data["datekensaku"];
      $this->set('datekensaku', $datekensaku);

      $ProductParent = $this->Products->find()
      ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();

      $htmlproductcheck = new htmlproductcheck();//クラスを使用
      $arrayproductdate = $htmlproductcheck->productcheckprogram($product_code);//クラスを使用

      $DailyReportsData = $this->DailyReports->find()
      ->contain(['Products'])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
      'start_datetime >=' => $datetimesta, 'start_datetime <' => $datetimefin,
      'DailyReports.delete_flag' => 0])->toArray();

      $product_code_datetime = $product_code
      ."_".substr($DailyReportsData[0]["finish_datetime"], 0, 10)
      ."_".substr($DailyReportsData[0]["finish_datetime"], 11, 5);
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheaderkensaku($product_code_datetime);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $product_code_ini = substr($product_code, 0, 11);
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code like' => $product_code_ini.'%',
       'InspectionStandardSizeParents.created_at <=' => $DailyReportsData[0]["finish_datetime"]->format("Y-m-d H:i:s")])
      ->order(["InspectionStandardSizeParents.created_at"=>"DESC"])->toArray();
  
      $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
      ->where(['inspection_standard_size_parent_id' => $inspectionStandardSizeParents[0]["id"]])->toArray();

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

        for($i=0; $i<count($InspectionStandardSizeChildren); $i++){

          $num = $InspectionStandardSizeChildren[$i]["size_number"];
          ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
          ${"input_type".$num} = $InspectionStandardSizeChildren[$i]["input_type"];
          $this->set('input_type'.$num,${"input_type".$num});

          if(${"size_name".$num} === "長さ"){

            $num_length = $num - 1;
            $this->set('num_length',$num_length);
      
            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = "-";
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = "-";
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = "-";
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = "※製品規格参照";
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
    
          }elseif($InspectionStandardSizeChildren[$i]["input_type"] === "judge"){

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
  
          }else{

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["upper_limit"]);
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["lower_limit"]);
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["size"]);
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
  
          }
    
        }
        $this->set('InspectionStandardSizeChildren', $InspectionStandardSizeChildren);

      }


      $gyoumax = $data["gyoumax"];
      $this->set('gyou', 0);
      $m = 0;
      for($j=0; $j<$gyoumax; $j++){

        $gouhi_check = 0;
        $n = $j + 1;

        if($data["delete_sokutei".$n] == 0){

          $m = $m + 1;
          $this->set('gyou', $m);

          ${"datetime".$n} = $data['datetime'.$n]['hour'].":".$data['datetime'.$n]['minute'];
          $this->set('datetime'.$m,${"datetime".$n});

          ${"product_id".$n} = $data['product_id'.$n];
          $Products = $this->Products->find()
          ->where(['id' => ${"product_id".$n}])->toArray();
          ${"lengthhyouji".$n} = $Products[0]["length"];
          $this->set('product_id'.$m,${"product_id".$n});
          $this->set('lengthhyouji'.$m,${"lengthhyouji".$n});

          ${"user_code".$n} = $data['user_code'.$n];
          $Users = $this->Users->find()->contain(["Staffs"])->where(['user_code' => ${"user_code".$n}, 'Users.delete_flag' => 0])->toArray();
          if(!isset($Users[0])){
            $mess = $n."行目に入力されたユーザーIDは登録されていません。もう一度やり直してください。";

            if(!isset($_SESSION)){
              session_start();
            }

            $_SESSION['editsokuteidata'] = array();
            $_SESSION['editsokuteidata'] = $data;

            return $this->redirect(['action' => 'editform',
            's' => ['mess' => $mess]]);

          }else{

            $this->set('user_code'.$m,${"user_code".$n});

            $Staffs = $this->Staffs->find()
            ->where(['id' => $Users[0]['staff_id'], 'delete_flag' => 0])->order(["id"=>"ASC"])->toArray();
            ${"staff_hyouji".$m} = $Staffs[0]['name'];
            $this->set('staff_hyouji'.$m,${"staff_hyouji".$m});
    
          }

          ${"inspection_data_conditon_parent_id".$n} = $data['inspection_data_conditon_parent_id'.$m];
          $this->set('inspection_data_conditon_parent_id'.$m,${"inspection_data_conditon_parent_id".$n});
          ${"inspection_standard_size_parent_id".$n} = $data['inspection_standard_size_parent_id'.$m];
          $this->set('inspection_standard_size_parent_id'.$m,${"inspection_standard_size_parent_id".$n});
          ${"product_condition_parent_id".$n} = $data['product_condition_parent_id'.$m];
          $this->set('product_condition_parent_id'.$m,${"product_condition_parent_id".$n});
  
          //ロットナンバーは変えない
          ${"lot_number".$m} = $data['lot_number'.$n];
          $this->set('lot_number'.$m,${"lot_number".$m});

          ${"appearance".$n} = $data['appearance'.$n];
          $this->set('appearance'.$m,${"appearance".$n});
          ${"result_weight".$n} = $data['result_weight'.$n];
          $this->set('result_weight'.$m,${"result_weight".$n});
         
          for($i=1; $i<=11; $i++){

            if(strlen($data["result_size".$n."_".$i]) > 0){
              ${"result_size".$n.$i} = $data["result_size".$n."_".$i];

              $dotini = substr(${"result_size".$n.$i}, 0, 1);
              $dotend = substr(${"result_size".$n.$i}, -1, 1);

              if($dotini == "."){
                ${"result_size".$n.$i} = "0".${"result_size".$n.$i};
              }elseif($dotend == "."){
                ${"result_size".$n.$i} = ${"result_size".$n.$i}."0";
              }
              ${"result_size".$n.$i} = sprintf("%.1f", ${"result_size".$n.$i});

            }else{
              ${"result_size".$n.$i} = "";
            }
            $this->set("result_size".$m."_".$i,${"result_size".$n.$i});

            $Productlengthcheck = $this->Products->find()
            ->where(['product_code like' => $product_code_ini.'%'
            , 'status_kensahyou' => 0, 'status_length' => 0, 'delete_flag' => 0])->order(["id"=>"ASC"])->toArray();
            if($i == $num_length + 1 && count($Productlengthcheck) > 0){//長さ列の場合

              ${"size".$i} = $Products[0]["length_cut"];
              ${"upper_limit".$i} = $Products[0]["length_upper_limit"];
              ${"lower_limit".$i} = $Products[0]["length_lower_limit"];
        
            }
    
            if(${"input_type".$i} !== "judge" && ${"result_size".$n.$i} <= (float)${"size".$i} + (float)${"upper_limit".$i}
            && ${"result_size".$n.$i} >= (float)${"size".$i} + (float)${"lower_limit".$i}){
              $gouhi_check = $gouhi_check;
            } elseif(${"input_type".$i} == "judge" && ${"result_size".$n.$i} < 1) {
              $gouhi_check = $gouhi_check;
            } else {
              $gouhi_check = $gouhi_check + 1;
            }
      
          }

        }else{

          //ロットナンバーは変えない
          $lot_number_m = $m + 1;

          ${"lot_number".$lot_number_m} = $data['lot_number'.$n];
          $this->set('lot_number'.$lot_number_m,${"lot_number".$lot_number_m});

        }

        if($gouhi_check > 0){
          ${"judge".$n} = 1;
          ${"gouhi".$n} = 1;
          ${"gouhihyouji".$n} = "否";
        } else {
          ${"judge".$n} = 0;
          ${"gouhi".$n} = 0;
          ${"gouhihyouji".$n} = "合";
        }
        $this->set('judge'.$m,${"judge".$n});
        $this->set('gouhi'.$m,${"gouhi".$n});
        $this->set('gouhihyouji'.$m,${"gouhihyouji".$n});
  
      }

      if($data["check"] < 1 && $m > 0){
        $mess = "上記のように更新します。よろしければ決定ボタンを押してください。";
        $this->set('delete_flag', 0);
      }else{
        $mess = "データを削除します。よろしければ決定ボタンを押してください。";
        $this->set('delete_flag', 1);
      }
      $this->set('mess', $mess);

      $countlength = $data["countlength"];
      $this->set('countlength', $countlength);

      $product_code_ini = substr($product_code, 0, 11);
      $DailyReportsData = $this->DailyReports->find()
      ->contain(['Products'])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
      'start_datetime >=' => $data["datetimesta"], 'start_datetime <' => $data["datetimefin"],
      'DailyReports.delete_flag' => 0])->toArray();

      for($j=0; $j<$data["countlength"]; $j++){

        ${"length".$j} = $DailyReportsData[$j]["product"]["length"];
        $this->set('length'.$j,${"length".$j});
        ${"amount".$j} = $data['amount'.$j];
        $this->set('amount'.$j,${"amount".$j});

        $total_weight = 0;
        $total_weight_count = 0;
        for($k=1; $k<=$data["gyoumax"]; $k++){

          if($data["length".$k] == ${"length".$j}){
            $total_weight = $total_weight + $data["result_weight".$k];
            $total_weight_count = $total_weight_count + 1;
          }
        }

        $weight_ave = $total_weight/($total_weight_count * 1000);
        ${"sum_weight".$j} = $weight_ave * $data["amount".$j];
        ${"sum_weight".$j} = sprintf("%.1f", ${"sum_weight".$j});
        $this->set('sum_weight'.$j, ${"sum_weight".$j});
  
        $InspectionDataResultParentDatas = $this->InspectionDataResultParents->find()
        ->contain(['InspectionStandardSizeParents', 'ProductConditionParents', 'Products'])
        ->where(['machine_num' => $machine_num, 'length' => ${"length".$j},
        'InspectionStandardSizeParents.delete_flag' => 0,
        'InspectionDataResultParents.delete_flag' => 0,
        'datetime >=' => $data["datetimesta"], 'datetime <=' => $data["datetimefin"]])
        ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();
    
        $total_loss_weight = 0;
        for($k=0; $k<count($InspectionDataResultParentDatas); $k++){
          $total_loss_weight = $total_loss_weight + $InspectionDataResultParentDatas[$k]["loss_amount"];
        }
        ${"total_loss_weight".$j} = $total_loss_weight;
        ${"total_loss_weight".$j} = sprintf("%.1f", ${"total_loss_weight".$j});
        $this->set('total_loss_weight'.$j, ${"total_loss_weight".$j});

      }

      $total_sum_weight = 0;
      $total_loss_weight = 0;
      for($j=0; $j<$data["countlength"]; $j++){
        $total_sum_weight = $total_sum_weight + ${"sum_weight".$j};
        $total_loss_weight = $total_loss_weight + ${"total_loss_weight".$j};
      }

      $tasseiritsu = $total_sum_weight * 100 / ($total_sum_weight + $total_loss_weight);
      $tasseiritsu = sprintf("%.1f", $tasseiritsu);
      $this->set('tasseiritsu', $tasseiritsu);
      $lossritsu = $total_loss_weight * 100 / ($total_sum_weight + $total_loss_weight);
      $lossritsu = sprintf("%.1f", $lossritsu);
      $this->set('lossritsu', $lossritsu);

      $bik = $data["bik"];
      $this->set('bik', $bik);
 
      $machine_datetime_product = $machine_num."_".$datekensaku."_00:00:00_".$product_code;

      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheaderkensaku($machine_datetime_product);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

      $machine_product_datetime = $machine_num."_".$product_code."_".$datetimefin;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();//クラスを使用
      $htmlseikeijouken = $htmlkensahyougenryouheader->seikeijouken($machine_product_datetime);//クラスを使用
      $this->set('htmlseikeijouken', $htmlseikeijouken);
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
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $product_code_ini = substr($product_code, 0, 11);

      $datetimesta = $data["datetimesta"];
      $datetimefin = $data["datetimefin"];

      $ProductID = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $product_id = $ProductID[0]["id"];

      $ProductParent = $this->Products->find()
      ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();

      $ig_bank_modes = $ProductParent[0]['ig_bank_modes'];
      if($ig_bank_modes == 0){
        $mode = "0（X-Y）";
      }else{
        $mode = "0（Y-Y）";
      }
      $this->set('mode',$mode);

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $datekensaku = $data["datekensaku"];
      $this->set('datekensaku', $datekensaku);

      $ProductParent = $this->Products->find()
      ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();

      $htmlproductcheck = new htmlproductcheck();//クラスを使用
      $arrayproductdate = $htmlproductcheck->productcheckprogram($product_code);//クラスを使用

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $machine_datetime_product = $machine_num."_".$datekensaku."_00:00:00_".$product_code;

      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheaderkensaku($machine_datetime_product);
      $this->set('htmlgenryouheader',$htmlgenryouheader);

      $machine_product_datetime = $machine_num."_".$product_code."_".$datetimefin;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();//クラスを使用
      $htmlseikeijouken = $htmlkensahyougenryouheader->seikeijouken($machine_product_datetime);//クラスを使用
      $this->set('htmlseikeijouken', $htmlseikeijouken);

      $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren->find()
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

        for($i=0; $i<count($InspectionStandardSizeChildren); $i++){

          $num = $InspectionStandardSizeChildren[$i]["size_number"];
          ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
          ${"input_type".$num} = $InspectionStandardSizeChildren[$i]["input_type"];
          $this->set('input_type'.$num,${"input_type".$num});

          if(${"size_name".$num} === "長さ"){

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = "-";
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = "-";
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = "-";
            $this->set('size'.$num,${"size".$num});
  //          ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
            ${"measuring_instrument".$num} = "※製品規格参照";
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
    
          }elseif($InspectionStandardSizeChildren[$i]["input_type"] === "judge"){

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
  
          }else{

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["upper_limit"]);
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["lower_limit"]);
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["size"]);
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
  
          }
    
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

        $Users = $this->Users->find()->contain(["Staffs"])->where(['user_code' => ${"user_code".$n}, 'Users.delete_flag' => 0])->toArray();
        $Staffs = $this->Staffs->find()
        ->where(['id' => $Users[0]['staff_id'], 'delete_flag' => 0])->order(["id"=>"ASC"])->toArray();
        ${"staff_hyouji".$n} = $Staffs[0]['name'];
        $this->set('staff_hyouji'.$n,${"staff_hyouji".$n});

        ${"lot_number".$n} = $data['lot_number'.$n];
        $this->set('lot_number'.$n,${"lot_number".$n});

        ${"product_id".$n} = $data['product_id'.$n];
        $Products = $this->Products->find()
        ->where(['id' => ${"product_id".$n}])->toArray();
        ${"lengthhyouji".$n} = $Products[0]["length"];
        $this->set('product_id'.$n,${"product_id".$n});
        $this->set('lengthhyouji'.$n,${"lengthhyouji".$n});

        ${"appearance".$n} = $data['appearance'.$n];
        $this->set('appearance'.$n,${"appearance".$n});
        ${"result_weight".$n} = $data['result_weight'.$n];
        $this->set('result_weight'.$n,${"result_weight".$n});
        ${"judge".$n} = $data['judge'.$n];
        $this->set('judge'.$n,${"judge".$n});

        for($i=1; $i<=11; $i++){

          if(strlen($data["result_size".$n."_".$i]) > 0){
            ${"result_size".$n.$i} = $data["result_size".$n."_".$i];
            ${"result_size".$n.$i} = sprintf("%.1f", ${"result_size".$n.$i});
          }else{
            ${"result_size".$n.$i} = "";
          }
          $this->set("result_size".$n."_".$i,${"result_size".$n.$i});

        }

      }

      if($data["delete_flag"] < 1){

        $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
        ->where(['product_code' => $product_code, 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

        if(!isset($InspectionStandardSizeParents[0])){//長さ違いのデータがあればそれを持ってくる

          $product_code_ini = substr($product_code, 0, 11);
          $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
          ->where(['product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
          ->order(["version"=>"DESC"])->toArray();
    
        }
  
        $inspection_standard_size_parent_id = $InspectionStandardSizeParents[0]['id'];

        $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code' => $product_code,
        'ProductConditionParents.is_active' => 0,
        'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

        if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

          $product_code_ini = substr($product_code, 0, 11);
          $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
          ->where(['product_code like' => $product_code_ini.'%',
          'ProductConditionParents.is_active' => 0,
          'ProductConditionParents.delete_flag' => 0])
          ->order(["version"=>"DESC"])->toArray();
    
        }
  
        $DailyReportsData = $this->DailyReports->find()
        ->contain(['Products'])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%',
        'start_datetime >=' => $data["datetimesta"], 'start_datetime <' => $data["datetimefin"],
        'DailyReports.delete_flag' => 0])->toArray();

        $updateDailyreportmoto = array();
        for($i=0; $i<count($DailyReportsData); $i++){

          $updateDailyreportmoto[] = [
            'product_id' => $DailyReportsData[$i]["product_id"],
            'machine_num' => $DailyReportsData[$i]["machine_num"],
            'start_datetime' => $DailyReportsData[$i]["start_datetime"],
            'finish_datetime' => $DailyReportsData[$i]["finish_datetime"],
            'amount' => $DailyReportsData[$i]["amount"],
            'sum_weight' => $DailyReportsData[$i]["sum_weight"],
            'total_loss_weight' => $DailyReportsData[$i]["total_loss_weight"],
            'bik' => $DailyReportsData[$i]["bik"],
            'created_at' => $DailyReportsData[$i]["created_at"],
            'created_staff' => $DailyReportsData[$i]["created_staff"],
            "delete_flag" => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_staff' => $staff_id,
          ];
  
        }

        $updateDailyreport = array();
        for($i=0; $i<$data["countlength"]; $i++){
        
          $product_code_ini = substr($product_code, 0, 11);
          $ProductDaily = $this->Products->find()
          ->where(['product_code like' => $product_code_ini.'%', 'length' => $data['length'.$i], 'delete_flag' => 0])->toArray();
  /*
          echo "<pre>";
          print_r($ProductDaily[0]);
          echo "</pre>";
    */
          $DailyReportsMoto = $this->DailyReports->find()
          ->contain(['Products'])
          ->where(['machine_num' => $machine_num, 'product_id' => $ProductDaily[0]["id"], 
          'start_datetime >=' => $datetimesta, 'start_datetime <' => $datetimefin,
          'DailyReports.delete_flag' => 0])->toArray();
    
          if(substr($data["datetime1"], 0, 2) < 6){
            $start_datetime = substr($data["datetimefin"], 0, 10)." ".$data["datetime1"].":00";
          }else{
            $start_datetime = substr($data["datetimesta"], 0, 10)." ".$data["datetime1"].":00";
          }
    
          if(substr($data["datetime".$data["gyou"]], 0, 2) < 6){
            $finish_datetime = substr($data["datetimefin"], 0, 10)." ".$data["datetime".$data["gyou"]].":00";
          }else{
            $finish_datetime = substr($data["datetimesta"], 0, 10)." ".$data["datetime".$data["gyou"]].":00";
          }

          $updateDailyreport[] = [
              'product_id' => $ProductDaily[0]["id"],
              "delete_flag" => 0,
              'machine_num' => $machine_num,
              'start_datetime' => $start_datetime,
              'finish_datetime' => $finish_datetime,
              'amount' => $data["amount".$i],
              'sum_weight' => $data["sum_weight".$i],
              'total_loss_weight' => $data["total_loss_weight".$i],
              'bik' => $data["bik"],
              'created_at' => $DailyReportsMoto[0]["created_at"]->format("Y-m-d H:i:s"),
              "created_staff" => $DailyReportsMoto[0]["created_staff"],
              'updated_at' => date('Y-m-d H:i:s'),
              'updated_staff' => $staff_id,
            ];
  
        }

        for($j=1; $j<=$gyou; $j++){

          $InspectionDataResultParentsmoto = $this->InspectionDataResultParents->find()->contain(['ProductConditionParents',"Products"])
          ->where(['product_code like' => $product_code_ini.'%'
          , 'lot_number' => $data['lot_number'.$j], 'datetime >=' => $data["datetimesta"], 'datetime <=' => $data["datetimefin"]
          ])->order(["InspectionDataResultParents.id"=>"DESC"])->toArray();
  
          ${"user_code".$j} = $data['user_code'.$j];
          $Users = $this->Users->find()->contain(["Staffs"])->where(['user_code' => ${"user_code".$j}, 'Users.delete_flag' => 0])->toArray();

          if(substr($data['datetime'.$j], 0, 2) < 6){
            $datetime = substr($data["datetimefin"], 0, 10)." ".$data['datetime'.$j].":00";
          }else{
            $datetime = substr($data["datetimesta"], 0, 10)." ".$data['datetime'.$j].":00";
          }

          $updateInspectionDataResultParents = [
            "inspection_data_conditon_parent_id" => $data['inspection_data_conditon_parent_id'.$j],
            "inspection_standard_size_parent_id" => $data['inspection_standard_size_parent_id'.$j],
            "product_condition_parent_id" => $data['product_condition_parent_id'.$j],
            'lot_number' => $data['lot_number'.$j],
            'datetime' => $datetime,
            'staff_id' => $Users[0]["staff_id"],
            'product_id' => $data['product_id'.$j],
            'appearance' => $data['appearance'.$j],
            'result_weight' => $data['result_weight'.$j],
            'judge' => $data['judge'.$j],
            'kanryou_flag' => 1,
            "delete_flag" => 0,
            'daily_report_id' => $InspectionDataResultParentsmoto[0]["daily_report_id"],
            'loss_amount' => $InspectionDataResultParentsmoto[0]["loss_amount"],
            'bik' => $InspectionDataResultParentsmoto[0]["bik"],
            'created_at' => $InspectionDataResultParentsmoto[0]["created_at"],
            "created_staff" => $staff_id
          ];

          $product_code_ini = substr($product_code, 0, 11);

          $connection = ConnectionManager::get('default');//トランザクション1
          // トランザクション開始2
          $connection->begin();//トランザクション3
          try {//トランザクション4

            if($j == 1){
              
              for($m=0; $m<$data["countlength"]; $m++){
                
                $Products = $this->Products->find()
                ->where(['product_code like' => $product_code_ini.'%', 'length' => $data['length'.$m], 'delete_flag' => 0])
                ->toArray();
  
                $this->InspectionDataResultParents->updateAll(
                  [ 'delete_flag' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_staff' => $staff_id],
                  ['product_id' => $Products[0]["id"], 'datetime >=' => $datetimesta, 'datetime <=' => $datetimefin]);
  
              }

              $DailyReportsMoto = $this->DailyReports->find()
              ->contain(['Products'])
              ->where(['machine_num' => $machine_num, 'product_id' => $ProductDaily[0]["id"], 
              'start_datetime >=' => $datetimesta, 'start_datetime <' => $datetimefin,
              'DailyReports.delete_flag' => 0])->toArray();
  
              for($k=0; $k<count($updateDailyreport); $k++){

                $this->DailyReports->updateAll(
                  [
                    'product_id' => $updateDailyreport[$k]["product_id"],
                    'machine_num' => $updateDailyreport[$k]["machine_num"],
                    'start_datetime' => $updateDailyreport[$k]["start_datetime"],
                    'finish_datetime' => $updateDailyreport[$k]["finish_datetime"],
                    'amount' => $updateDailyreport[$k]["amount"],
                    'sum_weight' => $updateDailyreport[$k]["sum_weight"],
                    'total_loss_weight' => $updateDailyreport[$k]["total_loss_weight"],
                    'bik' => $updateDailyreport[$k]["bik"],
                    'delete_flag' => 0,
                    'created_at' => $updateDailyreport[$k]["created_at"],
                    'created_staff' => $updateDailyreport[$k]["created_staff"],
                    'updated_at' => date('Y-m-d H:i:s'),
                  'updated_staff' => $staff_id],
                  ['id' => $DailyReportsData[$k]["id"]]);
              }

              $DailyReports = $this->DailyReports->patchEntities($this->DailyReports->newEntity(), $updateDailyreportmoto);
              $this->DailyReports->saveMany($DailyReports);

              }

              $InspectionDataResultParents = $this->InspectionDataResultParents->patchEntity($this->InspectionDataResultParents->newEntity(), $updateInspectionDataResultParents);
              if ($this->InspectionDataResultParents->save($InspectionDataResultParents)) {

                if(substr($data['datetime'.$j], 0, 2) < 6){//前日の測定
                  $date1 = strtotime($data['datekensaku']);
                  $datekensaku = date('Y-m-d', strtotime('+1 day', $date1));
                    }else{
                  $datekensaku = $data['datekensaku'];
                }
        
                $InspectionDataResultParentsId = $this->InspectionDataResultParents->find()->contain(['ProductConditionParents','Products'])
                ->where(['InspectionDataResultParents.delete_flag' => 0, 'machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
                'datetime >=' => $datetimesta, 'datetime <' => $datetimefin, 'lot_number' => $data['lot_number'.$j]])
                ->order(["InspectionDataResultParents.updated_at"=>"DESC"])->toArray();

                $tourokuInspectionDataResultChildren = array();
                for($i=1; $i<=11; $i++){

                  if(strlen($data["result_size".$j."_".$i]) > 0){

                    $InspectionStandardSizeChildren = $this->InspectionStandardSizeChildren->find()
                    ->where(['inspection_standard_size_parent_id' => $inspection_standard_size_parent_id, "size_number" => $i])
                    ->order(["id"=>"DESC"])->toArray();

                    $tourokuInspectionDataResultChildren[] = [
                      "inspection_data_result_parent_id" => $InspectionDataResultParentsId[0]["id"],
                      "inspection_standard_size_child_id" => $InspectionStandardSizeChildren[0]["id"],
                      "result_size" => $data["result_size".$j."_".$i],
                      "delete_flag" => 0,
                      'created_at' => date("Y-m-d H:i:s"),
                      "created_staff" => $staff_id
                    ];

                  }

                  if($i == 11){//各jに対して一括登録
          
                    $InspectionDataResultChildren = $this->InspectionDataResultChildren
                    ->patchEntities($this->InspectionDataResultChildren->newEntity(), $tourokuInspectionDataResultChildren);
                    if ($this->InspectionDataResultChildren->saveMany($InspectionDataResultChildren)) {

                      $mes = "※更新されました";
                      $this->set('mes',$mes);
                      $connection->commit();// コミット5

                    }else {

                      $mes = "※更新されませんでした";
                      $this->set('mes',$mes);
                      $this->Flash->error(__('The data could not be saved. Please, try again.'));
                      throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
      
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
            ['product_id' => $product_id, 'datetime >=' => $datetimesta, 'datetime <=' => $datetimefin])
          ) {

            $product_code_ini = substr($product_code, 0, 11);
            $DailyReportsData = $this->DailyReports->find()
            ->contain(['Products'])
            ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 
            'start_datetime >=' => $data["datetimesta"], 'start_datetime <' => $data["datetimefin"],
            'DailyReports.delete_flag' => 0])->toArray();
                for($i=0; $i<count($DailyReportsData); $i++){
                  $this->InspectionDataResultParents->updateAll(
                    [ 'delete_flag' => 1,
                      'updated_at' => date('Y-m-d H:i:s'),
                      'updated_staff' => $staff_id],
                    ['id' => $DailyReportsData[$i]["id"]]);
                }

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

    public function kensakuikkatsupre()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $datasession = $session->read();
      $staff_id = $datasession['Auth']['User']['staff_id'];
      $Staffs = $this->Staffs->find()->where(['id' => $staff_id])->toArray();
      $factory_id = $Staffs[0]["factory_id"];

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

      if($factory_id == 5){//本部の人がログインしている場合

        $Product_name_list = $this->Products->find()
        ->contain(['Customers'])
        ->where(['Customers.name' => $data["customer_name"], 'Products.status_kensahyou' => 0, 'Products.delete_flag' => 0])
        ->toArray();
 
      }else{

        $Product_name_list = $this->Products->find()
        ->contain(['Customers'])
        ->where(['Customers.name' => $data["customer_name"], 'Products.status_kensahyou' => 0, 'Products.delete_flag' => 0,
        'OR' => [['Products.factory_id' => $factory_id], ['Products.factory_id' => 5]]])
        ->toArray();

      }

       if(count($Product_name_list) < 1){//顧客名にミスがある場合

         $mess = "入力された顧客の製品は登録されていません。確認してください。";
         $this->set('mess',$mess);

         if($factory_id == 5){//本部の人がログインしている場合

          $Product_name_list = $this->Products->find()
          ->where(['status_kensahyou' => 0, 'delete_flag' => 0])
          ->toArray();
  
        }else{
  
          $Product_name_list = $this->Products->find()
          ->where(['status_kensahyou' => 0, 'delete_flag' => 0,
          'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
          ->toArray();
  
        }

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
  
        if($factory_id == 5){//本部の人がログインしている場合

          $Products = $this->Products->find()
          ->where(['name' => $name, 'length' => $length, 'delete_flag' => 0])->toArray();
    
        }else{
  
          $Products = $this->Products->find()
          ->where(['name' => $name, 'length' => $length, 'delete_flag' => 0,
           'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
          ->toArray();
  
        }

         if(isset($Products[0])){

           $product_code = $Products[0]["product_code"];

           return $this->redirect(['action' => 'kensakuikkatsugouki',
           's' => ['product_code' => $product_code]]);

         }else{

           $mess = "入力された製品名は登録されていません。確認してください。";
           $this->set('mess',$mess);

           if($factory_id == 5){//本部の人がログインしている場合

            $Product_name_list = $this->Products->find()
            ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();
       
          }else{
    
            $Product_name_list = $this->Products->find()
            ->where(['status_kensahyou' => 0, 'delete_flag' => 0,
             'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
            ->toArray();
    
          }

           $arrProduct_name_list = array();
           for($j=0; $j<count($Product_name_list); $j++){
             array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
           }
           $this->set('arrProduct_name_list', $arrProduct_name_list);

         }

       }else{//product_nameの入力がない

         $mess = "製品名が入力されていません。";
         $this->set('mess',$mess);

         if($factory_id == 5){//本部の人がログインしている場合

          $Product_name_list = $this->Products->find()
          ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();
     
        }else{
  
          $Product_name_list = $this->Products->find()
          ->where(['status_kensahyou' => 0, 'delete_flag' => 0,
           'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
          ->toArray();
  
        }

         $arrProduct_name_list = array();
         for($j=0; $j<count($Product_name_list); $j++){
           array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
         }
         $this->set('arrProduct_name_list', $arrProduct_name_list);

       }

     }else{//はじめ

      if($factory_id == 5){//本部の人がログインしている場合

        $Product_name_list = $this->Products->find()
        ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();
   
      }else{

        $Product_name_list = $this->Products->find()
        ->where(['status_kensahyou' => 0, 'delete_flag' => 0,
         'OR' => [['factory_id' => $factory_id], ['factory_id' => 5]]])
        ->toArray();

      }

      $arrProduct_name_list = array();
       for($j=0; $j<count($Product_name_list); $j++){
         array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
       }
       $this->set('arrProduct_name_list', $arrProduct_name_list);

     }

    }

    public function kensakuikkatsugouki()
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
/*
      echo "<pre>";
      print_r("data");
      print_r($data);
      echo "</pre>";
*/

      if(isset($data["next"])){//「次へ」ボタンを押したとき

        $product_code = $data["product_code"];
        $machine_num = $data["machine_num"];

        return $this->redirect(['action' => 'kensakuikkatsudate',
        's' => ['product_code' => $product_code, 'machine_num' => $machine_num]]);

      }
   
      $arrGouki = array();
      for($k=0; $k<count($ProductConditionParents); $k++){
        $ProductDatas = $this->Products->find()
        ->where(['product_code' => $product_code])->toArray();
        $LinenameDatas = $this->Linenames->find()
        ->where(['delete_flag' => 0, 'factory_id' => $ProductDatas[0]["factory_id"], 'machine_num' => $ProductConditionParents[$k]["machine_num"]])->toArray();

        $array = array($ProductConditionParents[$k]["machine_num"] => $LinenameDatas[0]["name"]);
        $arrGouki = $arrGouki + $array;
      }
      $this->set('arrGouki', $arrGouki);

    }

    public function kensakuikkatsudate()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data = $this->request->query('s');
      $product_code = $Data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $Data["machine_num"];
      $this->set('machine_num', $machine_num);
      $product_code_ini = substr($product_code, 0, 11);

      $Products = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $product_name = $Products[0]["name"];
      $this->set('product_name', $product_name);

      if(isset($data['saerch'])){

        $startY = $data['start']['year'];
    		$startM = $data['start']['month'];
    		$startD = $data['start']['day'];
        $startYMD = $startY."-".$startM."-".$startD." 00:00";

        $endY = $data['end']['year'];
    		$endM = $data['end']['month'];
    		$endD = $data['end']['day'];
        $endYMD = $endY."-".$endM."-".$endD." 23:59";
        
        $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()->contain(["ProductConditionParents"])
        ->contain(['InspectionDataResultParents' => ['Products']])//測定データのうち、管理ナンバーが一致するものを検索
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'InspectionDataResultChildren.delete_flag' => 0,
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
        ->contain(['InspectionDataResultParents' => ['Products']])//測定データのうち、管理ナンバーが一致するものを検索
        ->where(['product_code like' => $product_code_ini.'%', 'InspectionDataResultChildren.delete_flag' => 0])
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

      echo "<pre>";//フォームの再読み込みの防止
      print_r("");
      echo "</pre>";

    }

    public function kensakuikkatsuichiran()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);
      $machine_num = $data["machine_num"];
      $this->set('machine_num', $machine_num);

      $product_code_ini = substr($product_code, 0, 11);

      $ProductParent = $this->Products->find()
      ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();
      $ProductLength = $this->Products->find()
      ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();
      $Length = $ProductLength[0]['length'];
      $this->set('Length',$Length);

      $ig_bank_modes = $ProductParent[0]['ig_bank_modes'];
      if($ig_bank_modes == 0){
        $mode = "0（X-Y）";
      }else{
        $mode = "0（Y-Y）";
      }
      $this->set('mode',$mode);

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

        for($i=0; $i<count($InspectionStandardSizeChildren); $i++){

          $num = $InspectionStandardSizeChildren[$i]["size_number"];
          ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
          ${"input_type".$num} = $InspectionStandardSizeChildren[$i]["input_type"];
          $this->set('input_type'.$num,${"input_type".$num});

          if(${"size_name".$num} === "長さ"){

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = "-";
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = "-";
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = "-";
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = "※製品規格参照";
    //        ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
    
          }elseif($InspectionStandardSizeChildren[$i]["input_type"] === "judge"){

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
  
          }else{

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["upper_limit"]);
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["lower_limit"]);
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["size"]);
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
  
          }
    
        }
        
        $this->set('InspectionStandardSizeChildren', $InspectionStandardSizeChildren);

      }

      $Products = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $product_name = $Products[0]["name"];
      $this->set('product_name', $product_name);

      $startY = $data['start']['year'];
      $startM = $data['start']['month'];
      $startD = $data['start']['day'];
      $startYMD = $startY."-".$startM."-".$startD." 00:00";

      $endY = $data['end']['year'];
      $endM = $data['end']['month'];
      $endD = $data['end']['day'];
      $endYMD = $endY."-".$endM."-".$endD." 23:59";

      $datetimesta = $startYMD;
      $datetimefin = $endYMD;

      $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()
      ->contain(['InspectionDataResultParents' => ["ProductConditionParents", 'Products']])//測定データのうち、管理ナンバーが一致するものを検索
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'InspectionDataResultChildren.delete_flag' => 0,
      'datetime >=' => $startYMD, 'datetime <=' => $endYMD])
      ->order(["InspectionDataResultParents.datetime"=>"DESC"])->toArray();

      $arrDates = array();

      for($k=0; $k<count($InspectionDataResultChildren); $k++){

        $arrDates[] = $InspectionDataResultChildren[$k]['inspection_data_result_parent']['datetime']->format('Y-m-d');

      }
/*
      echo "<pre>";
      print_r($InspectionDataResultChildren);
      echo "</pre>";
*/
      $arrDates = array_unique($arrDates);
      $arrDates = array_values($arrDates);

      $this->set('arrDates', $arrDates);

      $mes = "検索期間： ".$startY."-".$startM."-".$startD .' ～ '.$endY."-".$endM."-".$endD;
      $this->set('mes', $mes);

      $checksaerch = 1;
      $this->set('checksaerch', $checksaerch);

      $InspectionDataResultParents = $this->InspectionDataResultParents->find()->contain(["ProductConditionParents"])
      ->contain(['InspectionStandardSizeParents', 'Products', 'ProductConditionParents' => ["ProductMaterialMachines"]])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.delete_flag' => 0,
       'InspectionDataResultParents.delete_flag' => 0,
      'datetime >=' => $datetimesta, 'datetime <=' => $datetimefin])
      ->order(["InspectionDataResultParents.datetime"=>"DESC"])->toArray();
/*
      echo "<pre>";
      print_r($InspectionDataResultParents);
      echo "</pre>";
*/
      $gyou = count($InspectionDataResultParents);
      $this->set('gyou', $gyou);

      $arr_numnakama_ondo = array();
      $arr_numnakama_kikaku = array();
      $numnakama_ondo = 0;
      $numnakama_kikau = 0;

      for($j=0; $j<count($InspectionDataResultParents); $j++){

        $n = $j + 1;
        
        $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()
        ->contain(['InspectionStandardSizeChildren'])
        ->where(['inspection_data_result_parent_id' => $InspectionDataResultParents[$j]["id"],
         'InspectionDataResultChildren.delete_flag' => 0])
        ->toArray();

        ${"length".$n} = $InspectionDataResultParents[$j]["product"]['length'];
        $this->set('length'.$n,${"length".$n});

        ${"product_condition_code".$n} = $InspectionDataResultParents[$j]["product_condition_parent"]['product_condition_code'];
        $this->set('product_condition_code'.$n,${"product_condition_code".$n});
        ${"lot_number".$n} = $InspectionDataResultParents[$j]['lot_number'];
        $this->set('lot_number'.$n,${"lot_number".$n});
        ${"datetime".$n} = $InspectionDataResultParents[$j]['datetime']->format('Y-m-d G:i');
        $this->set('datetime'.$n,${"datetime".$n});

        //datetimeのときの規格条件を取得する
        ${"kikaku_created_at".$n} = $InspectionDataResultParents[$j]["inspection_standard_size_parent"]["created_at"]->format('Y-m-d H:i:s');
        $this->set('kikaku_created_at'.$n,${"kikaku_created_at".$n});

        if(count($InspectionDataResultParents) == 1){

          ${"numnakama_kikau".$n} = $numnakama_kikau;
          $this->set('numnakama_kikau'.$n,$numnakama_kikau);

        }else{

          if($j > 0){

            $m = $n-1;
            if(${"kikaku_created_at".$m} == ${"kikaku_created_at".$n}){
  
              $numnakama_kikau = $numnakama_kikau;
              ${"numnakama_kikau".$n} = $numnakama_kikau;
              $this->set('numnakama_kikau'.$n,${"numnakama_kikau".$n});

            }else{
  
              $numnakama_kikau = $numnakama_kikau + 1;
              ${"numnakama_kikau".$n} = $numnakama_kikau;
              $this->set('numnakama_kikau'.$n,${"numnakama_kikau".$n});

            }
  
          }else{
            
            ${"numnakama_kikau".$n} = $numnakama_kikau;
            $this->set('numnakama_kikau'.$n,$numnakama_kikau);
  
          }
  
        }

//datetimeのときの温度条件を取得する
        $ProductConditonChildren = $this->ProductConditonChildren->find()
        ->where(['created_at <' => ${"datetime".$n}, 'cylinder_name' => $InspectionDataResultParents[$j]["product_condition_parent"]["product_material_machines"][0]["cylinder_name"]])
        ->order(["created_at"=>"DESC"])->toArray();

        ${"ondo_created_at".$n} = $ProductConditonChildren[0]["created_at"]->format('Y-m-d H:i:s');
        $this->set('ondo_created_at'.$n,${"ondo_created_at".$n});

        if(count($InspectionDataResultParents) == 1){

          ${"numnakama_ondo".$n} = $numnakama_ondo;
          $this->set('numnakama_ondo'.$n,${"numnakama_ondo".$n});

        }else{

          if($j > 0){

            $m = $n-1;
            if(${"ondo_created_at".$m} == ${"ondo_created_at".$n}){
  
              $numnakama_ondo = $numnakama_ondo;
              ${"numnakama_ondo".$n} = $numnakama_ondo;
              $this->set('numnakama_ondo'.$n,${"numnakama_ondo".$n});

            }else{
  
              $numnakama_ondo = $numnakama_ondo + 1;
              ${"numnakama_ondo".$n} = $numnakama_ondo;
              $this->set('numnakama_ondo'.$n,${"numnakama_ondo".$n});

            }
  
          }else{
  
            ${"numnakama_ondo".$n} = $numnakama_ondo;
            $this->set('numnakama_ondo'.$n,${"numnakama_ondo".$n});
  
          }
  
        }

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
        for($i=1; $i<=11; $i++){

          ${"result_size".$n.$i} = "";
          $this->set("result_size".$n."_".$i,${"result_size".$n.$i});

        }

        for($i=0; $i<count($InspectionDataResultChildren); $i++){

          $size_number = $InspectionDataResultChildren[$i]['inspection_standard_size_child']['size_number'];

          ${"result_size".$n.$size_number} = $InspectionDataResultChildren[$i]["result_size"];
          $this->set("result_size".$n."_".$size_number,${"result_size".$n.$size_number});

        }

      }

      $arr_numnakama_toujitu = array();
      for($i=1; $i<=count($InspectionDataResultParents); $i++){

        $arr_numnakama_ondo[] = ${"numnakama_ondo".$i};
        $arr_numnakama_kikaku[] = ${"numnakama_kikau".$i};
        $arr_numnakama_toujitu[] = $InspectionDataResultParents[$i-1]["inspection_data_conditon_parent_id"];
 
      }

      $arr_numnakama_ondo = array_count_values($arr_numnakama_ondo);
      $this->set('arr_numnakama_ondo', $arr_numnakama_ondo);
      $arr_numnakama_kikaku = array_count_values($arr_numnakama_kikaku);
      $this->set('arr_numnakama_kikaku', $arr_numnakama_kikaku);

      $arr_numnakama_toujitu = array_count_values($arr_numnakama_toujitu);
      $arr_numnakama_toujitu = array_values($arr_numnakama_toujitu);
      $this->set('arr_numnakama_toujitu', $arr_numnakama_toujitu);

      echo "<pre>";//フォームの再読み込みの防止
      print_r("");
      echo "</pre>";

    }

    public function kensakuikkatsujouken()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
      $keys = array_keys($data);
      $idarr2 = explode("_",$keys[2]);
 //     $idarr1 = array_keys($data, '表示');
 //     $idarr2 = explode("_",$idarr1[0]);

      $product_code = $data["product_code"];
      $machine_num = $data["machine_num"];
      $datetime = $idarr2[1]." ".$idarr2[2];
      $product_code_datetime = $product_code."_".$idarr2[1]."_".$idarr2[2];
      /*
      echo "<pre>";
      print_r($datetime);
      echo "</pre>";
*/
      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheaderkensaku($product_code_datetime);
      $this->set('htmlkensahyouheader',$htmlkensahyouheader);

      if($idarr2[0] == "kikaku"){

        $hyouji_flag = 1;
        $this->set('hyouji_flag', $hyouji_flag);
  
        $product_code_ini = substr($product_code, 0, 11);
        $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.created_at <=' => $datetime])
        ->order(["version"=>"DESC"])->toArray();
      
        $inspection_standard_size_parent_id = $InspectionStandardSizeParents[0]['id'];
        $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);
  
        $product_code_ini = substr($product_code, 0, 11);
        $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
        ->where(['inspection_standard_size_parent_id' => $inspection_standard_size_parent_id])
        ->order(["InspectionStandardSizeChildren.size_number"=>"ASC"])->toArray();

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
  
          for($i=0; $i<count($InspectionStandardSizeChildren); $i++){
  
            $num = $InspectionStandardSizeChildren[$i]["size_number"];
            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            ${"input_type".$num} = $InspectionStandardSizeChildren[$i]["input_type"];
            $this->set('input_type'.$num,${"input_type".$num});
  
            if(${"size_name".$num} === "長さ"){
  
              ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
              $this->set('size_name'.$num,${"size_name".$num});
              ${"upper_limit".$num} = "-";
              $this->set('upper_limit'.$num,${"upper_limit".$num});
              ${"lower_limit".$num} = "-";
              $this->set('lower_limit'.$num,${"lower_limit".$num});
              ${"size".$num} = "-";
              $this->set('size'.$num,${"size".$num});
              ${"measuring_instrument".$num} = "※製品規格参照";
        //      ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
              $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
      
            }elseif($InspectionStandardSizeChildren[$i]["input_type"] === "judge"){
  
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
    
            }else{
  
              ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
              $this->set('size_name'.$num,${"size_name".$num});
              ${"upper_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["upper_limit"]);
              $this->set('upper_limit'.$num,${"upper_limit".$num});
              ${"lower_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["lower_limit"]);
              $this->set('lower_limit'.$num,${"lower_limit".$num});
              ${"size".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["size"]);
              $this->set('size'.$num,${"size".$num});
              ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
              $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
    
            }
      
          }
          
          $this->set('InspectionStandardSizeChildren', $InspectionStandardSizeChildren);
  
        }
  
      }elseif($idarr2[0] == "ondo" || $idarr2[0] == "toujitu"){
        
        $hyouji_flag = 2;
        $this->set('hyouji_flag', $hyouji_flag);
        $machine_datetime_product = $machine_num."_".$idarr2[1]."_".$idarr2[2]."_".$product_code;

        $htmlkensahyougenryouheader = new htmlkensahyouprogram();
        $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheaderkensaku($machine_datetime_product);
        $this->set('htmlgenryouheader',$htmlgenryouheader);
  
        $product_code_ini = substr($product_code, 0, 11);
        $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
        ->where(['product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
        ->order(["version"=>"DESC"])->toArray();

        if(isset($ProductConditionParents[0])){
  
          $version = $ProductConditionParents[0]["version"];
  
          $product_code_ini = substr($product_code, 0, 11);
          $ProductMaterialMachines= $this->ProductMaterialMachines->find()
          ->where(['product_condition_parent_id' => $ProductConditionParents[0]["id"]])
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
            ->where(['product_material_machine_id' => ${"product_material_machine_id".$j}
            , 'cylinder_name' => ${"cylinder_name".$j}
            , 'ProductConditonChildren.created_at <=' => $datetime])
            ->order(["created_at"=>"DESC"])->toArray();
    
            if(isset($ProductConditonChildren[0])){
  
              ${"extrude_roatation".$j} = sprintf("%.1f", $ProductConditonChildren[0]["extrude_roatation"]);
              $this->set('extrude_roatation'.$j, ${"extrude_roatation".$j});
              ${"extrusion_load".$j} = sprintf("%.1f", $ProductConditonChildren[0]["extrusion_load"]);
              $this->set('extrusion_load'.$j, ${"extrusion_load".$j});
  
              for($n=1; $n<8; $n++){
                ${"temp_".$n.$j} = sprintf("%.0f", $ProductConditonChildren[0]["temp_".$n]);
                $this->set('temp_'.$n.$j, ${"temp_".$n.$j});
              }
  
              $pickup_speed = sprintf("%.1f", $ProductConditonChildren[0]["pickup_speed"]);
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
  
          }
  
        }
  
        if($idarr2[0] == "toujitu"){

          $hyouji_flag = 3;
          $this->set('hyouji_flag', $hyouji_flag);

          $product_code_ini = substr($product_code, 0, 11);
          $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
          ->where(['product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.created_at <=' => $datetime])
          ->order(["version"=>"DESC"])->toArray();
        
          $inspection_standard_size_parent_id = $InspectionStandardSizeParents[0]['id'];
          $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);
    
          $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
          ->where(['inspection_standard_size_parent_id' => $inspection_standard_size_parent_id])
          ->order(["created_at"=>"DESC"])->toArray();
  
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
    
            for($i=0; $i<count($InspectionStandardSizeChildren); $i++){
    
              $num = $InspectionStandardSizeChildren[$i]["size_number"];
              ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
              ${"input_type".$num} = $InspectionStandardSizeChildren[$i]["input_type"];
              $this->set('input_type'.$num,${"input_type".$num});
    
              if(${"size_name".$num} === "長さ"){
    
                ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
                $this->set('size_name'.$num,${"size_name".$num});
                ${"upper_limit".$num} = "-";
                $this->set('upper_limit'.$num,${"upper_limit".$num});
                ${"lower_limit".$num} = "-";
                $this->set('lower_limit'.$num,${"lower_limit".$num});
                ${"size".$num} = "-";
                $this->set('size'.$num,${"size".$num});
                ${"measuring_instrument".$num} = "※製品規格参照";
                //     ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
                $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
        
              }elseif($InspectionStandardSizeChildren[$i]["input_type"] === "judge"){
    
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
      
              }else{
    
                ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
                $this->set('size_name'.$num,${"size_name".$num});
                ${"upper_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["upper_limit"]);
                $this->set('upper_limit'.$num,${"upper_limit".$num});
                ${"lower_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["lower_limit"]);
                $this->set('lower_limit'.$num,${"lower_limit".$num});
                ${"size".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["size"]);
                $this->set('size'.$num,${"size".$num});
                ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
                $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
      
              }
        
            }
            
            $this->set('InspectionStandardSizeChildren', $InspectionStandardSizeChildren);
    
          }
  
          for($k=0; $k<$countseikeiki; $k++){//各成型機の基準値の呼び出し
        
            $cylinder_name = $ProductMaterialMachines[$k]["cylinder_name"];
            $product_code_ini = substr($product_code, 0, 11);

            //成形機毎に取り出し
            $InspectionDataConditonChildren = $this->InspectionDataConditonChildren->find()
            ->contain(['ProductConditonChildren', 'InspectionDataConditonParents'])
            ->where(['product_code like' => $product_code_ini.'%'
            , 'ProductConditonChildren.cylinder_name' => $cylinder_name
                , 'InspectionDataConditonChildren.created_at <=' => $datetime])
            ->order(["InspectionDataConditonChildren.created_at"=>"DESC"])->limit(1)->toArray();

            $j = $k + 1;
    
              ${"inspection_extrude_roatation".$j} = sprintf("%.1f", $InspectionDataConditonChildren[0]['inspection_extrude_roatation']);
              $this->set('inspection_extrude_roatation'.$j, ${"inspection_extrude_roatation".$j});
              ${"inspection_extrusion_load".$j} = sprintf("%.1f", $InspectionDataConditonChildren[0]['inspection_extrusion_load']);
              $this->set('inspection_extrusion_load'.$j, ${"inspection_extrusion_load".$j});
              ${"inspection_pickup_speed".$j} = sprintf("%.1f", $InspectionDataConditonChildren[0]['inspection_pickup_speed']);
              $this->set('inspection_pickup_speed'.$j, ${"inspection_pickup_speed".$j});

              for($n=1; $n<8; $n++){
      
                ${"inspection_temp_".$n.$j} = $InspectionDataConditonChildren[0]['inspection_temp_'.$n];
                $this->set('inspection_temp_'.$n.$j, ${"inspection_temp_".$n.$j});
      
              }
  
          }

        }

      }

    }

    public function kensatyuproducts()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $datasession = $session->read();
      $staff_id = $datasession['Auth']['User']['staff_id'];
      $Staffs = $this->Staffs->find()->where(['id' => $staff_id])->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      $datetimesta = date('Y-m-d 00:00:00');//今日の6時が境目

      //検査中一覧のクラス
      $datetimesta_factory = $datetimesta."_".$factory_id;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $arrInspectionDataResultParents = $htmlkensahyougenryouheader->toujitsuichiran($datetimesta_factory);
      $this->set('arrInspectionDataResultParents', $arrInspectionDataResultParents);

      //未検査一覧のクラス
      $datetimesta_factory = $datetimesta."_".$factory_id;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $arrInspectionDataResultParentnotfin = $htmlkensahyougenryouheader->mikanryouichiran($datetimesta_factory);
      $this->set('arrInspectionDataResultParentnotfin', $arrInspectionDataResultParentnotfin);


    }

    public function kensatyuichiran()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->query('s');
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $arrdata = explode("_",$data);
      if(isset($arrdata[2])){
        $mikan_check = $arrdata[0];
        $machine_num = $arrdata[1];
        $product_code = $arrdata[2];
      }else{
        $getData = $this->request->getData();
        $mikan_check = $getData["mikan_check"];
        $machine_num = $getData["machine_num"];
        $product_code = $getData["product_code"];
      }

      $check_num = 0;

      if(isset($getData["tuzukikara"])){//続きから登録を押したとき

        if(!isset($_SESSION)){
          session_start();
          }
          $session = $this->request->getSession();
  
          $_SESSION['tuzukikara'] = array();
          $_SESSION['tuzukikara']["check"] = 1;
          $_SESSION['tuzukikara']["data"] = $getData;

          return $this->redirect(['action' => 'addform']);
    
      }elseif(isset($getData["loss_flag"])){//異常登録の表示

        $ijou_nums = array_keys($getData, '異');
        $check_num = $ijou_nums[0];

      }

      if($check_num > 0){

        $InspectionDataResultParent_id = $getData["InspectionDataResultParent_id".$check_num];
        $InspectionDataResultParents_loss = $this->InspectionDataResultParents->find()
        ->where(['id' => $InspectionDataResultParent_id])->toArray();

        $loss_amount = $InspectionDataResultParents_loss[0]["loss_amount"];
        $loss_amount = sprintf("%.1f", $loss_amount);
        $bik = $InspectionDataResultParents_loss[0]["bik"];
        $this->set('loss_amount', $loss_amount);
        $this->set('bik', $bik);

      }
      $this->set('check_num', $check_num);
      $this->set('machine_num', $machine_num);
      $this->set('product_code', $product_code);
      $this->set('mikan_check', $mikan_check);

      $ProductParent = $this->Products->find()
      ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();
      $ProductLength = $this->Products->find()
      ->where(['product_code' => $product_code, 'delete_flag' => 0])->toArray();
      $Length = $ProductLength[0]['length'];
      $this->set('Length',$Length);
      $ig_bank_modes = $ProductParent[0]['ig_bank_modes'];
      if($ig_bank_modes == 0){
        $mode = "0（X-Y）";
      }else{
        $mode = "0（Y-Y）";
      }
      $this->set('mode',$mode);

      $datetimesta = date('Y-m-d 00:00:00');

      $htmlproductcheck = new htmlproductcheck();//クラスを使用
      $arrayproductdate = $htmlproductcheck->productcheckprogram($product_code);//クラスを使用

      $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
      $htmlkensahyouheader = $htmlkensahyoukadoumenu->kensahyouheader($product_code);
    	$this->set('htmlkensahyouheader',$htmlkensahyouheader);

      $product_code_ini = substr($product_code, 0, 11);
      $this->set('product_code_ini', $product_code_ini);

      $product_code_ini = substr($product_code, 0, 11);
      $InspectionStandardSizeChildren= $this->InspectionStandardSizeChildren->find()
      ->contain(['InspectionStandardSizeParents' => ["Products"]])
      ->where(['product_code like' => $product_code_ini.'%',
      'InspectionStandardSizeParents.is_active' => 0,
      'InspectionStandardSizeParents.delete_flag' => 0,
      'InspectionStandardSizeChildren.delete_flag' => 0])
      ->order(["InspectionStandardSizeParents.version"=>"DESC"])->toArray();

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

        for($i=0; $i<count($InspectionStandardSizeChildren); $i++){

          $num = $InspectionStandardSizeChildren[$i]["size_number"];
          ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
          ${"input_type".$num} = $InspectionStandardSizeChildren[$i]["input_type"];
          $this->set('input_type'.$num,${"input_type".$num});

          if(${"size_name".$num} === "長さ"){

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = "-";
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = "-";
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = "-";
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = "※製品規格参照";
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
    
          }elseif($InspectionStandardSizeChildren[$i]["input_type"] === "judge"){

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
  
          }else{

            ${"size_name".$num} = $InspectionStandardSizeChildren[$i]["size_name"];
            $this->set('size_name'.$num,${"size_name".$num});
            ${"upper_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["upper_limit"]);
            $this->set('upper_limit'.$num,${"upper_limit".$num});
            ${"lower_limit".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["lower_limit"]);
            $this->set('lower_limit'.$num,${"lower_limit".$num});
            ${"size".$num} = sprintf("%.1f", $InspectionStandardSizeChildren[$i]["size"]);
            $this->set('size'.$num,${"size".$num});
            ${"measuring_instrument".$num} = $InspectionStandardSizeChildren[$i]["measuring_instrument"];
            $this->set('measuring_instrument'.$num,${"measuring_instrument".$num});
  
          }
    
        }
        
        $this->set('InspectionStandardSizeChildren', $InspectionStandardSizeChildren);

      }

      
      if($mikan_check == 0){//本日

        $date1 = strtotime(date("Y-m-d"));
        $date_sta = date("Y-m-d")." 00:00:00";
        $date_fin = date('Y-m-d', strtotime('+1 day', $date1))." 00:00:00";
        $datetimefin = $date_fin;

        $this->set('date_sta', $date_sta);
        $this->set('date_fin', $date_fin);

        $machine_datetime_product = $machine_num."_".substr($date_sta, 0, 10)."_00:00:00_".$product_code;

        $htmlkensahyougenryouheader = new htmlkensahyouprogram();
        $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheaderkensaku($machine_datetime_product);
        $this->set('htmlgenryouheader',$htmlgenryouheader);

        $product_code_ini = substr($product_code, 0, 11);
        $InspectionDataResultParents = $this->InspectionDataResultParents->find()
        ->contain(['ProductConditionParents', 'InspectionStandardSizeParents', 'Products'])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.delete_flag' => 0,
         'InspectionDataResultParents.delete_flag' => 0,
        'datetime >=' => $datetimesta])
        ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();
  
        $gyou = count($InspectionDataResultParents);
        $this->set('gyou', $gyou);
  
        $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()
        ->contain(['InspectionDataResultParents' => ['Products']])
        ->where(['product_code like' => $product_code_ini.'%', 'InspectionDataResultChildren.delete_flag' => 0,
        'datetime >=' => $datetimesta])
        ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();
  
      }else{//昨日以前の場合

        $datetime_target = $arrdata[3];
  
        $date1 = strtotime($datetime_target);
        $date_sta = $datetime_target." 00:00:00";
        $date_fin = date('Y-m-d', strtotime('+1 day', $date1))." 00:00:00";

        $product_code_ini = substr($product_code, 0, 11);
        $InspectionDataResultParents = $this->InspectionDataResultParents->find()
        ->contain(['ProductConditionParents', 'InspectionStandardSizeParents', 'Products'])
        ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%',
         'InspectionStandardSizeParents.delete_flag' => 0,
        'kanryou_flag IS' => NULL,
        'datetime >=' => $date_sta, 'datetime <' => $date_fin,
        'InspectionDataResultParents.delete_flag' => 0])
        ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();
  
        $gyou = count($InspectionDataResultParents);
        $this->set('gyou', $gyou);
  
        $datetimestaresult = $InspectionDataResultParents[0]["created_at"]->format("Y-m-d H:i:s");

        $date1 = strtotime($InspectionDataResultParents[0]["created_at"]->format("Y-m-d"));
        $date_sta = $InspectionDataResultParents[0]["created_at"]->format("Y-m-d")." 00:00:00";

        $this->set('date_sta', $date_sta);
        $this->set('date_fin', $date_fin);

        $machine_datetime_product = $machine_num."_".substr($date_sta, 0, 10)."_00:00:00_".$product_code;

        $htmlkensahyougenryouheader = new htmlkensahyouprogram();
        $htmlgenryouheader = $htmlkensahyougenryouheader->genryouheaderkensaku($machine_datetime_product);
        $this->set('htmlgenryouheader',$htmlgenryouheader);
  
        $datetimefin = $InspectionDataResultParents[$gyou - 1]["datetime"]->format("Y-m-d H:i:s");

        $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()
        ->contain(['InspectionDataResultParents' => ['Products']])
        ->where(['product_code like' => $product_code_ini.'%', 
        'kanryou_flag IS' => NULL,
        'InspectionDataResultChildren.delete_flag' => 0])
        ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();
  
      }

      for($j=0; $j<count($InspectionDataResultParents); $j++){

        $n = $j + 1;

        $InspectionDataResultChildren = $this->InspectionDataResultChildren->find()
        ->contain(['InspectionStandardSizeChildren'])
        ->where(['inspection_data_result_parent_id' => $InspectionDataResultParents[$j]["id"],
         'InspectionDataResultChildren.delete_flag' => 0])
        ->toArray();

        ${"InspectionDataResultParent_id".$n} = $InspectionDataResultParents[$j]['id'];
        $this->set('InspectionDataResultParent_id'.$n,${"InspectionDataResultParent_id".$n});
        ${"length".$n} = $InspectionDataResultParents[$j]["product"]['length'];
        $this->set('length'.$n,${"length".$n});
        ${"product_id".$n} = $InspectionDataResultParents[$j]["product"]['id'];
        $this->set('product_id'.$n,${"product_id".$n});
        ${"lot_number".$n} = $InspectionDataResultParents[$j]['lot_number'];
        $this->set('lot_number'.$n,${"lot_number".$n});
        ${"datetime".$n} = $InspectionDataResultParents[$j]['datetime']->format('Y-n-j G:i');
        $this->set('datetime'.$n,${"datetime".$n});
        ${"datetime_h_i".$n} = $InspectionDataResultParents[$j]['datetime']->format('H:i');
        $this->set('datetime_h_i'.$n,${"datetime_h_i".$n});

        if($InspectionDataResultParents[$j]["loss_amount"] > 0){

          ${"loss".$n} = $n;
          $this->set('loss'.$n,${"loss".$n});
          ${"loss_check".$n} = 1;
          $this->set('loss_check'.$n,${"loss_check".$n});

        }else{

          ${"loss_check".$n} = 0;
          $this->set('loss_check'.$n,${"loss_check".$n});

        }

        $Staffs = $this->Staffs->find()
        ->where(['id' => $InspectionDataResultParents[$j]['staff_id'], 'delete_flag' => 0])->order(["id"=>"ASC"])->toArray();
        ${"staff_hyouji".$n} = $Staffs[0]['name'];
        $this->set('staff_hyouji'.$n,${"staff_hyouji".$n});

        $Users = $this->Users->find()
        ->where(['staff_id' => $InspectionDataResultParents[$j]['staff_id'], 'delete_flag' => 0])->toArray();
        ${"user_code".$n} = $Users[0]['user_code'];
        $this->set('user_code'.$n,${"user_code".$n});

        ${"appearance".$n} = $InspectionDataResultParents[$j]['appearance'];
        $this->set('appearance'.$n,${"appearance".$n});
        ${"result_weight".$n} = sprintf("%.1f", $InspectionDataResultParents[$j]['result_weight']);
        $this->set('result_weight'.$n,${"result_weight".$n});
        ${"judge".$n} = $InspectionDataResultParents[$j]['judge'];
        $this->set('judge'.$n,${"judge".$n});

        for($i=1; $i<=11; $i++){

          ${"result_size".$n.$i} = "";
          $this->set("result_size".$n."_".$i,${"result_size".$n.$i});

        }

        for($i=0; $i<count($InspectionDataResultChildren); $i++){

          $size_number = $InspectionDataResultChildren[$i]['inspection_standard_size_child']['size_number'];
    
          ${"result_size".$n.$size_number} = sprintf("%.1f", $InspectionDataResultChildren[$i]["result_size"]);
          $this->set("result_size".$n."_".$size_number,${"result_size".$n.$size_number});

        }

      }

      $machine_product_datetime = $machine_num."_".$product_code."_".$date_fin;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();//クラスを使用
      $htmlseikeijouken = $htmlkensahyougenryouheader->seikeijouken($machine_product_datetime);//クラスを使用
      $this->set('htmlseikeijouken', $htmlseikeijouken);

      $product_code_ini = substr($product_code, 0, 11);
      $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      $product_condition_parent_id = $ProductConditionParents[0]["id"];
      $this->set('product_condition_parent_id', $product_condition_parent_id);

      $version = $ProductConditionParents[0]["version"];

      $product_code_ini = substr($product_code, 0, 11);
      $ProductMaterialMachines= $this->ProductMaterialMachines->find()
      ->contain(['ProductConditionParents' => ["Products"]])
      ->where(['Products.product_code like' => $product_code_ini.'%',
      'ProductConditionParents.delete_flag' => 0,
      'ProductMaterialMachines.delete_flag' => 0,
      'ProductConditionParents.machine_num' => $machine_num,
      'ProductConditionParents.version' => $version])
      ->order(["cylinder_number"=>"ASC"])->toArray();

      $countseikeiki = count($ProductMaterialMachines);
      $this->set('countseikeiki', $countseikeiki);

      for($k=0; $k<$countseikeiki; $k++){//基準値の呼び出し

        $j = $k + 1;

        ${"product_material_machine_id".$j} = $ProductMaterialMachines[$k]["id"];
        $this->set('product_material_machine_id'.$j, ${"product_material_machine_id".$j});
        ${"cylinder_name".$j} = $ProductMaterialMachines[$k]["cylinder_name"];
        $this->set('cylinder_name'.$j, ${"cylinder_name".$j});

        $ProductConditonChildren = $this->ProductConditonChildren->find()
        ->where(['product_material_machine_id' => ${"product_material_machine_id".$j},
         'cylinder_name' => ${"cylinder_name".$j}, 'delete_flag' => 0])
        ->toArray();

        if(isset($ProductConditonChildren[0])){

          ${"product_conditon_child_id".$j} = $ProductConditonChildren[0]["id"];
          $this->set('product_conditon_child_id'.$j, ${"product_conditon_child_id".$j});

        }

      }

      $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
     ->order(["version"=>"DESC"])->toArray();

      $inspection_standard_size_parent_id = $InspectionStandardSizeParents[0]['id'];
      $this->set('inspection_standard_size_parent_id', $inspection_standard_size_parent_id);

      $arrConditionids = array();
      for($j=0; $j<count($InspectionDataResultParents); $j++){
        $arrConditionids[] = $InspectionDataResultParents[$j]['inspection_data_conditon_parent_id'];
      }
      $arrConditionids = array_unique($arrConditionids);
      $arrConditionids = array_values($arrConditionids);

      $count_seikeijouken = count($arrConditionids);
      $this->set('count_seikeijouken', $count_seikeijouken);

      $inspection_data_conditon_parent_id = $arrConditionids[$count_seikeijouken - 1];
      $this->set('inspection_data_conditon_parent_id', $inspection_data_conditon_parent_id);

      if($count_seikeijouken > 1){
        $inspection_data_conditon_parent_id_moto = $arrConditionids[0];
        $this->set('inspection_data_conditon_parent_id_moto', $inspection_data_conditon_parent_id_moto);
      }

      echo "<pre>";//フォームの再読み込みの防止
      print_r("");
      echo "</pre>";

    }

}
