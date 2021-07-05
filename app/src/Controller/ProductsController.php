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
       ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "製品・原料関係", 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }

     }

    }

    public function ichiran()
    {
        $this->paginate = [
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
/*
      echo "<pre>";
      print_r($Products);
      echo "</pre>";
*/
      $factory_name = $Products[0]["factory"]['name'];
      $this->set('factory_name', $factory_name);
      $customer_name = $Products[0]["customer"]['name'];
      $this->set('customer_name', $customer_name);
      $product_code = $Products[0]["product_code"];
      $this->set('product_code', $product_code);
      $customer_product_code = $Products[0]["customer_product_code"];
      $this->set('customer_product_code', $customer_product_code);
      $name = $Products[0]["name"];
      $this->set('name', $name);

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

      $ProductName = $this->Products->find()
      ->where(['factory_id' => $data['factory_id'], 'name' => $name])->toArray();

      if(isset($ProductName[0])){

        return $this->redirect(['action' => 'addform',
        's' => ['mess' => "入力された品名は既に存在します。長さを追加する場合は「長さ追加」メニューから登録してください。"]]);

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
      $customer_name = $data["customer_name"];
      $this->set('customer_name', $customer_name);
      $tuikalength = $data["tuikalength"];
      $this->set('tuikalength', $tuikalength);
      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);

      for($j=1; $j<=$tuikalength; $j++){
        ${"length".$j} = $data["length".$j];
        $this->set('length'.$j, ${"length".$j});
      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);
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
      $customer_name = $data["customer_name"];
      $this->set('customer_name', $customer_name);

      $CustomerName = $this->Customers->find()
      ->where(['name' => $customer_name])->toArray();
      $customer_id = $CustomerName[0]["id"];
      $customer_code = $CustomerName[0]["customer_code"];

      if($data['factory_id'] == 1){
        $code_factory = "D";
      }else{
        $code_factory = "I";
      }

      $Productnow = $this->Products->find()
      ->where(['product_code like' => $customer_code.$code_factory.'%'])
      ->order(["product_code"=>"DESC"])->toArray();

      if(isset($Productnow[0])){
        $prodct_renban = substr($Productnow[0]["product_code"], -6, 4) + 1;
        $prodct_renban = sprintf('%04d', $prodct_renban);//0埋め
      }else{
        $prodct_renban = "0001";
      }
      
      $tuikalength = $data["tuikalength"];
      $this->set('tuikalength', $tuikalength);

      for($j=1; $j<=$tuikalength; $j++){
        ${"length".$j} = $data["length".$j];
        $this->set('length'.$j, ${"length".$j});
      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokuproduct = array();
      for($j=1; $j<=$tuikalength; $j++){

        ${"length".$j} = $data["length".$j];
        $color_renban = sprintf('%02d', $j);

        $arrtourokuproduct[] = [
          'factory_id' => $data["factory_id"],
          'product_code' => $customer_code.$code_factory.$prodct_renban.$color_renban,
          'name' => $data["name"],
          'tanni' => $data["tanni"],
          'length' => ${"length".$j},
          'customer_id' => $customer_id,
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
      //新しいデータを登録
      $Products = $this->Products->patchEntities($this->Products->newEntity(), $arrtourokuproduct);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
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

      for($j=1; $j<=$tuikalength; $j++){
        ${"length".$j} = $data["length".$j];
        $this->set('length'.$j, ${"length".$j});
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

      $Productnow = $this->Products->find()
      ->where(['product_code like' => $product_code_moto.'%'])
      ->order(["product_code"=>"DESC"])->toArray();
      $product_color_renban = substr($Productnow[0]["product_code"], -2, 2);
 
      $tuikalength = $data["tuikalength"];
      $this->set('tuikalength', $tuikalength);

      for($j=1; $j<=$tuikalength; $j++){
        ${"length".$j} = $data["length".$j];
        $this->set('length'.$j, ${"length".$j});
      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokuproduct = array();
      for($j=1; $j<=$tuikalength; $j++){

        ${"length".$j} = $data["length".$j];
        $color_renban = sprintf('%02d', $product_color_renban + $j);

        $arrtourokuproduct[] = [
          'factory_id' => $data["factory_id"],
          'product_code' => $product_code_moto.$color_renban,
          'name' => $data["name"],
          'tanni' => $ProductName[0]["tanni"],
          'length' => ${"length".$j},
          'customer_id' => $ProductName[0]["customer_id"],
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
      //新しいデータを登録
      $Products = $this->Products->patchEntities($this->Products->newEntity(), $arrtourokuproduct);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
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
      $this->set('arrProduct_name_list', $arrProduct_name_list);
    }

    public function editform($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['productdata'];

      $product = $this->Products->get($id, [
        'contain' => ['Customers']
      ]);
      $customers = $this->Products->Customers->find('list', ['limit' => 200]);
      $this->set(compact('product', 'customers'));
      $this->set('id', $id);

      $Factories = $this->Factories->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrFactories = array();
      foreach ($Factories as $value) {
        $arrFactories[] = array($value->id=>$value->name);
      }
      $this->set('arrFactories', $arrFactories);
    }

    public function editconfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();

      $Customers = $this->Customers->find()
      ->where(['id' => $data['customer_id']])->toArray();
      $customer_name = $Customers[0]["name"];
      $this->set('customer_name', $customer_name);

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);
    }

    public function editdo()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Customers = $this->Customers->find()
      ->where(['id' => $data['customer_id']])->toArray();
      $customer_name = $Customers[0]["name"];
      $this->set('customer_name', $customer_name);

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdateproduct = array();
      $arrupdateproduct = [
        'factory_id' => $data["factory_id"],
        'product_code' => $data["product_code"],
        'customer_product_code' => $data["customer_product_code"],
        'name' => $data["name"],
        'customer_id' => $data["customer_id"],
        'is_active' => 0,
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrupdateproduct);
      echo "</pre>";
*/
      $Products = $this->Products->patchEntity($this->Products->newEntity(), $arrupdateproduct);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Products->save($Products)) {

         $this->Products->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $data['id']]);

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

}
