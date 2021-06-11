<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * InspectionDataConditonParents Controller
 *
 * @property \App\Model\Table\InspectionDataConditonParentsTable $InspectionDataConditonParents
 *
 * @method \App\Model\Entity\InspectionDataConditonParent[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InspectionDataConditonParentsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $inspectionDataConditonParents = $this->paginate($this->InspectionDataConditonParents);

        $this->set(compact('inspectionDataConditonParents'));
    }

    /**
     * View method
     *
     * @param string|null $id Inspection Data Conditon Parent id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $inspectionDataConditonParent = $this->InspectionDataConditonParents->get($id, [
            'contain' => ['InspectionDataConditonChildren']
        ]);

        $this->set('inspectionDataConditonParent', $inspectionDataConditonParent);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $inspectionDataConditonParent = $this->InspectionDataConditonParents->newEntity();
        if ($this->request->is('post')) {
            $inspectionDataConditonParent = $this->InspectionDataConditonParents->patchEntity($inspectionDataConditonParent, $this->request->getData());
            if ($this->InspectionDataConditonParents->save($inspectionDataConditonParent)) {
                $this->Flash->success(__('The inspection data conditon parent has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inspection data conditon parent could not be saved. Please, try again.'));
        }
        $this->set(compact('inspectionDataConditonParent'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Inspection Data Conditon Parent id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $inspectionDataConditonParent = $this->InspectionDataConditonParents->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $inspectionDataConditonParent = $this->InspectionDataConditonParents->patchEntity($inspectionDataConditonParent, $this->request->getData());
            if ($this->InspectionDataConditonParents->save($inspectionDataConditonParent)) {
                $this->Flash->success(__('The inspection data conditon parent has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inspection data conditon parent could not be saved. Please, try again.'));
        }
        $this->set(compact('inspectionDataConditonParent'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Inspection Data Conditon Parent id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $inspectionDataConditonParent = $this->InspectionDataConditonParents->get($id);
        if ($this->InspectionDataConditonParents->delete($inspectionDataConditonParent)) {
            $this->Flash->success(__('The inspection data conditon parent has been deleted.'));
        } else {
            $this->Flash->error(__('The inspection data conditon parent could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
