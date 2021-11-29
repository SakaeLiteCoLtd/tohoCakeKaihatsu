<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * DailyReports Controller
 *
 * @property \App\Model\Table\DailyReportsTable $DailyReports
 *
 * @method \App\Model\Entity\DailyReport[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DailyReportsController extends AppController
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
        $dailyReports = $this->paginate($this->DailyReports);

        $this->set(compact('dailyReports'));
    }

    /**
     * View method
     *
     * @param string|null $id Daily Report id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dailyReport = $this->DailyReports->get($id, [
            'contain' => ['Products', 'InspectionDataResultParents']
        ]);

        $this->set('dailyReport', $dailyReport);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $dailyReport = $this->DailyReports->newEntity();
        if ($this->request->is('post')) {
            $dailyReport = $this->DailyReports->patchEntity($dailyReport, $this->request->getData());
            if ($this->DailyReports->save($dailyReport)) {
                $this->Flash->success(__('The daily report has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The daily report could not be saved. Please, try again.'));
        }
        $products = $this->DailyReports->Products->find('list', ['limit' => 200]);
        $this->set(compact('dailyReport', 'products'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Daily Report id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $dailyReport = $this->DailyReports->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dailyReport = $this->DailyReports->patchEntity($dailyReport, $this->request->getData());
            if ($this->DailyReports->save($dailyReport)) {
                $this->Flash->success(__('The daily report has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The daily report could not be saved. Please, try again.'));
        }
        $products = $this->DailyReports->Products->find('list', ['limit' => 200]);
        $this->set(compact('dailyReport', 'products'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Daily Report id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dailyReport = $this->DailyReports->get($id);
        if ($this->DailyReports->delete($dailyReport)) {
            $this->Flash->success(__('The daily report has been deleted.'));
        } else {
            $this->Flash->error(__('The daily report could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
