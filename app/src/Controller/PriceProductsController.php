<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PriceProducts Controller
 *
 * @property \App\Model\Table\PriceProductsTable $PriceProducts
 *
 * @method \App\Model\Entity\PriceProduct[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PriceProductsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Products', 'Customers']
        ];
        $priceProducts = $this->paginate($this->PriceProducts);

        $this->set(compact('priceProducts'));
    }

    /**
     * View method
     *
     * @param string|null $id Price Product id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $priceProduct = $this->PriceProducts->get($id, [
            'contain' => ['Products', 'Customers']
        ]);

        $this->set('priceProduct', $priceProduct);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $priceProduct = $this->PriceProducts->newEntity();
        if ($this->request->is('post')) {
            $priceProduct = $this->PriceProducts->patchEntity($priceProduct, $this->request->getData());
            if ($this->PriceProducts->save($priceProduct)) {
                $this->Flash->success(__('The price product has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The price product could not be saved. Please, try again.'));
        }
        $products = $this->PriceProducts->Products->find('list', ['limit' => 200]);
        $customers = $this->PriceProducts->Customers->find('list', ['limit' => 200]);
        $this->set(compact('priceProduct', 'products', 'customers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Price Product id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $priceProduct = $this->PriceProducts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $priceProduct = $this->PriceProducts->patchEntity($priceProduct, $this->request->getData());
            if ($this->PriceProducts->save($priceProduct)) {
                $this->Flash->success(__('The price product has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The price product could not be saved. Please, try again.'));
        }
        $products = $this->PriceProducts->Products->find('list', ['limit' => 200]);
        $customers = $this->PriceProducts->Customers->find('list', ['limit' => 200]);
        $this->set(compact('priceProduct', 'products', 'customers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Price Product id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $priceProduct = $this->PriceProducts->get($id);
        if ($this->PriceProducts->delete($priceProduct)) {
            $this->Flash->success(__('The price product has been deleted.'));
        } else {
            $this->Flash->error(__('The price product could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
