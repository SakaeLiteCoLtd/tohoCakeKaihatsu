<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProductMachineMaterials Controller
 *
 * @property \App\Model\Table\ProductMachineMaterialsTable $ProductMachineMaterials
 *
 * @method \App\Model\Entity\ProductMachineMaterial[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductMachineMaterialsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ProductMaterialMachines']
        ];
        $productMachineMaterials = $this->paginate($this->ProductMachineMaterials);

        $this->set(compact('productMachineMaterials'));
    }

    /**
     * View method
     *
     * @param string|null $id Product Machine Material id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $productMachineMaterial = $this->ProductMachineMaterials->get($id, [
            'contain' => ['ProductMaterialMachines', 'ProductMaterialLotNumbers']
        ]);

        $this->set('productMachineMaterial', $productMachineMaterial);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productMachineMaterial = $this->ProductMachineMaterials->newEntity();
        if ($this->request->is('post')) {
            $productMachineMaterial = $this->ProductMachineMaterials->patchEntity($productMachineMaterial, $this->request->getData());
            if ($this->ProductMachineMaterials->save($productMachineMaterial)) {
                $this->Flash->success(__('The product machine material has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product machine material could not be saved. Please, try again.'));
        }
        $productMaterialMachines = $this->ProductMachineMaterials->ProductMaterialMachines->find('list', ['limit' => 200]);
        $this->set(compact('productMachineMaterial', 'productMaterialMachines'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product Machine Material id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $productMachineMaterial = $this->ProductMachineMaterials->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productMachineMaterial = $this->ProductMachineMaterials->patchEntity($productMachineMaterial, $this->request->getData());
            if ($this->ProductMachineMaterials->save($productMachineMaterial)) {
                $this->Flash->success(__('The product machine material has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product machine material could not be saved. Please, try again.'));
        }
        $productMaterialMachines = $this->ProductMachineMaterials->ProductMaterialMachines->find('list', ['limit' => 200]);
        $this->set(compact('productMachineMaterial', 'productMaterialMachines'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Product Machine Material id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $productMachineMaterial = $this->ProductMachineMaterials->get($id);
        if ($this->ProductMachineMaterials->delete($productMachineMaterial)) {
            $this->Flash->success(__('The product machine material has been deleted.'));
        } else {
            $this->Flash->error(__('The product machine material could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
