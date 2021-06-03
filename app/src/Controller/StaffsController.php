<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class StaffsController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Factories = TableRegistry::get('Factories');
     $this->Departments = TableRegistry::get('Departments');
     $this->Occupations = TableRegistry::get('Occupations');
     $this->Positions = TableRegistry::get('Positions');

     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

       return $this->redirect($this->Auth->logout());

     }

     if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

       $Groups = $this->Groups->find()->contain(["Menus"])
       ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "スタッフ", 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }

     }

    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Factories', 'Departments', 'Occupations', 'Positions']
        ];
        $staffs = $this->paginate($this->Staffs->find()->where(['Staffs.delete_flag' => 0]));

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

      $Staffs = $this->Staffs->find()->contain(["Factories", "Departments", "Occupations", "Positions"])
      ->where(['Staffs.id' => $id])->toArray();

      $name = $Staffs[0]["name"];
      $this->set('name', $name);
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

    }
/*

/*
    public function top()
    {
      return $this->redirect(
       ['controller' => 'Startmenus', 'action' => 'menu']
      );
    }

    public function logout()
    {
      return $this->redirect($this->Auth->logout());
    }

    public function view($id = null)
    {
        $staff = $this->Staffs->get($id, [
            'contain' => ['Factories', 'Departments', 'Occupations', 'Positions', 'StaffAbilities', 'Users']
        ]);

        $this->set('staff', $staff);
    }

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
        $occupations = $this->Staffs->Occupations->find('list', ['limit' => 200]);
        $positions = $this->Staffs->Positions->find('list', ['limit' => 200]);
        $this->set(compact('staff', 'Factories', 'departments', 'occupations', 'positions'));
    }
*/
    public function addform()
    {
      $Staffs = $this->Staffs->newEntity();
      $this->set('Staffs', $Staffs);

      $Factories = $this->Staffs->Factories->find('list', ['limit' => 200]);
      $this->set(compact('Factories'));

      $Departments = $this->Departments->find()
      ->where(['delete_flag' => 0])->toArray();
      $departments = array();
      foreach ($Departments as $value) {
        $departments[] = array($value->id=>$value->department);
      }
      $this->set('departments', $departments);

      $Occupations = $this->Occupations->find()
      ->where(['delete_flag' => 0])->toArray();
      $occupations = array();
      foreach ($Occupations as $value) {
        $occupations[] = array($value->id=>$value->occupation);
      }
      $this->set('occupations', $occupations);

      $Positions = $this->Positions->find()
      ->where(['delete_flag' => 0])->toArray();
      $positions = array();
      foreach ($Positions as $value) {
        $positions[] = array($value->id=>$value->position);
      }
      $this->set('positions', $positions);

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

      if($data['occupation_id'] > 0){
        $Occupations = $this->Occupations->find()
        ->where(['id' => $data['occupation_id']])->toArray();
        $occupation_name = $Occupations[0]['occupation'];
        $this->set('occupation_name', $occupation_name);
      }else{
        $occupation_name = "";
        $this->set('occupation_name', $occupation_name);
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

      if($data['occupation_id'] > 0){
        $Occupations = $this->Occupations->find()
        ->where(['id' => $data['occupation_id']])->toArray();
        $occupation_name = $Occupations[0]['occupation'];
        $this->set('occupation_name', $occupation_name);
      }else{
        $occupation_name = "";
        $this->set('occupation_name', $occupation_name);
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
        'occupation_id' => $data["occupation_id"],
        'position_id' => $data["position_id"],
        'name' => $data["name"],
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

      $Factories = $this->Staffs->Factories->find('list', ['limit' => 200]);
      $this->set(compact('Factories'));

      $Departments = $this->Departments->find()
      ->where(['delete_flag' => 0])->toArray();
      $departments = array();
      foreach ($Departments as $value) {
        $departments[] = array($value->id=>$value->department);
      }
      $this->set('departments', $departments);

      $Occupations = $this->Occupations->find()
      ->where(['delete_flag' => 0])->toArray();
      $occupations = array();
      foreach ($Occupations as $value) {
        $occupations[] = array($value->id=>$value->occupation);
      }
      $this->set('occupations', $occupations);

      $Positions = $this->Positions->find()
      ->where(['delete_flag' => 0])->toArray();
      $positions = array();
      foreach ($Positions as $value) {
        $positions[] = array($value->id=>$value->position);
      }
      $this->set('positions', $positions);
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

      if($data['occupation_id'] > 0){
        $Occupations = $this->Occupations->find()
        ->where(['id' => $data['occupation_id']])->toArray();
        $occupation_name = $Occupations[0]['occupation'];
        $this->set('occupation_name', $occupation_name);
      }else{
        $occupation_name = "";
        $this->set('occupation_name', $occupation_name);
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

      if($data['occupation_id'] > 0){
        $Occupations = $this->Occupations->find()
        ->where(['id' => $data['occupation_id']])->toArray();
        $occupation_name = $Occupations[0]['occupation'];
        $this->set('occupation_name', $occupation_name);
      }else{
        $occupation_name = "";
        $this->set('occupation_name', $occupation_name);
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

      $arrupdatestaff = array();
      $arrupdatestaff = [
        'factory_id' => $data["factory_id"],
        'department_id' => $data["department_id"],
        'occupation_id' => $data["occupation_id"],
        'position_id' => $data["position_id"],
        'name' => $data["name"],
        'sex' => $data["sex"],
        'mail' => $data["mail"],
        'tel' => $data["tel"],
        'address' => $data["address"],
        'birth' => $data["birth"],
        'date_start' => $data["date_start"],
        'date_finish' => $data["date_finish"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
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
/*
      echo "<pre>";
      print_r(strlen($arrupdatestaff["date_start"]));
      echo "</pre>";
*/
      $Staffs = $this->Staffs->patchEntity($this->Staffs->newEntity(), $arrupdatestaff);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Staffs->save($Staffs)) {

         $this->Staffs->updateAll(
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

    public function deleteconfirm()
    {
      $staff = $this->Staffs->newEntity();
      $this->set('staff', $staff);

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['staffdata'];
      $this->set('id', $id);

      $Staffs = $this->Staffs->find()->contain(["Factories", "Departments", "Occupations", "Positions"])
      ->where(['Staffs.id' => $id])->toArray();

      $name = $Staffs[0]["name"];
      $this->set('name', $name);
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
    }

    public function deletedo()
    {
      $staff = $this->Staffs->newEntity();
      $this->set('staff', $staff);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Staffs = $this->Staffs->find()->contain(["Factories", "Departments", "Occupations", "Positions"])
      ->where(['Staffs.id' => $data["id"]])->toArray();

      $name = $Staffs[0]["name"];
      $this->set('name', $name);
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
