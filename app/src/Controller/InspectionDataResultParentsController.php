<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * InspectionDataResultParents Controller
 *
 * @property \App\Model\Table\InspectionDataResultParentsTable $InspectionDataResultParents
 *
 * @method \App\Model\Entity\InspectionDataResultParent[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InspectionDataResultParentsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['InspectionStandardSizeParents', 'ProductConditonParents', 'ProductMaterialParents', 'Staffs']
        ];
        $inspectionDataResultParents = $this->paginate($this->InspectionDataResultParents);

        $this->set(compact('inspectionDataResultParents'));
    }

    /**
     * View method
     *
     * @param string|null $id Inspection Data Result Parent id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $inspectionDataResultParent = $this->InspectionDataResultParents->get($id, [
            'contain' => ['InspectionStandardSizeParents', 'ProductConditonParents', 'ProductMaterialParents', 'Staffs', 'InspectionDataResultChildren']
        ]);

        $this->set('inspectionDataResultParent', $inspectionDataResultParent);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $inspectionDataResultParent = $this->InspectionDataResultParents->newEntity();
        if ($this->request->is('post')) {
            $inspectionDataResultParent = $this->InspectionDataResultParents->patchEntity($inspectionDataResultParent, $this->request->getData());
            if ($this->InspectionDataResultParents->save($inspectionDataResultParent)) {
                $this->Flash->success(__('The inspection data result parent has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inspection data result parent could not be saved. Please, try again.'));
        }
        $inspectionStandardSizeParents = $this->InspectionDataResultParents->InspectionStandardSizeParents->find('list', ['limit' => 200]);
        $productConditonParents = $this->InspectionDataResultParents->ProductConditonParents->find('list', ['limit' => 200]);
        $productMaterialParents = $this->InspectionDataResultParents->ProductMaterialParents->find('list', ['limit' => 200]);
        $staffs = $this->InspectionDataResultParents->Staffs->find('list', ['limit' => 200]);
        $this->set(compact('inspectionDataResultParent', 'inspectionStandardSizeParents', 'productConditonParents', 'productMaterialParents', 'staffs'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Inspection Data Result Parent id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $inspectionDataResultParent = $this->InspectionDataResultParents->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $inspectionDataResultParent = $this->InspectionDataResultParents->patchEntity($inspectionDataResultParent, $this->request->getData());
            if ($this->InspectionDataResultParents->save($inspectionDataResultParent)) {
                $this->Flash->success(__('The inspection data result parent has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inspection data result parent could not be saved. Please, try again.'));
        }
        $inspectionStandardSizeParents = $this->InspectionDataResultParents->InspectionStandardSizeParents->find('list', ['limit' => 200]);
        $productConditonParents = $this->InspectionDataResultParents->ProductConditonParents->find('list', ['limit' => 200]);
        $productMaterialParents = $this->InspectionDataResultParents->ProductMaterialParents->find('list', ['limit' => 200]);
        $staffs = $this->InspectionDataResultParents->Staffs->find('list', ['limit' => 200]);
        $this->set(compact('inspectionDataResultParent', 'inspectionStandardSizeParents', 'productConditonParents', 'productMaterialParents', 'staffs'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Inspection Data Result Parent id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $inspectionDataResultParent = $this->InspectionDataResultParents->get($id);
        if ($this->InspectionDataResultParents->delete($inspectionDataResultParent)) {
            $this->Flash->success(__('The inspection data result parent has been deleted.'));
        } else {
            $this->Flash->error(__('The inspection data result parent could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
