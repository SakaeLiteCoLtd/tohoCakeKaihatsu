<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;//ログインに使用
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション

use App\myClass\classprograms\htmlproductcheck;//myClassフォルダに配置したクラスを使用
$htmlproductcheck = new htmlproductcheck();

class ImagesController extends AppController
{

  public function beforeFilter(Event $event){
    parent::beforeFilter($event);

    // 認証なしでアクセスできるアクションの指定
    $this->Auth->allow(["index", "detail"]);
  }

      public function initialize()
    {
     parent::initialize();
     $this->InspectionStandardSizeParents = TableRegistry::get('InspectionStandardSizeParents');
     $this->Products = TableRegistry::get('Products');
     $this->Customers = TableRegistry::get('Customers');
     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

       return $this->redirect($this->Auth->logout());

     }

     if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

       $Groups = $this->Groups->find()->contain(["Menus"])
       ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "検査表画像", 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }

     }
     
    }

    public function index()
    {
    }

    public function ichiran($id = null)
    {
      if(strlen($id) > 0){
  
        $this->paginate = [
          'contain' => ['Products'],
          'order' => ['updated_at' => 'desc',
          'created_at' => 'desc']
    ];
      $inspectionStandardSizeParents = $this
      ->paginate($this->InspectionStandardSizeParents->find()->where(['InspectionStandardSizeParents.delete_flag' => 0]));
      $this->set(compact('inspectionStandardSizeParents'));

      }else{

        $this->paginate = [
          'contain' => ['Products']
      ];
      $inspectionStandardSizeParents = $this
      ->paginate($this->InspectionStandardSizeParents->find()->where(['InspectionStandardSizeParents.delete_flag' => 0]));
      $this->set(compact('inspectionStandardSizeParents'));

      }
    }

    public function detail($id = null)
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);

      $data = $this->request->getData();

      if(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['inspectionStandardSizeParentdata'] = array();
        $_SESSION['inspectionStandardSizeParentdata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }elseif(isset($data["edit"])){

          $id = $data["id"];
  
          if(!isset($_SESSION)){
            session_start();
          }
          $_SESSION['inspectionStandardSizeParentdata'] = array();
          $_SESSION['inspectionStandardSizeParentdata'] = $id;
  
          return $this->redirect(['action' => 'editform']);
  
        }elseif(isset($data["name"])){

        $product_name_length = explode(";",$data["name"]);
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

         if(!isset($Products[0])){

           return $this->redirect(['action' => 'kensakupreform',
           's' => ['mess' => "入力された製品名は登録されていません。確認してください。"]]);

         }else{

          $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()
          ->where(['product_id' => $Products[0]["id"], 'delete_flag' => 0])->toArray();

          if(isset($InspectionStandardSizeParents[0])){

            $id = $InspectionStandardSizeParents[0]["id"];

          }else{

            $product_code_ini = substr($Products[0]["product_code"], 0, 11);
            $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
            ->where(['product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.delete_flag' => 0])
            ->toArray();

            if(isset($InspectionStandardSizeParents[0])){
                        
              $id = $InspectionStandardSizeParents[0]["id"];

            }else{

              return $this->redirect(['action' => 'kensakupreform',
              's' => ['mess' => "入力された製品は画像登録されていません。"]]);

            }


          }


         }

      }
      $this->set('id', $id);

      $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['InspectionStandardSizeParents.id' => $id])->toArray();

      $name = $InspectionStandardSizeParents[0]["product"]["name"];
      $this->set('name', $name);
      $product_length = $InspectionStandardSizeParents[0]["product"]["length"];
      $this->set('product_length', $product_length);
      $product_code = $InspectionStandardSizeParents[0]["product"]["product_code"];
      $this->set('product_code', $product_code);
      $image_file_name_dir = $InspectionStandardSizeParents[0]["image_file_name_dir"];
      $this->set('image_file_name_dir', $image_file_name_dir);

    }

    public function addpre()
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);

      $data = $this->request->getData();
      $mess = "";
      $this->set('mess', $mess);

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

           $product_code_ini = substr($product_code, 0, 11);
           $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
           ->where(['product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
           ->order(["version"=>"DESC"])->toArray();

          if(isset($InspectionStandardSizeParents[0])){

            $mess = "入力された製品は既に画像登録済みです。情報を更新する場合は登録済みのデータを削除してから再度登録してください。";
            $this->set('mess',$mess);
 
            $Product_name_list = $this->Products->find()
            ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();
 
            $arrProduct_name_list = array();
            for($j=0; $j<count($Product_name_list); $j++){
              array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
            }
            $this->set('arrProduct_name_list', $arrProduct_name_list);
 
          }else{

            return $this->redirect(['action' => 'addform',
            's' => ['product_code' => $product_code]]);

           }

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

      if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');
  
      print_r(" ");

    }

    public function addform()
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);

      $mess = "";
      $this->set('mess',$mess);

      $Data = $this->request->query('s');

      if(isset($Data["product_code"])){

        $product_code = $Data["product_code"];
        $this->set('product_code', $product_code);

      }elseif(isset($Data["mess"])){

        $mess = $Data["mess"];
        $this->set('mess',$mess);

        $session = $this->request->getSession();
        $_SESSION = $session->read();
        $product_code = $_SESSION["img_product_code"];
        $_SESSION['img_product_code'] = array();
        $this->set('product_code', $product_code);

      }else{

        $session = $this->request->getSession();
        $_SESSION = $session->read();
        $product_code = $_SESSION["img_product_code"];
        $_SESSION['img_product_code'] = array();
        $this->set('product_code', $product_code);

      }

      $ProductNs = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $product_name = $ProductNs[0]["name"];
      $this->set('product_name',$product_name);

      $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code' => $product_code, 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();
      if(isset($InspectionStandardSizeParents[0])){
        $mes = $product_name." の検査表画像は既に登録されています。データを更新する場合はこのまま進んでください。";
        $this->set('mes',$mes);
      }else{
        $mes = "";
        $this->set('mes',$mes);
      }

      if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');
  
      print_r(" ");

    }

    public function addcomfirm()
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

        return $this->redirect(['action' => 'addform',
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

          return $this->redirect(['action' => 'addform',
          's' => ['mess' => "※画像サイズが大き過ぎます（アップロードできるサイズの上限は２MBです）"]]);

        }else{

          if(move_uploaded_file($_FILES['upfile']["tmp_name"],"img/kensahyouimg/".$_FILES['upfile']["name"])){

          }else{
      
            if(!isset($_SESSION)){
              session_start();
            }
            $_SESSION['img_product_code'] = array();
            $_SESSION['img_product_code'] = $product_code;
  
            return $this->redirect(['action' => 'addform',
            's' => ['mess' => "※ファイルが読み込まれませんでした。"]]);
      
          }

        }

      }elseif($_FILES['upfile']['error'] == 1){

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['img_product_code'] = array();
        $_SESSION['img_product_code'] = $product_code;

        return $this->redirect(['action' => 'addform',
        's' => ['mess' => "※画像サイズが大き過ぎます（アップロードできるサイズの上限は２MBです）"]]);
        
      }else{

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['img_product_code'] = array();
        $_SESSION['img_product_code'] = $product_code;

        return $this->redirect(['action' => 'addform',
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

        return $this->redirect(['action' => 'addform',
        's' => ['mess' => "※ファイル名に半角スペースが含まれています。ファイル名に半角スペースを使用しないでください。"]]);
        }
        
			$gif = "kensahyouimg/".$selectfilename;//ローカル
			$this->set('gif',$gif);

    }

    public function adddo()
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $data = $this->request->getData();

      $this->set('product_code', $data['product_code']);
      $this->set('gif', $data['gif']);

      $Products = $this->Products->find()
      ->where(['product_code' => $data['product_code']])->toArray();
      $product_id = $Products[0]['id'];
      $product_name = $Products[0]["name"];
      $this->set('product_name',$product_name);

      $InspectionStandardSizeParentversion = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code' => $data['product_code'], 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

      if(isset($InspectionStandardSizeParentversion[0])){
        $version = $InspectionStandardSizeParentversion[0]["version"] + 1;
      }else{
        $version = 1;
      }

      $code_date = date('y').date('m').date('d');
      $InspectionStandardSizeParentcodes = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code' => $data['product_code'], 'inspection_standard_size_code like' => $code_date."%"])
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
      //新しいデータを登録
      $InspectionStandardSizeParents = $this->InspectionStandardSizeParents
      ->patchEntity($this->InspectionStandardSizeParents->newEntity(), $arrtourokuinspectionStandardSizeParent);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4

        $this->InspectionStandardSizeParents->updateAll(
          [ 'is_active' => 1,
            'delete_flag' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_staff' => $staff_id],
          ['product_id'  => $product_id]);

        if ($this->InspectionStandardSizeParents->save($InspectionStandardSizeParents)) {

          $connection->commit();// コミット5
          $mes = "以下のように登録されました。";
          $this->set('mes',$mes);

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

    public function deleteconfirm($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['inspectionStandardSizeParentdata'];
      $this->set('id', $id);

      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['InspectionStandardSizeParents.id' => $id])
      ->toArray();
      $this->set(compact('inspectionStandardSizeParents'));

      $product_code = $inspectionStandardSizeParents[0]["product"]["product_code"];
      $this->set('product_code', $product_code);
      $name = $inspectionStandardSizeParents[0]["product"]["name"];
      $this->set('name', $name);
      $image_file_name_dir = $inspectionStandardSizeParents[0]["image_file_name_dir"];
      $this->set('image_file_name_dir', $image_file_name_dir);

    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $id = $data['id'];

      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['InspectionStandardSizeParents.id' => $id])
      ->toArray();
      $this->set(compact('inspectionStandardSizeParents'));

      $product_code = $inspectionStandardSizeParents[0]["product"]["product_code"];
      $this->set('product_code', $product_code);
      $image_file_name_dir = $inspectionStandardSizeParents[0]["image_file_name_dir"];
      $this->set('image_file_name_dir', $image_file_name_dir);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeleteinspectionStandardSizeParents = array();
      $arrdeleteinspectionStandardSizeParents = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeleteinspectionStandardSizeParents);
      echo "</pre>";
*/
      $InspectionStandardSizeParents = $this->InspectionStandardSizeParents
      ->patchEntity($this->InspectionStandardSizeParents->newEntity(), $arrdeleteinspectionStandardSizeParents);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->InspectionStandardSizeParents->updateAll(
           [ 'is_active' => 1,
             'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $data["id"]]
         )){

         $mes = "※削除されました。";
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

    public function kensakupreform()
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

      $Product_name_list = $this->Products->find()
      ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();

      $arrProduct_name_list = array();
      for($j=0; $j<count($Product_name_list); $j++){
        array_push($arrProduct_name_list,$Product_name_list[$j]["name"].";".$Product_name_list[$j]["length"]."mm");
      }
      $this->set('arrProduct_name_list', $arrProduct_name_list);
 }

    public function editform()
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);

      $session = $this->request->getSession();
      $_SESSION = $session->read();
