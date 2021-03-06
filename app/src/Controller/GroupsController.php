<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;//ログインに使用
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション

class GroupsController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');
     $this->GroupNames = TableRegistry::get('GroupNames');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

       return $this->redirect($this->Auth->logout());

     }

     if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

       $Groups = $this->Groups->find()->contain(["Menus"])
       ->where(['Groups.group_name_id' => $datasession['Auth']['User']['group_name_id'], 'Menus.id' => 32, 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }

     }
    }

    public function index()
    {
        $this->paginate = [
        ];
        $groups = $this->paginate($this->GroupNames->find()
        ->where(['delete_flag' => 0]));

        $this->set(compact('groups'));

        $GroupNames = $this->GroupNames->find()
        ->where(['delete_flag' => 0])->toArray();
        $this->set('GroupNames', $GroupNames);

      }

    public function detail($id = null)
    {
      $this->set('id', $id);

      $groups = $this->Groups->newEntity();
      $this->set('groups', $groups);

      $data = $this->request->getData();
      if(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['groupnamedata'] = array();
        $_SESSION['groupnamedata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }
      $GroupNames = $this->GroupNames->find()
      ->where(['id' => $id])->toArray();
      $name_group = $GroupNames[0]["name"];
      $this->set('name_group', $name_group);

      $Groups = $this->Groups->find()->contain(["Menus"])
      ->where(['group_name_id' => $id, 'Groups.delete_flag' => 0])->toArray();

      for($k=0; $k<count($Groups); $k++){

        $arrGroups[] = $Groups[$k]['menu']['name_menu'];

      }
      $this->set('arrGroups', $arrGroups);

    }

    public function view($id = null)
    {
        $group = $this->Groups->get($id, [
            'contain' => ['Menus']
        ]);

        $this->set('group', $group);
    }

    public function addpre()
    {
      $Groups = $this->Groups->newEntity();
      $this->set('Groups', $Groups);
    }

    public function addform()
    {
      $Groups = $this->Groups->newEntity();
      $this->set('Groups', $Groups);

      $data = $this->request->getData();

      $Menus = $this->Menus->find()
      ->where(['delete_flag' => 0])->toArray();

      $arrmenu = array();
      for($k=0; $k<count($Menus); $k++){

        if($Menus[$k]['name_menu'] !== "メニュー" && $Menus[$k]['name_menu'] !== "会社" && $Menus[$k]['name_menu'] !== "工場・営業所"){
          $arrmenu[] = $Menus[$k]['name_menu'];
        }

      }
      $this->set('arrmenu', $arrmenu);

    }

    public function addform元()
    {
      $Groups = $this->Groups->newEntity();
      $this->set('Groups', $Groups);

      $data = $this->request->getData();
      $dataselect = array_keys($data, '登録済みグループ選択はこちら');

      if(isset($dataselect[0])){
        $selectcheck = 1;
      }else{
        $selectcheck = 0;
      }
      $this->set('selectcheck', $selectcheck);

      $arrGroups = $this->Groups->find()
      ->where(['delete_flag' => 0])->toArray();

      $arrname_group = array();
      for($k=0; $k<count($arrGroups); $k++){
        $arrname_group = array_merge($arrname_group,array($arrGroups[$k]['name_group']=>$arrGroups[$k]['name_group']));
      }

      $arrname_group = array_unique($arrname_group);
      $this->set('arrname_group', $arrname_group);

      $Menus = $this->Menus->find()
      ->where(['delete_flag' => 0])->toArray();

      $arrmenu_id = array();
      for($k=0; $k<count($Menus); $k++){
        $arrmenu_id = array_merge($arrmenu_id,array($Menus[$k]['name_menu']=>$Menus[$k]['name_menu']));
      }
      $this->set('arrmenu_id', $arrmenu_id);
    }

    public function addcomfirm()
    {
      $Groups = $this->Groups->newEntity();
      $this->set('Groups', $Groups);

      $data = $this->request->getData();

      $arraycheck = array();
      $checknum = 0;
      for ($k=0; $k<=$data["nummax"]; $k++){
        if($data['checkbox'.$k] == 1){
          $arraycheck[] = $data['menu'.$k];
        }
      }

      if(count($arraycheck) < 1){
        $arraycheck[] = "マスター登録権限なし";
      }

      $this->set('arraycheck', $arraycheck);

    }

    public function adddo元()
    {
      $Groups = $this->Groups->newEntity();
      $this->set('Groups', $Groups);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $data = $this->request->getData();

      $arrtourokugroup = array();
      $arrtourokugroup = [
        'name_group' => $data["name_group"],
        'menu_id' => $data["menu_id"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];

      //新しいデータを登録
      $Groups = $this->Groups->patchEntity($this->Groups->newEntity(), $arrtourokugroup);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Groups->save($Groups)) {

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

    public function adddo()
    {
      $Groups = $this->Groups->newEntity();
      $this->set('Groups', $Groups);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $data = $this->request->getData();

      $arrtourokugroupname = [
        'name' => $data["name_group"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];

      //新しいデータを登録
      $GroupNames = $this->GroupNames->patchEntity($this->GroupNames->newEntity(), $arrtourokugroupname);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->GroupNames->save($GroupNames)) {

          $GroupNames = $this->GroupNames->find()
          ->where(['name' => $data["name_group"], 'delete_flag' => 0])->toArray();
  
          $arrtourokugroup = array();
          for ($k=0; $k<=$data["num_menu"]; $k++){

            $Menus = $this->Menus->find()
            ->where(['name_menu' => $data['menu_name'.$k], 'delete_flag' => 0])->toArray();

              $arrtourokugroup[] = [
                'group_name_id' => $GroupNames[0]['id'],
                'menu_id' => $Menus[0]['id'],
                'delete_flag' => 0,
                'created_at' => date("Y-m-d H:i:s"),
                'created_staff' => $staff_id
              ];
          }

          $Groups = $this->Groups->patchEntities($this->Groups->newEntity(), $arrtourokugroup);
          if ($this->Groups->saveMany($Groups)) {
           
            $connection->commit();// コミット5
            $mes = "以下のように登録されました。";
            $this->set('mes',$mes);
  
          } else {

            $mes = "※登録されませんでした";
            $this->set('mes',$mes);
            $this->Flash->error(__('The data could not be saved. Please, try again.'));
            throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
  
          }
  

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

    public function deleteconfirm($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['groupnamedata'];
      $this->set('id', $id);

      $GroupNames = $this->GroupNames->find()
      ->where(['id' => $id])->toArray();
      $name = $GroupNames[0]["name"];
      $this->set('name', $name);
  }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $GroupNames = $this->GroupNames->find()
      ->where(['id' => $data["id"]])->toArray();
      $name = $GroupNames[0]["name"];
      $this->set('name', $name);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeletegroups = array();
      $arrdeletegroups = [
        'id' => $data["id"]
      ];

      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Groups->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['group_name_id'  => $data["id"]])
            && $this->GroupNames->updateAll(
          [ 'delete_flag' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_staff' => $staff_id],
          ['id'  => $data["id"]])){

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
