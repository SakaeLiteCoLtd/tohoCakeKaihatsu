<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProductMaterialParents Controller
 *
 * @property \App\Model\Table\ProductMaterialParentsTable $ProductMaterialParents
 *
 * @method \App\Model\Entity\ProductMaterialParent[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductMaterialParentsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Products']
        ];
        $productMaterialParents = $this->paginate($this->ProductMaterialParents);

        $this->set(compact('productMaterialParents'));
    }

    /**
     * View method
     *
     * @param string|null $id Product Material Parent id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $productMaterialParent = $this->ProductMaterialParents->get($id, [
            'contain' => ['Products', 'InspectionDataResultParents', 'ProductMaterialMachines']
        ]);

        $this->set('productMaterialParent', $productMaterialParent);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productMaterialParent = $this->ProductMaterialParents->newEntity();
        if ($this->request->is('post')) {
            $productMaterialParent = $this->ProductMaterialParents->patchEntity($productMaterialParent, $this->request->getData());
            if ($this->ProductMaterialParents->save($productMaterialParent)) {
                $this->Flash->success(__('The product material parent has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product material parent could not be saved. Please, try again.'));
        }
        $products = $this->ProductMaterialParents->Products->find('list', ['limit' => 200]);
        $this->set(compact('productMaterialParent', 'products'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product Material Parent id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $productMaterialParent = $this->ProductMaterialParents->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productMaterialParent = $this->ProductMaterialParents->patchEntity($productMaterialParent, $this->request->getData());
            if ($this->ProductMaterialParents->save($productMaterialParent)) {
                $this->Flash->success(__('The product material parent has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product material parent could not be saved. Please, try again.'));
        }
        $products = $this->ProductMaterialParents->Products->find('list', ['limit' => 200]);
        $this->set(compact('productMaterialParent', 'products'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Product Material Parent id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $productMaterialParent = $this->ProductMaterialParents->get($id);
        if ($this->ProductMaterialParents->delete($productMaterialParent)) {
            $this->Flash->success(__('The product material parent has been deleted.'));
        } else {
            $this->Flash->error(__('The product material parent could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
