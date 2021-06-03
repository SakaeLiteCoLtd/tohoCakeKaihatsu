<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProductLengths Controller
 *
 * @property \App\Model\Table\ProductLengthsTable $ProductLengths
 *
 * @method \App\Model\Entity\ProductLength[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductLengthsController extends AppController
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
        $productLengths = $this->paginate($this->ProductLengths);

        $this->set(compact('productLengths'));
    }

    /**
     * View method
     *
     * @param string|null $id Product Length id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $productLength = $this->ProductLengths->get($id, [
            'contain' => ['Products']
        ]);

        $this->set('productLength', $productLength);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productLength = $this->ProductLengths->newEntity();
        if ($this->request->is('post')) {
            $productLength = $this->ProductLengths->patchEntity($productLength, $this->request->getData());
            if ($this->ProductLengths->save($productLength)) {
                $this->Flash->success(__('The product length has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product length could not be saved. Please, try again.'));
        }
        $products = $this->ProductLengths->Products->find('list', ['limit' => 200]);
        $this->set(compact('productLength', 'products'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product Length id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $productLength = $this->ProductLengths->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productLength = $this->ProductLengths->patchEntity($productLength, $this->request->getData());
            if ($this->ProductLengths->save($productLength)) {
                $this->Flash->success(__('The product length has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product length could not be saved. Please, try again.'));
        }
        $products = $this->ProductLengths->Products->find('list', ['limit' => 200]);
        $this->set(compact('productLength', 'products'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Product Length id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $productLength = $this->ProductLengths->get($id);
        if ($this->ProductLengths->delete($productLength)) {
            $this->Flash->success(__('The product length has been deleted.'));
        } else {
            $this->Flash->error(__('The product length could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
