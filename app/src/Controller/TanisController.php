<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\Auth\DefaultPasswordHasher;//パスワードの更新時のハッシュ化

class TanisController extends AppController
{

    public function initialize()
    {
     parent::initialize();
     $this->Staffs = TableRegistry::get('Staffs');
     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

      return $this->redirect($this->Auth->logout());

    }

    if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

      $Groups = $this->Groups->find()->contain(["Menus"])
      ->where(['Groups.group_name_id' => $datasession['Auth']['User']['group_name_id'], 'Menus.id' => 29, 'Groups.delete_flag' => 0])
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
        $tanis = $this->paginate($this->Tanis->find()->where(['Tanis.delete_flag' => 0]));

        $this->set(compact('tanis'));
    }

    public function addform()
    {
      $tanis = $this->Tanis->newEntity();
      $this->set('tanis', $tanis);
    }

    public function addcomfirm()
    {
        $tanis = $this->Tanis->newEntity();
        $this->set('tanis', $tanis);
    }

    public function adddo()
    {
        $tanis = $this->Tanis->newEntity();
        $this->set('tanis', $tanis);

      $data = $this->request->getData();

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $Staffs = $this->Staffs->find()
      ->where(['id' => $staff_id])
      ->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      $arrtourokuTanis = array();
      $arrtourokuTanis = [
        'factory_id' => $factory_id,
        'name' => $data["name"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokuTanis);
      echo "</pre>";
*/
      //新しいデータを登録
      $tanis = $this->Tanis->patchEntity($this->Tanis->newEntity(), $arrtourokuTanis);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Tanis->save($tanis)) {

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
        $tanis = $this->Tanis->newEntity();
        $this->set('tanis', $tanis);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materialtani'] = array();
        $_SESSION['materialtani'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materialtani'] = array();
        $_SESSION['materialtani'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }elseif(isset($data["kensaku"])){
  
        $tanis = $this->Tanis->find()->where(['type' => $data['name']])->toArray();

        if(!isset($tanis[0])){

          return $this->redirect(['action' => 'editpreform',
          's' => ['mess' => "単位：「".$data['name']."」は存在しません。"]]);
  
        }else{
          $id = $tanis[0]["id"];
        }

      }
      $this->set('id', $id);

      $tanis = $this->Tanis->find()
      ->where(['id' => $id])->toArray();
      $name = $tanis[0]["name"];
      $this->set('name', $name);

    }

    public function editform($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['materialtani'];

      $tani = $this->Tanis->get($id, [
        'contain' => []
      ]);
      $this->set(compact('tani'));
      $this->set('id', $id);
    }

    public function editconfirm()
    {
        $tanis = $this->Tanis->newEntity();
        $this->set('tanis', $tanis);
    }

    public function editdo()
    {
        $tanis = $this->Tanis->newEntity();
        $this->set('tanis', $tanis);
  
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $tanismoto = $this->Tanis->find()
      ->where(['id' => $data['id']])->toArray();
      
      $arrupdateTanis = array();
      $arrupdateTanis = [
        'factory_id' => $tanismoto[0]["factory_id"],
        'name' => $tanismoto[0]["name"],
        'delete_flag' => 1,
        'created_at' => $tanismoto[0]["created_at"]->format("Y-m-d H:i:s"),
        'created_staff' => $tanismoto[0]["created_staff"],
        'updated_at' => date("Y-m-d H:i:s"),
        'updated_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrupdatematerialType);
      echo "</pre>";
*/
      $tanis = $this->Tanis->patchEntity($this->Tanis->newEntity(), $arrupdateTanis);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Tanis->save($tanis)) {

         $this->Tanis->updateAll(
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

      $id = $_SESSION['materialtani'];

      $tani = $this->Tanis->get($id, [
        'contain' => []
      ]);
      $this->set(compact('tani'));
      $this->set('id', $id);
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $tani = $this->Tanis->get($data["id"], [
          'contain' => ["Factories"]
      ]);
      $this->set(compact('tani'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeleteTanis = array();
      $arrdeleteTanis = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletematerialType);
      echo "</pre>";
*/
      $tanis = $this->Tanis->patchEntity($this->Tanis->newEntity(), $arrdeleteTanis);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Tanis->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeleteTanis['id']]
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
