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

//以下をapp/config/routes.phpに追加
//Router::extensions(['json', 'xml']);//apiのため追加

class ZzzapisController extends AppController
{

  public function beforeFilter(Event $event){
    parent::beforeFilter($event);

    // 認証なしでアクセスできるアクションの指定
    $this->Auth->allow(["test1","test2"]);
  }

      public function initialize()
    {
     parent::initialize();
    }

    //json出力
    //参考　https://qiita.com/tatamix/items/1758ed25442cc6940411
    public function test1()//http://localhost:5050/Zzzapis/test1/test.json
    {
      
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

      $arrScrew = [
        '-' => '',
        'フルフライト' => 'フルフライト',
        'ミキシング' => 'ミキシング',
        'ダルメージ' => 'ダルメージ'
              ];
      $this->set('arrScrew',$arrScrew);

      //  	 mb_convert_variables('UTF-8','SJIS-win',$arrapitest);//文字コードを変換
 
      $this->set([
        'arrapitest' => $arrapitest,
        'arrScrewMesh' => $arrScrewMesh,
        'arrScrewNumber' => $arrScrewNumber,
        'arrScrew' => $arrScrew,
        '_serialize' => ['arrapitest','arrScrewMesh','arrScrewNumber','arrScrew']
      ]);

 //     $xml = Xml::fromArray(['response' => $arrapitest]);
 //     echo $xml->asXML();

      $data = ['kikuko' => ['age' => 17]];

      $this->viewBuilder()->className('Json');
      $this->set([
        'data' => $data,
        'arrapitest' => $arrapitest,
        'arrScrewMesh' => $arrScrewMesh,
        'arrScrewNumber' => $arrScrewNumber,
        'arrScrew' => $arrScrew,
        '_serialize' => ['data','arrapitest','arrScrewMesh','arrScrewNumber','arrScrew']
    //    '_jsonp' => TRUE // json出力の場合はいらない
      ]);

    }

    //json取得
    public function test2()//http://localhost:5050/Zzzapis/test2/api/test.json
    {
      $url="http://192.168.4.235:8080/Zzzapis/test1/test.json";
      $json=file_get_contents($url);
      $arr=json_decode($json,true);

      echo "<pre>";
      print_r($arr);
      echo "</pre>";
    }
          
}
