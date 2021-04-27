<?php
namespace App\Controller;

use App\Controller\AppController;

class StaffAbilitiesController extends AppController
{

    public function index()
    {
        $this->paginate = [
            'contain' => ['Staffs', 'Menus']
        ];
        $staffAbilities = $this->paginate($this->StaffAbilities->find()->where(['StaffAbilities.delete_flag' => 0]));

        $this->set(compact('staffAbilities'));
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
