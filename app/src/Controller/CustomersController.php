<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\Auth\DefaultPasswordHasher;//パスワードの更新時のハッシュ化

class CustomersController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->MaterialSuppliers = TableRegistry::get('MaterialSuppliers');
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
       ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "顧客", 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }

     }
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Factories']
        ];
        $customers = $this->paginate($this->Customers->find()->where(['Customers.delete_flag' => 0]));

        $this->set(compact('customers'));
    }

    public function detail($id = null)
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['customerdata'] = array();
        $_SESSION['customerdata'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['customerdata'] = array();
        $_SESSION['customerdata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }
      $this->set('id', $id);

      $Customers = $this->Customers->find()->contain(["Factories"])
      ->where(['Customers.id' => $id])->toArray();

      $name = $Customers[0]["name"];
      $this->set('name', $name);
      $factory_name = $Customers[0]["factory"]['name'];
      $this->set('factory_name', $factory_name);
      $this->set('customer_code', $Customers[0]["customer_code"]);
      $this->set('furigana', $Customers[0]["furigana"]);
      $this->set('department', $Customers[0]["department"]);
      $this->set('tel', $Customers[0]["tel"]);
      $this->set('fax', $Customers[0]["fax"]);
      $this->set('yuubin', $Customers[0]["yuubin"]);
      $this->set('address', $Customers[0]["address"]);

    }

    public function view($id = null)
    {
        $customer = $this->Customers->get($id, [
            'contain' => []
        ]);

        $this->set('customer', $customer);
    }

    public function addform()
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

      $Factories = $this->Factories->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrFactories = array();
      foreach ($Factories as $value) {
        $arrFactories[] = array($value->id=>$value->name);
      }
      $this->set('arrFactories', $arrFactories);

    }

    public function addcomfirm()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $data = $this->request->getData();

      $CustomerData = $this->Customers->find()
      ->where(['customer_code' => $data['customer_code']])->toArray();

      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['material_supplier_code' => $data['customer_code']])->toArray();

      $CustomerData = $CustomerData + $MaterialSuppliers;

      if(isset($CustomerData[0])){
  
        return $this->redirect(['action' => 'addform',
        's' => ['mess' => "得意先コード：「".$data['customer_code']."」は既に存在します。"]]);

      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);
    }

    public function adddo()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokucustomer = array();
      $arrtourokucustomer = [
        'factory_id' => $data["factory_id"],
        'name' => $data["name"],
        'customer_code' => $data["customer_code"],
        'department' => $data["department"],
        'furigana' => $data["furigana"],
        'tel' => $data["tel"],
        'fax' => $data["fax"],
        'yuubin' => $data["yuubin"],
        'address' => $data["address"],
        'is_active' => 0,
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokucustomer);
      echo "</pre>";
*/
      //新しいデータを登録
      $Customers = $this->Customers->patchEntity($this->Customers->newEntity(), $arrtourokucustomer);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Customers->save($Customers)) {

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
      $arrCustomer_name_list = array_unique($arrCustomer_name_list);
      $arrCustomer_name_list = array_values($arrCustomer_name_list);
      $this->set('arrCustomer_name_list', $arrCustomer_name_list);
    }

    public function editsyousai()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);

         $session = $this->request->getSession();
         $_SESSION = $session->read();
        $data = $_SESSION['customer_edit'];
      }else{
        $mess = "";
        $this->set('mess',$mess);
        $data = $this->request->getData();
      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_id', $data['factory_id']);
      $this->set('factory_name', $factory_name);

      $CustomerData = $this->Customers->find()
      ->where(['factory_id' => $data['factory_id'], 'name' => $data['name']])->toArray();

      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['material_supplier_code' => $data['customer_code']])->toArray();

      $CustomerData = $CustomerData + $MaterialSuppliers;

      if(!isset($CustomerData[0])){

        return $this->redirect(['action' => 'editpreform',
        's' => ['mess' => "自社工場：".$factory_name."、得意先名：「".$data['name']."」の得意先は存在しません。"]]);

      }

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

      $Factories = $this->Factories->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrFactories = array();
      foreach ($Factories as $value) {
        $arrFactories[] = array($value->id=>$value->name);
      }
      $this->set('arrFactories', $arrFactories);

    }

    public function editform()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);

         $session = $this->request->getSession();
         $_SESSION = $session->read();
        $data = $_SESSION['customer_edit'];
      }else{
        $mess = "";
        $this->set('mess',$mess);
        $data = $this->request->getData();
      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $CustomerData = $this->Customers->find()
      ->where(['id' => $data['id']])->toArray();

      $this->set('CustomerData', $CustomerData);
      $this->set('id', $data["id"]);
      $this->set('name', $CustomerData[0]["name"]);
      $this->set('customer_code', $CustomerData[0]["customer_code"]);
      $this->set('furigana', $CustomerData[0]["furigana"]);
      $this->set('department', $CustomerData[0]["department"]);
      $this->set('tel', $CustomerData[0]["tel"]);
      $this->set('fax', $CustomerData[0]["fax"]);
      $this->set('yuubin', $CustomerData[0]["yuubin"]);
      $this->set('address', $CustomerData[0]["address"]);

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
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $data = $this->request->getData();

      $CustomerData = $this->Customers->find()
      ->where(['id IS NOT' => $data['id'], 'customer_code' => $data['customer_code']])->toArray();

      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['material_supplier_code' => $data['customer_code']])->toArray();

      $CustomerData = $CustomerData + $MaterialSuppliers;

      if(isset($CustomerData[0])){

        if(!isset($_SESSION)){
          session_start();
          }
          $_SESSION['customer_edit'] = array();
          $_SESSION['customer_edit'] = ["id" => $data['id'], "factory_id" => $data['factory_id'], "name" => $data['name']];
  
        return $this->redirect(['action' => 'editform',
        's' => ['mess' => "得意先コード：「".$data['customer_code']."」は既に存在します。"]]);

      }
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      if($data["check"] > 0){
        $mess = "以下のデータを削除します。よろしければ「決定」ボタンを押してください。";
        $delete_flag = 1;
      }else{
        $mess = "以下のように更新します。よろしければ「決定」ボタンを押してください。";
        $delete_flag = 0;
      }
      $this->set('mess', $mess);
      $this->set('delete_flag', $delete_flag);
    }

    public function editdo()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $Customers = $this->Customers->patchEntity($this->Customers->newEntity(), $data);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Customers->updateAll(
           ['factory_id' => $data["factory_id"],
            'name' => $data["name"],
            'customer_code' => $data["customer_code"],
            'furigana' => $data["furigana"],
            'department' => $data["department"],
            'tel' => $data["tel"],
            'fax' => $data["fax"],
            'yuubin' => $data["yuubin"],
            'address' => $data["address"],
            'delete_flag' => $data["delete_flag"],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_staff' => $staff_id],
           ['id'  => $data['id']])){

        if($data["delete_flag"] > 0){
          $mes = "※下記のデータが削除されました";
        }else{
          $mes = "※下記のように更新されました";
        }
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

      $id = $_SESSION['customerdata'];

        $customer = $this->Customers->get($id, [
            'contain' => []
        ]);
        $this->set(compact('customer'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $customer = $this->Customers->get($data["id"], [
          'contain' => []
      ]);
      $this->set(compact('customer'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeletecustomer = array();
      $arrdeletecustomer = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletecustomer);
      echo "</pre>";
*/
      $Customers = $this->Customers->patchEntity($this->Customers->newEntity(), $arrdeletecustomer);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Customers->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeletecustomer['id']]
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
