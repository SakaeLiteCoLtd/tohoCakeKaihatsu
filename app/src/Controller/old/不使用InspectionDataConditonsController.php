<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * InspectionDataConditons Controller
 *
 * @property \App\Model\Table\InspectionDataConditonsTable $InspectionDataConditons
 *
 * @method \App\Model\Entity\InspectionDataConditon[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InspectionDataConditonsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ProductConditonChildren']
        ];
        $inspectionDataConditons = $this->paginate($this->InspectionDataConditons);

        $this->set(compact('inspectionDataConditons'));
    }

    /**
     * View method
     *
     * @param string|null $id Inspection Data Conditon id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $inspectionDataConditon = $this->InspectionDataConditons->get($id, [
            'contain' => ['ProductConditonChildren']
        ]);

        $this->set('inspectionDataConditon', $inspectionDataConditon);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $inspectionDataConditon = $this->InspectionDataConditons->newEntity();
        if ($this->request->is('post')) {
            $inspectionDataConditon = $this->InspectionDataConditons->patchEntity($inspectionDataConditon, $this->request->getData());
            if ($this->InspectionDataConditons->save($inspectionDataConditon)) {
                $this->Flash->success(__('The inspection data conditon has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inspection data conditon could not be saved. Please, try again.'));
        }
        $productConditonChildren = $this->InspectionDataConditons->ProductConditonChildren->find('list', ['limit' => 200]);
        $this->set(compact('inspectionDataConditon', 'productConditonChildren'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Inspection Data Conditon id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $inspectionDataConditon = $this->InspectionDataConditons->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $inspectionDataConditon = $this->InspectionDataConditons->patchEntity($inspectionDataConditon, $this->request->getData());
            if ($this->InspectionDataConditons->save($inspectionDataConditon)) {
                $this->Flash->success(__('The inspection data conditon has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inspection data conditon could not be saved. Please, try again.'));
        }
        $productConditonChildren = $this->InspectionDataConditons->ProductConditonChildren->find('list', ['limit' => 200]);
        $this->set(compact('inspectionDataConditon', 'productConditonChildren'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Inspection Data Conditon id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $inspectionDataConditon = $this->InspectionDataConditons->get($id);
        if ($this->InspectionDataConditons->delete($inspectionDataConditon)) {
            $this->Flash->success(__('The inspection data conditon has been deleted.'));
        } else {
            $this->Flash->error(__('The inspection data conditon could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
