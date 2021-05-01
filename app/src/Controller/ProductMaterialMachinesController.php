<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProductMaterialMachines Controller
 *
 * @property \App\Model\Table\ProductMaterialMachinesTable $ProductMaterialMachines
 *
 * @method \App\Model\Entity\ProductMaterialMachine[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductMaterialMachinesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ProductMaterialParents']
        ];
        $productMaterialMachines = $this->paginate($this->ProductMaterialMachines);

        $this->set(compact('productMaterialMachines'));
    }

    /**
     * View method
     *
     * @param string|null $id Product Material Machine id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $productMaterialMachine = $this->ProductMaterialMachines->get($id, [
            'contain' => ['ProductMaterialParents', 'ProductMachineMaterials']
        ]);

        $this->set('productMaterialMachine', $productMaterialMachine);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productMaterialMachine = $this->ProductMaterialMachines->newEntity();
        if ($this->request->is('post')) {
            $productMaterialMachine = $this->ProductMaterialMachines->patchEntity($productMaterialMachine, $this->request->getData());
            if ($this->ProductMaterialMachines->save($productMaterialMachine)) {
                $this->Flash->success(__('The product material machine has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product material machine could not be saved. Please, try again.'));
        }
        $productMaterialParents = $this->ProductMaterialMachines->ProductMaterialParents->find('list', ['limit' => 200]);
        $this->set(compact('productMaterialMachine', 'productMaterialParents'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product Material Machine id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $productMaterialMachine = $this->ProductMaterialMachines->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productMaterialMachine = $this->ProductMaterialMachines->patchEntity($productMaterialMachine, $this->request->getData());
            if ($this->ProductMaterialMachines->save($productMaterialMachine)) {
                $this->Flash->success(__('The product material machine has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product material machine could not be saved. Please, try again.'));
        }
        $productMaterialParents = $this->ProductMaterialMachines->ProductMaterialParents->find('list', ['limit' => 200]);
        $this->set(compact('productMaterialMachine', 'productMaterialParents'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Product Material Machine id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $productMaterialMachine = $this->ProductMaterialMachines->get($id);
        if ($this->ProductMaterialMachines->delete($productMaterialMachine)) {
            $this->Flash->success(__('The product material machine has been deleted.'));
        } else {
            $this->Flash->error(__('The product material machine could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
