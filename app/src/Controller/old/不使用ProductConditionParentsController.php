<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProductConditionParents Controller
 *
 * @property \App\Model\Table\ProductConditionParentsTable $ProductConditionParents
 *
 * @method \App\Model\Entity\ProductConditionParent[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductConditionParentsController extends AppController
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
        $productConditionParents = $this->paginate($this->ProductConditionParents);

        $this->set(compact('productConditionParents'));
    }

    /**
     * View method
     *
     * @param string|null $id Product Condition Parent id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $productConditionParent = $this->ProductConditionParents->get($id, [
            'contain' => ['Products', 'ProductConditonChildren']
        ]);

        $this->set('productConditionParent', $productConditionParent);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productConditionParent = $this->ProductConditionParents->newEntity();
        if ($this->request->is('post')) {
            $productConditionParent = $this->ProductConditionParents->patchEntity($productConditionParent, $this->request->getData());
            if ($this->ProductConditionParents->save($productConditionParent)) {
                $this->Flash->success(__('The product condition parent has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product condition parent could not be saved. Please, try again.'));
        }
        $products = $this->ProductConditionParents->Products->find('list', ['limit' => 200]);
        $this->set(compact('productConditionParent', 'products'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product Condition Parent id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $productConditionParent = $this->ProductConditionParents->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productConditionParent = $this->ProductConditionParents->patchEntity($productConditionParent, $this->request->getData());
            if ($this->ProductConditionParents->save($productConditionParent)) {
                $this->Flash->success(__('The product condition parent has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product condition parent could not be saved. Please, try again.'));
        }
        $products = $this->ProductConditionParents->Products->find('list', ['limit' => 200]);
        $this->set(compact('productConditionParent', 'products'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Product Condition Parent id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $productConditionParent = $this->ProductConditionParents->get($id);
        if ($this->ProductConditionParents->delete($productConditionParent)) {
            $this->Flash->success(__('The product condition parent has been deleted.'));
        } else {
            $this->Flash->error(__('The product condition parent could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
