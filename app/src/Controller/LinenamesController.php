<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\Auth\DefaultPasswordHasher;//パスワードの更新時のハッシュ化

class LinenamesController extends AppController
{

    public function initialize()
    {
     parent::initialize();
     $this->Staffs = TableRegistry::get('Staffs');
     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');
     $this->Users = TableRegistry::get('Users');
     $this->Factories = TableRegistry::get('Factories');

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
      $linenames = $this->paginate($this->Linenames->find()
      ->where(['Linenames.delete_flag' => 0])
      ->order(["factory_id"=>"ASC"]));

      $this->set('usercheck', 1);

      }else{
        $linenames = $this->paginate($this->Linenames->find()
        ->where(['Linenames.factory_id' => $Users[0]["staff"]["factory_id"], 'Linenames.delete_flag' => 0])
        ->order(["factory_id"=>"ASC"]));

        $this->set('usercheck', 0);

        }

    $this->set(compact('linenames'));

/*
        $this->paginate = [
            'contain' => ['Factories']
        ];
        $linenames = $this->paginate($this->Linenames->find()->where(['Linenames.delete_flag' => 0]));

        $this->set(compact('linenames'));

      */
    
    }

    public function addform()
    {
      $linenames = $this->Linenames->newEntity();
      $this->set('linenames', $linenames);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }

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
        $linenames = $this->Linenames->newEntity();
        $this->set('linenames', $linenames);

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

        if(isset($data["factory_id"])){
          $factory_id = $data["factory_id"];
        }else{
          $session = $this->request->getSession();
          $datasession = $session->read();
          $staff_id = $datasession['Auth']['User']['staff_id'];

          $Staffs = $this->Staffs->find()
          ->where(['id' => $staff_id])
          ->toArray();
          $factory_id = $Staffs[0]["factory_id"];
        }
  
        $Linenames = $this->Linenames->find()->where(['factory_id' => $factory_id
        , 'machine_num' => $data["machine_num"], 'delete_flag' => 0])->toArray();

        if(isset($Linenames[0])){

          return $this->redirect(['action' => 'addform',
          's' => ['mess' => "ライン番号".$data["machine_num"]."は既に存在します。"]]);

        }
  
    }

    public function adddo()
    {
      $linenames = $this->Linenames->newEntity();
      $this->set('linenames', $linenames);

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

      $arrtourokuLinenames = array();
      $arrtourokuLinenames = [
        'factory_id' => $factory_id,
        'machine_num' => $data["machine_num"],
        'name' => $data["name"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokuLinenames);
      echo "</pre>";
*/
      //新しいデータを登録
      $linenames = $this->Linenames->patchEntity($this->Linenames->newEntity(), $arrtourokuLinenames);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Linenames->save($linenames)) {

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
        $linenames = $this->Linenames->newEntity();
        $this->set('linenames', $linenames);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materiallinename'] = array();
        $_SESSION['materiallinename'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materiallinename'] = array();
        $_SESSION['materiallinename'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }elseif(isset($data["kensaku"])){
  
        $linenames = $this->Linenames->find()->where(['type' => $data['name']])->toArray();

        if(!isset($linenames[0])){

          return $this->redirect(['action' => 'editpreform',
          's' => ['mess' => "ライン：「".$data['name']."」は存在しません。"]]);
  
        }else{
          $id = $linenames[0]["id"];
        }

      }
      $this->set('id', $id);

      $linenames = $this->Linenames->find()
      ->where(['id' => $id])->toArray();
      $name = $linenames[0]["name"];
      $this->set('name', $name);
      $machine_num = $linenames[0]["machine_num"];
      $this->set('machine_num', $machine_num);

    }

    public function editform($id = null)
    {
      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['materiallinename'];
      $Linenames = $this->Linenames->find()->where(['id' => $id])->toArray();
      $machine_num = $Linenames[0]["machine_num"];
      $this->set('machine_num',$machine_num);

      $linename = $this->Linenames->get($id, [
        'contain' => []
      ]);
      $this->set(compact('linename'));
      $this->set('id', $id);
    }

    public function editconfirm()
    {
      $linenames = $this->Linenames->newEntity();
      $this->set('linenames', $linenames);

      $data = $this->request->getData();

      $Linenames = $this->Linenames->find()->where(['id' => $data['id']])->toArray();
      $factory_id = $Linenames[0]["factory_id"];

      $Linenames = $this->Linenames->find()->where(['id IS NOT' => $data['id'], 'factory_id' => $factory_id
      , 'machine_num' => $data["machine_num"], 'delete_flag' => 0])->toArray();

      if(isset($Linenames[0])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materiallinename'] = array();
        $_SESSION['materiallinename'] = $id;

        return $this->redirect(['action' => 'editform',
        's' => ['mess' => "ライン番号".$data["machine_num"]."は既に存在します。"]]);

      }

  }

    public function editdo()
    {
        $linenames = $this->Linenames->newEntity();
        $this->set('linenames', $linenames);
  
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $linenamesmoto = $this->Linenames->find()
      ->where(['id' => $data['id']])->toArray();
      
      $arrupdateLinenames = array();
      $arrupdateLinenames = [
        'factory_id' => $linenamesmoto[0]["factory_id"],
        'machine_num' => $linenamesmoto[0]["machine_num"],
        'name' => $linenamesmoto[0]["name"],
        'delete_flag' => 1,
        'created_at' => $linenamesmoto[0]["created_at"]->format("Y-m-d H:i:s"),
        'created_staff' => $linenamesmoto[0]["created_staff"],
        'updated_at' => date("Y-m-d H:i:s"),
        'updated_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrupdatematerialType);
      echo "</pre>";
*/
      $linenames = $this->Linenames->patchEntity($this->Linenames->newEntity(), $arrupdateLinenames);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Linenames->save($linenames)) {

         $this->Linenames->updateAll(
           [
            'machine_num' => $data["machine_num"],
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

      $id = $_SESSION['materiallinename'];

      $linename = $this->Linenames->get($id, [
        'contain' => []
      ]);
      $this->set(compact('linename'));
      $this->set('id', $id);
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $linename = $this->Linenames->get($data["id"], [
          'contain' => ["Factories"]
      ]);
      $this->set(compact('linename'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeleteLinenames = array();
      $arrdeleteLinenames = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletematerialType);
      echo "</pre>";
*/
      $linenames = $this->Linenames->patchEntity($this->Linenames->newEntity(), $arrdeleteLinenames);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Linenames->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeleteLinenames['id']]
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
