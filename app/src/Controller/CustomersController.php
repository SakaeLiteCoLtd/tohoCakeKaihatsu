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

    public function index()
    {
        $customers = $this->paginate($this->Customers);

        $this->set(compact('customers'));
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
    }

    public function addcomfirm()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);
    }

    public function adddo()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $data = $this->request->getData();

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokucustomer = array();
      $arrtourokucustomer = [
        'name' => $data["name"],
        'office' => $data["office"],
        'department' => $data["department"],
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
      $customer = $this->Customers->get($id, [
        'contain' => []
      ]);
      $this->set(compact('customer'));
      $this->set('id', $id);
    }

    public function editconfirm()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);
    }

    public function editdo()
    {
      $customer = $this->Customers->newEntity();
      $this->set('customer', $customer);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdatecustomer = array();
      $arrupdatecustomer = [
        'name' => $data["name"],
        'office' => $data["office"],
        'department' => $data["department"],
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
