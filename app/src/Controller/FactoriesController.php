<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;//ログインに使用
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション

class FactoriesController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Companies = TableRegistry::get('Companies');
     $this->Staffs = TableRegistry::get('Staffs');
     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

       return $this->redirect($this->Auth->logout());

     }

     if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

      return $this->redirect($this->Auth->logout());

     }
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Companies']
        ];
        $Factories = $this->paginate($this->Factories->find()->where(['Factories.delete_flag' => 0]));

        $this->set(compact('Factories'));
    }

    public function detail($id = null)
    {
      $factory = $this->Factories->newEntity();
      $this->set('factory', $factory);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['factorydata'] = array();
        $_SESSION['factorydata'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['factorydata'] = array();
        $_SESSION['factorydata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }
      $this->set('id', $id);

      $Factories = $this->Factories->find()->contain(["Companies", "Staffs"])
      ->where(['Factories.id' => $id])->toArray();
/*
      echo "<pre>";
      print_r($Products);
      echo "</pre>";
*/
      $Company_name = $Factories[0]["company"]['name'];
      $this->set('Company_name', $Company_name);
      $staff_name = $Factories[0]["staff"]['name'];
      $this->set('staff_name', $staff_name);
      $name = $Factories[0]["name"];
      $this->set('name', $name);

    }

    public function view($id = null)
    {
        $factory = $this->Factories->get($id, [
            'contain' => ['Companies', 'Departments', 'Occupations', 'Positions', 'Staffs']
        ]);

        $this->set('factory', $factory);
    }

    public function addform()
    {
      $Factories = $this->Factories->newEntity();
      $this->set('Factories', $Factories);

      $Companies = $this->Companies->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrCompanies = array();
			foreach ($Companies as $value) {
				$arrCompanies[] = array($value->id=>$value->name);
			}
      $this->set('arrCompanies', $arrCompanies);

      $Staffs = $this->Staffs->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrStaffs = array();
			foreach ($Staffs as $value) {
				$arrStaffs[] = array($value->id=>$value->name);
			}
      $this->set('arrStaffs', $arrStaffs);

    }

    public function addcomfirm()
    {
      $Factories = $this->Factories->newEntity();
      $this->set('Factories', $Factories);

      $data = $this->request->getData();

      $Companies = $this->Companies->find()
      ->where(['id' => $data['company_id']])->toArray();
      $Company_name = $Companies[0]['name'];
      $this->set('Company_name', $Company_name);

      $Staffs = $this->Staffs->find()
      ->where(['id' => $data['staff_id']])->toArray();
      $staff_name = $Staffs[0]['name'];
      $this->set('staff_name', $staff_name);
    }

    public function adddo()
    {
      $Factories = $this->Factories->newEntity();
      $this->set('Factories', $Factories);

      $data = $this->request->getData();

      $Companies = $this->Companies->find()
      ->where(['id' => $data['company_id']])->toArray();
      $Company_name = $Companies[0]['name'];
      $this->set('Company_name', $Company_name);

      $Staffs = $this->Staffs->find()
      ->where(['id' => $data['staff_id']])->toArray();
      $staff_name = $Staffs[0]['name'];
      $this->set('staff_name', $staff_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokufactory = array();
      $arrtourokufactory = [
        'name' => $data["name"],
        'company_id' => $data["company_id"],
        'staff_id' => $data["staff_id"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokufactory);
      echo "</pre>";
*/
      //新しいデータを登録
      $Factories = $this->Factories->patchEntity($this->Factories->newEntity(), $arrtourokufactory);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Factories->save($Factories)) {

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

      $id = $_SESSION['factorydata'];

        $factory = $this->Factories->get($id, [
            'contain' => []
        ]);
        $this->set(compact('factory'));
        $this->set('id', $id);

        $Companies = $this->Companies->find()
        ->where(['delete_flag' => 0])->toArray();
        $arrCompanies = array();
  			foreach ($Companies as $value) {
  				$arrCompanies[] = array($value->id=>$value->name);
  			}
        $this->set('arrCompanies', $arrCompanies);

        $Staffs = $this->Staffs->find()
        ->where(['delete_flag' => 0])->toArray();
        $arrStaffs = array();
  			foreach ($Staffs as $value) {
  				$arrStaffs[] = array($value->id=>$value->name);
  			}
        $this->set('arrStaffs', $arrStaffs);

    }

    public function editconfirm()
    {
      $Factories = $this->Factories->newEntity();
      $this->set('Factories', $Factories);

      $data = $this->request->getData();

      $Companies = $this->Companies->find()
      ->where(['id' => $data['company_id']])->toArray();
      $Company_name = $Companies[0]['name'];
      $this->set('Company_name', $Company_name);

      $Staffs = $this->Staffs->find()
      ->where(['id' => $data['staff_id']])->toArray();
      $staff_name = $Staffs[0]['name'];
      $this->set('staff_name', $staff_name);
    }

    public function editdo()
    {
      $Factories = $this->Factories->newEntity();
      $this->set('Factories', $Factories);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Companies = $this->Companies->find()
      ->where(['id' => $data['company_id']])->toArray();
      $Company_name = $Companies[0]['name'];
      $this->set('Company_name', $Company_name);

      $Staffs = $this->Staffs->find()
      ->where(['id' => $data['staff_id']])->toArray();
      $staff_name = $Staffs[0]['name'];
      $this->set('staff_name', $staff_name);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdateFactories = array();
      $arrupdateFactories = [
        'name' => $data["name"],
        'company_id' => $data["company_id"],
        'staff_id' => $data["staff_id"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrupdateFactories);
      echo "</pre>";
*/
      $Factories = $this->Factories->patchEntity($this->Factories->newEntity(), $arrupdateFactories);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Factories->save($Factories)) {

         $this->Factories->updateAll(
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

      $id = $_SESSION['factorydata'];

        $factory = $this->Factories->get($id, [
          'contain' => ['Companies', 'Staffs']
        ]);
        $this->set(compact('factory'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $factory = $this->Factories->get($data["id"], [
        'contain' => ['Companies', 'Staffs']
      ]);
      $this->set(compact('factory'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeleteFactories = array();
      $arrdeleteFactories = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletecompany);
      echo "</pre>";
*/
      $Factories = $this->Factories->patchEntity($this->Factories->newEntity(), $arrdeleteFactories);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Factories->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeleteFactories['id']]
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
