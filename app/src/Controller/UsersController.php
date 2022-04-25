<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;//ログインに使用
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\Auth\DefaultPasswordHasher;//パスワードの更新時のハッシュ化

class UsersController extends AppController
{

    public function initialize()
  {
   parent::initialize();
   $this->Staffs = TableRegistry::get('Staffs');
   $this->StaffAbilities = TableRegistry::get('StaffAbilities');
   $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
   $this->Groups = TableRegistry::get('Groups');
   $this->Users = TableRegistry::get('Users');

   $session = $this->request->getSession();
   $datasession = $session->read();

   if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

     return $this->redirect($this->Auth->logout());

   }

   if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

     $Groups = $this->Groups->find()->contain(["Menus"])
     ->where(['Groups.group_code' => $datasession['Auth']['User']['group_code'], 'Menus.id' => 29, 'Groups.delete_flag' => 0])
     ->toArray();

     if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

       return $this->redirect($this->Auth->logout());

     }

   }

  }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Staffs']
        ];
        $users = $this->paginate($this->Users->find()->where(['Users.delete_flag' => 0]));

        $this->set(compact('users'));
    }

    public function detail($id = null)
    {
      $user = $this->Users->newEntity();
      $this->set('user', $user);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['userdata'] = array();
        $_SESSION['userdata'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['userdata'] = array();
        $_SESSION['userdata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }
      $this->set('id', $id);

      $Users = $this->Users->find()->contain(["Staffs"])
      ->where(['Users.id' => $id])->toArray();

      $user_code = $Users[0]["user_code"];
      $this->set('user_code', $user_code);
      $group_name = $Users[0]["group_name"];
      $this->set('group_name', $group_name);
      $staffhyouji = $Users[0]["staff"]['name'];
      $this->set('staffhyouji', $staffhyouji);

    }

    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Staffs']
        ]);

        $this->set('user', $user);
    }

    public function addpre()
    {
      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }

      $user = $this->Users->newEntity();
      $staffs = $this->Users->Staffs->find('list', ['limit' => 200])->where(['delete_flag' => 0]);
      $this->set(compact('user', 'staffs'));
    }

    public function addform()
    {
      $user = $this->Users->newEntity();
      $staffs = $this->Users->Staffs->find('list', ['limit' => 200]);
      $this->set(compact('user', 'staffs'));

      $data = $this->request->getData();

      $Staffs = $this->Staffs->find()
      ->where(['id' => $data['staff_id']])->toArray();
      $staffhyouji = $Staffs[0]["name"];
      $this->set('staffhyouji', $staffhyouji);

      $Users = $this->Users->find()
      ->where(['staff_id' => $data['staff_id'], 'delete_flag' => 0])->toArray();

      if(isset($Users[0])){

        return $this->redirect(['action' => 'addpre',
        's' => ['mess' => "※".$staffhyouji."は既にユーザー登録済みです。"]]);

      }

      $Groups = $this->Groups->find()
      ->where(['delete_flag' => 0])->toArray();

      $Groupnames = array();
      for($k=0; $k<count($Groups); $k++){
        $Groupnames = array_merge($Groupnames,array($Groups[$k]['name_group']=>$Groups[$k]['name_group']));
      }

      $Groupnames = array_unique($Groupnames);
      $this->set('Groupnames', $Groupnames);
    }

    public function addcomfirm()
    {
      $Users = $this->Users->newEntity();
      $this->set('Users', $Users);

      $data = $this->request->getData();

      $Staffs = $this->Staffs->find()
      ->where(['id' => $data['staff_id']])->toArray();
      $staffhyouji = $Staffs[0]["name"];
      $this->set('staffhyouji', $staffhyouji);
    }

    public function adddo()
    {
      $Users = $this->Users->newEntity();
      $this->set('Users', $Users);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Staffs = $this->Staffs->find()
      ->where(['id' => $data['staff_id']])->toArray();
      $staffhyouji = $Staffs[0]["name"];
      $this->set('staffhyouji', $staffhyouji);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokuuser = array();
      $arrtourokuuser = [
        'user_code' => $data["user_code"],
        'password' => $data["password"],
        'staff_id' => $data["staff_id"],
        'super_user' => 0,
        'group_name' => $data["group_name"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];

      $Groups = $this->Groups->find()->contain(["Menus"])//GroupsテーブルとMenusテーブルを関連付ける
      ->where(['Groups.name_group' => $data["group_name"], 'Groups.delete_flag' => 0])->order(["menu_id"=>"ASC"])->toArray();

      $arrMenuids = array();
      for($k=0; $k<count($Groups); $k++){

        $StaffAbilities = $this->StaffAbilities->find()
        ->where(['staff_id' => $data["staff_id"], 'menu_id' => $Groups[$k]['menu_id'], 'delete_flag' => 0])->toArray();

        if(count($StaffAbilities) < 1){
          $arrMenuids[] = array(
            'staff_id' => $data["staff_id"],
            'menu_id' => $Groups[$k]['menu_id'],
            'delete_flag' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'created_staff' => $staff_id
          );
        }

      }

      //新しいデータを登録
      $Users = $this->Users->patchEntity($this->Users->newEntity(), $arrtourokuuser);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Users->save($Users)) {//ここでstaff_abilitiesテーブルも登録//グループが修正される場合は一度グループを削除して作り直す

          $StaffAbilities = $this->StaffAbilities->patchEntities($this->StaffAbilities->newEntity(), $arrMenuids);
          $this->StaffAbilities->saveMany($StaffAbilities);

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

      $id = $_SESSION['userdata'];

      $user = $this->Users->get($id, [
          'contain' => []
      ]);
      $staffs = $this->Users->Staffs->find('list', ['limit' => 200]);
      $this->set(compact('user', 'staffs'));
      $this->set('id', $id);

      $Groups = $this->Groups->find()
      ->where(['delete_flag' => 0])->toArray();

      $Groupnames = array();
      for($k=0; $k<count($Groups); $k++){
        $Groupnames = array_merge($Groupnames,array($Groups[$k]['name_group']=>$Groups[$k]['name_group']));
      }

      $Groupnames = array_unique($Groupnames);
      $this->set('Groupnames', $Groupnames);
    }

    public function editconfirm()
    {
      $user = $this->Users->newEntity();
      $this->set('user', $user);

      $data = $this->request->getData();

      $Staffs = $this->Staffs->find()
      ->where(['id' => $data['staff_id']])->toArray();
      $staffhyouji = $Staffs[0]["name"];
      $this->set('staffhyouji', $staffhyouji);
    }

    public function editdo()
    {
      $user = $this->Users->newEntity();
      $this->set('user', $user);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Staffs = $this->Staffs->find()
      ->where(['id' => $data['staff_id']])->toArray();
      $staffhyouji = $Staffs[0]["name"];
      $this->set('staffhyouji', $staffhyouji);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $makepassword = new DefaultPasswordHasher();
      $password = $makepassword->hash($data["password"]);

      $Users = $this->Users->find()
      ->where(['id' => $data['id']])->toArray();

      $arrupdateuser = array();
      $arrupdateuser = [
        'user_code' => $Users[0]["user_code"],
        'password' => $Users[0]["password"],
        'staff_id' => $Users[0]["staff_id"],
        'super_user' => 0,
        'group_name' => $Users[0]["group_name"],
        'delete_flag' => 1,
        'created_at' => $Users[0]["created_at"]->format("Y-m-d H:i:s"),
        'created_staff' => $Users[0]["created_staff"],
        'updated_at' => date("Y-m-d H:i:s"),
        'updated_staff' => $staff_id
      ];

      $Groups = $this->Groups->find()->contain(["Menus"])//GroupsテーブルとMenusテーブルを関連付ける
      ->where(['Groups.name_group' => $data["group_name"], 'Groups.delete_flag' => 0])->order(["menu_id"=>"ASC"])->toArray();

      $arrMenuids = array();
      for($k=0; $k<count($Groups); $k++){

        $arrMenuids[] = array(
          'staff_id' => $data["staff_id"],
          'menu_id' => $Groups[$k]['menu_id'],
          'delete_flag' => 0,
          'created_at' => date("Y-m-d H:i:s"),
          'created_staff' => $staff_id
        );

      }

      $Users = $this->Users->patchEntity($this->Users->newEntity(), $arrupdateuser);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Users->save($Users)) {

         $this->Users->updateAll(
           [ 
            'user_code' => $data["user_code"],
            'password' => $password,
            'staff_id' => $data["staff_id"],
            'super_user' => 0,
            'group_name' => $data["group_name"],
            'delete_flag' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'created_staff' => $staff_id,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_staff' => $staff_id],
           ['id'  => $data['id']]);

           $this->StaffAbilities->updateAll(
             [ 'delete_flag' => 1,
               'updated_at' => date('Y-m-d H:i:s'),
               'updated_staff' => $staff_id],
             ['staff_id'  => $data["staff_id"]]);

             $StaffAbilities = $this->StaffAbilities->patchEntities($this->StaffAbilities->newEntity(), $arrMenuids);
             $this->StaffAbilities->saveMany($StaffAbilities);

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

      $id = $_SESSION['userdata'];

      $user = $this->Users->get($id, [
        'contain' => ['Staffs']
      ]);
      $this->set(compact('user'));

      if($user['super_user'] == 0){
        $super_userhyouji = "いいえ";
      }else{
        $super_userhyouji = "はい";
      }
      $this->set('super_userhyouji', $super_userhyouji);
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $user = $this->Users->get($data["id"], [
        'contain' => ['Staffs']
      ]);
      $this->set(compact('user'));

      if($user['super_user'] == 0){
        $super_userhyouji = "いいえ";
      }else{
        $super_userhyouji = "はい";
      }
      $this->set('super_userhyouji', $super_userhyouji);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeleteuser = array();
      $arrdeleteuser = [
        'id' => $data["id"]
      ];

      $Users = $this->Users->patchEntity($this->Users->newEntity(), $arrdeleteuser);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Users->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeleteuser['id']]
         )){

           $this->StaffAbilities->updateAll(
             [ 'delete_flag' => 1,
               'updated_at' => date('Y-m-d H:i:s'),
               'updated_staff' => $staff_id],
             ['staff_id'  => $staff_id]);

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
