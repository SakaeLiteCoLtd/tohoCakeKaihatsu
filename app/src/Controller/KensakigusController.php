<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\Auth\DefaultPasswordHasher;//パスワードの更新時のハッシュ化

class KensakigusController extends AppController
{

    public function initialize()
    {
     parent::initialize();
     $this->Staffs = TableRegistry::get('Staffs');
     $this->Factories = TableRegistry::get('Factories');
     $this->Users = TableRegistry::get('Users');
     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

       return $this->redirect($this->Auth->logout());

     }

     if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

       $Groups = $this->Groups->find()->contain(["Menus"])
       ->where(['Groups.group_code' => $datasession['Auth']['User']['group_code'], 'Menus.id' => 40, 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }

     }
    }

    public function index()
    {
      /*
        $this->paginate = [
            'contain' => ['Factories']
        ];
        $kensakigus = $this->paginate($this->Kensakigus->find()->where(['Kensakigus.delete_flag' => 0]));

        $this->set(compact('kensakigus'));
*/
        $session = $this->request->getSession();
        $datasession = $session->read();
  
        $Users = $this->Users->find()->contain(["Staffs"])
        ->where(['Staffs.id' => $datasession['Auth']['User']['staff_id'], 'Users.delete_flag' => 0])
        ->toArray();
  
        $this->paginate = [
          'limit' => 13,
          'contain' => ['Factories']
      ];
  
      if($Users[0]["staff"]["factory_id"] == 5){//本部の場合
        $kensakigus = $this->paginate($this->Kensakigus->find()
        ->where(['Kensakigus.delete_flag' => 0])
        ->order(["factory_id"=>"ASC"]));
  
        $this->set('usercheck', 1);
  
        }else{
          $kensakigus = $this->paginate($this->Kensakigus->find()
          ->where(['Kensakigus.factory_id' => $Users[0]["staff"]["factory_id"], 'Kensakigus.delete_flag' => 0])
          ->order(["factory_id"=>"ASC"]));
  
          $this->set('usercheck', 0);
  
          }
  
      $this->set(compact('kensakigus'));
  
    }

    public function addform()
    {
      $kensakigus = $this->Kensakigus->newEntity();
      $this->set('kensakigus', $kensakigus);

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
        $kensakigus = $this->Kensakigus->newEntity();
        $this->set('kensakigus', $kensakigus);

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
        $kensakigus = $this->Kensakigus->newEntity();
        $this->set('kensakigus', $kensakigus);

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

      $arrtourokuKensakigus = array();
      $arrtourokuKensakigus = [
        'factory_id' => $factory_id,
        'name' => $data["name"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokuKensakigus);
      echo "</pre>";
*/
      //新しいデータを登録
      $kensakigus = $this->Kensakigus->patchEntity($this->Kensakigus->newEntity(), $arrtourokuKensakigus);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Kensakigus->save($kensakigus)) {

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

    public function detail($id = null)
    {
        $kensakigus = $this->Kensakigus->newEntity();
        $this->set('kensakigus', $kensakigus);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materialkensakigu'] = array();
        $_SESSION['materialkensakigu'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materialkensakigu'] = array();
        $_SESSION['materialkensakigu'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }elseif(isset($data["kensaku"])){
  
        $kensakigus = $this->Kensakigus->find()->where(['type' => $data['name']])->toArray();

        if(!isset($kensakigus[0])){

          return $this->redirect(['action' => 'editpreform',
          's' => ['mess' => "検査器具：「".$data['name']."」は存在しません。"]]);
  
        }else{
          $id = $kensakigus[0]["id"];
        }

      }
      $this->set('id', $id);

      $kensakigus = $this->Kensakigus->find()
      ->where(['id' => $id])->toArray();
      $name = $kensakigus[0]["name"];
      $this->set('name', $name);

    }

    public function editform($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['materialkensakigu'];

      $kensakigu = $this->Kensakigus->get($id, [
        'contain' => []
      ]);
      $this->set(compact('kensakigu'));
      $this->set('id', $id);
    }

    public function editconfirm()
    {
        $kensakigus = $this->Kensakigus->newEntity();
        $this->set('kensakigus', $kensakigus);
    }

    public function editdo()
    {
        $kensakigus = $this->Kensakigus->newEntity();
        $this->set('kensakigus', $kensakigus);
  
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $kensakigusmoto = $this->Kensakigus->find()
      ->where(['id' => $data['id']])->toArray();
      
      $arrupdateKensakigus = array();
      $arrupdateKensakigus = [
        'factory_id' => $kensakigusmoto[0]["factory_id"],
        'name' => $kensakigusmoto[0]["name"],
        'delete_flag' => 1,
        'created_at' => $kensakigusmoto[0]["created_at"]->format("Y-m-d H:i:s"),
        'created_staff' => $kensakigusmoto[0]["created_staff"],
        'updated_at' => date("Y-m-d H:i:s"),
        'updated_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrupdatematerialType);
      echo "</pre>";
*/
      $kensakigus = $this->Kensakigus->patchEntity($this->Kensakigus->newEntity(), $arrupdateKensakigus);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Kensakigus->save($kensakigus)) {

         $this->Kensakigus->updateAll(
           [
           'name' => $data["name"],
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

      $id = $_SESSION['materialkensakigu'];

      $kensakigu = $this->Kensakigus->get($id, [
        'contain' => []
      ]);
      $this->set(compact('kensakigu'));
      $this->set('id', $id);
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $kensakigu = $this->Kensakigus->get($data["id"], [
          'contain' => ["Factories"]
      ]);
      $this->set(compact('kensakigu'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeleteKensakigus = array();
      $arrdeleteKensakigus = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletematerialType);
      echo "</pre>";
*/
      $kensakigus = $this->Kensakigus->patchEntity($this->Kensakigus->newEntity(), $arrdeleteKensakigus);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Kensakigus->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeleteKensakigus['id']]
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
