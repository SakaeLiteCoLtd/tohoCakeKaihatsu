<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\Auth\DefaultPasswordHasher;//パスワードの更新時のハッシュ化

class ProductMaterialsController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Products = TableRegistry::get('Products');
     $this->Materials = TableRegistry::get('Materials');
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Products', 'Materials']
        ];
        $productMaterials = $this->paginate($this->ProductMaterials);

        $this->set(compact('productMaterials'));
    }

    public function view($id = null)
    {
        $productMaterial = $this->ProductMaterials->get($id, [
            'contain' => ['Products', 'Materials']
        ]);

        $this->set('productMaterial', $productMaterial);
    }

    public function addform()
    {
      $productMaterial = $this->ProductMaterials->newEntity();
      $this->set('productMaterial', $productMaterial);

      $Products = $this->Products->find()
      ->where(['delete_flag' => 0])->order(["product_code"=>"ASC"])->toArray();

      $arrProducts = array();
      foreach ($Products as $value) {
        $arrProducts[] = array($value->id=>$value->product_code);
      }
      $this->set('arrProducts', $arrProducts);

      $Materials = $this->Materials->find()
      ->where(['delete_flag' => 0])->order(["material_code"=>"ASC"])->toArray();

      $arrMaterials = array();
      foreach ($Materials as $value) {
        $arrMaterials[] = array($value->id=>$value->material_code);
      }
      $this->set('arrMaterials', $arrMaterials);
    }

    public function addcomfirm()
    {
      $productMaterial = $this->ProductMaterials->newEntity();
      $this->set('productMaterial', $productMaterial);

      $data = $this->request->getData();

      $Products = $this->Products->find()
      ->where(['id' => $data['product_id']])->toArray();
      $product_name = $Products[0]['product_code']."（".$Products[0]['name']."）";
      $this->set('product_name', $product_name);

      $Materials = $this->Materials->find()
      ->where(['id' => $data['material_id']])->toArray();
      $material_name = $Materials[0]['material_code'];
      $this->set('material_name', $material_name);
    }

    public function adddo()
    {
      $productMaterial = $this->ProductMaterials->newEntity();
      $this->set('productMaterial', $productMaterial);

      $data = $this->request->getData();

      $Products = $this->Products->find()
      ->where(['id' => $data['product_id']])->toArray();
      $product_name = $Products[0]['product_code']."（".$Products[0]['name']."）";
      $this->set('product_name', $product_name);

      $Materials = $this->Materials->find()
      ->where(['id' => $data['material_id']])->toArray();
      $material_name = $Materials[0]['material_code'];
      $this->set('material_name', $material_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokuproductMaterial = array();
      $arrtourokuproductMaterial = [
        'product_id' => $data["product_id"],
        'material_id' => $data["material_id"],
        'is_active' => 0,
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokuproductMaterial);
      echo "</pre>";
*/
      //新しいデータを登録
      $ProductMaterials = $this->ProductMaterials->patchEntity($this->ProductMaterials->newEntity(), $arrtourokuproductMaterial);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->ProductMaterials->save($ProductMaterials)) {

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


    public function add()
    {
        $productMaterial = $this->ProductMaterials->newEntity();
        if ($this->request->is('post')) {
            $productMaterial = $this->ProductMaterials->patchEntity($productMaterial, $this->request->getData());
            if ($this->ProductMaterials->save($productMaterial)) {
                $this->Flash->success(__('The product material has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product material could not be saved. Please, try again.'));
        }
        $products = $this->ProductMaterials->Products->find('list', ['limit' => 200]);
        $materials = $this->ProductMaterials->Materials->find('list', ['limit' => 200]);
        $this->set(compact('productMaterial', 'products', 'materials'));
    }

    public function edit($id = null)
    {
        $productMaterial = $this->ProductMaterials->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productMaterial = $this->ProductMaterials->patchEntity($productMaterial, $this->request->getData());
            if ($this->ProductMaterials->save($productMaterial)) {
                $this->Flash->success(__('The product material has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product material could not be saved. Please, try again.'));
        }
        $products = $this->ProductMaterials->Products->find('list', ['limit' => 200]);
        $materials = $this->ProductMaterials->Materials->find('list', ['limit' => 200]);
        $this->set(compact('productMaterial', 'products', 'materials'));
    }

    public function deleteconfirm($id = null)
    {
        $productMaterial = $this->ProductMaterials->get($id, [
          'contain' => ['Products', 'Materials']
        ]);
        $this->set(compact('productMaterial'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $productMaterial = $this->ProductMaterials->get($data["id"], [
        'contain' => ['Products', 'Materials']
      ]);
      $this->set(compact('productMaterial'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeleteproductMaterial = array();
      $arrdeleteproductMaterial = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeleteproductMaterial);
      echo "</pre>";
*/
      $ProductMaterials = $this->ProductMaterials->patchEntity($this->ProductMaterials->newEntity(), $arrdeleteproductMaterial);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->ProductMaterials->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeleteproductMaterial['id']]
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
