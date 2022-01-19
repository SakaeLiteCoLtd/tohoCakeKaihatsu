<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ShotdataBases Controller
 *
 * @property \App\Model\Table\ShotdataBasesTable $ShotdataBases
 *
 * @method \App\Model\Entity\ShotdataBase[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ShotdataBasesController extends AppController
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
        $shotdataBases = $this->paginate($this->ShotdataBases);

        $this->set(compact('shotdataBases'));
    }

    /**
     * View method
     *
     * @param string|null $id Shotdata Base id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shotdataBase = $this->ShotdataBases->get($id, [
            'contain' => ['Factories', 'ProductConditionParents']
        ]);

        $this->set('shotdataBase', $shotdataBase);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shotdataBase = $this->ShotdataBases->newEntity();
        if ($this->request->is('post')) {
            $shotdataBase = $this->ShotdataBases->patchEntity($shotdataBase, $this->request->getData());
            if ($this->ShotdataBases->save($shotdataBase)) {
                $this->Flash->success(__('The shotdata base has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The shotdata base could not be saved. Please, try again.'));
        }
        $factories = $this->ShotdataBases->Factories->find('list', ['limit' => 200]);
        $productConditionParents = $this->ShotdataBases->ProductConditionParents->find('list', ['limit' => 200]);
        $this->set(compact('shotdataBase', 'factories', 'productConditionParents'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Shotdata Base id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shotdataBase = $this->ShotdataBases->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shotdataBase = $this->ShotdataBases->patchEntity($shotdataBase, $this->request->getData());
            if ($this->ShotdataBases->save($shotdataBase)) {
                $this->Flash->success(__('The shotdata base has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The shotdata base could not be saved. Please, try again.'));
        }
        $factories = $this->ShotdataBases->Factories->find('list', ['limit' => 200]);
        $productConditionParents = $this->ShotdataBases->ProductConditionParents->find('list', ['limit' => 200]);
        $this->set(compact('shotdataBase', 'factories', 'productConditionParents'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Shotdata Base id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shotdataBase = $this->ShotdataBases->get($id);
        if ($this->ShotdataBases->delete($shotdataBase)) {
            $this->Flash->success(__('The shotdata base has been deleted.'));
        } else {
            $this->Flash->error(__('The shotdata base could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
