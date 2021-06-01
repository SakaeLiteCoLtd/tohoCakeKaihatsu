<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class DepartmentsController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Factories = TableRegistry::get('Factories');
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Factories']
        ];
        $departments = $this->paginate($this->Departments->find()->where(['Departments.delete_flag' => 0]));

        $this->set(compact('departments'));
    }

    public function detail($id = null)
    {
      $department = $this->Departments->newEntity();
      $this->set('department', $department);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['departmentdata'] = array();
        $_SESSION['departmentdata'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['departmentdata'] = array();
        $_SESSION['departmentdata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }
      $this->set('id', $id);

      $Departments = $this->Departments->find()->contain(["Factories"])
      ->where(['Departments.id' => $id])->toArray();

      $department = $Departments[0]["department"];
      $this->set('department', $department);
      $factory_name = $Departments[0]["factory"]['name'];
      $this->set('factory_name', $factory_name);

    }

    public function view($id = null)
    {
        $department = $this->Departments->get($id, [
            'contain' => ['Factories', 'Staffs']
        ]);

        $this->set('department', $department);
    }

    public function addform()
    {
      $department = $this->Departments->newEntity();
      $this->set('department', $department);

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
      $department = $this->Departments->newEntity();
      $this->set('department', $department);

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

    }

    public function adddo()
    {
      $department = $this->Departments->newEntity();
      $this->set('department', $department);

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokudepartment = array();
      $arrtourokudepartment = [
        'department' => $data["department"],
        'factory_id' => $data["factory_id"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokudepartment);
      echo "</pre>";
*/
      //新しいデータを登録
      $Departments = $this->Departments->patchEntity($this->Departments->newEntity(), $arrtourokudepartment);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Departments->save($Departments)) {

          $connection->commit();// コミット5
          $mes = "以下のように登録されました。";
          $this->set('mes',$mes);

        } else {

          $this->Flash->error(__('The data could not be saved. Please, try again.'));
          throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
          $mes = "※登録されませんでした";
          $this->set('mes',$mes);

        }

      } catch (Exception $e) {//トランザクション7
      //ロールバック8
        $connection->rollback();//トランザクション9
      }//トランザクション10

    }

    public function editform($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['departmentdata'];

      $department = $this->Departments->get($id, [
          'contain' => []
      ]);
      $this->set(compact('department'));
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
      $department = $this->Departments->newEntity();
      $this->set('department', $department);

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);
    }

    public function editdo()
    {
      $department = $this->Departments->newEntity();
      $this->set('department', $department);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdatedepartment = array();
      $arrupdatedepartment = [
        'department' => $data["department"],
        'factory_id' => $data["factory_id"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrupdatedepartment);
      echo "</pre>";
*/
      $Departments = $this->Departments->patchEntity($this->Departments->newEntity(), $arrupdatedepartment);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Departments->save($Departments)) {

         $this->Departments->updateAll(
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

      $id = $_SESSION['departmentdata'];

      $department = $this->Departments->get($id, [
        'contain' => ['Factories']
      ]);
      $this->set(compact('department'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $department = $this->Departments->get($data["id"], [
        'contain' => ['Factories']
      ]);
      $this->set(compact('department'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeletedepartment = array();
      $arrdeletedepartment = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletecompany);
      echo "</pre>";
*/
      $Departments = $this->Departments->patchEntity($this->Departments->newEntity(), $arrdeletedepartment);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Departments->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeletedepartment['id']]
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
