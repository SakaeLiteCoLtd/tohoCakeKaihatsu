<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション

class PriceMaterialsController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Materials = TableRegistry::get('Materials');
     $this->MaterialSuppliers = TableRegistry::get('MaterialSuppliers');
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Materials', 'MaterialSuppliers']
        ];
        $priceMaterials = $this->paginate($this->PriceMaterials);

        $this->set(compact('priceMaterials'));
    }

    public function view($id = null)
    {
        $priceMaterial = $this->PriceMaterials->get($id, [
            'contain' => ['Materials', 'MaterialSuppliers']
        ]);

        $this->set('priceMaterial', $priceMaterial);
    }

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

    public function addform()
    {
      $priceMaterial = $this->PriceMaterials->newEntity();
      $this->set('priceMaterial', $priceMaterial);

      $Materials = $this->Materials->find()
      ->where(['delete_flag' => 0])->toArray();

      $arrMaterials = array();
      foreach ($Materials as $value) {
        $arrMaterials[] = array($value->id=>$value->material_code);
      }
      $this->set('arrMaterials', $arrMaterials);

      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['delete_flag' => 0])->toArray();

      $arrMaterialSuppliers = array();
      foreach ($MaterialSuppliers as $value) {
        $arrMaterialSuppliers[] = array($value->id=>$value->name);
      }
      $this->set('arrMaterialSuppliers', $arrMaterialSuppliers);

    }

    public function addcomfirm()
    {
      $priceMaterial = $this->PriceMaterials->newEntity();
      $this->set('priceMaterial', $priceMaterial);

      $data = $this->request->getData();

      $Materials = $this->Materials->find()
      ->where(['id' => $data['material_id']])->toArray();
      $material_code = $Materials[0]['material_code'];
      $this->set('material_code', $material_code);

      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['id' => $data['material_supplier_id']])->toArray();
      $MaterialSupplier_name = $MaterialSuppliers[0]['name'];
      $this->set('MaterialSupplier_name', $MaterialSupplier_name);

      if($data['start_deal']['year'] > 0){
        $start_deal = $data['start_deal']['year']."-".$data['start_deal']['month']."-".$data['start_deal']['day'];
      }else{
        $start_deal = "";
      }
      $this->set('start_deal', $start_deal);

      if($data['finish_deal']['year'] > 0){
        $finish_deal = $data['finish_deal']['year']."-".$data['finish_deal']['month']."-".$data['finish_deal']['day'];
      }else{
        $finish_deal = "";
      }
      $this->set('finish_deal', $finish_deal);
    }

    public function adddo()
    {
      $priceMaterial = $this->PriceMaterials->newEntity();
      $this->set('priceMaterial', $priceMaterial);

      $data = $this->request->getData();

      $Materials = $this->Materials->find()
      ->where(['id' => $data['material_id']])->toArray();
      $material_code = $Materials[0]['material_code'];
      $this->set('material_code', $material_code);

      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['id' => $data['material_supplier_id']])->toArray();
      $MaterialSupplier_name = $MaterialSuppliers[0]['name'];
      $this->set('MaterialSupplier_name', $MaterialSupplier_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokupriceMaterial = array();
      $arrtourokupriceMaterial = [
        'material_id' => $data["material_id"],
        'material_supplier_id' => $data["material_supplier_id"],
        'price' => $data["price"],
        'start_deal' => $data["start_deal"],
        'finish_deal' => $data["finish_deal"],
        'lot_remarks' => $data["lot_remarks"],
        'is_active' => 0,
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokupriceMaterial);
      echo "</pre>";
*/
      //新しいデータを登録
      $PriceMaterials = $this->PriceMaterials->patchEntity($this->PriceMaterials->newEntity(), $arrtourokupriceMaterial);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->PriceMaterials->save($PriceMaterials)) {

          $connection->commit();// コミット5
          $mes = "以下のように登録されました。";
          $this->set('mes',$mes);

        } else {

          $mes = "※登録されませんでした";
          $this->set('mes',$mes);
          $this->Flash->error(__('The data could not be saved. Please, try again.'));
          throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

        }

      } catch (Exception $e) {//トランザクション7
      //ロールバック8
         $connection->rollback();//トランザクション9
      }//トランザクション10

    }

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
