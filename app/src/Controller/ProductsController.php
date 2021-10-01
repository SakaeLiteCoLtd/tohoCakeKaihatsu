<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\Auth\DefaultPasswordHasher;//パスワードの更新時のハッシュ化

class ProductsController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Customers = TableRegistry::get('Customers');
     $this->Factories = TableRegistry::get('Factories');
     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

       return $this->redirect($this->Auth->logout());

     }

     if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

       $Groups = $this->Groups->find()->contain(["Menus"])
       ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "製品・仕入品", 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }

     }

    }

    public function ichiran()
    {
        $this->paginate = [
          'limit' => 13,
          'contain' => ['Customers']
        ];
        $products = $this->paginate($this->Products->find()->where(['Products.delete_flag' => 0]));

        $this->set(compact('products'));
    }

    public function detail($id = null)
    {
      $products = $this->Products->newEntity();
      $this->set('products', $products);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['productdata'] = array();
        $_SESSION['productdata'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['productdata'] = array();
        $_SESSION['productdata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }
      $this->set('id', $id);

      $Products = $this->Products->find()->contain(["Factories", "Customers"])
      ->where(['Products.id' => $id])->toArray();

      $Factories = $this->Factories->find()
      ->where(['id' => $Products[0]["factory"]['id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $product_code_ini = substr($Products[0]["product_code"], 0, 11);

      $ProductName = $this->Products->find()
      ->where(['product_code like' => $product_code_ini.'%', 'delete_flag' => 0])->toArray();

      if(!isset($ProductName[0])){

        return $this->redirect(['action' => 'editpreform',
        's' => ['mess' => "自社工場：".$factory_name."、製品名：「".$data['name']."」の製品は存在しません。"]]);

      }
      $this->set('ProductName', $ProductName);

      $name = $Products[0]['name'];
      $this->set('name', $name);
      $tanni = $ProductName[0]["tanni"];
      $this->set('tanni', $tanni);
      $weight = $ProductName[0]["weight"];
      $this->set('weight', $weight);
      $status_kensahyou = $ProductName[0]["status_kensahyou"];
      $this->set('status_kensahyou', $status_kensahyou);
      $ig_bank_modes = $ProductName[0]["ig_bank_modes"];
      $this->set('ig_bank_modes', $ig_bank_modes);
      
      $Customers = $this->Customers->find()
      ->where(['id' => $ProductName[0]['customer_id']])->toArray();
      $customer_name = $Customers[0]["name"];
      $this->set('customer_name', $customer_name);

    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Customers']
        ];
        $products = $this->paginate($this->Products);

        $this->set(compact('products'));
    }

    public function view($id = null)
    {
        $product = $this->Products->get($id, [
            'contain' => ['Customers', 'PriceProducts', 'ProductMaterials']
        ]);

        $this->set('product', $product);
    }

    public function addform()
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

      $Factories = $this->Factories->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrFactories = array();
      foreach ($Factories as $value) {
        $arrFactories[] = array($value->id=>$value->name);
      }
      $this->set('arrFactories', $arrFactories);

      $Customer_name_list = $this->Customers->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrCustomer_name_list = array();
      for($j=0; $j<count($Customer_name_list); $j++){
        array_push($arrCustomer_name_list,$Customer_name_list[$j]["name"]);
      }
      $this->set('arrCustomer_name_list', $arrCustomer_name_list);

      $arrStatusKensahyou = ["0" => "表示", "1" => "非表示"];
      $this->set('arrStatusKensahyou', $arrStatusKensahyou);

      $arrTanni = ["" => "", "kg" => "kg", "枚" => "枚", "個" => "個"];
      $this->set('arrTanni', $arrTanni);

      $arrig_bank_modes = [
        "" => "",
        0 => 0,
        1 => 1,
        2 => 2,
        3 => 3,
      ];
      $this->set('arrig_bank_modes', $arrig_bank_modes);

      if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');

    }

    public function addformlength()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $name = $data["name"];
      $this->set('name', $name);
      $tanni = $data["tanni"];
      $this->set('tanni', $tanni);
      $weight = $data["weight"];
      $this->set('weight', $weight);

      $status_kensahyou = $data["status_kensahyou"];
      if($status_kensahyou > 0){//非表示の場合
        $status_kensahyou_flag = 0;
      }else{//非表示の場合
        $status_kensahyou_flag = 1;
      }
      $this->set('status_kensahyou_flag', $status_kensahyou_flag);

      $ProductName = $this->Products->find()
      ->where(['factory_id' => $data['factory_id'], 'name' => $name])->toArray();

      if(isset($ProductName[0])){

        return $this->redirect(['action' => 'addform',
        's' => ['mess' => "入力された品名は既に存在します。長さを追加する場合は「長さ追加」メニューから登録してください。"]]);

      }

      if(strpos($name,';') !== false){
        return $this->redirect(['action' => 'addform',
        's' => ['mess' => "品名に「;」（セミコロン）は使用できません。"]]);
      }

      $customer_name = $data["customer_name"];
      $this->set('customer_name', $customer_name);

      $CustomerName = $this->Customers->find()
      ->where(['name' => $customer_name])->toArray();

      if(!isset($CustomerName[0])){

        return $this->redirect(['action' => 'addform',
        's' => ['mess' => "入力された得意先名は存在しません。もう一度やり直してください。"]]);

      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      if(isset($data["tuika"])){//追加

        $tuikalength = $data["tuikalength"] + 1;
        $this->set('tuikalength', $tuikalength);

      }elseif(isset($data["kakuninn"])){//確認

        if(!isset($_SESSION)){
          session_start();
          header('Expires:-1');
          header('Cache-Control:');
          header('Pragma:');
        }
        
        $_SESSION['newproduct'] = array();
        $_SESSION['newproduct'] = $data;

        return $this->redirect(['action' => 'addcomfirm']);

      }else{//最初

        $tuikalength = 1;
        $this->set('tuikalength', $tuikalength);

      }

      if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');
  
      print_r("");

    }

    public function addcomfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $data = $_SESSION['newproduct'];

      $name = $data["name"];
      $this->set('name', $name);
      $tanni = $data["tanni"];
      $this->set('tanni', $tanni);
      $weight = $data["weight"];
      $this->set('weight', $weight);
      $customer_name = $data["customer_name"];
      $this->set('customer_name', $customer_name);
      $tuikalength = $data["tuikalength"];
      $this->set('tuikalength', $tuikalength);
      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);
      $status_kensahyou = $data["status_kensahyou"];
      $this->set('status_kensahyou', $status_kensahyou);
      $ig_bank_modes = $data["ig_bank_modes"];
      $this->set('ig_bank_modes', $ig_bank_modes);

      $status_kensahyou_flag = $data["status_kensahyou_flag"];
      $this->set('status_kensahyou_flag', $status_kensahyou_flag);

      for($j=1; $j<=$tuikalength; $j++){
        ${"length".$j} = $data["length".$j];
        $this->set('length'.$j, ${"length".$j});
        ${"length_cut".$j} = $data["length_cut".$j];
        $this->set('length_cut'.$j, ${"length_cut".$j});
        ${"length_upper_limit".$j} = $data["length_upper_limit".$j];
        $this->set('length_upper_limit'.$j, ${"length_upper_limit".$j});
        ${"length_lower_limit".$j} = $data["length_lower_limit".$j];
        $this->set('length_lower_limit'.$j, ${"length_lower_limit".$j});
        ${"bik".$j} = $data["bik".$j];
        $this->set('bik'.$j, ${"bik".$j});
      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');
  
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
      $name = $data["name"];
      $this->set('name', $name);
      $tanni = $data["tanni"];
      $this->set('tanni', $tanni);
      $weight = $data["weight"];
      $this->set('weight', $weight);
      $customer_name = $data["customer_name"];
      $this->set('customer_name', $customer_name);
      $ig_bank_modes = $data["ig_bank_modes"];
      $this->set('ig_bank_modes', $ig_bank_modes);

      $status_kensahyou_flag = $data["status_kensahyou_flag"];
      $this->set('status_kensahyou_flag', $status_kensahyou_flag);

      $CustomerName = $this->Customers->find()
      ->where(['name' => $customer_name])->toArray();
      $customer_id = $CustomerName[0]["id"];
      $customer_code = $CustomerName[0]["customer_code"];

      if($data['factory_id'] == 1){
        $code_factory = "D";
      }elseif($data['factory_id'] == 2){
        $code_factory = "I";
      }elseif($data['factory_id'] == 3){
        $code_factory = "B";
      }elseif($data['factory_id'] == 4){
        $code_factory = "K";
      }else{
        $code_factory = "H";
      }
      
      $tuikalength = $data["tuikalength"];
      $this->set('tuikalength', $tuikalength);

      for($j=1; $j<=$tuikalength; $j++){
        ${"length".$j} = $data["length".$j];
        $this->set('length'.$j, ${"length".$j});
        ${"length_cut".$j} = $data["length_cut".$j];
        $this->set('length_cut'.$j, ${"length_cut".$j});
        ${"length_upper_limit".$j} = $data["length_upper_limit".$j];
        $this->set('length_upper_limit'.$j, ${"length_upper_limit".$j});
        ${"length_lower_limit".$j} = $data["length_lower_limit".$j];
        $this->set('length_lower_limit'.$j, ${"length_lower_limit".$j});
        ${"bik".$j} = $data["bik".$j];
        $this->set('bik'.$j, ${"bik".$j});
      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      //新しいデータを登録
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4

        $Productnow = $this->Products->find()
        ->where(['product_code like' => $customer_code.$code_factory.'%'])
        ->order(["product_code"=>"DESC"])->toArray();
  
        if(isset($Productnow[0])){
          $prodct_renban = substr($Productnow[0]["product_code"], -6, 4) + 1;
          $prodct_renban = sprintf('%04d', $prodct_renban);//0埋め
        }else{
          $prodct_renban = "0001";
        }
  
        $arrtourokuproduct = array();
        for($j=1; $j<=$tuikalength; $j++){
  
          ${"length".$j} = $data["length".$j];
          $color_renban = sprintf('%02d', $j);
  
          ${"product_code".$j} = $customer_code.$code_factory.$prodct_renban.$color_renban;
          $this->set('product_code'.$j, ${"product_code".$j});
  
          $arrtourokuproduct[] = [
            'factory_id' => $data["factory_id"],
            'product_code' => $customer_code.$code_factory.$prodct_renban.$color_renban,
            'name' => $data["name"],
            'tanni' => $data["tanni"],
            'weight' => $data["weight"],
            'ig_bank_modes' => $data["ig_bank_modes"],
            'length' => ${"length".$j},
            'length_cut' => ${"length_cut".$j},
            'length_upper_limit' => ${"length_upper_limit".$j},
            'length_lower_limit' => ${"length_lower_limit".$j},
            'bik' => ${"bik".$j},
            'customer_id' => $customer_id,
            'status_kensahyou' => $data["status_kensahyou"],
            'is_active' => 0,
            'delete_flag' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'created_staff' => $staff_id
          ];
  
        }
  /*
        echo "<pre>";
        print_r($arrtourokuproduct);
        echo "</pre>";
  */
        $Products = $this->Products->patchEntities($this->Products->newEntity(), $arrtourokuproduct);
        if ($this->Products->saveMany($Products)) {

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

    public function editlengthpreform()
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

      $Factories = $this->Factories->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrFactories = array();
      foreach ($Factories as $value) {
        $arrFactories[] = array($value->id=>$value->name);
      }
      $this->set('arrFactories', $arrFactories);

      $Product_name_list = $this->Products->find()
      ->where(['delete_flag' => 0])->toArray();

      $arrProduct_name_list = array();
      for($j=0; $j<count($Product_name_list); $j++){
        array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
      }
      $arrProduct_name_list = array_unique($arrProduct_name_list);
      $arrProduct_name_list = array_values($arrProduct_name_list);
      $this->set('arrProduct_name_list', $arrProduct_name_list);
    }
    
    public function editlengthform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $ProductName = $this->Products->find()
      ->where(['factory_id' => $data['factory_id'], 'name' => $data['name']])->toArray();

      if(!isset($ProductName[0])){

        return $this->redirect(['action' => 'editlengthpreform',
        's' => ['mess' => "自社工場：".$factory_name."、製品名：「".$data['name']."」の製品は存在しません。"]]);

      }

      $product_code_ini = substr($ProductName[0]["product_code"], 0, 11);

      $ProductName = $this->Products->find()
      ->where(['product_code like' => $product_code_ini.'%', 'delete_flag' => 0])->toArray();

      $this->set('ProductName', $ProductName);

      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);
      $name = $data["name"];
      $this->set('name', $name);
      
      if(isset($data["tuika"])){//追加

        $tuikalength = $data["tuikalength"] + 1;
        $this->set('tuikalength', $tuikalength);

      }elseif(isset($data["kakuninn"])){//確認

        if(!isset($_SESSION)){
          session_start();
          header('Expires:-1');
          header('Cache-Control:');
          header('Pragma:');
        }
        
        $_SESSION['editlengthproduct'] = array();
        $_SESSION['editlengthproduct'] = $data;

        return $this->redirect(['action' => 'editlengthcomfirm']);

      }else{//最初

        $tuikalength = 1;
        $this->set('tuikalength', $tuikalength);

      }

    }

    public function editlengthcomfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $data = $_SESSION['editlengthproduct'];
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $name = $data["name"];
      $this->set('name', $name);
      $tuikalength = $data["tuikalength"];
      $this->set('tuikalength', $tuikalength);
      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);

      $ProductName = $this->Products->find()
      ->where(['factory_id' => $data['factory_id'], 'name' => $data['name']])->toArray();
      $this->set('ProductName', $ProductName);

      $product_code_ini = substr($ProductName[0]["product_code"], 0, 11);

      $ProductName = $this->Products->find()
      ->where(['product_code like' => $product_code_ini.'%', 'delete_flag' => 0])->toArray();

      $this->set('ProductName', $ProductName);

      for($j=1; $j<=$tuikalength; $j++){
        ${"length".$j} = $data["length".$j];
        $this->set('length'.$j, ${"length".$j});
        ${"length_cut".$j} = $data["length_cut".$j];
        $this->set('length_cut'.$j, ${"length_cut".$j});
        ${"length_upper_limit".$j} = $data["length_upper_limit".$j];
        $this->set('length_upper_limit'.$j, ${"length_upper_limit".$j});
        ${"length_lower_limit".$j} = $data["length_lower_limit".$j];
        $this->set('length_lower_limit'.$j, ${"length_lower_limit".$j});
        ${"bik".$j} = $data["bik".$j];
        $this->set('bik'.$j, ${"bik".$j});
      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);
    }

    public function editlengthdo()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $name = $data["name"];
      $this->set('name', $name);
      $tuikalength = $data["tuikalength"];
      $this->set('tuikalength', $tuikalength);
      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);

      $ProductName = $this->Products->find()
      ->where(['factory_id' => $data['factory_id'], 'name' => $data['name']])->toArray();
      $this->set('ProductName', $ProductName);

      $product_code_moto = substr($ProductName[0]["product_code"], 0, 11);

      $product_code_ini = substr($ProductName[0]["product_code"], 0, 11);

      $status_kensahyou = $ProductName[0]["status_kensahyou"];

      $ProductName = $this->Products->find()
      ->where(['product_code like' => $product_code_ini.'%', 'delete_flag' => 0])->toArray();

      $this->set('ProductName', $ProductName);

      $Productnow = $this->Products->find()
      ->where(['product_code like' => $product_code_moto.'%'])
      ->order(["product_code"=>"DESC"])->toArray();
      $product_color_renban = substr($Productnow[0]["product_code"], -2, 2);
 
      $tuikalength = $data["tuikalength"];
      $this->set('tuikalength', $tuikalength);

      for($j=1; $j<=$tuikalength; $j++){
        ${"length".$j} = $data["length".$j];
        $this->set('length'.$j, ${"length".$j});
        ${"length_cut".$j} = $data["length_cut".$j];
        $this->set('length_cut'.$j, ${"length_cut".$j});
        ${"length_upper_limit".$j} = $data["length_upper_limit".$j];
        $this->set('length_upper_limit'.$j, ${"length_upper_limit".$j});
        ${"length_lower_limit".$j} = $data["length_lower_limit".$j];
        $this->set('length_lower_limit'.$j, ${"length_lower_limit".$j});
        ${"bik".$j} = $data["bik".$j];
        $this->set('bik'.$j, ${"bik".$j});
      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];
