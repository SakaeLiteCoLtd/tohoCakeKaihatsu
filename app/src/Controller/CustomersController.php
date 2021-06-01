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
     $this->Factories = TableRegistry::get('Factories');
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
      $address = $Customers[0]["address"];
      $this->set('address', $address);
      $tel = $Customers[0]["tel"];
      $this->set('tel', $tel);
      $fax = $Customers[0]["fax"];
      $this->set('fax', $fax);

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
    //    'office' => $data["office"],
    //    'department' => $data["department"],
        'tel' => $data["tel"],
        'fax' => $data["fax"],
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

    public function edit($id = null)
    {
        $customer = $this->Customers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $customer = $this->Customers->patchEntity($customer, $this->request->getData());
            if ($this->Customers->save($customer)) {
                $this->Flash->success(__('The customer has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The customer could not be saved. Please, try again.'));
        }
        $this->set(compact('customer'));
    }

    public function editform($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['customerdata'];

      $customer = $this->Customers->get($id, [
        'contain' => []
      ]);
      $this->set(compact('customer'));
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
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

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

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdatecustomer = array();
      $arrupdatecustomer = [
        'factory_id' => $data["factory_id"],
        'name' => $data["name"],
  //      'office' => $data["office"],
  //      'department' => $data["department"],
        'tel' => $data["tel"],
        'fax' => $data["fax"],
        'address' => $data["address"],
        'is_active' => 0,
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $Customers = $this->Customers->patchEntity($this->Customers->newEntity(), $arrupdatecustomer);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Customers->save($Customers)) {

         $this->Customers->updateAll(
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
