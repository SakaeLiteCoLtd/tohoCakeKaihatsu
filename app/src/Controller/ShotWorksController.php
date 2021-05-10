<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ShotWorks Controller
 *
 * @property \App\Model\Table\ShotWorksTable $ShotWorks
 *
 * @method \App\Model\Entity\ShotWork[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ShotWorksController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Factories', 'ProductConditionParents']
        ];
        $shotWorks = $this->paginate($this->ShotWorks);

        $this->set(compact('shotWorks'));
    }

    /**
     * View method
     *
     * @param string|null $id Shot Work id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shotWork = $this->ShotWorks->get($id, [
            'contain' => ['Factories', 'ProductConditionParents']
        ]);

        $this->set('shotWork', $shotWork);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shotWork = $this->ShotWorks->newEntity();
        if ($this->request->is('post')) {
            $shotWork = $this->ShotWorks->patchEntity($shotWork, $this->request->getData());
            if ($this->ShotWorks->save($shotWork)) {
                $this->Flash->success(__('The shot work has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The shot work could not be saved. Please, try again.'));
        }
        $factories = $this->ShotWorks->Factories->find('list', ['limit' => 200]);
        $productConditionParents = $this->ShotWorks->ProductConditionParents->find('list', ['limit' => 200]);
        $this->set(compact('shotWork', 'factories', 'productConditionParents'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Shot Work id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shotWork = $this->ShotWorks->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shotWork = $this->ShotWorks->patchEntity($shotWork, $this->request->getData());
            if ($this->ShotWorks->save($shotWork)) {
                $this->Flash->success(__('The shot work has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The shot work could not be saved. Please, try again.'));
        }
        $factories = $this->ShotWorks->Factories->find('list', ['limit' => 200]);
        $productConditionParents = $this->ShotWorks->ProductConditionParents->find('list', ['limit' => 200]);
        $this->set(compact('shotWork', 'factories', 'productConditionParents'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Shot Work id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shotWork = $this->ShotWorks->get($id);
        if ($this->ShotWorks->delete($shotWork)) {
            $this->Flash->success(__('The shot work has been deleted.'));
        } else {
            $this->Flash->error(__('The shot work could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
