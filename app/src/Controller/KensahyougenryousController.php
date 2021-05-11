<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Event\Event;

class KensahyougenryousController extends AppController
{

  	public function beforeFilter(Event $event){
  		parent::beforeFilter($event);

  		// 認証なしでアクセスできるアクションの指定
  		$this->Auth->allow(["addformpre","addform","addcomfirm"]);
  	}

      public function initialize()
    {
     parent::initialize();
     $this->Companies = TableRegistry::get('Companies');
     $this->Users = TableRegistry::get('Users');
     $this->Menus = TableRegistry::get('Menus');
     $this->Groups = TableRegistry::get('Groups');
     $this->Products = TableRegistry::get('Products');
     $this->Customers = TableRegistry::get('Customers');
    }

    public function addformpre()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);
    }

    public function addform()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);

      $Products= $this->Products->find()->contain(["Customers"])->where(['product_code' => $product_code])->toArray();

      if(isset($Products[0])){

        $name = $Products[0]["name"];
        $this->set('name', $name);

      }else{

        return $this->redirect(['action' => 'addformpre',
        's' => ['mess' => "製品「".$product_code."」は存在しません。"]]);

      }

      if(isset($data["genryoutuika"])){//原料追加ボタン

        if(!isset($data["tuikaseikeiki"])){//成形機の追加前

          $tuikaseikeiki = 1;

        }else{//成形機の追加後

          $tuikaseikeiki = $data["tuikaseikeiki"];

        }
        $this->set('tuikaseikeiki', $tuikaseikeiki);

        ${"tuikagenryou".$tuikaseikeiki} = $data["tuikagenryou".$tuikaseikeiki] + 1;

        for($j=1; $j<=$tuikaseikeiki; $j++){

          if($j < $tuikaseikeiki){

            ${"tuikagenryou".$j} = $data["tuikagenryou".$j];
          }

          $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            if(isset($data["product_code".$j.$i])){
              ${"product_code".$j.$i} = $data["product_code".$j.$i];
              $this->set('product_code'.$j.$i,${"product_code".$j.$i});
            }else{
              ${"product_code".$j.$i} = "";
              $this->set('product_code'.$j.$i,${"product_code".$j.$i});
            }

          }

        }

      }elseif(isset($data["seikeikituika"])){//成形機追加ボタン

        $tuikaseikeiki = $data["tuikaseikeiki"] + 1;
        $this->set('tuikaseikeiki', $tuikaseikeiki);

        for($j=1; $j<=$tuikaseikeiki; $j++){

          if(isset($data['tuikagenryou'.$j])){
            ${"tuikagenryou".$j} = $data['tuikagenryou'.$j];
          }else{
            ${"tuikagenryou".$j} = 1;
          }

          $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

          for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            if(isset($data["product_code".$j.$i])){
              ${"product_code".$j.$i} = $data["product_code".$j.$i];
              $this->set('product_code'.$j.$i,${"product_code".$j.$i});
            }else{
              ${"product_code".$j.$i} = "";
              $this->set('product_code'.$j.$i,${"product_code".$j.$i});
            }

          }

        }

      }elseif(isset($data["kakuninn"])){//確認ボタン

        return $this->redirect(['action' => 'addcomfirm',
        's' => ['data' => "aaa"]]);

      }else{//最初にこの画面に来た時

        $i = $j = 1;
        $tuikagenryou = 1;
        $this->set('tuikagenryou'.$i, $tuikagenryou);
        $tuikaseikeiki = 1;
        $this->set('tuikaseikeiki', $tuikaseikeiki);
        ${"product_code".$j.$i} = "";
        $this->set('product_code'.$j.$i,${"product_code".$j.$i});

      }

    }

    public function addcomfirm()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $Data=$this->request->query('s');
      $this->set('Data',$Data);

      echo "<pre>";
      print_r($Data);
      echo "</pre>";

    }

    public function adddo()
    {
      $company = $this->Companies->newEntity();
      $this->set('company', $company);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokucompany = array();
      $arrtourokucompany = [
        'name' => $data["name"],
        'address' => $data["address"],
        'tel' => $data["tel"],
        'fax' => $data["fax"],
        'president' => $data["president"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokucompany);
      echo "</pre>";
*/
      //新しいデータを登録
      $Companies = $this->Companies->patchEntity($this->Companies->newEntity(), $arrtourokucompany);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Companies->save($Companies)) {

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

    public function editform($id = null)
    {
        $company = $this->Companies->get($id, [
            'contain' => []
        ]);
        $this->set(compact('company'));
        $this->set('id', $id);
    }

    public function editconfirm()
    {
      $company = $this->Companies->newEntity();
      $this->set('company', $company);
    }

    public function editdo()
    {
      $company = $this->Companies->newEntity();
      $this->set('company', $company);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdatecompany = array();
      $arrupdatecompany = [
        'name' => $data["name"],
        'address' => $data["address"],
        'tel' => $data["tel"],
        'fax' => $data["fax"],
        'president' => $data["president"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrupdatecompany);
      echo "</pre>";
*/
      $Companies = $this->Companies->patchEntity($this->Companies->newEntity(), $arrupdatecompany);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Companies->save($Companies)) {

         $this->Companies->updateAll(
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
        $company = $this->Companies->get($id, [
            'contain' => []
        ]);
        $this->set(compact('company'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $company = $this->Companies->get($data["id"], [
          'contain' => []
      ]);
      $this->set(compact('company'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeletecompany = array();
      $arrdeletecompany = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletecompany);
      echo "</pre>";
*/
      $Companies = $this->Companies->patchEntity($this->Companies->newEntity(), $arrdeletecompany);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Companies->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeletecompany['id']]
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
