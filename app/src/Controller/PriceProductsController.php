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
     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

       return $this->redirect($this->Auth->logout());

     }

     if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)
/*
       $Groups = $this->Groups->find()->contain(["Menus"])
       ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "単価関係", 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }
*/
     }

    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Products', 'Customers']
        ];
        $priceProducts = $this->paginate($this->PriceProducts);

        $this->set(compact('priceProducts'));
    }

    public function ichiran()
    {
        $this->paginate = [
            'contain' => ['Products', 'Customers']
        ];
        $priceProducts = $this->paginate($this->PriceProducts->find()->where(['PriceProducts.delete_flag' => 0]));

        $this->set(compact('priceProducts'));
    }

    public function detail($id = null)
    {
      $priceProduct = $this->PriceProducts->newEntity();
      $this->set('priceProduct', $priceProduct);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['priceProductdata'] = array();
        $_SESSION['priceProductdata'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['priceProductdata'] = array();
        $_SESSION['priceProductdata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }
      $this->set('id', $id);

      $PriceProducts = $this->PriceProducts->find()->contain(["Products", "Customers"])
      ->where(['PriceProducts.id' => $id])->toArray();
/*
      echo "<pre>";
      print_r($PriceProducts);
      echo "</pre>";
*/
      $product_code = $PriceProducts[0]["product"]['product_code'];
      $this->set('product_code', $product_code);
      $customer_name = $PriceProducts[0]["customer"]['name'];
      $this->set('customer_name', $customer_name);
      $price = $PriceProducts[0]["price"];
      $this->set('price', $price);
      $start_deal = $PriceProducts[0]["start_deal"];
      $this->set('start_deal', $start_deal);
      $finish_deal = $PriceProducts[0]["finish_deal"];
      $this->set('finish_deal', $finish_deal);

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

    public function editform($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['priceProductdata'];

      $priceProduct = $this->PriceProducts->get($id, [
        'contain' => ["Products", "Customers"]
      ]);
      $this->set(compact('priceProduct'));
      $this->set('id', $id);

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

    public function editconfirm()
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

    public function editdo()
    {
      $priceProduct = $this->PriceProducts->newEntity();
      $this->set('priceProduct', $priceProduct);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Products = $this->Products->find()
      ->where(['id' => $data['product_id']])->toArray();
      $product_code = $Products[0]['product_code'];
      $this->set('product_code', $product_code);

      $Customers = $this->Customers->find()
      ->where(['id' => $data['custmoer_id']])->toArray();
      $customer_name = $Customers[0]['name'];
      $this->set('customer_name', $customer_name);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdatepriceProduct = array();
      $arrupdatepriceProduct = [
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
      print_r($arrupdatepriceProduct);
      echo "</pre>";
*/
      $PriceProducts = $this->PriceProducts->patchEntity($this->PriceProducts->newEntity(), $arrupdatepriceProduct);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->PriceProducts->save($PriceProducts)) {

         $this->PriceProducts->updateAll(
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

      $id = $_SESSION['priceProductdata'];

      $priceProduct = $this->PriceProducts->get($id, [
        'contain' => ["Products", "Customers"]
      ]);
      $this->set(compact('priceProduct'));
      $this->set('id', $id);
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $priceProduct = $this->PriceProducts->get($data["id"], [
        'contain' => ["Products", "Customers"]
      ]);
      $this->set(compact('priceProduct'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeletepriceProduct = array();
      $arrdeletepriceProduct = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeleteproduct);
      echo "</pre>";
*/
      $PriceProducts = $this->PriceProducts->patchEntity($this->PriceProducts->newEntity(), $arrdeletepriceProduct);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->PriceProducts->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeletepriceProduct['id']]
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
