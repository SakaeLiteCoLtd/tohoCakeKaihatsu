<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション

class PriceProductsController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Products = TableRegistry::get('Products');
     $this->Customers = TableRegistry::get('Customers');
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Products', 'Customers']
        ];
        $priceProducts = $this->paginate($this->PriceProducts);

        $this->set(compact('priceProducts'));
    }

    public function view($id = null)
    {
        $priceProduct = $this->PriceProducts->get($id, [
            'contain' => ['Products', 'Customers']
        ]);

        $this->set('priceProduct', $priceProduct);
    }

    public function add()
    {
        $priceProduct = $this->PriceProducts->newEntity();
        if ($this->request->is('post')) {
            $priceProduct = $this->PriceProducts->patchEntity($priceProduct, $this->request->getData());
            if ($this->PriceProducts->save($priceProduct)) {
                $this->Flash->success(__('The price product has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The price product could not be saved. Please, try again.'));
        }
        $products = $this->PriceProducts->Products->find('list', ['limit' => 200]);
        $customers = $this->PriceProducts->Customers->find('list', ['limit' => 200]);
        $this->set(compact('priceProduct', 'products', 'customers'));
    }

    public function addform()
    {
      $priceProduct = $this->PriceProducts->newEntity();
      $this->set('priceProduct', $priceProduct);

      $Products = $this->Products->find()
      ->where(['delete_flag' => 0])->toArray();

      $arrProducts = array();
      foreach ($Products as $value) {
        $arrProducts[] = array($value->id=>$value->product_code);
      }
      $this->set('arrProducts', $arrProducts);

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
      $priceProduct = $this->PriceProducts->newEntity();
      $this->set('priceProduct', $priceProduct);

      $data = $this->request->getData();

      $Products = $this->Products->find()
      ->where(['id' => $data['product_id']])->toArray();
      $product_code = $Products[0]['product_code'];
      $this->set('product_code', $product_code);

      $Customers = $this->Customers->find()
      ->where(['id' => $data['custmoer_id']])->toArray();
      $customer_name = $Customers[0]['name'];
      $this->set('customer_name', $customer_name);

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
      $priceProduct = $this->PriceProducts->newEntity();
      $this->set('priceProduct', $priceProduct);

      $data = $this->request->getData();

      $Products = $this->Products->find()
      ->where(['id' => $data['product_id']])->toArray();
      $product_code = $Products[0]['product_code'];
      $this->set('product_code', $product_code);

      $Customers = $this->Customers->find()
      ->where(['id' => $data['custmoer_id']])->toArray();
      $customer_name = $Customers[0]['name'];
      $this->set('customer_name', $customer_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokupriceProduct = array();
      $arrtourokupriceProduct = [
        'product_id' => $data["product_id"],
        'custmoer_id' => $data["custmoer_id"],
        'price' => $data["price"],
        'start_deal' => $data["start_deal"],
        'finish_deal' => $data["finish_deal"],
        'is_active' => 0,
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokupriceProduct);
      echo "</pre>";
*/
      //新しいデータを登録
      $PriceProducts = $this->PriceProducts->patchEntity($this->PriceProducts->newEntity(), $arrtourokupriceProduct);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->PriceProducts->save($PriceProducts)) {

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
        $priceProduct = $this->PriceProducts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $priceProduct = $this->PriceProducts->patchEntity($priceProduct, $this->request->getData());
            if ($this->PriceProducts->save($priceProduct)) {
                $this->Flash->success(__('The price product has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The price product could not be saved. Please, try again.'));
        }
        $products = $this->PriceProducts->Products->find('list', ['limit' => 200]);
        $customers = $this->PriceProducts->Customers->find('list', ['limit' => 200]);
        $this->set(compact('priceProduct', 'products', 'customers'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $priceProduct = $this->PriceProducts->get($id);
        if ($this->PriceProducts->delete($priceProduct)) {
            $this->Flash->success(__('The price product has been deleted.'));
        } else {
            $this->Flash->error(__('The price product could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
