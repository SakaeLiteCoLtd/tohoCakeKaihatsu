<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * InspectionStandardSizeParents Controller
 *
 * @property \App\Model\Table\InspectionStandardSizeParentsTable $InspectionStandardSizeParents
 *
 * @method \App\Model\Entity\InspectionStandardSizeParent[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InspectionStandardSizeParentsController extends AppController
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
        $inspectionStandardSizeParents = $this->paginate($this->InspectionStandardSizeParents);

        $this->set(compact('inspectionStandardSizeParents'));
    }

    /**
     * View method
     *
     * @param string|null $id Inspection Standard Size Parent id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $inspectionStandardSizeParent = $this->InspectionStandardSizeParents->get($id, [
            'contain' => ['Products', 'InspectionDataResultParents', 'InspectionStandardSizeChildren']
        ]);

        $this->set('inspectionStandardSizeParent', $inspectionStandardSizeParent);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $inspectionStandardSizeParent = $this->InspectionStandardSizeParents->newEntity();
        if ($this->request->is('post')) {
            $inspectionStandardSizeParent = $this->InspectionStandardSizeParents->patchEntity($inspectionStandardSizeParent, $this->request->getData());
            if ($this->InspectionStandardSizeParents->save($inspectionStandardSizeParent)) {
                $this->Flash->success(__('The inspection standard size parent has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inspection standard size parent could not be saved. Please, try again.'));
        }
        $products = $this->InspectionStandardSizeParents->Products->find('list', ['limit' => 200]);
        $this->set(compact('inspectionStandardSizeParent', 'products'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Inspection Standard Size Parent id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $inspectionStandardSizeParent = $this->InspectionStandardSizeParents->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $inspectionStandardSizeParent = $this->InspectionStandardSizeParents->patchEntity($inspectionStandardSizeParent, $this->request->getData());
            if ($this->InspectionStandardSizeParents->save($inspectionStandardSizeParent)) {
                $this->Flash->success(__('The inspection standard size parent has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The inspection standard size parent could not be saved. Please, try again.'));
        }
        $products = $this->InspectionStandardSizeParents->Products->find('list', ['limit' => 200]);
        $this->set(compact('inspectionStandardSizeParent', 'products'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Inspection Standard Size Parent id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $inspectionStandardSizeParent = $this->InspectionStandardSizeParents->get($id);
        if ($this->InspectionStandardSizeParents->delete($inspectionStandardSizeParent)) {
            $this->Flash->success(__('The inspection standard size parent has been deleted.'));
        } else {
            $this->Flash->error(__('The inspection standard size parent could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
