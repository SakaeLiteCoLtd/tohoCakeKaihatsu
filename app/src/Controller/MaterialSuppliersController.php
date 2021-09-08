<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class MaterialSuppliersController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Customers = TableRegistry::get('Customers');
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
       ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "原料関係", 'Groups.delete_flag' => 0])
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
        $materialSuppliers = $this->paginate($this->MaterialSuppliers->find()->where(['MaterialSuppliers.delete_flag' => 0]));

        $this->set(compact('materialSuppliers'));
    }

    public function detail($id = null)
    {
      $materialSuppliers = $this->MaterialSuppliers->newEntity();
      $this->set('materialSuppliers', $materialSuppliers);

      $data = $this->request->getData();

      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materialSupplierdata'] = array();
        $_SESSION['materialSupplierdata'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materialSupplierdata'] = array();
        $_SESSION['materialSupplierdata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }elseif(isset($data["kensaku"])){
  
        $MaterialSuppliers = $this->MaterialSuppliers->find()->where(['name' => $data['name']])->toArray();

        if(!isset($MaterialSuppliers[0])){

          return $this->redirect(['action' => 'editpreform',
          's' => ['mess' => "原料仕入先名：「".$data['name']."」は存在しません。"]]);
  
        }else{
          $id = $MaterialSuppliers[0]["id"];
        }

      }
      $this->set('id', $id);

      $MaterialSuppliers = $this->MaterialSuppliers->find()->contain(["Factories"])
      ->where(['MaterialSuppliers.id' => $id])->toArray();

      $factory_name = $MaterialSuppliers[0]["factory"]['name'];
      $this->set('factory_name', $factory_name);
      $name = $MaterialSuppliers[0]["name"];
      $this->set('name', $name);
      $material_supplier_code = $MaterialSuppliers[0]["material_supplier_code"];
      $this->set('material_supplier_code', $material_supplier_code);
      $tel = $MaterialSuppliers[0]["tel"];
      $this->set('tel', $tel);
      $fax = $MaterialSuppliers[0]["fax"];
      $this->set('fax', $fax);
      $department = $MaterialSuppliers[0]["department"];
      $this->set('department', $department);
      $yuubin = $MaterialSuppliers[0]["yuubin"];
      $this->set('yuubin', $yuubin);
      $address = $MaterialSuppliers[0]["address"];
      $this->set('address', $address);

    }

    public function view($id = null)
    {
        $materialSupplier = $this->MaterialSuppliers->get($id, [
            'contain' => ['PriceMaterials']
        ]);

        $this->set('materialSupplier', $materialSupplier);
    }

    public function add()
    {
        $materialSupplier = $this->MaterialSuppliers->newEntity();
        if ($this->request->is('post')) {
            $materialSupplier = $this->MaterialSuppliers->patchEntity($materialSupplier, $this->request->getData());
            if ($this->MaterialSuppliers->save($materialSupplier)) {
                $this->Flash->success(__('The material supplier has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The material supplier could not be saved. Please, try again.'));
        }
        $this->set(compact('materialSupplier'));
    }

    public function addform()
    {
      $materialSupplier = $this->MaterialSuppliers->newEntity();
      $this->set('materialSupplier', $materialSupplier);

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
      $materialSupplier = $this->MaterialSuppliers->newEntity();
      $this->set('materialSupplier', $materialSupplier);

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $CustomerData = $this->Customers->find()
      ->where(['customer_code' => $data['material_supplier_code']])->toArray();

      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['material_supplier_code' => $data['material_supplier_code']])->toArray();

      $CustomerData = $CustomerData + $MaterialSuppliers;

      if(isset($CustomerData[0])){
  
        return $this->redirect(['action' => 'addform',
        's' => ['mess' => "仕入先コード：「".$data['material_supplier_code']."」は既に存在します。"]]);

      }

    }

    public function adddo()
    {
      $materialSupplier = $this->MaterialSuppliers->newEntity();
      $this->set('materialSupplier', $materialSupplier);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokumaterialSupplier = array();
      $arrtourokumaterialSupplier = [
        'factory_id' => $data["factory_id"],
        'name' => $data["name"],
        'material_supplier_code' => $data["material_supplier_code"],
        'yuubin' => $data["yuubin"],
        'department' => $data["department"],
        'address' => $data["address"],
        'tel' => $data["tel"],
        'fax' => $data["fax"],
        'is_active' => 0,
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokumaterialSupplier);
      echo "</pre>";
*/
      //新しいデータを登録
      $MaterialSuppliers = $this->MaterialSuppliers->patchEntity($this->MaterialSuppliers->newEntity(), $arrtourokumaterialSupplier);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->MaterialSuppliers->save($MaterialSuppliers)) {

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
      $materialSupplier = $this->MaterialSuppliers->newEntity();
      $this->set('materialSupplier', $materialSupplier);

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

      $MaterialSupplier_name_list = $this->MaterialSuppliers->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrMaterialSupplier_name_list = array();
      for($j=0; $j<count($MaterialSupplier_name_list); $j++){
        array_push($arrMaterialSupplier_name_list,$MaterialSupplier_name_list[$j]["name"]);
      }
      $arrMaterialSupplier_name_list = array_unique($arrMaterialSupplier_name_list);
      $arrMaterialSupplier_name_list = array_values($arrMaterialSupplier_name_list);
      $this->set('arrMaterialSupplier_name_list', $arrMaterialSupplier_name_list);
    }

    public function editform($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['materialSupplierdata'];

      $materialSupplier = $this->MaterialSuppliers->get($id, [
        'contain' => []
      ]);
      $this->set(compact('materialSupplier'));
      $this->set('id', $id);

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

    public function editconfirm()
    {
      $materialSupplier = $this->MaterialSuppliers->newEntity();
      $this->set('materialSupplier', $materialSupplier);

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $CustomerData = $this->Customers->find()
      ->where(['customer_code' => $data['material_supplier_code']])->toArray();

      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['id IS NOT' => $data['id'], 'material_supplier_code' => $data['material_supplier_code']])->toArray();

      $CustomerData = $CustomerData + $MaterialSuppliers;

      if(isset($CustomerData[0])){
  
        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materialSupplierdata'] = array();
        $_SESSION['materialSupplierdata'] = $id;

        return $this->redirect(['action' => 'editform',
        's' => ['mess' => "仕入先コード：「".$data['material_supplier_code']."」は既に存在します。"]]);

      }
    }

    public function editdo()
    {
      $materialSupplier = $this->MaterialSuppliers->newEntity();
      $this->set('materialSupplier', $materialSupplier);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $MaterialSuppliersmoto = $this->MaterialSuppliers->find()
      ->where(['id' => $data['id']])->toArray();

      $arrMaterialSuppliermoto = array();
      $arrMaterialSuppliermoto = [
        'factory_id' => $MaterialSuppliersmoto[0]["factory_id"],
        'name' => $MaterialSuppliersmoto[0]["name"],
        'material_supplier_code' => $MaterialSuppliersmoto[0]["material_supplier_code"],
        'department' => $MaterialSuppliersmoto[0]["department"],
        'address' => $MaterialSuppliersmoto[0]["address"],
        'yuubin' => $MaterialSuppliersmoto[0]["yuubin"],
        'tel' => $MaterialSuppliersmoto[0]["tel"],
        'fax' => $MaterialSuppliersmoto[0]["fax"],
        'is_active' => 1,
        'delete_flag' => 1,
        'created_at' => $MaterialSuppliersmoto[0]["created_at"]->format("Y-m-d H:i:s"),
        'created_staff' => $MaterialSuppliersmoto[0]["created_staff"],
        'updated_at' => date("Y-m-d H:i:s"),
        'updated_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrupdatematerialType);
      echo "</pre>";
*/
      $MaterialSuppliers = $this->MaterialSuppliers->patchEntity($this->MaterialSuppliers->newEntity(), $data);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
        if ($this->MaterialSuppliers->updateAll(
          ['factory_id' => $data["factory_id"],
           'name' => $data["name"],
           'material_supplier_code' => $data["material_supplier_code"],
           'department' => $data["department"],
           'tel' => $data["tel"],
           'fax' => $data["fax"],
           'yuubin' => $data["yuubin"],
           'address' => $data["address"],
           'updated_at' => date('Y-m-d H:i:s'),
           'updated_staff' => $staff_id],
          ['id'  => $data['id']])){

            $MaterialSuppliers = $this->MaterialSuppliers->patchEntity($this->MaterialSuppliers->newEntity(), $arrMaterialSuppliermoto);
            $this->MaterialSuppliers->save($MaterialSuppliers);

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

      $id = $_SESSION['materialSupplierdata'];

      $materialSupplier = $this->MaterialSuppliers->get($id, [
        'contain' => ["Factories"]
      ]);
      $this->set(compact('materialSupplier'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $materialSupplier = $this->MaterialSuppliers->get($data["id"], [
        'contain' => ["Factories"]
      ]);
      $this->set(compact('materialSupplier'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeleteMaterialSupplier = array();
      $arrdeleteMaterialSupplier = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeleteproduct);
      echo "</pre>";
*/
      $MaterialSuppliers = $this->MaterialSuppliers->patchEntity($this->MaterialSuppliers->newEntity(), $arrdeleteMaterialSupplier);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->MaterialSuppliers->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeleteMaterialSupplier['id']]
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
