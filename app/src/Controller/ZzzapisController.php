<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;//ログインに使用
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\Utility\Xml;//xmlのファイルを読み込みために必要
use Cake\Routing\Router;//urlの取得
use Cake\Http\Client;//httpの読取に必要

//以下をapp/config/routes.phpに追加//AppcontrollerはそのままでOK
//Router::extensions(['json', 'xml']);//apiのため追加

class ZzzapisController extends AppController
{

  public function beforeFilter(Event $event){
    parent::beforeFilter($event);

    // 認証なしでアクセスできるアクションの指定
    $this->Auth->allow(["nagasacheck","test1","test2","testadd","testedit"]);
  }

      public function initialize()
    {
     parent::initialize();
     $this->MaterialTypes = TableRegistry::get('MaterialTypes');
     $this->Products = TableRegistry::get('Products');
    }

    public function nagasacheck()//http://localhost:5050/Zzzapis/nagasacheck
    {

      //productsテーブルに、同じ製品で同じ長さで検査表表示になっているものが存在しないか確認するためのプログラム
      $Productsall = $this->Products->find()
      ->where(['delete_flag' => 0])
      ->order(["product_code"=>"ASC"])->toArray();
/*
      echo "<pre>";
      print_r(count($Productsall));
      echo "</pre>";
*/
      $arrProducts = array();
      for($j=0; $j<count($Productsall); $j++){

        if($Productsall[$j]["status_kensahyou"] == 0 && $Productsall[$j]["length"] > 0){

          $product_code_ini = substr($Productsall[$j]["product_code"], 0, 11);

          $arrProducts[] = [
            "name" => $Productsall[$j]["name"],
            "product_code_ini" => $product_code_ini,
            "status_kensahyou" => $Productsall[$j]["status_kensahyou"],
            "length" => $Productsall[$j]["length"],
            "check" => $product_code_ini.";".$Productsall[$j]["status_kensahyou"].";".$Productsall[$j]["length"],
          ];

     //     array_push($arrProducts,$product_code_ini.";".$Productsall[$j]["status_kensahyou"].";".$Productsall[$j]["length"]);

        }

      }
/*
      echo "<pre>";
      print_r(count($arrProducts));
      echo "</pre>";
*/
  //    $arrProducts = array_unique($arrProducts);
  //    $arrProducts = array_values($arrProducts);
/*
      echo "<pre>";
      print_r(count($arrProducts));
      echo "</pre>";
*/

             //CSV形式で情報をファイルに出力のための準備
             $csvFileName = '/tmp/' . time() . rand() . '.csv';
             $res = fopen($csvFileName, 'w');
             if ($res === FALSE) {
               throw new Exception('ファイルの書き込みに失敗しました。');
             }
   
             foreach($arrProducts as $dataInfo) {
               // 文字コード変換。エクセルで開けるようにする
               mb_convert_variables('SJIS', 'UTF-8', $dataInfo);
               fputcsv($res, $dataInfo);
             }
             fclose($res);
   
             header('Content-Type: application/octet-stream');
   
             $filename = "製品長さチェック220329.csv";
             header("Content-Disposition: attachment; filename=${filename}"); 
             header('Content-Transfer-Encoding: binary');
             header('Content-Length: ' . filesize($csvFileName));
             readfile($csvFileName);
   
             exit;//exitをいれておかないとhtmlのソースを含んだCSVファイルになってしまう
   
    }