/*
      echo "<pre>";
      print_r($_SESSION);
      echo "</pre>";
*/
      $id = $_SESSION['inspectionStandardSizeParentdata'];
      $this->set('id', $id);

      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['InspectionStandardSizeParents.id' => $id])
      ->toArray();
      $this->set(compact('inspectionStandardSizeParents'));

      $product_code = $inspectionStandardSizeParents[0]["product"]["product_code"];
      $this->set('product_code', $product_code);
      $product_name = $inspectionStandardSizeParents[0]["product"]["name"];
      $this->set('product_name', $product_name);
      
      $mes = "";
      $this->set('mes',$mes);
      $mess = "";
      $this->set('mess',$mess);

      $Data = $this->request->query('s');

      if(isset($Data["product_code"])){

        $product_code = $Data["product_code"];
        $this->set('product_code', $product_code);

      }elseif(isset($Data["mess"])){

        $mess = $Data["mess"];
        $this->set('mess',$mess);

        $session = $this->request->getSession();
        $_SESSION = $session->read();
        $product_code = $_SESSION["img_product_code_edit"];
        $this->set('product_code', $product_code);
        $id = $_SESSION["img_id_edit"];
        $this->set('id', $id);

      }else{

        $session = $this->request->getSession();
        $_SESSION = $session->read();
        $product_code = $_SESSION["img_product_code_edit"];
        $this->set('product_code', $product_code);
        $id = $_SESSION["img_id_edit"];
        $this->set('id', $id);

      }

      if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');

      print_r("");

    }

    public function editcomfirm()
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);

      $session = $this->request->getSession();
      $_SESSION = $session->read();
