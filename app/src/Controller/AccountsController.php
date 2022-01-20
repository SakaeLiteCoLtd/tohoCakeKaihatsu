<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Event\Event;//ログインに使用
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション

class AccountsController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Customers = TableRegistry::get('Customers');
     $this->Products = TableRegistry::get('Products');
     $this->Factories = TableRegistry::get('Factories');
     $this->Users = TableRegistry::get('Users');
     $this->Staffs = TableRegistry::get('Staffs');
     $this->Materials = TableRegistry::get('Materials');
     $this->MaterialSuppliers = TableRegistry::get('MaterialSuppliers');
     $this->Menus = TableRegistry::get('Menus');
     $this->Groups = TableRegistry::get('Groups');
     $this->LoginStaffs = TableRegistry::get('LoginStaffs');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

       return $this->redirect($this->Auth->logout());

     }

     if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

       $Groups = $this->Groups->find()->contain(["Menus"])
       ->where(['Groups.group_name_id' => $datasession['Auth']['User']['group_name_id'], 'Menus.id' => 29, 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }

     }

    }

    public function index()
    {
    }

    public function customercodeselect()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

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
        array_push($arrCustomer_name_list,$Customer_name_list[$j]["name"]."_".$Customer_name_list[$j]["department"]);
      }
      $arrCustomer_name_list = array_unique($arrCustomer_name_list);
      $arrCustomer_name_list = array_values($arrCustomer_name_list);
      $this->set('arrCustomer_name_list', $arrCustomer_name_list);
    }

    public function customercodeeditform()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);

        $data = $Data;
  
      }else{

        $mess = "";
        $this->set('mess',$mess);
        $data = $this->request->getData();

      }

      if(!isset($data['name'])){

        return $this->redirect(['action' => 'customercodeselect']);

      }
      $arrname = explode("_",$data['name']);
      $name = $arrname[0];
      if(strlen($arrname[1]) > 0){

        $CustomerData = $this->Customers->find()
        ->where(['name' => $name, 'department' => $arrname[1]])->toArray();
  
        if(!isset($CustomerData[0])){
  
          return $this->redirect(['action' => 'customercodeselect',
          's' => ['mess' => "得意先名：「".$data['name']."」部署名：「".$arrname[1]."」の得意先は存在しません。"]]);
  
        }

      }else{

        $CustomerData = $this->Customers->find()
        ->where(['name' => $name])->toArray();
  
        if(!isset($CustomerData[0])){
  
          return $this->redirect(['action' => 'customercodeselect',
          's' => ['mess' => "得意先名：「".$data['name']."」の得意先は存在しません。"]]);
  
        }
  
      }

      $this->set('factory_id', $CustomerData[0]['factory_id']);
      $this->set('CustomerData', $CustomerData);
      $this->set('id', $CustomerData[0]["id"]);
      $this->set('name', $CustomerData[0]["name"]);
      $this->set('customer_code', $CustomerData[0]["customer_code"]);
      $this->set('furigana', $CustomerData[0]["furigana"]);
      $this->set('department', $CustomerData[0]["department"]);
      $this->set('tel', $CustomerData[0]["tel"]);
      $this->set('fax', $CustomerData[0]["fax"]);
      $this->set('yuubin', $CustomerData[0]["yuubin"]);
      $this->set('address', $CustomerData[0]["address"]);
      $this->set('ryakusyou', $CustomerData[0]["ryakusyou"]);

      if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');
  
      print_r(" ");

    }

    public function customercodeeditconfirm()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $data = $this->request->getData();
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $CustomerData = $this->Customers->find()
      ->where(['id' => $data["id"]])->toArray();

      $Customercheck = $this->Customers->find()
      ->where(['customer_code' => $data["customer_code"]])->toArray();

      if(isset($Customercheck[0])){
  
        return $this->redirect(['action' => 'customercodeeditform',
        's' => ['mess' => "入力されたコード（".$data["customer_code"]."）は既に存在します。", "name" => $CustomerData[0]["name"]."_".$CustomerData[0]["department"]]]);

      }

      $this->set('id', $data["id"]);
      $this->set('customer_code', $data["customer_code"]);

      $this->set('factory_id', $CustomerData[0]['factory_id']);
      $this->set('customer_code_moto', $CustomerData[0]['customer_code']);
      $this->set('name', $CustomerData[0]["name"]);
      $this->set('furigana', $CustomerData[0]["furigana"]);
      $this->set('department', $CustomerData[0]["department"]);
      $this->set('tel', $CustomerData[0]["tel"]);
      $this->set('fax', $CustomerData[0]["fax"]);
      $this->set('yuubin', $CustomerData[0]["yuubin"]);
      $this->set('address', $CustomerData[0]["address"]);
      $this->set('ryakusyou', $CustomerData[0]["ryakusyou"]);

      if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');

    }

    public function customercodeeditdo()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $data = $this->request->getData();
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $Staffs = $this->Staffs->find()->where(['id' => $staff_id])->toArray();//工場を取得
      $factory_id = $Staffs[0]["factory_id"];

      $Customermoto = $this->Customers->find()
      ->where(['id' => $data['id']])->toArray();

      $MaterialSuppliermoto = $this->MaterialSuppliers->find()
      ->where(['material_supplier_code' => $Customermoto[0]["customer_code"], 'delete_flag' => 0])->toArray();

      $arrCustomermoto = array();
      $arrCustomermoto = [
        'factory_id' => $Customermoto[0]["factory_id"],
        'name' => $Customermoto[0]["name"],
        'customer_code' => $Customermoto[0]["customer_code"],
        'furigana' => $Customermoto[0]["furigana"],
        'ryakusyou' => $Customermoto[0]["ryakusyou"],
        'department' => $Customermoto[0]["department"],
        'tel' => $Customermoto[0]["tel"],
        'fax' => $Customermoto[0]["fax"],
        'yuubin' => $Customermoto[0]["yuubin"],
        'address' => $Customermoto[0]["address"],
        'is_active' => 1,
        'delete_flag' => 1,
        'created_at' => $Customermoto[0]["created_at"]->format("Y-m-d H:i:s"),
        'created_staff' => $Customermoto[0]["created_staff"],
        'updated_at' => date("Y-m-d H:i:s"),
        'updated_staff' => $staff_id
      ];

      $arrMaterialSuppliermoto = [
        'factory_id' => $Customermoto[0]["factory_id"],
        'name' => $Customermoto[0]["name"],
        'material_supplier_code' => $Customermoto[0]["customer_code"],
        'furigana' => $Customermoto[0]["furigana"],
        'ryakusyou' => $Customermoto[0]["ryakusyou"],
        'department' => $Customermoto[0]["department"],
        'tel' => $Customermoto[0]["tel"],
        'fax' => $Customermoto[0]["fax"],
        'yuubin' => $Customermoto[0]["yuubin"],
        'address' => $Customermoto[0]["address"],
        'is_active' => 1,
        'delete_flag' => 1,
        'created_at' => $Customermoto[0]["created_at"]->format("Y-m-d H:i:s"),
        'created_staff' => $Customermoto[0]["created_staff"],
        'updated_at' => date("Y-m-d H:i:s"),
        'updated_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrCustomermoto);
      echo "</pre>";
      echo "<pre>";
      print_r($arrMaterialSuppliermoto);
      echo "</pre>";
*/
      $Customers = $this->Customers->patchEntity($this->Customers->newEntity(), $data);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4

         if ($this->Customers->updateAll(
           ['customer_code' => $data["customer_code"],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_staff' => $staff_id],
           ['id'  => $data['id']])){

            $this->MaterialSuppliers->updateAll(
              ['material_supplier_code' => $data["customer_code"],
               'updated_at' => date('Y-m-d H:i:s'),
               'updated_staff' => $staff_id],
              ['id'  => $MaterialSuppliermoto[0]['id']]);

              $MaterialSuppliers = $this->MaterialSuppliers->patchEntity($this->MaterialSuppliers->newEntity(), $arrMaterialSuppliermoto);
              $this->MaterialSuppliers->save($MaterialSuppliers);
    
              $Customers = $this->Customers->patchEntity($this->Customers->newEntity(), $arrCustomermoto);
              $this->Customers->save($Customers);
      
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
/*
    public function productcodeselect()
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

      $this->set('countFactories', count($Factories));
      for($i=0; $i<count($Factories); $i++){

        $this->set('factory_id'.$i, $Factories[$i]["id"]);

        ${"Product_name_list".$i} = $this->Products->find()
        ->where(['factory_id' => $Factories[$i]["id"], 'delete_flag' => 0])->toArray();
  
        ${"arrProduct_name_list".$i} = array();
        for($j=0; $j<count(${"Product_name_list".$i}); $j++){
          array_push(${"arrProduct_name_list".$i},${"Product_name_list".$i}[$j]["name"].";".${"Product_name_list".$i}[$j]["length"]."mm");
        }
        ${"arrProduct_name_list".$i} = array_unique(${"arrProduct_name_list".$i});
        ${"arrProduct_name_list".$i} = array_values(${"arrProduct_name_list".$i});
  
        $this->set('arrProduct_name_list'.$i, ${"arrProduct_name_list".$i});
      }
    }
  */
    public function productcodeeditform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $Data=$this->request->query('s');
      if(isset($Data["mess"])){

        $mess = $Data["mess"];
        $this->set('mess',$mess);
        $data = $Data;

      }else{

        $data = $this->request->getData();
        $mess = "";
        $this->set('mess',$mess);

      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $product_name_length = explode(";",$data["name"]);
      $name = $product_name_length[0];

      if(isset($product_name_length[1])){
        $length = str_replace('mm', '', $product_name_length[1]);
        $ProductName1 = $this->Products->find()
        ->where(['factory_id' => $data['factory_id'], 'name' => $name, 'length' => $length, 'delete_flag' => 0])->toArray();
       }else{
        $length = "";
        $ProductName1 = $this->Products->find()
        ->where(['factory_id' => $data['factory_id'], 'name' => $name, 'delete_flag' => 0])->toArray();
       }
       $this->set('length',$length);

      if(!isset($ProductName1[0])){

        return $this->redirect(['action' => 'productcodeselect',
        's' => ['mess' => "入力された製品は存在しません"]]);

      }

      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);
      $product_code = $ProductName1[0]["product_code"];
      $this->set('product_code', $product_code);
      $name = $ProductName1[0]["name"];
      $this->set('name', $name);
      $tanni = $ProductName1[0]["tanni"];
      $this->set('tanni', $tanni);
      $weight = $ProductName1[0]["weight"];
      $this->set('weight', $weight);
      $id = $ProductName1[0]["id"];
      $this->set('id', $id);
      
      $product_code_ini = substr($ProductName1[0]["product_code"], 0, 11);

      $ProductName = $this->Products->find()
      ->where(['product_code like' => $product_code_ini.'%', 'length !=' => $length, 'delete_flag' => 0])->toArray();

      $this->set('ProductName', $ProductName);

      $Customers = $this->Customers->find()
      ->where(['id' => $ProductName[0]['customer_id']])->toArray();
      $customer_name = $Customers[0]["name"];
      $this->set('customer_name', $customer_name);

      if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');
  
      print_r(" ");

    }

    public function productcodeeditconfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $ProductsData = $this->Products->find()
      ->where(['id' => $data["id"]])->toArray();

      $Productcheck = $this->Products->find()
      ->where(['product_code' => $data["product_code"]])->toArray();
      $this->set('product_code', $data["product_code"]);

      if(isset($Productcheck[0])){
  
        $factory_id = $data["factory_id"];

        return $this->redirect(['action' => 'productcodeeditform',
        's' => ['mess' => "入力されたコード（".$data["product_code"]."）は既に存在します。"
        , "name" => $ProductsData[0]["name"].";".$ProductsData[0]["length"], "factory_id" => $factory_id]]);

      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);
      $product_code_moto = $ProductsData[0]["product_code"];
      $this->set('product_code_moto', $product_code_moto);
      $name = $ProductsData[0]["name"];
      $this->set('name', $name);
      $tanni = $ProductsData[0]["tanni"];
      $this->set('tanni', $tanni);
      $weight = $ProductsData[0]["weight"];
      $this->set('weight', $weight);
      $id = $ProductsData[0]["id"];
      $this->set('id', $id);
      $length = $ProductsData[0]["length"];
      $this->set('length', $length);
      
      $product_code_ini = substr($ProductsData[0]["product_code"], 0, 11);

      $ProductName = $this->Products->find()
      ->where(['product_code like' => $product_code_ini.'%', 'length !=' => $length, 'delete_flag' => 0])->toArray();

      $this->set('ProductName', $ProductName);

      $Customers = $this->Customers->find()
      ->where(['id' => $ProductName[0]['customer_id']])->toArray();
      $customer_name = $Customers[0]["name"];
      $this->set('customer_name', $customer_name);

      $product_code_new_ini = substr($data["product_code"], 0, 11);

      if($product_code_ini == $product_code_new_ini){

        $mess = "以下のデータの紐づけは保持されます";
        $this->set('mess',$mess);

      }else{

        $mess = "以下のデータが紐づけされなくなります";
        $this->set('mess',$mess);

      }

    }

    public function productcodeeditdo()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $this->set('product_code', $data["product_code"]);

      $ProductsData = $this->Products->find()
      ->where(['id' => $data["id"]])->toArray();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);
      $product_code_moto = $ProductsData[0]["product_code"];
      $this->set('product_code_moto', $product_code_moto);
      $name = $ProductsData[0]["name"];
      $this->set('name', $name);
      $tanni = $ProductsData[0]["tanni"];
      $this->set('tanni', $tanni);
      $weight = $ProductsData[0]["weight"];
      $this->set('weight', $weight);
      $id = $ProductsData[0]["id"];
      $this->set('id', $id);
      $length = $ProductsData[0]["length"];
      $this->set('length', $length);
      
      $product_code_ini = substr($ProductsData[0]["product_code"], 0, 11);

      $ProductName = $this->Products->find()
      ->where(['product_code like' => $product_code_ini.'%', 'length !=' => $length, 'delete_flag' => 0])->toArray();

      $this->set('ProductName', $ProductName);

      $Customers = $this->Customers->find()
      ->where(['id' => $ProductName[0]['customer_id']])->toArray();
      $customer_name = $Customers[0]["name"];
      $this->set('customer_name', $customer_name);

      $product_code_new_ini = substr($data["product_code"], 0, 11);

      if($product_code_ini == $product_code_new_ini){

        $mess = "以下のデータの紐づけは保持されています";
        $this->set('mess',$mess);

      }else{

        $mess = "以下のデータが紐づけされなくなりました";
        $this->set('mess',$mess);

      }

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdateproductmoto = array();
      $arrupdateproductmoto = [
        'factory_id' => $ProductsData[0]["factory_id"],
        'product_code' => $ProductsData[0]["product_code"],
        'length' => $ProductsData[0]["length"],
        'length_cut' => $ProductsData[0]["length_cut"],
        'length_upper_limit' => $ProductsData[0]["length_upper_limit"],
        'length_lower_limit' => $ProductsData[0]["length_lower_limit"],
        'ig_bank_modes' => $ProductsData[0]["ig_bank_modes"],
        'bik' => $ProductsData[0]["bik"],
        'name' => $ProductsData[0]["name"],
        'tanni' => $ProductsData[0]["tanni"],
        'weight' => $ProductsData[0]["weight"],
        'status_kensahyou' => $ProductsData[0]["status_kensahyou"],
        'customer_id' => $ProductsData[0]["customer_id"],
        'is_active' => 1,
        'delete_flag' => 1,
        'created_at' => $ProductsData[0]["created_at"]->format("Y-m-d H:i:s"),
        'created_staff' => $ProductsData[0]["created_staff"],
        'updated_at' => date("Y-m-d H:i:s"),
        'updated_staff' => $staff_id
      ];

      $Products = $this->Products->patchEntity($this->Products->newEntity(), $data);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4

         if ($this->Products->updateAll(
           ['product_code' => $data["product_code"],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_staff' => $staff_id],
           ['id'  => $data['id']])){
    
              $Products = $this->Products->patchEntity($this->Products->newEntity(), $arrupdateproductmoto);
              $this->Products->save($Products);
      
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

    public function productdeletedselect()
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

      $this->set('countFactories', count($Factories));
      for($i=0; $i<count($Factories); $i++){

        $this->set('factory_id'.$i, $Factories[$i]["id"]);

        ${"Product_name_list".$i} = $this->Products->find()
        ->where(['factory_id' => $Factories[$i]["id"], 'delete_flag' => 1])->toArray();
  
        ${"arrProduct_name_list".$i} = array();
        for($j=0; $j<count(${"Product_name_list".$i}); $j++){

          ${"Product_check".$j} = $this->Products->find()
          ->where(['product_code' => ${"Product_name_list".$i}[$j]["product_code"], 'delete_flag' => 0])->toArray();
          if(!isset(${"Product_check".$j}[0])){
            array_push(${"arrProduct_name_list".$i},${"Product_name_list".$i}[$j]["name"].";".${"Product_name_list".$i}[$j]["length"]."mm");
          }

        }
        ${"arrProduct_name_list".$i} = array_unique(${"arrProduct_name_list".$i});
        ${"arrProduct_name_list".$i} = array_values(${"arrProduct_name_list".$i});

        $this->set('arrProduct_name_list'.$i, ${"arrProduct_name_list".$i});
  
      }

    $Product_name_delete = $this->Products->find()->contain(["Factories"])
    ->where(['Products.delete_flag' => 1])
    ->order(["product_code"=>"ASC"])->toArray();

    $Product_name_lists = array();
    for($j=0; $j<count($Product_name_delete); $j++){
      $Product_check = $this->Products->find()
      ->where(['product_code' => $Product_name_delete[$j]["product_code"], 'delete_flag' => 0])->toArray();
      if(!isset($Product_check[0])){
        $Product_name_lists[] = [
          "factory" => $Product_name_delete[$j]["factory"]["name"],
          "product_code" => $Product_name_delete[$j]["product_code"],
          "name" => $Product_name_delete[$j]["name"],
          "length" => $Product_name_delete[$j]["length"],
        ];
      }
    }

    $Product_name_lists = array_unique($Product_name_lists, SORT_REGULAR);
    $Product_name_lists = array_values($Product_name_lists);

    $this->set(compact('Product_name_lists'));

    }

    public function productdeletedconfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){

        $mess = $Data["mess"];
        $this->set('mess',$mess);
        $data = $Data;

      }else{

        $data = $this->request->getData();
        $mess = "";
        $this->set('mess',$mess);

      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $product_name_length = explode(";",$data["name"]);
      $name = $product_name_length[0];

      if(isset($product_name_length[1])){
        $length = str_replace('mm', '', $product_name_length[1]);
        $ProductName1 = $this->Products->find()
        ->where(['factory_id' => $data['factory_id'], 'name' => $name, 'length' => $length, 'delete_flag' => 1])->toArray();
       }else{
        $length = "";
        $ProductName1 = $this->Products->find()
        ->where(['factory_id' => $data['factory_id'], 'name' => $name, 'delete_flag' => 1])->toArray();
       }
       $this->set('length',$length);

      if(!isset($ProductName1[0])){

        return $this->redirect(['action' => 'productdeletedselect',
        's' => ['mess' => "入力された削除済み製品は存在しません"]]);

      }

      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);
      $product_code = $ProductName1[0]["product_code"];
      $this->set('product_code', $product_code);
      $name = $ProductName1[0]["name"];
      $this->set('name', $name);
      $tanni = $ProductName1[0]["tanni"];
      $this->set('tanni', $tanni);
      $weight = $ProductName1[0]["weight"];
      $this->set('weight', $weight);
      $id = $ProductName1[0]["id"];
      $this->set('id', $id);

      $Customers = $this->Customers->find()
      ->where(['id' => $ProductName1[0]['customer_id']])->toArray();
      $customer_name = $Customers[0]["name"];
      $this->set('customer_name', $customer_name);

    }

    public function productdeleteddo()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);
      
      $data = $this->request->getData();
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $ProductName1 = $this->Products->find()
      ->where(['id' => $data['id']])->toArray();

      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);
      $product_code = $ProductName1[0]["product_code"];
      $this->set('product_code', $product_code);
      $name = $ProductName1[0]["name"];
      $this->set('name', $name);
      $tanni = $ProductName1[0]["tanni"];
      $this->set('tanni', $tanni);
      $weight = $ProductName1[0]["weight"];
      $this->set('weight', $weight);
      $id = $ProductName1[0]["id"];
      $this->set('id', $id);
      $length = $ProductName1[0]["length"];
      $this->set('length', $length);

      $Customers = $this->Customers->find()
      ->where(['id' => $ProductName1[0]['customer_id']])->toArray();
      $customer_name = $Customers[0]["name"];
      $this->set('customer_name', $customer_name);

      $session = $this->request->getSession();
      $datasession = $session->read();
      $staff_id = $datasession['Auth']['User']['staff_id'];

      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Products->updateAll(
           [ 'is_active' => 0,
             'delete_flag' => 0,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $data['id']]
         )){

         $mes = "※以下のデータが復元されました。";
         $this->set('mes',$mes);
         $connection->commit();// コミット5

       } else {

         $mes = "※復元されませんでした";
         $this->set('mes',$mes);
         $this->Flash->error(__('The data could not be saved. Please, try again.'));
         throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

       }

     } catch (Exception $e) {//トランザクション7
     //ロールバック8
       $connection->rollback();//トランザクション9
     }//トランザクション10

    }

    public function materialdeletedselect()
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

      $this->set('countFactories', count($Factories));
      for($i=0; $i<count($Factories); $i++){

        $this->set('factory_id'.$i, $Factories[$i]["id"]);

        ${"Material_name_list".$i} = $this->Materials->find()
        ->where(['factory_id' => $Factories[$i]["id"], 'delete_flag' => 1])->toArray();
  
        ${"arrMaterial_name_list".$i} = array();
        for($j=0; $j<count(${"Material_name_list".$i}); $j++){

          ${"Material_check".$j} = $this->Materials->find()
          ->where(['material_code' => ${"Material_name_list".$i}[$j]["material_code"], 'delete_flag' => 0])->toArray();
          if(!isset(${"Material_check".$j}[0])){
            array_push(${"arrMaterial_name_list".$i},${"Material_name_list".$i}[$j]["name"]);
          }

        }
        ${"arrMaterial_name_list".$i} = array_unique(${"arrMaterial_name_list".$i});
        ${"arrMaterial_name_list".$i} = array_values(${"arrMaterial_name_list".$i});
  
        $this->set('arrMaterial_name_list'.$i, ${"arrMaterial_name_list".$i});
  
      }


      $Material_name_delete = $this->Materials->find()->contain(["Factories"])
      ->where(['Materials.delete_flag' => 1])
      ->order(["material_code"=>"ASC"])->toArray();
  
      $Material_name_lists = array();
      for($j=0; $j<count($Material_name_delete); $j++){
        $Material_check = $this->Materials->find()
        ->where(['material_code' => $Material_name_delete[$j]["material_code"], 'delete_flag' => 0])->toArray();
        if(!isset($Material_check[0])){
          $Material_name_lists[] = [
            "factory" => $Material_name_delete[$j]["factory"]["name"],
            "name" => $Material_name_delete[$j]["name"],
            "material_code" => $Material_name_delete[$j]["material_code"],
          ];
        }
      }
  
      $Material_name_lists = array_unique($Material_name_lists, SORT_REGULAR);
      $Material_name_lists = array_values($Material_name_lists);
  
      $this->set(compact('Material_name_lists'));
  
    }

    public function materialdeletedconfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){

        $mess = $Data["mess"];
        $this->set('mess',$mess);
        $data = $Data;

      }else{

        $data = $this->request->getData();
        $mess = "";
        $this->set('mess',$mess);

      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $name = $data["name"];

      $MaterialName1 = $this->Materials->find()
      ->where(['factory_id' => $data['factory_id'], 'name' => $name, 'delete_flag' => 1])->toArray();

      if(!isset($MaterialName1[0])){

        return $this->redirect(['action' => 'materialdeletedselect',
        's' => ['mess' => "入力された削除済み仕入品は存在しません"]]);

      }

      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);
      $material_code = $MaterialName1[0]["material_code"];
      $this->set('material_code', $material_code);
      $name = $MaterialName1[0]["name"];
      $this->set('name', $name);
      $id = $MaterialName1[0]["id"];
      $this->set('id', $id);

    }

    public function materialdeleteddo()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);
      
      $data = $this->request->getData();
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $MaterialName1 = $this->Materials->find()
      ->where(['id' => $data['id']])->toArray();

      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);
      $material_code = $MaterialName1[0]["material_code"];
      $this->set('material_code', $material_code);
      $name = $MaterialName1[0]["name"];
      $this->set('name', $name);

      $session = $this->request->getSession();
      $datasession = $session->read();
      $staff_id = $datasession['Auth']['User']['staff_id'];

      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Materials->updateAll(
           [ 'is_active' => 0,
             'delete_flag' => 0,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $data['id']]
         )){

         $mes = "※以下のデータが復元されました。";
         $this->set('mes',$mes);
         $connection->commit();// コミット5

       } else {

         $mes = "※復元されませんでした";
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