    //json出力
    //参考　https://qiita.com/tatamix/items/1758ed25442cc6940411
    public function test1()//http://localhost:5050/Zzzapis/test1/test.json
    {
      
      $data = ['kikuko' => ['age' => 17]];

      $arrapitest = [
        'aaa' => date('Y-m-d H:i:s'),
        'bbb' => 'abcd',
        'ccc' => 'あいうえお',
        'ddd' => 'テストです',
      ];

      $arrScrewMesh = [
        '-' => '',
        '#30' => '#30',
        '#40' => '#40',
        '#60' => '#60',
        '#80' => '#80',
        '#100' => '#100'
              ];
      $this->set('arrScrewMesh',$arrScrewMesh);

      $arrScrewNumber = [
        '-' => '',
        '0枚(無し)' => '0枚(無し)',
        '1枚' => '1枚',
        '2枚' => '2枚',
        '3枚' => '3枚'
              ];
      $this->set('arrScrewNumber',$arrScrewNumber);

      $this->viewBuilder()->className('Json');
 //     $this->viewBuilder()->className('Xml');//使えない
      $this->set([
        'data' => $data,
        'arrapitest' => $arrapitest,
        'arrScrewMesh' => $arrScrewMesh,
        'arrScrewNumber' => $arrScrewNumber,
        '_serialize' => ['data','arrapitest','arrScrewMesh','arrScrewNumber']
      ]);

    }

    public function testadd()//http://localhost:5050/Zzzapis/testadd/test.json
    {

      $arrMaterialTypes = [
        'factory_id' => 1,
        'type' => '登録テスト',
        'delete_flag' => 0,
        'created_at' => date('Y-m-d H:i:s'),
        'created_staff' => 1111
      ];
      $this->set('arrMaterialTypes',$arrMaterialTypes);

      $this->viewBuilder()->className('Json');
      $this->set([
        'arrMaterialTypes' => $arrMaterialTypes,
        '_serialize' => ['arrMaterialTypes']
      ]);

    }

    //json取得
    //参考　https://qiita.com/yukou29good0910/items/79a1ac589465ec895f95
    public function test2()//http://localhost:5050/Zzzapis/test2/api/test.json
    {
    //  $url="http://localhost:5050/Zzzapis/test1/test.json";
      $url="http://192.168.4.235:8080/Zzzapis/testadd/test.json";
      $json=file_get_contents($url);
      $arr=json_decode($json,true);
/*
      echo "<pre>";
      print_r($arr["arrMaterialTypes"]);
      echo "</pre>";
*/
      $MaterialTypes = $this->MaterialTypes->newEntity($arr["arrMaterialTypes"]);
      if ($this->MaterialTypes->save($MaterialTypes)) {
          $message = 'Saved';
      } else {
          $message = 'Error';
      }

      $this->viewBuilder()->className('Json');
      $this->set([
          'message' => $message,
          'MaterialTypes' => $MaterialTypes,
          '_serialize' => ['message', 'MaterialTypes']
      ]);

    }
   
    public function testedit()//http://localhost:5050/Zzzapis/testedit/api/test.json
    {
    //  $url="http://localhost:5050/Zzzapis/test1/test.json";
      $url="http://192.168.4.235:8080/Zzzapis/testadd/test.json";
      $json=file_get_contents($url);
      $arr=json_decode($json,true);
/*
      echo "<pre>";
      print_r($arr["arrMaterialTypes"]);
      echo "</pre>";
*/
      $MaterialTypes = $this->MaterialTypes->newEntity($arr["arrMaterialTypes"]);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4

        $this->MaterialTypes->updateAll(
          [ 'delete_flag' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_staff' => 9999],
          ['type'  => $arr["arrMaterialTypes"]['type']]);

         if ($this->MaterialTypes->save($MaterialTypes)) {

          $message = 'Saved';
          $connection->commit();// コミット5

       } else {

        $message = 'Error';
        $this->Flash->error(__('The data could not be saved. Please, try again.'));
        throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

       }

     } catch (Exception $e) {//トランザクション7
     //ロールバック8
       $connection->rollback();//トランザクション9
     }//トランザクション10

      $this->viewBuilder()->className('Json');
      $this->set([
          'message' => $message,
          'MaterialTypes' => $MaterialTypes,
          '_serialize' => ['message', 'MaterialTypes']
      ]);

    }

}
