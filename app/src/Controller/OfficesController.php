<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;//ログインに使用
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション

class OfficesController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Companies = TableRegistry::get('Companies');
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Companies']
        ];
        $offices = $this->paginate($this->Offices->find()->where(['Offices.delete_flag' => 0]));

        $this->set(compact('offices'));
    }

    public function view($id = null)
    {
        $office = $this->Offices->get($id, [
            'contain' => ['Companies', 'Departments', 'Occupations', 'Positions', 'Staffs']
        ]);

        $this->set('office', $office);
    }

    public function addform()
    {
      $Offices = $this->Offices->newEntity();
      $this->set('Offices', $Offices);

      $Companies = $this->Companies->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrCompanies = array();
			foreach ($Companies as $value) {
				$arrCompanies[] = array($value->id=>$value->name);
			}
      $this->set('arrCompanies', $arrCompanies);
    }

    public function addcomfirm()
    {
      $Offices = $this->Offices->newEntity();
      $this->set('Offices', $Offices);

      $data = $this->request->getData();

      $Companies = $this->Companies->find()
      ->where(['id' => $data['company_id']])->toArray();
      $Company_name = $Companies[0]['name'];
      $this->set('Company_name', $Company_name);
    }

    public function adddo()
    {
      $Offices = $this->Offices->newEntity();
      $this->set('Offices', $Offices);

      $data = $this->request->getData();

      $Companies = $this->Companies->find()
      ->where(['id' => $data['company_id']])->toArray();
      $Company_name = $Companies[0]['name'];
      $this->set('Company_name', $Company_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokuoffice = array();
      $arrtourokuoffice = [
        'name' => $data["name"],
        'company_id' => $data["company_id"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokuoffice);
      echo "</pre>";
*/
      //新しいデータを登録
      $Offices = $this->Offices->patchEntity($this->Offices->newEntity(), $arrtourokuoffice);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Offices->save($Offices)) {

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
        $office = $this->Offices->get($id, [
            'contain' => []
        ]);
        $this->set(compact('office'));
        $this->set('id', $id);

        $Companies = $this->Companies->find()
        ->where(['delete_flag' => 0])->toArray();
        $arrCompanies = array();
  			foreach ($Companies as $value) {
  				$arrCompanies[] = array($value->id=>$value->name);
  			}
        $this->set('arrCompanies', $arrCompanies);
    }

    public function editconfirm()
    {
      $Offices = $this->Offices->newEntity();
      $this->set('Offices', $Offices);

      $data = $this->request->getData();

      $Companies = $this->Companies->find()
      ->where(['id' => $data['company_id']])->toArray();
      $Company_name = $Companies[0]['name'];
      $this->set('Company_name', $Company_name);
    }

    public function editdo()
    {
      $Offices = $this->Offices->newEntity();
      $this->set('Offices', $Offices);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Companies = $this->Companies->find()
      ->where(['id' => $data['company_id']])->toArray();
      $Company_name = $Companies[0]['name'];
      $this->set('Company_name', $Company_name);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdateoffices = array();
      $arrupdateoffices = [
        'id' => $data["id"],
        'name' => $data["name"],
        'company_id' => $data["company_id"],
      ];
/*
      echo "<pre>";
      print_r($arrupdateoffices);
      echo "</pre>";
*/
      $Offices = $this->Offices->patchEntity($this->Offices->newEntity(), $arrupdateoffices);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Offices->updateAll(
           [ 'name' => $arrupdateoffices['name'],
             'company_id' => $arrupdateoffices['company_id'],
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrupdateoffices['id']]
         )){

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
        $office = $this->Offices->get($id, [
          'contain' => ['Companies']
        ]);
        $this->set(compact('office'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $office = $this->Offices->get($data["id"], [
        'contain' => ['Companies']
      ]);
      $this->set(compact('office'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeleteoffices = array();
      $arrdeleteoffices = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletecompany);
      echo "</pre>";
*/
      $Offices = $this->Offices->patchEntity($this->Offices->newEntity(), $arrdeleteoffices);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Offices->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeleteoffices['id']]
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
