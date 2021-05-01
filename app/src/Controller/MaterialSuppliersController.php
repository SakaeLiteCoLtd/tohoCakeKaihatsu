<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class MaterialSuppliersController extends AppController
{

    public function index()
    {
        $materialSuppliers = $this->paginate($this->MaterialSuppliers);

        $this->set(compact('materialSuppliers'));
    }

    public function view($id = null)
    {
        $materialSupplier = $this->MaterialSuppliers->get($id, [
            'contain' => ['PriceMaterials']
        ]);

        $this->set('materialSupplier', $materialSupplier);
    }

    public function add()
    {
        $materialSupplier = $this->MaterialSuppliers->newEntity();
        if ($this->request->is('post')) {
            $materialSupplier = $this->MaterialSuppliers->patchEntity($materialSupplier, $this->request->getData());
            if ($this->MaterialSuppliers->save($materialSupplier)) {
                $this->Flash->success(__('The material supplier has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The material supplier could not be saved. Please, try again.'));
        }
        $this->set(compact('materialSupplier'));
    }

    public function addform()
    {
      $materialSupplier = $this->MaterialSuppliers->newEntity();
      $this->set('materialSupplier', $materialSupplier);
    }

    public function addcomfirm()
    {
      $materialSupplier = $this->MaterialSuppliers->newEntity();
      $this->set('materialSupplier', $materialSupplier);
    }

    public function adddo()
    {
      $materialSupplier = $this->MaterialSuppliers->newEntity();
      $this->set('materialSupplier', $materialSupplier);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokumaterialSupplier = array();
      $arrtourokumaterialSupplier = [
        'name' => $data["name"],
        'office' => $data["office"],
        'department' => $data["department"],
        'address' => $data["address"],
        'tel' => $data["tel"],
        'fax' => $data["fax"],
        'is_active' => 0,
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokumaterialSupplier);
      echo "</pre>";
*/
      //新しいデータを登録
      $MaterialSuppliers = $this->MaterialSuppliers->patchEntity($this->MaterialSuppliers->newEntity(), $arrtourokumaterialSupplier);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->MaterialSuppliers->save($MaterialSuppliers)) {

          $connection->commit();// コミット5
          $mes = "以下のように登録されました。";
          $this->set('mes',$mes);

        } else {

          $this->Flash->error(__('The data could not be saved. Please, try again.'));
          throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
          $mes = "※登録されませんでした";
          $this->set('mes',$mes);

        }

      } catch (Exception $e) {//トランザクション7
      //ロールバック8
        $connection->rollback();//トランザクション9
      }//トランザクション10

    }

    public function edit($id = null)
    {
        $materialSupplier = $this->MaterialSuppliers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $materialSupplier = $this->MaterialSuppliers->patchEntity($materialSupplier, $this->request->getData());
            if ($this->MaterialSuppliers->save($materialSupplier)) {
                $this->Flash->success(__('The material supplier has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The material supplier could not be saved. Please, try again.'));
        }
        $this->set(compact('materialSupplier'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $materialSupplier = $this->MaterialSuppliers->get($id);
        if ($this->MaterialSuppliers->delete($materialSupplier)) {
            $this->Flash->success(__('The material supplier has been deleted.'));
        } else {
            $this->Flash->error(__('The material supplier could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
