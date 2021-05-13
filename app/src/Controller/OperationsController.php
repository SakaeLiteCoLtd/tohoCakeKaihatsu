<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Operations Controller
 *
 * @property \App\Model\Table\OperationsTable $Operations
 *
 * @method \App\Model\Entity\Operation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OperationsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Companies', 'Offices']
        ];
        $operations = $this->paginate($this->Operations);

        $this->set(compact('operations'));
    }

    /**
     * View method
     *
     * @param string|null $id Operation id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $operation = $this->Operations->get($id, [
            'contain' => ['Companies', 'Offices']
        ]);

        $this->set('operation', $operation);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $operation = $this->Operations->newEntity();
        if ($this->request->is('post')) {
            $operation = $this->Operations->patchEntity($operation, $this->request->getData());
            if ($this->Operations->save($operation)) {
                $this->Flash->success(__('The operation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The operation could not be saved. Please, try again.'));
        }
        $companies = $this->Operations->Companies->find('list', ['limit' => 200]);
        $offices = $this->Operations->Offices->find('list', ['limit' => 200]);
        $this->set(compact('operation', 'companies', 'offices'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Operation id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $operation = $this->Operations->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $operation = $this->Operations->patchEntity($operation, $this->request->getData());
            if ($this->Operations->save($operation)) {
                $this->Flash->success(__('The operation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The operation could not be saved. Please, try again.'));
        }
        $companies = $this->Operations->Companies->find('list', ['limit' => 200]);
        $offices = $this->Operations->Offices->find('list', ['limit' => 200]);
        $this->set(compact('operation', 'companies', 'offices'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Operation id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $operation = $this->Operations->get($id);
        if ($this->Operations->delete($operation)) {
            $this->Flash->success(__('The operation has been deleted.'));
        } else {
            $this->Flash->error(__('The operation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
