<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class MenusController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

       return $this->redirect($this->Auth->logout());

     }

     if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

       $Groups = $this->Groups->find()->contain(["Menus"])
       ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "メニュー", 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }

     }

    }

    public function index()
    {
        $menus = $this->paginate($this->Menus->find()->where(['Menus.delete_flag' => 0]));

        $this->set(compact('menus'));
    }

    public function detail($id = null)
    {
      $menu = $this->Menus->newEntity();
      $this->set('menu', $menu);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['menudata'] = array();
        $_SESSION['menudata'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['menudata'] = array();
        $_SESSION['menudata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }
      $this->set('id', $id);

      $Menus = $this->Menus->find()
      ->where(['Menus.id' => $id])->toArray();

      $name_menu = $Menus[0]["name_menu"];
      $this->set('name_menu', $name_menu);

    }

    public function view($id = null)
    {
        $menu = $this->Menus->get($id, [
            'contain' => ['Groups', 'StaffAbilities']
        ]);

        $this->set('menu', $menu);
    }

    public function addform()
    {
      $menu = $this->Menus->newEntity();
      $this->set('menu', $menu);
    }

    public function addcomfirm()
    {
      $menu = $this->Menus->newEntity();
      $this->set('menu', $menu);
    }

    public function adddo()
    {
      $menus = $this->Menus->newEntity();
      $this->set('menus', $menus);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokumenu = array();
      $arrtourokumenu = [
        'name_menu' => $data["name_menu"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];

      //新しいデータを登録
      $Menus = $this->Menus->patchEntity($this->Menus->newEntity(), $arrtourokumenu);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Menus->save($Menus)) {

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

      $id = $_SESSION['menudata'];

        $menu = $this->Menus->get($id, [
            'contain' => []
        ]);
        $this->set(compact('menu'));
        $this->set('id', $id);
    }

    public function editconfirm()
    {
      $menu = $this->Menus->newEntity();
      $this->set('menu', $menu);
    }

    public function editdo()
    {
      $menu = $this->Menus->newEntity();
      $this->set('menu', $menu);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdatemenus = array();
      $arrupdatemenus = [
        'name_menu' => $data["name_menu"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrupdatemenus);
      echo "</pre>";
*/
      $Menus = $this->Menus->patchEntity($this->Menus->newEntity(), $arrupdatemenus);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Menus->save($Menus)) {

         $this->Menus->updateAll(
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

      $id = $_SESSION['menudata'];

        $menu = $this->Menus->get($id, [
          'contain' => []
        ]);
        $this->set(compact('menu'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $menu = $this->Menus->get($data["id"], [
        'contain' => []
      ]);
      $this->set(compact('menu'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeletemenu = array();
      $arrdeletemenu = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletemenu);
      echo "</pre>";
*/
      $Menus = $this->Menus->patchEntity($this->Menus->newEntity(), $arrdeletemenu);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Menus->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeletemenu['id']]
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
