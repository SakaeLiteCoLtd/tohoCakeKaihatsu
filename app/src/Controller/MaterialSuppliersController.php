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

    public function index()
    {
        $this->paginate = [
            'contain' => ['Factories']
        ];
        $materialSuppliers = $this->paginate($this->MaterialSuppliers);

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

      }
      $this->set('id', $id);

      $MaterialSuppliers = $this->MaterialSuppliers->find()->contain(["Factories"])
      ->where(['MaterialSuppliers.id' => $id])->toArray();
/*
      echo "<pre>";
      print_r($Products);
      echo "</pre>";
*/
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

    public function edit($id = null)
    {
        $materialSupplier = $this->MaterialSuppliers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $materialSupplier = $this->MaterialSuppliers->patchEntity($materialSupplier, $this->request->getData());
            if ($this->MaterialSuppliers->save($materialSupplier)) {
                $this->Flash->success(__('The material supplier has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The material supplier could not be saved. Please, try again.'));
        }
        $this->set(compact('materialSupplier'));
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

      $arrupdatematerialSupplier = array();
      $arrupdatematerialSupplier = [
        'factory_id' => $data["factory_id"],
        'name' => $data["name"],
        'office' => $data["office"],
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
      print_r($arrupdatematerialType);
      echo "</pre>";
*/
      $MaterialSuppliers = $this->MaterialSuppliers->patchEntity($this->MaterialSuppliers->newEntity(), $arrupdatematerialSupplier);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->MaterialSuppliers->save($MaterialSuppliers)) {

         $this->MaterialSuppliers->updateAll(
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