/*
      echo "<pre>";
      print_r($_SESSION);
      echo "</pre>";
*/
      $id = $_SESSION["inspectionStandardSizeParentdata"];
      $this->set('id', $id);
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['InspectionStandardSizeParents.id' => $id])
      ->toArray();
      $this->set(compact('inspectionStandardSizeParents'));

      $product_code = $inspectionStandardSizeParents[0]["product"]["product_code"];
      $this->set('product_code', $product_code);
      $product_name = $inspectionStandardSizeParents[0]["product"]["name"];
      $this->set('product_name', $product_name);

      $ProductNs = $this->Products->find()
      ->where(['product_code' => $product_code])->toArray();
      $product_name = $ProductNs[0]["name"];
      $this->set('product_name',$product_name);

      $data = $this->request->getData();

      $fileName = $_FILES['upfile']['tmp_name'];

      if(substr($_FILES['upfile']["name"], -4) !== ".JPG"
      && substr($_FILES['upfile']["name"], -4) !== ".jpg"
      && substr($_FILES['upfile']["name"], -5) !== ".JPEG"
      && substr($_FILES['upfile']["name"], -5) !== ".jpeg"){

        return $this->redirect(['action' => 'editform',
        's' => ['mess' => "※拡張子が「.JPG」でないファイルが選択されました。"]]);

      }
