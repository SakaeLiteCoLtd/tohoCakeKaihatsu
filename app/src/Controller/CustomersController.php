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
     $this->CustomerCodeRules = TableRegistry::get('CustomerCodeRules');
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
       ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "得意先・仕入先", 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }

     }

    }

    public function index($id = null)
    {

      if(strlen($id) > 0){
  
        $this->paginate = [
          'limit' => 13,
          'contain' => ['Factories'],
          'order' => ['Customers.updated_at' => 'desc',
          'Customers.created_at' => 'desc']
        ];
        $customers = $this->paginate($this->Customers->find()
        ->where(['Customers.delete_flag' => 0]));

      $this->set(compact('customers'));

      }else{

        $this->paginate = [
          'limit' => 13,
          'contain' => ['Factories']
      ];
      $customers = $this->paginate($this->Customers->find()
      ->where(['Customers.delete_flag' => 0])->order(["customer_code"=>"ASC"]));

      $this->set(compact('customers'));

      }

    }

    public function detail($id = null)
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $Customers = $this->Customers->find()->contain(["Factories"])
      ->where(['Customers.id' => $id])->toArray();

      $name = $Customers[0]["name"];
      $department = $Customers[0]["department"];

      if(!isset($_SESSION)){
        session_start();
      }
      $_SESSION['customername_department'] = array();
      $_SESSION['customername_department'][0] = "check";
      $_SESSION['customername_department']["name"] = $name."_".$department;

      return $this->redirect(['action' => 'editsyousai']);
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

      $arrcustomer_code_last = [
        "1" => "１：大阪顧客",
        "2" => "２：石狩顧客",
        "3" => "３：大阪顧客予備",
        "4" => "４：石狩顧客予備",
        "5" => "５：大阪仕入",
        "6" => "６：石狩仕入",
        "7" => "７：大阪仕入予備",
        "8" => "８：石狩仕入予備"
      ];
      $this->set('arrcustomer_code_last', $arrcustomer_code_last);

      if(!isset($_SESSION)){
        session_start();
        header('Expires:-1');
        header('Cache-Control:');
        header('Pragma:');
      }

    }

    public function addcomfirm()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $data = $this->request->getData();

      $initial_kana = mb_substr($data["furigana"], 0, 1);//半角カタカナはmb_substrを使う

      $CustomerCodeRules = $this->CustomerCodeRules->find()
      ->where(['initial_kana' => $initial_kana, 'delete_flag' => 0])->toArray();

      if(!isset($CustomerCodeRules[0])){

        return $this->redirect(['action' => 'addform',
        's' => ['mess' => "フリガナは半角カタカナで入力してください"]]);

      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $customer_code_last = $data['customer_code_last'];
      if($customer_code_last == 1){
        $customer_code_last_hyouji = "１：大阪顧客";
      }elseif($customer_code_last == 2){
        $customer_code_last_hyouji = "２：石狩顧客";
      }elseif($customer_code_last == 3){
        $customer_code_last_hyouji = "３：大阪顧客予備";
      }elseif($customer_code_last == 4){
        $customer_code_last_hyouji = "４：石狩顧客予備";
      }elseif($customer_code_last == 5){
        $customer_code_last_hyouji = "５：大阪仕入";
      }elseif($customer_code_last == 6){
        $customer_code_last_hyouji = "６：石狩仕入";
      }elseif($customer_code_last == 7){
        $customer_code_last_hyouji = "７：大阪仕入予備";
      }else{
        $customer_code_last_hyouji = "８：石狩仕入予備";
      }
      $this->set('customer_code_last_hyouji', $customer_code_last_hyouji);

      if(!isset($_SESSION)){
        session_start();
        header('Expires:-1');
        header('Cache-Control:');
        header('Pragma:');
      }
  
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

      //新しいデータを登録
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4

        $initial_kana = mb_substr($data["furigana"], 0, 1);//半角カタカナはmb_substrを使う
        $CustomerCodeRules = $this->CustomerCodeRules->find()
        ->where(['initial_kana' => $initial_kana, 'delete_flag' => 0])->toArray();
  
        $code_ini = "7".$CustomerCodeRules[0]["code"];
  
        $CustomerData = $this->Customers->find()
        ->where(['customer_code like' => $code_ini.'%'])->toArray();
  
        $MaterialSuppliers = $this->MaterialSuppliers->find()
        ->where(['material_supplier_code like' => $code_ini.'%'])->toArray();
  
        $CustomerData = $CustomerData + $MaterialSuppliers;
  
        $customer_code_renban = count($CustomerData) + 1;
        $customer_code_renban = sprintf('%02d', $customer_code_renban);//0埋め
  
        for($j=0; $j<count($CustomerData); $j++){
  
          if(substr($CustomerData[$j]["customer_code"], 0, 5) >= $customer_code_renban){//被っていればプラス１していく
  
            $customer_code_renban = sprintf('%02d', substr($CustomerData[$j]["customer_code"], 0, 5) + 1);//0埋め
  
          }
          
        }
  /*
        echo "<pre>";
        print_r($customer_code_renban);
        echo "</pre>";
  */
        $customer_code_last = $data['customer_code_last'];
        $customer_code = $customer_code_renban.$customer_code_last;
        $this->set('customer_code', $customer_code);
  
        $arrtourokucustomer = array();
        $arrtourokucustomer = [
          'factory_id' => $data["factory_id"],
          'name' => $data["name"],
          'customer_code' => $customer_code,
          'department' => $data["department"],
          'furigana' => $data["furigana"],
          'ryakusyou' => $data["ryakusyou"],
          'tel' => $data["tel"],
          'fax' => $data["fax"],
          'yuubin' => $data["yuubin"],
          'address' => $data["address"],
          'is_active' => 0,
          'delete_flag' => 0,
          'created_at' => date("Y-m-d H:i:s"),
          'created_staff' => $staff_id
        ];

        $arrtourokuMaterialSuppliers = [
          'factory_id' => $data["factory_id"],
          'name' => $data["name"],
          'material_supplier_code' => $customer_code,
          'department' => $data["department"],
          'furigana' => $data["furigana"],
          'ryakusyou' => $data["ryakusyou"],
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
        $Customers = $this->Customers->patchEntity($this->Customers->newEntity(), $arrtourokucustomer);
        if ($this->Customers->save($Customers)) {

          $MaterialSuppliers = $this->MaterialSuppliers->patchEntity($this->MaterialSuppliers->newEntity(), $arrtourokuMaterialSuppliers);
          $this->MaterialSuppliers->save($MaterialSuppliers);
    
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
        array_push($arrCustomer_name_list,$Customer_name_list[$j]["name"]."_".$Customer_name_list[$j]["department"]);
      }
      $arrCustomer_name_list = array_unique($arrCustomer_name_list);
      $arrCustomer_name_list = array_values($arrCustomer_name_list);
      $this->set('arrCustomer_name_list', $arrCustomer_name_list);
    }

    
    public function editsyousai()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);

        $data = $_SESSION['customer_edit'];

      }elseif(isset($_SESSION['customername_department'][0])){

        $mess = "";
        $this->set('mess',$mess);
        $data = $_SESSION['customername_department'];
        $_SESSION['customername_department'] = array();

      }else{

        $mess = "";
        $this->set('mess',$mess);
        $data = $this->request->getData();

      }

      $arrname = explode("_",$data['name']);
      $name = $arrname[0];
      if(strlen($arrname[1]) > 0){

        $CustomerData = $this->Customers->find()
        ->where(['name' => $name, 'department' => $arrname[1]])->toArray();
  
        if(!isset($CustomerData[0])){
  
          return $this->redirect(['action' => 'editpreform',
          's' => ['mess' => "得意先名：「".$data['name']."」部署名：「".$arrname[1]."」の得意先は存在しません。"]]);
  
        }

      }else{

        $CustomerData = $this->Customers->find()
        ->where(['name' => $name])->toArray();
  
        if(!isset($CustomerData[0])){
  
          return $this->redirect(['action' => 'editpreform',
          's' => ['mess' => "得意先名：「".$data['name']."」の得意先は存在しません。"]]);
  
        }
  
      }

      $Factories = $this->Factories->find()
      ->where(['id' => $CustomerData[0]['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_id', $CustomerData[0]['factory_id']);
      $this->set('factory_name', $factory_name);
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
      $this->set('ryakusyou', $CustomerData[0]["ryakusyou"]);

      $Factories = $this->Factories->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrFactories = array();
      foreach ($Factories as $value) {
        $arrFactories[] = array($value->id=>$value->name);
      }
      $this->set('arrFactories', $arrFactories);

      $customer_code_last = substr($CustomerData[0]["customer_code"], -1, 1);
      $this->set('customer_code_last', $customer_code_last);

      $arrcustomer_code_last = [
        "1" => "１：大阪顧客",
        "2" => "２：石狩顧客",
        "3" => "３：大阪顧客予備",
        "4" => "４：石狩顧客予備",
        "5" => "５：大阪仕入",
        "6" => "６：石狩仕入",
        "7" => "７：大阪仕入予備",
        "8" => "８：石狩仕入予備"
      ];
      $this->set('arrcustomer_code_last', $arrcustomer_code_last);

    }

    public function editconfirm()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $data = $this->request->getData();

      $customer_code_new = substr($data['customer_code'], 0, -1).$data['customer_code_last'];
      $this->set('customer_code_new', $customer_code_new);

      $CustomerData = $this->Customers->find()
      ->where(['id IS NOT' => $data['id'], 'customer_code' => $customer_code_new])->toArray();

 //     $MaterialSuppliers = $this->MaterialSuppliers->find()
 //     ->where(['material_supplier_code' => $customer_code_new])->toArray();

 //     $CustomerData = $CustomerData + $MaterialSuppliers;

      if(isset($CustomerData[0])){

        if(!isset($_SESSION)){
          session_start();
          }
          $_SESSION['customer_edit'] = array();
          $_SESSION['customer_edit'] = ["id" => $data['id'], "factory_id" => $data['factory_id'], "name" => $data['name']];
  
        return $this->redirect(['action' => 'editform',
        's' => ['mess' => "コード：「".$customer_code_new."」は既に存在します。"]]);

      }

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

      $Customers = $this->Customers->patchEntity($this->Customers->newEntity(), $data);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4

         if ($this->Customers->updateAll(
           ['factory_id' => $data["factory_id"],
           'name' => $data["name"],
           'customer_code' => $data["customer_code_new"],
           'furigana' => $data["furigana"],
            'ryakusyou' => $data["ryakusyou"],
            'department' => $data["department"],
            'tel' => $data["tel"],
            'fax' => $data["fax"],
            'yuubin' => $data["yuubin"],
            'address' => $data["address"],
            'delete_flag' => $data["delete_flag"],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_staff' => $staff_id],
           ['id'  => $data['id']])){

            $this->MaterialSuppliers->updateAll(
              ['factory_id' => $data["factory_id"],
               'name' => $data["name"],
               'material_supplier_code' => $data["customer_code_new"],
               'furigana' => $data["furigana"],
               'ryakusyou' => $data["ryakusyou"],
               'department' => $data["department"],
               'tel' => $data["tel"],
               'fax' => $data["fax"],
               'yuubin' => $data["yuubin"],
               'address' => $data["address"],
               'delete_flag' => $data["delete_flag"],
               'updated_at' => date('Y-m-d H:i:s'),
               'updated_staff' => $staff_id],
              ['id'  => $MaterialSuppliermoto[0]['id']]);
  
        if($data["delete_flag"] > 0){
          $mes = "※下記のデータが削除されました";
        }else{

          $MaterialSuppliers = $this->MaterialSuppliers->patchEntity($this->MaterialSuppliers->newEntity(), $arrMaterialSuppliermoto);
          $this->MaterialSuppliers->save($MaterialSuppliers);

          $Customers = $this->Customers->patchEntity($this->Customers->newEntity(), $arrCustomermoto);
          $this->Customers->save($Customers);
  
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
/*
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
*/
    public function kensakupreform()
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
        array_push($arrCustomer_name_list,$Customer_name_list[$j]["name"]);
      }
      $arrCustomer_name_list = array_unique($arrCustomer_name_list);
      $arrCustomer_name_list = array_values($arrCustomer_name_list);
      $this->set('arrCustomer_name_list', $arrCustomer_name_list);
    }

    public function kensakuichiran()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $data = $this->request->getData();

      $Customers = $this->Customers->find()->contain(["Factories"])
      ->where(['Customers.name like' => "%".$data["name"]."%", 'Customers.delete_flag' => 0])->toArray();
      $this->set('Customers', $Customers);

    }

}
