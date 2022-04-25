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
     $this->Users = TableRegistry::get('Users');
     $this->Staffs = TableRegistry::get('Staffs');
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
       ->where(['Groups.group_name_id' => $datasession['Auth']['User']['group_name_id'], 'Menus.id' => 31, 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }

     }

    }

    public function index()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $Users = $this->Users->find()->contain(["Staffs"])
      ->where(['Staffs.id' => $datasession['Auth']['User']['staff_id'], 'Users.delete_flag' => 0])
      ->toArray();

        $this->paginate = [
            'contain' => ['Factories']
        ];
        if($Users[0]["staff"]["factory_id"] == 5){//本部の場合
          $positions = $this->paginate($this->Positions->find()
          ->where(['Positions.delete_flag' => 0]));
            }else{
              $positions = $this->paginate($this->Positions->find()
              ->where(['Positions.factory_id' => $Users[0]["staff"]["factory_id"], 'Positions.delete_flag' => 0]));
                }

        $this->set(compact('positions'));
    }

    public function detail($id = null)
    {
      $position = $this->Positions->newEntity();
      $this->set('position', $position);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['positiondata'] = array();
        $_SESSION['positiondata'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['positiondata'] = array();
        $_SESSION['positiondata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }
      $this->set('id', $id);

      $Positions = $this->Positions->find()->contain(["Factories"])
      ->where(['Positions.id' => $id])->toArray();

      $position = $Positions[0]["position"];
      $this->set('position', $position);
      $factory_name = $Positions[0]["factory"]['name'];
      $this->set('factory_name', $factory_name);

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

      $session = $this->request->getSession();
      $datasession = $session->read();

      $Users = $this->Users->find()->contain(["Staffs"])
      ->where(['Staffs.id' => $datasession['Auth']['User']['staff_id'], 'Users.delete_flag' => 0])
      ->toArray();

      if($Users[0]["staff"]["factory_id"] == 5){//本部の場合
  
        $this->set('usercheck', 1);
  
        }else{
  
          $this->set('usercheck', 0);
  
          }
  
          $Factories = $this->Factories->find('list');
          $this->set(compact('Factories'));

    }

    public function addcomfirm()
    {
      $position = $this->Positions->newEntity();
      $this->set('position', $position);

      $data = $this->request->getData();

      $this->set('usercheck', 0);

      if(isset($data["factory_id"])){
        $factory_id = $data["factory_id"];

        $this->set('usercheck', 1);

        $Factories = $this->Factories->find()
        ->where(['id' => $factory_id])
        ->toArray();
        $factory_name = $Factories[0]["name"];
        $this->set('factory_name', $factory_name);
      }

    }

    public function adddo()
    {
      $position = $this->Positions->newEntity();
      $this->set('position', $position);

      $data = $this->request->getData();

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];
      $this->set('usercheck', 0);

      if(isset($data["factory_id"])){
        $this->set('usercheck', 1);
        $factory_id = $data["factory_id"];
        $factory_name = $data["factory_name"];
        $this->set('factory_name', $factory_name);
      }else{
        $Staffs = $this->Staffs->find()
        ->where(['id' => $staff_id])
        ->toArray();
        $factory_id = $Staffs[0]["factory_id"];
      }

      $arrtourokuposition = array();
      $arrtourokuposition = [
        'position' => $data["position"],
        'factory_id' => $factory_id,
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];

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
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['positiondata'];

        $position = $this->Positions->get($id, [
            'contain' => []
        ]);
        $this->set(compact('position'));
        $this->set('id', $id);
    }

    public function editconfirm()
    {
      $position = $this->Positions->newEntity();
      $this->set('position', $position);
    }

    public function editdo()
    {
      $position = $this->Positions->newEntity();
      $this->set('position', $position);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $Positionsmoto = $this->Positions->find()
      ->where(['id' => $data['id']])->toArray();

      $arrupdateposition = array();
      $arrupdateposition = [
        'position' => $Positionsmoto[0]["position"],
        'factory_id' => $Positionsmoto[0]["factory_id"],
        'delete_flag' => 1,
        'created_at' => $Positionsmoto[0]["created_at"]->format("Y-m-d H:i:s"),
        'created_staff' => $Positionsmoto[0]["created_staff"],
        'updated_at' => date("Y-m-d H:i:s"),
        'updated_staff' => $staff_id
      ];

      $Positions = $this->Positions->patchEntity($this->Positions->newEntity(), $arrupdateposition);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Positions->save($Positions)) {

         $this->Positions->updateAll(
           [ 'position' => $data['position'],
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

      $id = $_SESSION['positiondata'];

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
