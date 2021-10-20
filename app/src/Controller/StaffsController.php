<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Auth\DefaultPasswordHasher;//パスワードの更新時のハッシュ化

class StaffsController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Staffs = TableRegistry::get('Staffs');
     $this->Users = TableRegistry::get('Users');
     $this->StaffAbilities = TableRegistry::get('StaffAbilities');
     $this->Factories = TableRegistry::get('Factories');
     $this->Departments = TableRegistry::get('Departments');
     $this->Positions = TableRegistry::get('Positions');

     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

       return $this->redirect($this->Auth->logout());

     }

     if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)
/*
       $Groups = $this->Groups->find()->contain(["Menus"])
       ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "", 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }
*/
     }

    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Factories', 'Departments', 'Positions']
        ];
        $staffs = $this->paginate($this->Staffs->find()->where(['Staffs.delete_flag' => 0]));

        $this->set(compact('staffs'));
    }

    public function ichiran()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $Users = $this->Users->find()->contain(["Staffs"])
      ->where(['Staffs.id' => $datasession['Auth']['User']['staff_id'], 'Users.delete_flag' => 0])
      ->toArray();

        $this->paginate = [
            'contain' => ['Factories', 'Departments', 'Positions']
        ];
        if($Users[0]["staff"]["factory_id"] == 5){//本部の場合
          $staffs = $this->paginate($this->Staffs->find()
          ->where(['Staffs.delete_flag' => 0]));
            }else{
              $staffs = $this->paginate($this->Staffs->find()
              ->where(['Staffs.factory_id' => $Users[0]["staff"]["factory_id"], 'Staffs.delete_flag' => 0]));
                  }
  
        $this->set(compact('staffs'));
    }

    public function detail($id = null)
    {
      $staff = $this->Staffs->newEntity();
      $this->set('staff', $staff);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['staffdata'] = array();
        $_SESSION['staffdata'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['staffdata'] = array();
        $_SESSION['staffdata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }
      $this->set('id', $id);

      $Staffs = $this->Staffs->find()->contain(["Factories", "Departments", "Positions"])
      ->where(['Staffs.id' => $id, 'Staffs.delete_flag' => 0])->toArray();

      $name = $Staffs[0]["name"];
      $this->set('name', $name);
      $staff_code = $Staffs[0]["staff_code"];
      $this->set('staff_code', $staff_code);
      $tel = $Staffs[0]["tel"];
      $this->set('tel', $tel);
      $mail = $Staffs[0]["mail"];
      $this->set('mail', $mail);
      $address = $Staffs[0]["address"];
      $this->set('address', $address);
      $birth = $Staffs[0]["birth"];
      $this->set('birth', $birth);
      $date_start = $Staffs[0]["date_start"];
      $this->set('date_start', $date_start);
      $date_finish = $Staffs[0]["date_finish"];
      $this->set('date_finish', $date_finish);

      if($Staffs[0]['sex'] == 0){
        $sexhyouji = "男";
      }else{
        $sexhyouji = "女";
      }
      $this->set('sexhyouji', $sexhyouji);

      if($Staffs[0]["factory"]['id'] >= 0){
        $factory_name = $Staffs[0]["factory"]['name'];
        $this->set('factory_name', $factory_name);
      }else{
        $factory_name = "";
        $this->set('factory_name', $factory_name);
      }

      if($Staffs[0]["department"]['id'] >= 0){
        $department_name = $Staffs[0]["department"]['department'];
        $this->set('department_name', $department_name);
      }else{
        $factory_name = "";
        $this->set('department_name', $department_name);
      }

      if($Staffs[0]["position"]['id'] >= 0){
        $position_name = $Staffs[0]["position"]['position'];
        $this->set('position_name', $position_name);
      }else{
        $occupation_name = "";
        $this->set('position_name', $position_name);
      }

      $Users = $this->Users->find()
      ->where(['staff_id' => $id, 'delete_flag' => 0])->toArray();

      $user_code = $Users[0]["user_code"];
      $this->set('user_code', $user_code);
      $group_name = $Users[0]["group_name"];
      $this->set('group_name', $group_name);
      $staffhyouji = $Users[0]["staff"]['name'];
      $this->set('staffhyouji', $staffhyouji);

    }
/*
    public function addmoto()
    {
        $staff = $this->Staffs->newEntity();
        if ($this->request->is('post')) {
            $staff = $this->Staffs->patchEntity($staff, $this->request->getData());
            if ($this->Staffs->save($staff)) {
                $this->Flash->success(__('The staff has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The staff could not be saved. Please, try again.'));
        }
        $Factories = $this->Staffs->Factories->find('list', ['limit' => 200]);
        $departments = $this->Staffs->Departments->find('list', ['limit' => 200]);
        $positions = $this->Staffs->Positions->find('list', ['limit' => 200]);
        $this->set(compact('staff', 'Factories', 'departments', 'occupations', 'positions'));
    }
*/
    public function addform()
    {
      $Staffs = $this->Staffs->newEntity();
      $this->set('Staffs', $Staffs);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $Users = $this->Users->find()->contain(["Staffs"])
      ->where(['Staffs.id' => $datasession['Auth']['User']['staff_id'], 'Users.delete_flag' => 0])
      ->toArray();

      $Factories = $this->Staffs->Factories->find('list', ['limit' => 200]);
      $this->set(compact('Factories'));

      if($Users[0]["staff"]["factory_id"] == 5){//本部の場合
        $Departments = $this->Departments->find()
        ->where(['delete_flag' => 0])->toArray();
      }else{
        $Departments = $this->Departments->find()
        ->where(['factory_id' => $Users[0]["staff"]["factory_id"], 'delete_flag' => 0])->toArray();
      }
      $departments = array();
      for($k=0; $k<count($Departments); $k++){
        $departments = $departments + array($Departments[$k]['id']=>$Departments[$k]['department']);
      }
      $this->set('departments', $departments);

      if($Users[0]["staff"]["factory_id"] == 5){//本部の場合
        $Positions = $this->Positions->find()
        ->where(['delete_flag' => 0])->toArray();
      }else{
        $Positions = $this->Positions->find()
        ->where(['factory_id' => $Users[0]["staff"]["factory_id"], 'delete_flag' => 0])->toArray();
      }
      $positions = array();
      for($k=0; $k<count($Positions); $k++){
        $positions = $positions + array($Positions[$k]['id']=>$Positions[$k]['position']);
      }
      $this->set('positions', $positions);

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
      $Staffs = $this->Staffs->newEntity();
      $this->set('Staffs', $Staffs);

      $data = $this->request->getData();

      if($data['sex'] == 0){
        $sexhyouji = "男";
      }else{
        $sexhyouji = "女";
      }
      $this->set('sexhyouji', $sexhyouji);

      if($data['factory_id'] > 0){
        $Factories = $this->Factories->find()
        ->where(['id' => $data['factory_id']])->toArray();
        $factory_name = $Factories[0]['name'];
        $this->set('factory_name', $factory_name);
      }else{
        $factory_name = "";
        $this->set('factory_name', $factory_name);
      }

      if($data['department_id'] > 0){
        $Departments = $this->Departments->find()
        ->where(['id' => $data['department_id']])->toArray();
        $department_name = $Departments[0]['department'];
        $this->set('department_name', $department_name);
      }else{
        $department_name = "";
        $this->set('department_name', $department_name);
      }

      if($data['position_id'] > 0){
        $Positions = $this->Positions->find()
        ->where(['id' => $data['position_id']])->toArray();
        $position_name = $Positions[0]['position'];
        $this->set('position_name', $position_name);
      }else{
        $position_name = "";
        $this->set('position_name', $position_name);
      }

      if($data['birth']['year'] > 0){
        $birth = $data['birth']['year']."-".$data['birth']['month']."-".$data['birth']['day'];
      }else{
        $birth = "";
      }
      $this->set('birth', $birth);

      if($data['date_start']['year'] > 0){
        $date_start = $data['date_start']['year']."-".$data['date_start']['month']."-".$data['date_start']['day'];
      }else{
        $date_start = "";
      }
      $this->set('date_start', $date_start);

    }

    public function adddo()
    {
      $Staffs = $this->Staffs->newEntity();
      $this->set('Staffs', $Staffs);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      if($data['sex'] == 0){
        $sexhyouji = "男";
      }else{
        $sexhyouji = "女";
      }
      $this->set('sexhyouji', $sexhyouji);
      if($data['factory_id'] > 0){
        $Factories = $this->Factories->find()
        ->where(['id' => $data['factory_id']])->toArray();
        $factory_name = $Factories[0]['name'];
        $this->set('factory_name', $factory_name);
      }else{
        $factory_name = "";
        $this->set('factory_name', $factory_name);
      }

      if($data['department_id'] > 0){
        $Departments = $this->Departments->find()
        ->where(['id' => $data['department_id']])->toArray();
        $department_name = $Departments[0]['department'];
        $this->set('department_name', $department_name);
      }else{
        $department_name = "";
        $this->set('department_name', $department_name);
      }

      if($data['position_id'] > 0){
        $Positions = $this->Positions->find()
        ->where(['id' => $data['position_id']])->toArray();
        $position_name = $Positions[0]['position'];
        $this->set('position_name', $position_name);
      }else{
        $position_name = "";
        $this->set('position_name', $position_name);
      }

      $birth = $data['birth'];
      $this->set('birth', $birth);
      $date_start = $data['date_start'];
      $this->set('date_start', $date_start);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokustaff = array();
      $arrtourokustaff = [
        'factory_id' => $data["factory_id"],
        'department_id' => $data["department_id"],
        'position_id' => $data["position_id"],
        'name' => $data["name"],
        'staff_code' => $data["staff_code"],
        'sex' => $data["sex"],
        'mail' => $data["mail"],
        'tel' => $data["tel"],
        'address' => $data["address"],
        'birth' => $data["birth"],
        'date_start' => $data["date_start"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokustaff);
      echo "</pre>";
*/
      //新しいデータを登録
      $Staffs = $this->Staffs->patchEntity($this->Staffs->newEntity(), $arrtourokustaff);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Staffs->save($Staffs)) {

          $Staffs = $this->Staffs->find()
          ->where(['name' => $data["name"], 'delete_flag' => 0])->order(["id"=>"DESC"])->toArray();

            $arrtourokuuser = array();
            $arrtourokuuser = [
              'user_code' => $data["user_code"],
              'password' => $data["password"],
              'staff_id' => $Staffs[0]["id"],
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
              ->where(['staff_id' => $Staffs[0]["id"], 'menu_id' => $Groups[$k]['menu_id'], 'delete_flag' => 0])->toArray();

              if(count($StaffAbilities) < 1){
                $arrMenuids[] = array(
                  'staff_id' => $Staffs[0]["id"],
                  'menu_id' => $Groups[$k]['menu_id'],
                  'delete_flag' => 0,
                  'created_at' => date("Y-m-d H:i:s"),
                  'created_staff' => $staff_id
                );
              }

            }
/*
            echo "<pre>";
            print_r($arrtourokuuser);
            echo "</pre>";
            echo "<pre>";
            print_r($arrMenuids);
            echo "</pre>";
*/
          $Users = $this->Users->patchEntity($this->Users->newEntity(), $arrtourokuuser);
          $this->Users->save($Users);

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

    public function editform()
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['staffdata'];

      $Staffs = $this->Staffs->get($id, [
          'contain' => []
      ]);
      $this->set(compact('Staffs'));
      $this->set('id', $id);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $Users = $this->Users->find()->contain(["Staffs"])
      ->where(['Staffs.id' => $datasession['Auth']['User']['staff_id'], 'Users.delete_flag' => 0])
      ->toArray();

      $Factories = $this->Staffs->Factories->find('list', ['limit' => 200]);
      $this->set(compact('Factories'));

      $Users = $this->Users->find()
      ->where(['staff_id' => $id, 'delete_flag' => 0])->toArray();
      $user_code = $Users[0]["user_code"];
      $this->set('user_code', $user_code);
      $group_name = $Users[0]["group_name"];
      $this->set('group_name', $group_name);

      if($Users[0]["staff"]["factory_id"] == 5){//本部の場合
        $Departments = $this->Departments->find()
        ->where(['delete_flag' => 0])->toArray();
            }else{
              $Departments = $this->Departments->find()
              ->where(['factory_id' => $Users[0]["staff"]["factory_id"], 'delete_flag' => 0])->toArray();
                      }
      $departments = array();
      foreach ($Departments as $value) {
        $departments[] = array($value->id=>$value->department);
      }
      $this->set('departments', $departments);

      if($Users[0]["staff"]["factory_id"] == 5){//本部の場合
        $Positions = $this->Positions->find()
        ->where(['delete_flag' => 0])->toArray();
              }else{
                $Positions = $this->Positions->find()
                ->where(['factory_id' => $Users[0]["staff"]["factory_id"], 'delete_flag' => 0])->toArray();
                                }
      $positions = array();
      foreach ($Positions as $value) {
        $positions[] = array($value->id=>$value->position);
      }
      $this->set('positions', $positions);

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
      $Staffs = $this->Staffs->newEntity();
      $this->set('Staffs', $Staffs);

      $data = $this->request->getData();

      if($data['sex'] == 0){
        $sexhyouji = "男";
      }else{
        $sexhyouji = "女";
      }
      $this->set('sexhyouji', $sexhyouji);

      if($data['factory_id'] > 0){
        $Factories = $this->Factories->find()
        ->where(['id' => $data['factory_id']])->toArray();
        $factory_name = $Factories[0]['name'];
        $this->set('factory_name', $factory_name);
      }else{
        $factory_name = "";
        $this->set('factory_name', $factory_name);
      }

      if($data['department_id'] > 0){
        $Departments = $this->Departments->find()
        ->where(['id' => $data['department_id']])->toArray();
        $department_name = $Departments[0]['department'];
        $this->set('department_name', $department_name);
      }else{
        $department_name = "";
        $this->set('department_name', $department_name);
      }

      if($data['position_id'] > 0){
        $Positions = $this->Positions->find()
        ->where(['id' => $data['position_id']])->toArray();
        $position_name = $Positions[0]['position'];
        $this->set('position_name', $position_name);
      }else{
        $position_name = "";
        $this->set('position_name', $position_name);
      }

      if($data['birth']['year'] > 0){
        $birth = $data['birth']['year']."-".$data['birth']['month']."-".$data['birth']['day'];
      }else{
        $birth = "";
      }
      $this->set('birth', $birth);

      if($data['date_start']['year'] > 0){
        $date_start = $data['date_start']['year']."-".$data['date_start']['month']."-".$data['date_start']['day'];
      }else{
        $date_start = "";
      }
      $this->set('date_start', $date_start);

      if($data['date_finish']['year'] > 0){
        $date_finish = $data['date_finish']['year']."-".$data['date_finish']['month']."-".$data['date_finish']['day'];
      }else{
        $date_finish = "";
      }
      $this->set('date_finish', $date_finish);
    }

    public function editdo()
    {
      $Staffs = $this->Staffs->newEntity();
      $this->set('Staffs', $Staffs);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      if($data['sex'] == 0){
        $sexhyouji = "男";
      }else{
        $sexhyouji = "女";
      }
      $this->set('sexhyouji', $sexhyouji);
      if($data['factory_id'] > 0){
        $Factories = $this->Factories->find()
        ->where(['id' => $data['factory_id']])->toArray();
        $factory_name = $Factories[0]['name'];
        $this->set('factory_name', $factory_name);
      }else{
        $factory_name = "";
        $this->set('factory_name', $factory_name);
      }

      if($data['department_id'] > 0){
        $Departments = $this->Departments->find()
        ->where(['id' => $data['department_id']])->toArray();
        $department_name = $Departments[0]['department'];
        $this->set('department_name', $department_name);
      }else{
        $department_name = "";
        $this->set('department_name', $department_name);
      }

      if($data['position_id'] > 0){
        $Positions = $this->Positions->find()
        ->where(['id' => $data['position_id']])->toArray();
        $position_name = $Positions[0]['position'];
        $this->set('position_name', $position_name);
      }else{
        $position_name = "";
        $this->set('position_name', $position_name);
      }

      $birth = $data['birth'];
      $this->set('birth', $birth);
      $date_start = $data['date_start'];
      $this->set('date_start', $date_start);
      $date_finish = $data['date_finish'];
      $this->set('date_finish', $date_finish);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $Staffs = $this->Staffs->find()
      ->where(['id' => $data['id']])->toArray();
      $userId = $Staffs[0]['id'];

      $arrupdatestaff = array();
      $arrupdatestaff = [
        'factory_id' => $Staffs[0]["factory_id"],
        'department_id' => $Staffs[0]["department_id"],
        'position_id' => $Staffs[0]["position_id"],
        'name' => $Staffs[0]["name"],
        'staff_code' => $Staffs[0]["staff_code"],
        'sex' => $Staffs[0]["sex"],
        'mail' => $Staffs[0]["mail"],
        'tel' => $Staffs[0]["tel"],
        'address' => $Staffs[0]["address"],
        'birth' => $Staffs[0]["birth"],
        'date_start' => $Staffs[0]["date_start"],
        'date_finish' => $Staffs[0]["date_finish"],
        'delete_flag' => 1,
        'created_at' => $Staffs[0]["created_at"]->format("Y-m-d H:i:s"),
        'created_staff' => $Staffs[0]["created_staff"],
        'updated_at' => date("Y-m-d H:i:s"),
        'updated_staff' => $staff_id
      ];

      if(strlen($arrupdatestaff["birth"]) < 1){
        $updatebirth = NULL;
      }else{
        $updatebirth = $arrupdatestaff["birth"];
      }

      if(strlen($arrupdatestaff["date_start"]) < 1){
        $updatedate_start = NULL;
      }else{
        $updatedate_start = $arrupdatestaff["date_start"];
      }

      if(strlen($arrupdatestaff["date_finish"]) < 1){
        $updatedate_finish = NULL;
      }else{
        $updatedate_finish = $arrupdatestaff["date_finish"];
      }

      $makepassword = new DefaultPasswordHasher();
      $password = $makepassword->hash($data["password"]);

      $Users = $this->Users->find()
      ->where(['staff_id' => $data['id'], 'delete_flag' => 0])->toArray();
      $userId = $Users[0]['id'];

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
      ->where(['Groups.name_group' => $data["group_name"], 'Groups.delete_flag' => 0])
      ->order(["menu_id"=>"ASC"])->toArray();

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
/*
      echo "<pre>";
      print_r(strlen($data["date_start"]));
      echo "</pre>";
      
      if(strlen($data["date_finish"]) > 0){
        echo "<pre>";
        print_r("1");
        echo "</pre>";
        }else{
          echo "<pre>";
          print_r("2");
          echo "</pre>";
        }
*/
      $Staffs = $this->Staffs->patchEntity($this->Staffs->newEntity(), $arrupdatestaff);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Staffs->save($Staffs)) {

        if(strlen($data["date_finish"]) > 0){
          $this->Staffs->updateAll(
            [  'factory_id' => $data["factory_id"],
               'department_id' => $data["department_id"],
               'position_id' => $data["position_id"],
               'name' => $data["name"],
               'staff_code' => $data["staff_code"],
               'sex' => $data["sex"],
               'mail' => $data["mail"],
               'tel' => $data["tel"],
               'address' => $data["address"],
               'birth' => $data["birth"],
               'date_start' => $data["date_start"],
               'date_finish' => $data["date_finish"],
               'delete_flag' => 0,
               'updated_at' => date('Y-m-d H:i:s'),
               'updated_staff' => $staff_id],
            ['id'  => $data['id']]);
         }else{
          $this->Staffs->updateAll(
            [  'factory_id' => $data["factory_id"],
               'department_id' => $data["department_id"],
               'position_id' => $data["position_id"],
               'name' => $data["name"],
               'staff_code' => $data["staff_code"],
               'sex' => $data["sex"],
               'mail' => $data["mail"],
               'tel' => $data["tel"],
               'address' => $data["address"],
               'birth' => $data["birth"],
               'date_start' => $data["date_start"],
               'delete_flag' => 0,
               'updated_at' => date('Y-m-d H:i:s'),
               'updated_staff' => $staff_id],
            ['id'  => $data['id']]);
         }

           $Users = $this->Users->patchEntity($this->Users->newEntity(), $arrupdateuser);
           $this->Users->save($Users);

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
            ['id'  => $userId]);
 
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

    public function deleteconfirm()
    {
      $staff = $this->Staffs->newEntity();
      $this->set('staff', $staff);

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['staffdata'];
      $this->set('id', $id);

      $Staffs = $this->Staffs->find()->contain(["Factories", "Departments", "Positions"])
      ->where(['Staffs.id' => $id])->toArray();

      $name = $Staffs[0]["name"];
      $this->set('name', $name);
      $staff_code = $Staffs[0]["staff_code"];
      $this->set('staff_code', $staff_code);
      $tel = $Staffs[0]["tel"];
      $this->set('tel', $tel);
      $mail = $Staffs[0]["mail"];
      $this->set('mail', $mail);
      $address = $Staffs[0]["address"];
      $this->set('address', $address);
      $birth = $Staffs[0]["birth"];
      $this->set('birth', $birth);
      $date_start = $Staffs[0]["date_start"];
      $this->set('date_start', $date_start);
      $date_finish = $Staffs[0]["date_finish"];
      $this->set('date_finish', $date_finish);

      if($Staffs[0]['sex'] == 0){
        $sexhyouji = "男";
      }else{
        $sexhyouji = "女";
      }
      $this->set('sexhyouji', $sexhyouji);

      if($Staffs[0]["factory"]['id'] >= 0){
        $factory_name = $Staffs[0]["factory"]['name'];
        $this->set('factory_name', $factory_name);
      }else{
        $factory_name = "";
        $this->set('factory_name', $factory_name);
      }

      if($Staffs[0]["department"]['id'] >= 0){
        $department_name = $Staffs[0]["department"]['department'];
        $this->set('department_name', $department_name);
      }else{
        $factory_name = "";
        $this->set('department_name', $department_name);
      }

      if($Staffs[0]["occupation"]['id'] >= 0){
        $occupation_name = $Staffs[0]["occupation"]['occupation'];
        $this->set('occupation_name', $occupation_name);
      }else{
        $occupation_name = "";
        $this->set('occupation_name', $occupation_name);
      }

      if($Staffs[0]["position"]['id'] >= 0){
        $position_name = $Staffs[0]["position"]['position'];
        $this->set('position_name', $position_name);
      }else{
        $occupation_name = "";
        $this->set('position_name', $position_name);
      }

      $Users = $this->Users->find()
      ->where(['staff_id' => $id, 'delete_flag' => 0])->toArray();

      $user_code = $Users[0]["user_code"];
      $this->set('user_code', $user_code);
      $group_name = $Users[0]["group_name"];
      $this->set('group_name', $group_name);
      $staffhyouji = $Users[0]["staff"]['name'];
      $this->set('staffhyouji', $staffhyouji);

    }

    public function deletedo()
    {
      $staff = $this->Staffs->newEntity();
      $this->set('staff', $staff);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Staffs = $this->Staffs->find()->contain(["Factories", "Departments", "Positions"])
      ->where(['Staffs.id' => $data["id"]])->toArray();

      $name = $Staffs[0]["name"];
      $this->set('name', $name);
      $staff_code = $Staffs[0]["staff_code"];
      $this->set('staff_code', $staff_code);
      $tel = $Staffs[0]["tel"];
      $this->set('tel', $tel);
      $mail = $Staffs[0]["mail"];
      $this->set('mail', $mail);
      $address = $Staffs[0]["address"];
      $this->set('address', $address);
      $birth = $Staffs[0]["birth"];
      $this->set('birth', $birth);
      $date_start = $Staffs[0]["date_start"];
      $this->set('date_start', $date_start);
      $date_finish = $Staffs[0]["date_finish"];
      $this->set('date_finish', $date_finish);

      if($Staffs[0]['sex'] == 0){
        $sexhyouji = "男";
      }else{
        $sexhyouji = "女";
      }
      $this->set('sexhyouji', $sexhyouji);

      if($Staffs[0]["factory"]['id'] >= 0){
        $factory_name = $Staffs[0]["factory"]['name'];
        $this->set('factory_name', $factory_name);
      }else{
        $factory_name = "";
        $this->set('factory_name', $factory_name);
      }

      if($Staffs[0]["department"]['id'] >= 0){
        $department_name = $Staffs[0]["department"]['department'];
        $this->set('department_name', $department_name);
      }else{
        $factory_name = "";
        $this->set('department_name', $department_name);
      }

      if($Staffs[0]["occupation"]['id'] >= 0){
        $occupation_name = $Staffs[0]["occupation"]['occupation'];
        $this->set('occupation_name', $occupation_name);
      }else{
        $occupation_name = "";
        $this->set('occupation_name', $occupation_name);
      }

      if($Staffs[0]["position"]['id'] >= 0){
        $position_name = $Staffs[0]["position"]['position'];
        $this->set('position_name', $position_name);
      }else{
        $occupation_name = "";
        $this->set('position_name', $position_name);
      }

      $Users = $this->Users->find()
      ->where(['staff_id' => $data["id"], 'delete_flag' => 0])->toArray();

      $user_code = $Users[0]["user_code"];
      $this->set('user_code', $user_code);
      $group_name = $Users[0]["group_name"];
      $this->set('group_name', $group_name);
      $staffhyouji = $Users[0]["staff"]['name'];
      $this->set('staffhyouji', $staffhyouji);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeletestaff = array();
      $arrdeletestaff = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletecompany);
      echo "</pre>";
*/
      $Staffs = $this->Staffs->patchEntity($this->Staffs->newEntity(), $arrdeletestaff);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Staffs->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeletestaff['id']]
         )){

          $this->Users->updateAll(
            [ 'delete_flag' => 1,
              'updated_at' => date('Y-m-d H:i:s'),
              'updated_staff' => $staff_id],
            ['staff_id'  => $arrdeletestaff['id']]
          );
 
            $this->StaffAbilities->updateAll(
              [ 'delete_flag' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_staff' => $staff_id],
              ['staff_id'  => $arrdeletestaff['id']]);
 
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
