<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class CompaniesController extends AppController
{

    public function top()
    {
      return $this->redirect(
       ['controller' => 'Startmenus', 'action' => 'menu']
      );
    }

    public function logout()
    {
      return $this->redirect($this->Auth->logout());
    }

    public function index()
    {
      $companies = $this->Companies->find()->where(['delete_flag' => 0]);
  //    $companies = $this->paginate($this->Companies);
      $companies = $this->paginate($companies);
      $this->set(compact('companies'));
    }

    public function view($id = null)
    {
        $company = $this->Companies->get($id, [
            'contain' => []
        ]);

        $this->set('company', $company);
    }

    public function addform()
    {
      $company = $this->Companies->newEntity();
      $this->set('company', $company);
    }

    public function addcomfirm()
    {
      $company = $this->Companies->newEntity();
      $this->set('company', $company);
    }

    public function adddo()
    {
      $company = $this->Companies->newEntity();
      $this->set('company', $company);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokucompany = array();
      $arrtourokucompany = [
        'name' => $data["name"],
        'address' => $data["address"],
        'tel' => $data["tel"],
        'fax' => $data["fax"],
        'president' => $data["president"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokucompany);
      echo "</pre>";
*/
      //新しいデータを登録
      $Companies = $this->Companies->patchEntity($this->Companies->newEntity(), $arrtourokucompany);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Companies->save($Companies)) {

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
        $company = $this->Companies->get($id, [
            'contain' => []
        ]);
        $this->set(compact('company'));
        $this->set('id', $id);
    }

    public function editconfirm()
    {
      $company = $this->Companies->newEntity();
      $this->set('company', $company);
    }

    public function editdo()
    {
      $company = $this->Companies->newEntity();
      $this->set('company', $company);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdatecompany = array();
      $arrupdatecompany = [
        'id' => $data["id"],
        'name' => $data["name"],
        'address' => $data["address"],
        'tel' => $data["tel"],
        'fax' => $data["fax"],
        'president' => $data["president"],
      ];
/*
      echo "<pre>";
      print_r($arrupdatecompany);
      echo "</pre>";
*/
      $Companies = $this->Companies->patchEntity($this->Companies->newEntity(), $arrupdatecompany);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Companies->updateAll(
           [ 'name' => $arrupdatecompany['name'],
             'address' => $arrupdatecompany['address'],
             'tel' => $arrupdatecompany['tel'],
             'fax' => $arrupdatecompany['fax'],
             'president' => $arrupdatecompany['president'],
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrupdatecompany['id']]
         )){

         $mes = "※下記のように更新されました";
         $this->set('mes',$mes);
         $connection->commit();// コミット5

       } else {

         $mes = "※更新されませんでした";
         $this->set('mes',$mes);
         $this->Flash->error(__('The product could not be saved. Please, try again.'));
         throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

       }

     } catch (Exception $e) {//トランザクション7
     //ロールバック8
       $connection->rollback();//トランザクション9
     }//トランザクション10

    }

    public function deleteconfirm($id = null)
    {
        $company = $this->Companies->get($id, [
            'contain' => []
        ]);
        $this->set(compact('company'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $company = $this->Companies->get($data["id"], [
          'contain' => []
      ]);
      $this->set(compact('company'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeletecompany = array();
      $arrdeletecompany = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletecompany);
      echo "</pre>";
*/
      $Companies = $this->Companies->patchEntity($this->Companies->newEntity(), $arrdeletecompany);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Companies->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeletecompany['id']]
         )){

         $mes = "※以下のデータが削除されました。";
         $this->set('mes',$mes);
         $connection->commit();// コミット5

       } else {

         $mes = "※削除されませんでした";
         $this->set('mes',$mes);
         $this->Flash->error(__('The product could not be saved. Please, try again.'));
         throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

       }

     } catch (Exception $e) {//トランザクション7
     //ロールバック8
       $connection->rollback();//トランザクション9
     }//トランザクション10

    }

}
