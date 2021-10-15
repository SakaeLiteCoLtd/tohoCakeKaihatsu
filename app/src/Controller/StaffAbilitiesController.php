<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;//ログインに使用
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション

class StaffAbilitiesController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Users = TableRegistry::get('Users');
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
       ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "メンバー", 'Groups.delete_flag' => 0])
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
        $factory_id = $Users[0]["staff"]["factory_id"];
        $this->set('factory_id', $factory_id);

        $this->paginate = [
            'contain' => ['Staffs']
        ];
        $staffAbilities = $this->paginate($this->StaffAbilities->find()->contain(["Staffs"])
        ->select(['StaffAbilities.staff_id', 'Staffs.name', 'Staffs.factory_id' ,'delete_flag' => 0])
        ->group(['staff_id']));
        $this->set(compact('staffAbilities'));
  
    }

    public function detail($staff_id = null)
    {
      $staffAbility = $this->StaffAbilities->newEntity();
      $this->set('staffAbility', $staffAbility);

      $Staffs = $this->Staffs->find()
      ->where(['id' => $staff_id])->toArray();
      $staff_name = $Staffs[0]["name"];
      $this->set('staff_name', $staff_name);

      $StaffAbilities = $this->StaffAbilities->find()->contain(["Menus"])
      ->where(['staff_id' => $staff_id, 'StaffAbilities.delete_flag' => 0])->toArray();

      $arrStaffAbilities = array();
      for($k=0; $k<count($StaffAbilities); $k++){

        $arrStaffAbilities[] = $StaffAbilities[$k]['menu']['name_menu'];

      }
      $this->set('arrStaffAbilities', $arrStaffAbilities);
/*
      echo "<pre>";
      print_r($arrGroups);
      echo "</pre>";
*/
    }

    public function view($id = null)
    {
        $staffAbility = $this->StaffAbilities->get($id, [
            'contain' => ['Staffs', 'Menus']
        ]);

        $this->set('staffAbility', $staffAbility);
    }

    public function add()
    {
        $staffAbility = $this->StaffAbilities->newEntity();
        if ($this->request->is('post')) {
            $staffAbility = $this->StaffAbilities->patchEntity($staffAbility, $this->request->getData());
            if ($this->StaffAbilities->save($staffAbility)) {
                $this->Flash->success(__('The staff ability has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The staff ability could not be saved. Please, try again.'));
        }
        $staffs = $this->StaffAbilities->Staffs->find('list', ['limit' => 200]);
        $menus = $this->StaffAbilities->Menus->find('list', ['limit' => 200]);
        $this->set(compact('staffAbility', 'staffs', 'menus'));
    }

    public function edit($id = null)
    {
        $staffAbility = $this->StaffAbilities->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $staffAbility = $this->StaffAbilities->patchEntity($staffAbility, $this->request->getData());
            if ($this->StaffAbilities->save($staffAbility)) {
                $this->Flash->success(__('The staff ability has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The staff ability could not be saved. Please, try again.'));
        }
        $staffs = $this->StaffAbilities->Staffs->find('list', ['limit' => 200]);
        $menus = $this->StaffAbilities->Menus->find('list', ['limit' => 200]);
        $this->set(compact('staffAbility', 'staffs', 'menus'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $staffAbility = $this->StaffAbilities->get($id);
        if ($this->StaffAbilities->delete($staffAbility)) {
            $this->Flash->success(__('The staff ability has been deleted.'));
        } else {
            $this->Flash->error(__('The staff ability could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
