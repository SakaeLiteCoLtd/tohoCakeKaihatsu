<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProductMaterialLotNumbers Controller
 *
 * @property \App\Model\Table\ProductMaterialLotNumbersTable $ProductMaterialLotNumbers
 *
 * @method \App\Model\Entity\ProductMaterialLotNumber[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductMaterialLotNumbersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ProductMachineMaterials', 'Staffs']
        ];
        $productMaterialLotNumbers = $this->paginate($this->ProductMaterialLotNumbers);

        $this->set(compact('productMaterialLotNumbers'));
    }

    /**
     * View method
     *
     * @param string|null $id Product Material Lot Number id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $productMaterialLotNumber = $this->ProductMaterialLotNumbers->get($id, [
            'contain' => ['ProductMachineMaterials', 'Staffs']
        ]);

        $this->set('productMaterialLotNumber', $productMaterialLotNumber);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productMaterialLotNumber = $this->ProductMaterialLotNumbers->newEntity();
        if ($this->request->is('post')) {
            $productMaterialLotNumber = $this->ProductMaterialLotNumbers->patchEntity($productMaterialLotNumber, $this->request->getData());
            if ($this->ProductMaterialLotNumbers->save($productMaterialLotNumber)) {
                $this->Flash->success(__('The product material lot number has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product material lot number could not be saved. Please, try again.'));
        }
        $productMachineMaterials = $this->ProductMaterialLotNumbers->ProductMachineMaterials->find('list', ['limit' => 200]);
        $staffs = $this->ProductMaterialLotNumbers->Staffs->find('list', ['limit' => 200]);
        $this->set(compact('productMaterialLotNumber', 'productMachineMaterials', 'staffs'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product Material Lot Number id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $productMaterialLotNumber = $this->ProductMaterialLotNumbers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productMaterialLotNumber = $this->ProductMaterialLotNumbers->patchEntity($productMaterialLotNumber, $this->request->getData());
            if ($this->ProductMaterialLotNumbers->save($productMaterialLotNumber)) {
                $this->Flash->success(__('The product material lot number has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product material lot number could not be saved. Please, try again.'));
        }
        $productMachineMaterials = $this->ProductMaterialLotNumbers->ProductMachineMaterials->find('list', ['limit' => 200]);
        $staffs = $this->ProductMaterialLotNumbers->Staffs->find('list', ['limit' => 200]);
        $this->set(compact('productMaterialLotNumber', 'productMachineMaterials', 'staffs'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Product Material Lot Number id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $productMaterialLotNumber = $this->ProductMaterialLotNumbers->get($id);
        if ($this->ProductMaterialLotNumbers->delete($productMaterialLotNumber)) {
            $this->Flash->success(__('The product material lot number has been deleted.'));
        } else {
            $this->Flash->error(__('The product material lot number could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
