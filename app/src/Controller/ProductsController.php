<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\Auth\DefaultPasswordHasher;//パスワードの更新時のハッシュ化

class ProductsController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Customers = TableRegistry::get('Customers');
    }

    public function ichiran()
    {
        $this->paginate = [
            'contain' => ['Customers']
        ];
        $products = $this->paginate($this->Products);

        $this->set(compact('products'));
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Customers']
        ];
        $products = $this->paginate($this->Products);

        $this->set(compact('products'));
    }

    public function view($id = null)
    {
        $product = $this->Products->get($id, [
            'contain' => ['Customers', 'PriceProducts', 'ProductMaterials']
        ]);

        $this->set('product', $product);
    }

    public function addform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Customers = $this->Customers->find()
      ->where(['delete_flag' => 0])->toArray();

      $arrCustomers = array();
      foreach ($Customers as $value) {
        $arrCustomers[] = array($value->id=>$value->name);
      }
      $this->set('arrCustomers', $arrCustomers);

    }

    public function addcomfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();

      $Customers = $this->Customers->find()
      ->where(['id' => $data['customer_id']])->toArray();
      $customer_name = $Customers[0]['name'];
      $this->set('customer_name', $customer_name);
    }

    public function adddo()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();

      $Customers = $this->Customers->find()
      ->where(['id' => $data['customer_id']])->toArray();
      $customer_name = $Customers[0]['name'];
      $this->set('customer_name', $customer_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokuproduct = array();
      $arrtourokuproduct = [
        'product_code' => $data["product_code"],
        'customer_product_code' => $data["customer_product_code"],
        'name' => $data["name"],
        'customer_id' => $data["customer_id"],
        'is_active' => 0,
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokuproduct);
      echo "</pre>";
*/
      //新しいデータを登録
      $Products = $this->Products->patchEntity($this->Products->newEntity(), $arrtourokuproduct);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Products->save($Products)) {

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
        $product = $this->Products->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $product = $this->Products->patchEntity($product, $this->request->getData());
            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }
        $customers = $this->Products->Customers->find('list', ['limit' => 200]);
        $this->set(compact('product', 'customers'));
    }

    public function editform($id = null)
    {
      $product = $this->Products->get($id, [
        'contain' => ['Customers']
      ]);
      $customers = $this->Products->Customers->find('list', ['limit' => 200]);
      $this->set(compact('product', 'customers'));
      $this->set('id', $id);
    }

    public function editconfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();

      $Customers = $this->Customers->find()
      ->where(['id' => $data['customer_id']])->toArray();
      $customer_name = $Customers[0]["name"];
      $this->set('customer_name', $customer_name);
    }

    public function editdo()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Customers = $this->Customers->find()
      ->where(['id' => $data['customer_id']])->toArray();
      $customer_name = $Customers[0]["name"];
      $this->set('customer_name', $customer_name);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdateproduct = array();
      $arrupdateproduct = [
        'product_code' => $data["product_code"],
        'customer_product_code' => $data["customer_product_code"],
        'name' => $data["name"],
        'customer_id' => $data["customer_id"],
        'is_active' => 0,
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrupdateproduct);
      echo "</pre>";
*/
      $Products = $this->Products->patchEntity($this->Products->newEntity(), $arrupdateproduct);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Products->save($Products)) {

         $this->Products->updateAll(
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
        $product = $this->Products->get($id, [
          'contain' => ['Customers', 'PriceProducts', 'ProductMaterials']
        ]);
        $this->set(compact('product'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $product = $this->Products->get($data["id"], [
        'contain' => ['Customers', 'PriceProducts', 'ProductMaterials']
      ]);
      $this->set(compact('product'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeleteproduct = array();
      $arrdeleteproduct = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeleteproduct);
      echo "</pre>";
*/
      $Products = $this->Products->patchEntity($this->Products->newEntity(), $arrdeleteproduct);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Products->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeleteproduct['id']]
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