/*
      echo "<pre>";
      print_r($_FILES);
      echo "</pre>";
*/
      if($_FILES['upfile']['error'] == 0){

        if($_FILES['upfile']['size']>2000000){

          return $this->redirect(['action' => 'editform',
          's' => ['mess' => "※画像サイズが大き過ぎます（アップロードできるサイズの上限は２MBです）"]]);

        }else{

          if(move_uploaded_file($_FILES['upfile']["tmp_name"],"img/kensahyouimg/".$_FILES['upfile']["name"])){

          }else{
      
            return $this->redirect(['action' => 'editform',
            's' => ['mess' => "※ファイルが読み込まれませんでした。"]]);
      
          }

        }

      }elseif($_FILES['upfile']['error'] == 1){

        return $this->redirect(['action' => 'editform',
        's' => ['mess' => "※画像サイズが大き過ぎます（アップロードできるサイズの上限は２MBです）"]]);
        
      }else{

        return $this->redirect(['action' => 'editform',
        's' => ['mess' => "※ファイルが読み込まれませんでした。"]]);

      }

      $selectfilename = $_FILES['upfile']["name"];
      $filename = str_replace(' ', '_', $selectfilename);

      if($selectfilename !== $filename){//半角スペースが含まれている場合はNG

        return $this->redirect(['action' => 'editform',
        's' => ['mess' => "※ファイル名に半角スペースが含まれています。ファイル名に半角スペースを使用しないでください。"]]);
        }
        
			$gif = "kensahyouimg/".$selectfilename;//ローカル
			$this->set('gif',$gif);

    }

    public function editdo()
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $data = $this->request->getData();

      $id = $data['id'];
      $this->set('product_code', $data['product_code']);
      $this->set('gif', $data['gif']);

      $Products = $this->Products->find()
      ->where(['product_code' => $data['product_code']])->toArray();
      $product_id = $Products[0]['id'];
      $product_name = $Products[0]["name"];
      $this->set('product_name',$product_name);

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
      ->where(['id' => $data['id']])->toArray();

      $arrupdateInspectionStandardSizeParentsmoto = [
        'product_id' => $InspectionStandardSizeParentsmoto[0]["product_id"],
        'image_file_name_dir' => $InspectionStandardSizeParentsmoto[0]["image_file_name_dir"],
        'inspection_standard_size_code' => $InspectionStandardSizeParentsmoto[0]["inspection_standard_size_code"],
        'version' => $InspectionStandardSizeParentsmoto[0]["version"],
        'is_active' => 1,
        'delete_flag' => 1,
        'created_at' => $InspectionStandardSizeParentsmoto[0]["created_at"]->format("Y-m-d H:i:s"),
        'created_staff' => $InspectionStandardSizeParentsmoto[0]["created_staff"],
        'updated_at' => date("Y-m-d H:i:s"),
        'updated_staff' => $staff_id
          ];

      $code_date = date('y').date('m').date('d');
      $InspectionStandardSizeParentcodes = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code' => $data['product_code'], 'inspection_standard_size_code like' => $code_date."%"])
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

      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4

        if ($this->InspectionStandardSizeParents->updateAll(
          [ 'image_file_name_dir' => $arrtourokuinspectionStandardSizeParent["image_file_name_dir"],
          'inspection_standard_size_code' => $arrtourokuinspectionStandardSizeParent["inspection_standard_size_code"],
          'version' => $arrtourokuinspectionStandardSizeParent["version"],
          'updated_at' => date('Y-m-d H:i:s'),
            'updated_staff' => $staff_id],
          ['id'  => $id])){
      
          //新しいデータを登録
          $InspectionStandardSizeParents = $this->InspectionStandardSizeParents
          ->patchEntity($this->InspectionStandardSizeParents->newEntity(), $arrupdateInspectionStandardSizeParentsmoto);
          if ($this->InspectionStandardSizeParents->save($InspectionStandardSizeParents)){
        
          $connection->commit();// コミット5
          $mes = "以下のように更新されました。";
          $this->set('mes',$mes);

        } else {

          $mes = "※更新されませんでした";
          $this->set('mes',$mes);
          $this->Flash->error(__('The data could not be saved. Please, try again.'));
          throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

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

}
