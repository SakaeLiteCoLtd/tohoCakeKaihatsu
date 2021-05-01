<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * InspectionDataResultChildren Controller
 *
 * @property \App\Model\Table\InspectionDataResultChildrenTable $InspectionDataResultChildren
 *
 * @method \App\Model\Entity\InspectionDataResultChild[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InspectionDataResultChildrenController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['InspectionDataResultParents', 'InspectionStandardSizeChildren']
        ];
        $inspectionDataResultChildren = $this->paginate($this->InspectionDataResultChildren);

        $this->set(compact('inspectionDataResultChildren'));
    }

    /**
     * View method
     *
     * @param string|null $id Inspection Data Result Child id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $inspectionDataResultChild = $this->InspectionDataResultChildren->get($id, [
            'contain' => ['InspectionDataResultParents', 'InspectionStandardSizeChildren']
        ]);

        $this->set('inspectionDataResultChild', $inspectionDataResultChild);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $inspectionDataResultChild = $this->InspectionDataResultChildren->newEntity();
        if ($this->request->is('post')) {
            $inspectionDataResultChild = $this->InspectionDataResultChildren->patchEntity($inspectionDataResultChild, $this->request->getData());
            if ($this->InspectionDataResultChildren->save($inspectionDataResultChild)) {
                $this->Flash->success(__('The inspection data result child has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inspection data result child could not be saved. Please, try again.'));
        }
        $inspectionDataResultParents = $this->InspectionDataResultChildren->InspectionDataResultParents->find('list', ['limit' => 200]);
        $inspectionStandardSizeChildren = $this->InspectionDataResultChildren->InspectionStandardSizeChildren->find('list', ['limit' => 200]);
        $this->set(compact('inspectionDataResultChild', 'inspectionDataResultParents', 'inspectionStandardSizeChildren'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Inspection Data Result Child id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $inspectionDataResultChild = $this->InspectionDataResultChildren->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $inspectionDataResultChild = $this->InspectionDataResultChildren->patchEntity($inspectionDataResultChild, $this->request->getData());
            if ($this->InspectionDataResultChildren->save($inspectionDataResultChild)) {
                $this->Flash->success(__('The inspection data result child has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inspection data result child could not be saved. Please, try again.'));
        }
        $inspectionDataResultParents = $this->InspectionDataResultChildren->InspectionDataResultParents->find('list', ['limit' => 200]);
        $inspectionStandardSizeChildren = $this->InspectionDataResultChildren->InspectionStandardSizeChildren->find('list', ['limit' => 200]);
        $this->set(compact('inspectionDataResultChild', 'inspectionDataResultParents', 'inspectionStandardSizeChildren'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Inspection Data Result Child id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $inspectionDataResultChild = $this->InspectionDataResultChildren->get($id);
        if ($this->InspectionDataResultChildren->delete($inspectionDataResultChild)) {
            $this->Flash->success(__('The inspection data result child has been deleted.'));
        } else {
            $this->Flash->error(__('The inspection data result child could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
