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
     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

       return $this->redirect($this->Auth->logout());

     }

    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Materials', 'MaterialSuppliers']
        ];
        $priceMaterials = $this->paginate($this->PriceMaterials);

        $this->set(compact('priceMaterials'));
    }

    public function detail($id = null)
    {
      $priceMaterials = $this->PriceMaterials->newEntity();
      $this->set('priceMaterials', $priceMaterials);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['priceMaterialdata'] = array();
        $_SESSION['priceMaterialdata'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['priceMaterialdata'] = array();
        $_SESSION['priceMaterialdata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }
      $this->set('id', $id);

      $PriceMaterials = $this->PriceMaterials->find()->contain(["Materials", "MaterialSuppliers"])
      ->where(['PriceMaterials.id' => $id])->toArray();

      $material_code = $PriceMaterials[0]["material"]['material_code'];
      $this->set('material_code', $material_code);
      $materialSupplier = $PriceMaterials[0]["material_supplier"]['name'];
      $this->set('materialSupplier', $materialSupplier);
      $price = $PriceMaterials[0]["price"];
      $this->set('price', $price);
      $lot_remarks = $PriceMaterials[0]["lot_remarks"];
      $this->set('lot_remarks', $lot_remarks);
      $start_deal = $PriceMaterials[0]["start_deal"];
      $this->set('start_deal', $start_deal);
      $finish_deal = $PriceMaterials[0]["finish_deal"];
      $this->set('finish_deal', $finish_deal);

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

    public function editform($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['priceMaterialdata'];

      $priceMaterial = $this->PriceMaterials->get($id, [
        'contain' => ['Materials', 'MaterialSuppliers']
      ]);
      $this->set(compact('priceMaterial'));
      $this->set('id', $id);

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

    public function editconfirm()
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

    public function editdo()
    {
      $priceMaterial = $this->PriceMaterials->newEntity();
      $this->set('priceMaterial', $priceMaterial);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Materials = $this->Materials->find()
      ->where(['id' => $data['material_id']])->toArray();
      $material_code = $Materials[0]['material_code'];
      $this->set('material_code', $material_code);

      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['id' => $data['material_supplier_id']])->toArray();
      $MaterialSupplier_name = $MaterialSuppliers[0]['name'];
      $this->set('MaterialSupplier_name', $MaterialSupplier_name);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdatepriceMaterial = array();
      $arrupdatepriceMaterial = [
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

      $PriceMaterials = $this->PriceMaterials->patchEntity($this->PriceMaterials->newEntity(), $arrupdatepriceMaterial);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->PriceMaterials->save($PriceMaterials)) {

         $this->PriceMaterials->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $data['id']]);

         $mes = "※下記のように更新されました";
         $this->set('mes',$mes);
         $connection->commit();// コミット5

       } else {

         $mes = "※更新されませんでした";
         $this->set('mes',$mes);
         $this->Flash->error(__('The data could not be saved. Please, try again.'));
         throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

       }

     } catch (Exception $e) {//トランザクション7
     //ロールバック8
       $connection->rollback();//トランザクション9
     }//トランザクション10

    }

    public function deleteconfirm($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['priceMaterialdata'];

      $priceMaterial = $this->PriceMaterials->get($id, [
        'contain' => ['Materials', 'MaterialSuppliers']
      ]);
      $this->set(compact('priceMaterial'));
      $this->set('id', $id);
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $priceMaterial = $this->PriceMaterials->get($data["id"], [
        'contain' => ['Materials', 'MaterialSuppliers']
      ]);
      $this->set(compact('priceMaterial'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeletepriceMaterial = array();
      $arrdeletepriceMaterial = [
        'id' => $data["id"]
      ];

      $PriceMaterials = $this->PriceMaterials->patchEntity($this->PriceMaterials->newEntity(), $arrdeletepriceMaterial);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->PriceMaterials->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeletepriceMaterial['id']]
         )){

         $mes = "※以下のデータが削除されました。";
         $this->set('mes',$mes);
         $connection->commit();// コミット5

       } else {

         $mes = "※削除されませんでした";
         $this->set('mes',$mes);
         $this->Flash->error(__('The data could not be saved. Please, try again.'));
         throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

       }

     } catch (Exception $e) {//トランザクション7
     //ロールバック8
       $connection->rollback();//トランザクション9
     }//トランザクション10

    }

}
