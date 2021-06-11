<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * InspectionDataConditonChildren Controller
 *
 * @property \App\Model\Table\InspectionDataConditonChildrenTable $InspectionDataConditonChildren
 *
 * @method \App\Model\Entity\InspectionDataConditonChild[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InspectionDataConditonChildrenController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['InspectionDataConditonParents', 'ProductConditonChildren']
        ];
        $inspectionDataConditonChildren = $this->paginate($this->InspectionDataConditonChildren);

        $this->set(compact('inspectionDataConditonChildren'));
    }

    /**
     * View method
     *
     * @param string|null $id Inspection Data Conditon Child id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $inspectionDataConditonChild = $this->InspectionDataConditonChildren->get($id, [
            'contain' => ['InspectionDataConditonParents', 'ProductConditonChildren']
        ]);

        $this->set('inspectionDataConditonChild', $inspectionDataConditonChild);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $inspectionDataConditonChild = $this->InspectionDataConditonChildren->newEntity();
        if ($this->request->is('post')) {
            $inspectionDataConditonChild = $this->InspectionDataConditonChildren->patchEntity($inspectionDataConditonChild, $this->request->getData());
            if ($this->InspectionDataConditonChildren->save($inspectionDataConditonChild)) {
                $this->Flash->success(__('The inspection data conditon child has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inspection data conditon child could not be saved. Please, try again.'));
        }
        $inspectionDataConditonParents = $this->InspectionDataConditonChildren->InspectionDataConditonParents->find('list', ['limit' => 200]);
        $productConditonChildren = $this->InspectionDataConditonChildren->ProductConditonChildren->find('list', ['limit' => 200]);
        $this->set(compact('inspectionDataConditonChild', 'inspectionDataConditonParents', 'productConditonChildren'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Inspection Data Conditon Child id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $inspectionDataConditonChild = $this->InspectionDataConditonChildren->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $inspectionDataConditonChild = $this->InspectionDataConditonChildren->patchEntity($inspectionDataConditonChild, $this->request->getData());
            if ($this->InspectionDataConditonChildren->save($inspectionDataConditonChild)) {
                $this->Flash->success(__('The inspection data conditon child has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inspection data conditon child could not be saved. Please, try again.'));
        }
        $inspectionDataConditonParents = $this->InspectionDataConditonChildren->InspectionDataConditonParents->find('list', ['limit' => 200]);
        $productConditonChildren = $this->InspectionDataConditonChildren->ProductConditonChildren->find('list', ['limit' => 200]);
        $this->set(compact('inspectionDataConditonChild', 'inspectionDataConditonParents', 'productConditonChildren'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Inspection Data Conditon Child id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $inspectionDataConditonChild = $this->InspectionDataConditonChildren->get($id);
        if ($this->InspectionDataConditonChildren->delete($inspectionDataConditonChild)) {
            $this->Flash->success(__('The inspection data conditon child has been deleted.'));
        } else {
            $this->Flash->error(__('The inspection data conditon child could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
