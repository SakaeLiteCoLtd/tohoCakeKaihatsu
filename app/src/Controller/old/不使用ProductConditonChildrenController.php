<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProductConditonChildren Controller
 *
 * @property \App\Model\Table\ProductConditonChildrenTable $ProductConditonChildren
 *
 * @method \App\Model\Entity\ProductConditonChild[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductConditonChildrenController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ProductConditionParents']
        ];
        $productConditonChildren = $this->paginate($this->ProductConditonChildren);

        $this->set(compact('productConditonChildren'));
    }

    /**
     * View method
     *
     * @param string|null $id Product Conditon Child id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $productConditonChild = $this->ProductConditonChildren->get($id, [
            'contain' => ['ProductConditionParents']
        ]);

        $this->set('productConditonChild', $productConditonChild);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productConditonChild = $this->ProductConditonChildren->newEntity();
        if ($this->request->is('post')) {
            $productConditonChild = $this->ProductConditonChildren->patchEntity($productConditonChild, $this->request->getData());
            if ($this->ProductConditonChildren->save($productConditonChild)) {
                $this->Flash->success(__('The product conditon child has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product conditon child could not be saved. Please, try again.'));
        }
        $productConditionParents = $this->ProductConditonChildren->ProductConditionParents->find('list', ['limit' => 200]);
        $this->set(compact('productConditonChild', 'productConditionParents'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product Conditon Child id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $productConditonChild = $this->ProductConditonChildren->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productConditonChild = $this->ProductConditonChildren->patchEntity($productConditonChild, $this->request->getData());
            if ($this->ProductConditonChildren->save($productConditonChild)) {
                $this->Flash->success(__('The product conditon child has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product conditon child could not be saved. Please, try again.'));
        }
        $productConditionParents = $this->ProductConditonChildren->ProductConditionParents->find('list', ['limit' => 200]);
        $this->set(compact('productConditonChild', 'productConditionParents'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Product Conditon Child id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $productConditonChild = $this->ProductConditonChildren->get($id);
        if ($this->ProductConditonChildren->delete($productConditonChild)) {
            $this->Flash->success(__('The product conditon child has been deleted.'));
        } else {
            $this->Flash->error(__('The product conditon child could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
