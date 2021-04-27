<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * MaterialSuppliers Controller
 *
 * @property \App\Model\Table\MaterialSuppliersTable $MaterialSuppliers
 *
 * @method \App\Model\Entity\MaterialSupplier[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MaterialSuppliersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $materialSuppliers = $this->paginate($this->MaterialSuppliers);

        $this->set(compact('materialSuppliers'));
    }

    /**
     * View method
     *
     * @param string|null $id Material Supplier id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $materialSupplier = $this->MaterialSuppliers->get($id, [
            'contain' => ['PriceMaterials']
        ]);

        $this->set('materialSupplier', $materialSupplier);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $materialSupplier = $this->MaterialSuppliers->newEntity();
        if ($this->request->is('post')) {
            $materialSupplier = $this->MaterialSuppliers->patchEntity($materialSupplier, $this->request->getData());
            if ($this->MaterialSuppliers->save($materialSupplier)) {
                $this->Flash->success(__('The material supplier has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The material supplier could not be saved. Please, try again.'));
        }
        $this->set(compact('materialSupplier'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Material Supplier id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $materialSupplier = $this->MaterialSuppliers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $materialSupplier = $this->MaterialSuppliers->patchEntity($materialSupplier, $this->request->getData());
            if ($this->MaterialSuppliers->save($materialSupplier)) {
                $this->Flash->success(__('The material supplier has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The material supplier could not be saved. Please, try again.'));
        }
        $this->set(compact('materialSupplier'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Material Supplier id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $materialSupplier = $this->MaterialSuppliers->get($id);
        if ($this->MaterialSuppliers->delete($materialSupplier)) {
            $this->Flash->success(__('The material supplier has been deleted.'));
        } else {
            $this->Flash->error(__('The material supplier could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
