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
     $this->Groups = TableRegistry::get('Groups');
     $this->Menus = TableRegistry::get('Menus');
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Menus']
        ];
        $groups = $this->paginate($this->Groups->find()->select(['name_group','delete_flag' => 0])->group(['name_group']));

        $this->set(compact('groups'));
    }

    public function detail($name_group = null)
    {
      $groups = $this->Groups->newEntity();
      $this->set('groups', $groups);

      $data = $this->request->getData();
      if(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['groupdata'] = array();
        $_SESSION['groupdata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }

      $this->set('name_group', $name_group);

      $Groups = $this->Groups->find()->contain(["Menus"])
      ->where(['name_group' => $name_group, 'Groups.delete_flag' => 0])->toArray();
      $this->set('id', $Groups[0]["id"]);

      for($k=0; $k<count($Groups); $k++){

        $arrGroups[] = $Groups[$k]['menu']['name_menu'];

      }
      $this->set('arrGroups', $arrGroups);
/*
      echo "<pre>";
      print_r($arrGroups);
      echo "</pre>";
*/
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
      $this->set('arraycheck', $arraycheck);
/*
      echo "<pre>";
      print_r($arraycheck);
      echo "</pre>";
*/
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

      $arrtourokugroup = array();
      for ($k=0; $k<=$data["num_menu"]; $k++){

        $Menus = $this->Menus->find()
        ->where(['name_menu' => $data['menu_name'.$k]])->toArray();

          $arrtourokugroup[] = [
            'name_group' => $data["name_group"],
            'menu_id' => $Menus[0]['id'],
            'delete_flag' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'created_staff' => $staff_id
          ];
      }
/*
      echo "<pre>";
      print_r($arrtourokugroup);
      echo "</pre>";
*/
      //新しいデータを登録
      $Groups = $this->Groups->patchEntities($this->Groups->newEntity(), $arrtourokugroup);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Groups->saveMany($Groups)) {

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

    public function deleteconfirm($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['groupdata'];

        $group = $this->Groups->get($id, [
          'contain' => ['Menus']
        ]);
        $this->set(compact('group'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $group = $this->Groups->get($data["id"], [
        'contain' => ['Menus']
      ]);
      $this->set(compact('group'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeletegroups = array();
      $arrdeletegroups = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletecompany);
      echo "</pre>";
*/
      $Groups = $this->Groups->patchEntity($this->Groups->newEntity(), $arrdeletegroups);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Groups->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['name_group'  => $group['name_group']]
         )){

           //スタッフ権限もこのタイミングで削除

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
