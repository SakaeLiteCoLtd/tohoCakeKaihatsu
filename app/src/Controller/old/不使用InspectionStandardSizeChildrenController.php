<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * InspectionStandardSizeChildren Controller
 *
 * @property \App\Model\Table\InspectionStandardSizeChildrenTable $InspectionStandardSizeChildren
 *
 * @method \App\Model\Entity\InspectionStandardSizeChild[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InspectionStandardSizeChildrenController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['InspectionStandardSizeParents']
        ];
        $inspectionStandardSizeChildren = $this->paginate($this->InspectionStandardSizeChildren);

        $this->set(compact('inspectionStandardSizeChildren'));
    }

    /**
     * View method
     *
     * @param string|null $id Inspection Standard Size Child id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $inspectionStandardSizeChild = $this->InspectionStandardSizeChildren->get($id, [
            'contain' => ['InspectionStandardSizeParents', 'InspectionDataResultChildren']
        ]);

        $this->set('inspectionStandardSizeChild', $inspectionStandardSizeChild);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $inspectionStandardSizeChild = $this->InspectionStandardSizeChildren->newEntity();
        if ($this->request->is('post')) {
            $inspectionStandardSizeChild = $this->InspectionStandardSizeChildren->patchEntity($inspectionStandardSizeChild, $this->request->getData());
            if ($this->InspectionStandardSizeChildren->save($inspectionStandardSizeChild)) {
                $this->Flash->success(__('The inspection standard size child has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inspection standard size child could not be saved. Please, try again.'));
        }
        $inspectionStandardSizeParents = $this->InspectionStandardSizeChildren->InspectionStandardSizeParents->find('list', ['limit' => 200]);
        $this->set(compact('inspectionStandardSizeChild', 'inspectionStandardSizeParents'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Inspection Standard Size Child id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $inspectionStandardSizeChild = $this->InspectionStandardSizeChildren->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $inspectionStandardSizeChild = $this->InspectionStandardSizeChildren->patchEntity($inspectionStandardSizeChild, $this->request->getData());
            if ($this->InspectionStandardSizeChildren->save($inspectionStandardSizeChild)) {
                $this->Flash->success(__('The inspection standard size child has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inspection standard size child could not be saved. Please, try again.'));
        }
        $inspectionStandardSizeParents = $this->InspectionStandardSizeChildren->InspectionStandardSizeParents->find('list', ['limit' => 200]);
        $this->set(compact('inspectionStandardSizeChild', 'inspectionStandardSizeParents'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Inspection Standard Size Child id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $inspectionStandardSizeChild = $this->InspectionStandardSizeChildren->get($id);
        if ($this->InspectionStandardSizeChildren->delete($inspectionStandardSizeChild)) {
            $this->Flash->success(__('The inspection standard size child has been deleted.'));
        } else {
            $this->Flash->error(__('The inspection standard size child could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
