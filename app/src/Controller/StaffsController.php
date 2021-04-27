<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション

class StaffsController extends AppController
{

    public function index()
    {
        $this->paginate = [
            'contain' => ['Offices', 'Departments', 'Occupations', 'Positions']
        ];
        $staffs = $this->paginate($this->Staffs->find()->where(['Staffs.delete_flag' => 0]));

        $this->set(compact('staffs'));
    }
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
            'contain' => ['Offices', 'Departments', 'Occupations', 'Positions', 'StaffAbilities', 'Users']
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
        $offices = $this->Staffs->Offices->find('list', ['limit' => 200]);
        $departments = $this->Staffs->Departments->find('list', ['limit' => 200]);
        $occupations = $this->Staffs->Occupations->find('list', ['limit' => 200]);
        $positions = $this->Staffs->Positions->find('list', ['limit' => 200]);
        $this->set(compact('staff', 'offices', 'departments', 'occupations', 'positions'));
    }
*/
    public function addform()
    {
      $Staffs = $this->Staffs->newEntity();
      $this->set('Staffs', $Staffs);

      $offices = $this->Staffs->Offices->find('list', ['limit' => 200]);
      $departments = $this->Staffs->Departments->find('list', ['limit' => 200]);
      $occupations = $this->Staffs->Occupations->find('list', ['limit' => 200]);
      $positions = $this->Staffs->Positions->find('list', ['limit' => 200]);
      $this->set(compact('staff', 'offices', 'departments', 'occupations', 'positions'));
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

      $birth = $data['birth'];
      $this->set('birth', $birth);
      $date_start = $data['date_start'];
      $this->set('date_start', $date_start);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokustaff = array();
      $arrtourokustaff = [
        'office_id' => $data["office_id"],
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

    public function editform($id = null)
    {
        $Staffs = $this->Staffs->get($id, [
            'contain' => []
        ]);
        $this->set(compact('Staffs'));
        $this->set('id', $id);

        $offices = $this->Staffs->Offices->find('list', ['limit' => 200]);
        $departments = $this->Staffs->Departments->find('list', ['limit' => 200]);
        $occupations = $this->Staffs->Occupations->find('list', ['limit' => 200]);
        $positions = $this->Staffs->Positions->find('list', ['limit' => 200]);
        $this->set(compact('staff', 'offices', 'departments', 'occupations', 'positions'));
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

      $birth = $data['birth'];
      $this->set('birth', $birth);
      $date_start = $data['date_start'];
      $this->set('date_start', $date_start);
      $date_finish = $data['date_finish'];
      $this->set('date_finish', $date_finish);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdatestaff = array();
      $arrupdatestaff = [
        'id' => $data["id"],
        'office_id' => $data["office_id"],
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
         if ($this->Staffs->updateAll(
           [ 'office_id' => $arrupdatestaff['office_id'],
             'department_id' => $arrupdatestaff['department_id'],
             'occupation_id' => $arrupdatestaff['occupation_id'],
             'position_id' => $arrupdatestaff['position_id'],
             'name' => $arrupdatestaff['name'],
             'sex' => $arrupdatestaff['sex'],
             'mail' => $arrupdatestaff['mail'],
             'tel' => $arrupdatestaff['tel'],
             'address' => $arrupdatestaff['address'],
             'birth' => $updatebirth,
             'date_start' => $updatedate_start,
             'date_finish' => $updatedate_finish,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrupdatestaff['id']]
         )){

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
        $staff = $this->Staffs->get($id, [
          'contain' => ['Offices', 'Departments', 'Occupations', 'Positions']
        ]);
        $this->set(compact('staff'));

        if($staff['sex'] == 0){
          $sexhyouji = "男";
        }else{
          $sexhyouji = "女";
        }
        $this->set('sexhyouji', $sexhyouji);

    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff = $this->Staffs->get($data["id"], [
        'contain' => ['Offices', 'Departments', 'Occupations', 'Positions']
      ]);
      $this->set(compact('staff'));

      if($staff['sex'] == 0){
        $sexhyouji = "男";
      }else{
        $sexhyouji = "女";
      }
      $this->set('sexhyouji', $sexhyouji);

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
