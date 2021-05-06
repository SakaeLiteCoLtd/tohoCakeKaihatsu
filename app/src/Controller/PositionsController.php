<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class PositionsController extends AppController
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
        $positions = $this->paginate($this->Positions->find()->where(['Positions.delete_flag' => 0]));

        $this->set(compact('positions'));
    }

    public function view($id = null)
    {
        $position = $this->Positions->get($id, [
            'contain' => ['Factories', 'Staffs']
        ]);

        $this->set('position', $position);
    }

    public function addform()
    {
      $position = $this->Positions->newEntity();
      $this->set('position', $position);

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
      $position = $this->Positions->newEntity();
      $this->set('position', $position);

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

    }

    public function adddo()
    {
      $position = $this->Positions->newEntity();
      $this->set('position', $position);

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokuposition = array();
      $arrtourokuposition = [
        'position' => $data["position"],
        'factory_id' => $data["factory_id"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokuposition);
      echo "</pre>";
*/
      //新しいデータを登録
      $Positions = $this->Positions->patchEntity($this->Positions->newEntity(), $arrtourokuposition);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Positions->save($Positions)) {

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
        $position = $this->Positions->get($id, [
            'contain' => []
        ]);
        $this->set(compact('position'));
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
      $position = $this->Positions->newEntity();
      $this->set('position', $position);

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);
    }

    public function editdo()
    {
      $position = $this->Positions->newEntity();
      $this->set('position', $position);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdateposition = array();
      $arrupdateposition = [
        'position' => $data["position"],
        'factory_id' => $data["factory_id"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrupdateposition);
      echo "</pre>";
*/
      $Positions = $this->Positions->patchEntity($this->Positions->newEntity(), $arrupdateposition);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Positions->save($Positions)) {

         $this->Positions->updateAll(
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
        $position = $this->Positions->get($id, [
          'contain' => ['Factories']
        ]);
        $this->set(compact('position'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $position = $this->Positions->get($data["id"], [
        'contain' => ['Factories']
      ]);
      $this->set(compact('position'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeleteposition = array();
      $arrdeleteposition = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletecompany);
      echo "</pre>";
*/
      $Occupations = $this->Positions->patchEntity($this->Positions->newEntity(), $arrdeleteposition);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Positions->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeleteposition['id']]
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