/*
      echo "<pre>";
      print_r($arrtourokuproduct);
      echo "</pre>";
*/
      //新しいデータを登録
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4

        $arrtourokuproduct = array();
        for($j=1; $j<=$tuikalength; $j++){
  
          ${"length".$j} = $data["length".$j];
          ${"length_cut".$j} = $data["length_cut".$j];
          $color_renban = sprintf('%02d', $product_color_renban + $j);
  
          $arrtourokuproduct[] = [
            'factory_id' => $data["factory_id"],
            'product_code' => $product_code_moto.$color_renban,
            'name' => $data["name"],
            'tanni' => $ProductName[0]["tanni"],
            'length' => ${"length".$j},
            'length_cut' => ${"length_cut".$j},
            'length_upper_limit' => ${"length_upper_limit".$j},
            'length_lower_limit' => ${"length_lower_limit".$j},
            'bik' => ${"bik".$j},
            'customer_id' => $ProductName[0]["customer_id"],
            'status_kensahyou' => $status_kensahyou,
            'is_active' => 0,
            'delete_flag' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'created_staff' => $staff_id
          ];
  
        }
  
        $Products = $this->Products->patchEntities($this->Products->newEntity(), $arrtourokuproduct);
        if ($this->Products->saveMany($Products)) {

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
    
    public function editpreform()
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

      $Factories = $this->Factories->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrFactories = array();
      foreach ($Factories as $value) {
        $arrFactories[] = array($value->id=>$value->name);
      }
      $this->set('arrFactories', $arrFactories);

      $Product_name_list = $this->Products->find()
      ->where(['delete_flag' => 0])->toArray();

      $arrProduct_name_list = array();
      for($j=0; $j<count($Product_name_list); $j++){
        array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
      }
      $arrProduct_name_list = array_unique($arrProduct_name_list);
      $arrProduct_name_list = array_values($arrProduct_name_list);

      $this->set('arrProduct_name_list', $arrProduct_name_list);
    }

    public function editsyousai()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);

        $session = $this->request->getSession();
        $_SESSION = $session->read();
  
        $data = $_SESSION['editproductcheck'];
  
      }else{
        $mess = "";
        $this->set('mess',$mess);
        $data = $this->request->getData();
      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $ProductName = $this->Products->find()
      ->where(['factory_id' => $data['factory_id'], 'name' => $data['name']])->toArray();

      if(!isset($ProductName[0])){

        return $this->redirect(['action' => 'editpreform',
        's' => ['mess' => "自社工場：".$factory_name."、製品名：「".$data['name']."」の製品は存在しません。"]]);

      }

      $product_code_ini = substr($ProductName[0]["product_code"], 0, 11);

      $ProductName = $this->Products->find()
      ->where(['product_code like' => $product_code_ini.'%', 'delete_flag' => 0])->toArray();

      $this->set('ProductName', $ProductName);

      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);
      $name = $data["name"];
      $this->set('name', $name);
      $tanni = $ProductName[0]["tanni"];
      $this->set('tanni', $tanni);
      $weight = $ProductName[0]["weight"];
      $this->set('weight', $weight);
      $status_kensahyou = $ProductName[0]["status_kensahyou"];
      $this->set('status_kensahyou', $status_kensahyou);
      $ig_bank_modes = $ProductName[0]["ig_bank_modes"];
      $this->set('ig_bank_modes', $ig_bank_modes);
      
      $status_kensahyou = $ProductName[0]["status_kensahyou"];
      if($status_kensahyou == 0){
        $status_kensahyou_name = "表示";
      }else{
        $status_kensahyou_name = "非表示";
      }
      $this->set('status_kensahyou_name', $status_kensahyou_name);

      $Customers = $this->Customers->find()
      ->where(['id' => $ProductName[0]['customer_id']])->toArray();
      $customer_name = $Customers[0]["name"];
      $this->set('customer_name', $customer_name);

    }
    
    public function editform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);

        $session = $this->request->getSession();
        $_SESSION = $session->read();
  
        $data = $_SESSION['editproductcheck'];
  
      }else{
        $mess = "";
        $this->set('mess',$mess);
        $data = $this->request->getData();
      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $ProductName = $this->Products->find()
      ->where(['factory_id' => $data['factory_id'], 'name' => $data['name']])->toArray();

      if(!isset($ProductName[0])){

        return $this->redirect(['action' => 'editpreform',
        's' => ['mess' => "自社工場：".$factory_name."、製品名：「".$data['name']."」の製品は存在しません。"]]);

      }

      $product_code_ini = substr($ProductName[0]["product_code"], 0, 11);

      $ProductName = $this->Products->find()
      ->where(['product_code like' => $product_code_ini.'%', 'delete_flag' => 0])->toArray();

      $this->set('ProductName', $ProductName);

      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);
      $name = $data["name"];
      $this->set('name', $name);
      $tanni = $ProductName[0]["tanni"];
      $this->set('tanni', $tanni);
      $weight = $ProductName[0]["weight"];
      $this->set('weight', $weight);
      $status_kensahyou = $ProductName[0]["status_kensahyou"];
      $this->set('status_kensahyou', $status_kensahyou);
      $ig_bank_modes = $ProductName[0]["ig_bank_modes"];
      $this->set('ig_bank_modes', $ig_bank_modes);

      $arrTanni = ["" => "", "kg" => "kg", "枚" => "枚", "個" => "個"];
      $this->set('arrTanni', $arrTanni);

      $arrig_bank_modes = [
        "" => "",
        0 => 0,
        1 => 1,
        2 => 2,
        3 => 3,
      ];
      $this->set('arrig_bank_modes', $arrig_bank_modes);

      $Customers = $this->Customers->find()
      ->where(['id' => $ProductName[0]['customer_id']])->toArray();
      $customer_name = $Customers[0]["name"];
      $this->set('customer_name', $customer_name);

      $arrStatusKensahyou = ["0" => "表示", "1" => "非表示"];
      $this->set('arrStatusKensahyou', $arrStatusKensahyou);

      if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');

      print_r("");

    }

    public function editconfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $ProductName = $this->Products->find()
      ->where(['factory_id' => $data['factory_id'], 'name' => $data['namemoto']])->toArray();
      $this->set('ProductName', $ProductName);

      $arrKoushinproduct = array();
      $arrDeleteproduct = array();
      for($i=0; $i<=$data["num"]; $i++){

        if($data["delete".$i] < 1){//登録する場合

          if(strpos($data["name".$i],';') !== false){

            if(!isset($_SESSION)){
              session_start();
              header('Expires:-1');
              header('Cache-Control:');
              header('Pragma:');
            }
            
            $_SESSION['editproductcheck'] = array();
            $_SESSION['editproductcheck'] = ["factory_id" => $data['factory_id'], "name" => $data['namemoto']];
      
            return $this->redirect(['action' => 'editform',
            's' => ['mess' => "品名に「;」（セミコロン）は使用できません。"]]);
          }
    
          $arrKoushinproduct[] = ["product_code" => $data["product_code".$i],
           "name" => $data["name".$i],
            "length" => $data["length".$i],
            "length_cut" => $data["length_cut".$i],
            "length_upper_limit" => $data["length_upper_limit".$i],
            "length_lower_limit" => $data["length_lower_limit".$i],
            "bik" => $data["bik".$i]
          ];

            }else{

              $arrDeleteproduct[] = ["product_code" => $data["product_code".$i],
               "name" => $data["name".$i],
                "length" => $data["length".$i],
                "length_cut" => $data["length_cut".$i],
                "length_upper_limit" => $data["length_upper_limit".$i],
                "length_lower_limit" => $data["length_lower_limit".$i],
                "bik" => $data["bik".$i]
                  ];

            }

          }
          $this->set('arrKoushinproduct', $arrKoushinproduct);
          $this->set('arrDeleteproduct', $arrDeleteproduct);
    
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
      $ProductName = $this->Products->find()
      ->where(['factory_id' => $data['factory_id'], 'name' => $data['namemoto']])->toArray();
      $this->set('ProductName', $ProductName);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdateproduct = array();
      $arrupdateproductmoto = array();
      if(isset($data["num"])){
        for($i=0; $i<=$data["num"]; $i++){

          $Productsmoto = $this->Products->find()
          ->where(['product_code' => $data['product_code'.$i]])->toArray();
    
          $arrupdateproductmoto = array();
          $arrupdateproductmoto[] = [
            'factory_id' => $Productsmoto[0]["factory_id"],
            'product_code' => $Productsmoto[0]["product_code"],
            'length' => $Productsmoto[0]["length"],
            'length_cut' => $Productsmoto[0]["length_cut"],
            'length_upper_limit' => $Productsmoto[0]["length_upper_limit"],
            'length_lower_limit' => $Productsmoto[0]["length_lower_limit"],
            'ig_bank_modes' => $Productsmoto[0]["ig_bank_modes"],
            'bik' => $Productsmoto[0]["bik"],
            'name' => $Productsmoto[0]["name"],
            'tanni' => $Productsmoto[0]["tanni"],
            'weight' => $Productsmoto[0]["weight"],
            'status_kensahyou' => $Productsmoto[0]["status_kensahyou"],
            'customer_id' => $Productsmoto[0]["customer_id"],
            'is_active' => 1,
            'delete_flag' => 1,
            'created_at' => $Productsmoto[0]["created_at"]->format("Y-m-d H:i:s"),
            'created_staff' => $Productsmoto[0]["created_staff"],
            'updated_at' => date("Y-m-d H:i:s"),
            'updated_staff' => $staff_id
              ];

          $arrupdateproduct[] = [
            'product_code' => $data["product_code".$i],
            'length' => $data["length".$i],
            'length_cut' => $data["length_cut".$i],
            'length_upper_limit' => $data["length_upper_limit".$i],
            'length_lower_limit' => $data["length_lower_limit".$i],
            'bik' => $data["bik".$i],
            'name' => $data["name".$i],
            'tanni' => $data["tanni"],
            'weight' => $data["weight"],
            'status_kensahyou' => $data["status_kensahyou"],
            'updated_at' => date("Y-m-d H:i:s"),
            'updated_staff' => $staff_id
          ];
  
          }
  
        }
        $this->set('arrupdateproduct', $arrupdateproduct);

        $arrdeleteproduct = array();
        if(isset($data["delete_num"])){
          for($i=0; $i<=$data["delete_num"]; $i++){
    
            $arrdeleteproduct[] = [
              'product_code' => $data["delete_product_code".$i],
              'name' => $data["delete_name".$i],
              'length' => $data["delete_length".$i],
              'length_cut' => $data["delete_length_cut".$i],
              'length_upper_limit' => $data["delete_length_upper_limit".$i],
              'length_lower_limit' => $data["delete_length_lower_limit".$i],
              'bik' => $data["delete_bik".$i],
              'delete_flag' => 1,
              'updated_at' => date("Y-m-d H:i:s"),
              'updated_staff' => $staff_id
            ];
    
            }
        }
        $this->set('arrdeleteproduct', $arrdeleteproduct);

      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4

        $Products = $this->Products->patchEntities($this->Products->newEntity(), $arrupdateproductmoto);
        $this->Products->saveMany($Products);

          $count_update = 0;
          for($i=0; $i<count($arrupdateproduct); $i++){

            $ProductsId = $this->Products->find()
            ->where(['product_code' => $data['product_code'.$i], 'delete_flag' => 0])
            ->toArray();

            if ($this->Products->updateAll(
              [ 'length' => $data["length".$i],
                'length_cut' => $data["length_cut".$i],
                'length_upper_limit' => $data["length_upper_limit".$i],
                'length_lower_limit' => $data["length_lower_limit".$i],
                'bik' => $data["bik".$i],
                'name' => $data["name".$i],
                'tanni' => $data["tanni"],
                'weight' => $data["weight"],
                'status_kensahyou' => $data["status_kensahyou"],
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_staff' => $staff_id],
                ['id'  => $ProductsId[0]["id"]])){
                }
                $count_update = $count_update + 1;
            }
  
            for($i=0; $i<count($arrdeleteproduct); $i++){
              if ($this->Products->updateAll(
                [ 'delete_flag' => 1,
                  'updated_at' => date('Y-m-d H:i:s'),
                  'updated_staff' => $staff_id],
                  ['product_code'  => $data["delete_product_code".$i]])){
                  }
                  $count_update = $count_update + 1;
                }
    
         if ($count_update == count($arrupdateproduct) + count($arrdeleteproduct)) {//全部の登録ができた場合

         $mes = "※下記のように更新されました";
         $this->set('mes',$mes);
         $connection->commit();// コミット5

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

    public function deleteconfirm($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['productdata'];

        $product = $this->Products->get($id, [
          'contain' => ['Customers', 'PriceProducts', 'ProductMaterials']
        ]);
        $this->set(compact('product'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $product = $this->Products->get($data["id"], [
        'contain' => ['Customers', 'PriceProducts', 'ProductMaterials']
      ]);
      $this->set(compact('product'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeleteproduct = array();
      $arrdeleteproduct = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeleteproduct);
      echo "</pre>";
*/
      $Products = $this->Products->patchEntity($this->Products->newEntity(), $arrdeleteproduct);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Products->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeleteproduct['id']]
         )){

         $mes = "※以下のデータが削除されました。";
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
      ->where(['delete_flag' => 0])->toArray();

      $arrProduct_name_list = array();
      for($j=0; $j<count($Product_name_list); $j++){
        array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
      }
      $arrProduct_name_list = array_unique($arrProduct_name_list);
      $arrProduct_name_list = array_values($arrProduct_name_list);

      $this->set('arrProduct_name_list', $arrProduct_name_list);
    }

    public function kensakuichiran()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();

      $Products = $this->Products->find()->contain(["Factories"])
      ->where(['Products.name like' => "%".$data["name"]."%", 'Products.delete_flag' => 0])->toArray();
      $this->set('Products', $Products);

    }

}
