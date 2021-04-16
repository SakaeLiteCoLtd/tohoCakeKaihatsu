<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * StaffAbilities Controller
 *
 * @property \App\Model\Table\StaffAbilitiesTable $StaffAbilities
 *
 * @method \App\Model\Entity\StaffAbility[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StaffAbilitiesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Staffs', 'Menus']
        ];
        $staffAbilities = $this->paginate($this->StaffAbilities);

        $this->set(compact('staffAbilities'));
    }

    /**
     * View method
     *
     * @param string|null $id Staff Ability id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $staffAbility = $this->StaffAbilities->get($id, [
            'contain' => ['Staffs', 'Menus']
        ]);

        $this->set('staffAbility', $staffAbility);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
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

    /**
     * Edit method
     *
     * @param string|null $id Staff Ability id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
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

    /**
     * Delete method
     *
     * @param string|null $id Staff Ability id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
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
