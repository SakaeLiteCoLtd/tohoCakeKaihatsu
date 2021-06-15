<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;//ログインに使用
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\Auth\DefaultPasswordHasher;//パスワードの更新時のハッシュ化

class ZzzcsvsController extends AppController
{

  public function beforeFilter(Event $event){
    parent::beforeFilter($event);

    // 認証なしでアクセスできるアクションの指定
    $this->Auth->allow(["torikomicustomer","torikomiproduct"]);
  }

      public function initialize()
    {
     parent::initialize();
     $this->MaterialTypes = TableRegistry::get('MaterialTypes');
     $this->Materials = TableRegistry::get('Materials');
     $this->MaterialSuppliers = TableRegistry::get('MaterialSuppliers');
     $this->Products = TableRegistry::get('Products');
     $this->Customers = TableRegistry::get('Customers');
    }

    public function torikomicustomer()//http://localhost:5050/Zzzcsvs/torikomicustomer
    {

      $fp = fopen("torikomicsvs/customertest1.csv", "r");//csvファイルはwebrootに入れる
    	$fpcount = fopen("torikomicsvs/customertest1.csv", 'r' );
    	for( $count = 0; fgets( $fpcount ); $count++ );
    	$this->set('count',$count);

    	$arrFp = array();//空の配列を作る
    	$line = fgets($fp);//ファイル$fpの上の１行を取る（１行目はカラム名）
    	for ($k=1; $k<=$count-1; $k++) {//行数分
    		$line = fgets($fp);//ファイル$fpの上の１行を取る（２行目から）
    		$sample = explode(',',$line);//$lineを","毎に配列に入れる

    		$keys=array_keys($sample);
    		$keys[array_search('0',$keys)]='customer_code';//名前の変更
    		$keys[array_search('1',$keys)]='name';
    		$keys[array_search('2',$keys)]='department';
    		$keys[array_search('3',$keys)]='ryakusyou';
    		$keys[array_search('4',$keys)]='furigana';
        $keys[array_search('5',$keys)]='yuubin';
        $keys[array_search('6',$keys)]='address';
        $keys[array_search('7',$keys)]='tel';
        $keys[array_search('8',$keys)]='fax';
    		$sample = array_combine($keys, $sample);

        $sample = array_merge($sample,array('created_at' => date("Y-m-d H:i:s")));
        $sample = array_merge($sample,array('created_staff' => 1));
        $sample = array_merge($sample,array('factory_id' => 1));
        $sample = array_merge($sample,array('is_active' => 0));
        $sample = array_merge($sample,array('delete_flag' => 0));
/*
        unset($sample['office']);
        unset($sample['customercode_local']);
        unset($sample['department']);
*/
    		$arrFp[] = $sample;//配列に追加する
    	}
    	$this->set('arrFp',$arrFp);//$arrFpをctpで使用できるようセット

    	echo "<pre>";
     	print_r($arrFp);
      echo "</pre>";

      $Customers = $this->Customers->patchEntities($this->Customers->newEntity(), $arrFp);
      $this->Customers->saveMany($Customers);

    }

    public function torikomiproduct()//http://localhost:5050/Zzzcsvs/torikomiproduct
    {

      $fp = fopen("torikomicsvs/product.csv", "r");//csvファイルはwebrootに入れる
    	$fpcount = fopen("torikomicsvs/product.csv", 'r' );
    	for( $count = 0; fgets( $fpcount ); $count++ );
    	$this->set('count',$count);

    	$arrFp = array();//空の配列を作る
    	$line = fgets($fp);//ファイル$fpの上の１行を取る（１行目はカラム名）
    	for ($k=1; $k<=$count-1; $k++) {//行数分
    		$line = fgets($fp);//ファイル$fpの上の１行を取る（２行目から）
    		$sample = explode(',',$line);//$lineを","毎に配列に入れる

    		$keys=array_keys($sample);
    		$keys[array_search('0',$keys)]='product_code';//名前の変更
    		$keys[array_search('1',$keys)]='name';
    		$keys[array_search('2',$keys)]='tanni';
    		$keys[array_search('3',$keys)]='sakuin';
    		$keys[array_search('4',$keys)]='customer_id';
    		$sample = array_combine($keys, $sample);

        $sample = array_merge($sample,array('created_at' => date("Y-m-d H:i:s")));
        $sample = array_merge($sample,array('created_staff' => 1));
        $sample = array_merge($sample,array('factory_id' => 1));
        $sample = array_merge($sample,array('is_active' => 0));
        $sample = array_merge($sample,array('delete_flag' => 0));

    		$arrFp[] = $sample;//配列に追加する
    	}
    	$this->set('arrFp',$arrFp);//$arrFpをctpで使用できるようセット

      for($j=0; $j<count($arrFp); $j++){

        $Customers = $this->Customers->find()
        ->where(['customer_code' => (int)$arrFp[$j]["customer_id"]])
        ->toArray();

        if(isset($Customers[0])){
          $arrFp[$j] = array_merge($arrFp[$j],array('customer_id'=>$Customers[0]['id']));
        }else{
          $arrFp[$j] = array_merge($arrFp[$j],array('customer_id'=>2));
        }

      }
/*
    	echo "<pre>";
     	print_r($arrFp);
    	echo "<br>";
*/
      for($j=0; $j<3; $j++){
        $tourokuarr[] = $arrFp[$j];
      }
      echo "<pre>";
     	print_r($tourokuarr);
    	echo "<br>";

      $Products = $this->Products->patchEntities($this->Products->newEntity(), $tourokuarr);
      $this->Products->saveMany($Products);

    }

}
