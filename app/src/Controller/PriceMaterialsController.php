<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PriceMaterials Controller
 *
 * @property \App\Model\Table\PriceMaterialsTable $PriceMaterials
 *
 * @method \App\Model\Entity\PriceMaterial[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PriceMaterialsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Materials', 'MaterialSuppliers']
        ];
        $priceMaterials = $this->paginate($this->PriceMaterials);

        $this->set(compact('priceMaterials'));
    }

    /**
     * View method
     *
     * @param string|null $id Price Material id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $priceMaterial = $this->PriceMaterials->get($id, [
            'contain' => ['Materials', 'MaterialSuppliers']
        ]);

        $this->set('priceMaterial', $priceMaterial);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $priceMaterial = $this->PriceMaterials->newEntity();
        if ($this->request->is('post')) {
            $priceMaterial = $this->PriceMaterials->patchEntity($priceMaterial, $this->request->getData());
            if ($this->PriceMaterials->save($priceMaterial)) {
                $this->Flash->success(__('The price material has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The price material could not be saved. Please, try again.'));
        }
        $materials = $this->PriceMaterials->Materials->find('list', ['limit' => 200]);
        $materialSuppliers = $this->PriceMaterials->MaterialSuppliers->find('list', ['limit' => 200]);
        $this->set(compact('priceMaterial', 'materials', 'materialSuppliers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Price Material id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $priceMaterial = $this->PriceMaterials->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $priceMaterial = $this->PriceMaterials->patchEntity($priceMaterial, $this->request->getData());
            if ($this->PriceMaterials->save($priceMaterial)) {
                $this->Flash->success(__('The price material has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The price material could not be saved. Please, try again.'));
        }
        $materials = $this->PriceMaterials->Materials->find('list', ['limit' => 200]);
        $materialSuppliers = $this->PriceMaterials->MaterialSuppliers->find('list', ['limit' => 200]);
        $this->set(compact('priceMaterial', 'materials', 'materialSuppliers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Price Material id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $priceMaterial = $this->PriceMaterials->get($id);
        if ($this->PriceMaterials->delete($priceMaterial)) {
            $this->Flash->success(__('The price material has been deleted.'));
        } else {
            $this->Flash->error(__('The price material could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
