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
    $this->Auth->allow(["torikomicustomer","torikomiproduct","torikomiMaterialSuppliers","torikomiMaterials"]);
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

    public function torikomimaterials()//http://localhost:5050/Zzzcsvs/torikomimaterials
    {

      $fp = fopen("torikomicsvs/Materials.csv", "r");//csvファイルはwebrootに入れる
    	$fpcount = fopen("torikomicsvs/Materials.csv", 'r' );
    	for( $count = 0; fgets( $fpcount ); $count++ );
    	$this->set('count',$count);

    	$arrFp = array();//空の配列を作る
    	$line = fgets($fp);//ファイル$fpの上の１行を取る（１行目はカラム名）
    	for ($k=1; $k<=$count-1; $k++) {//行数分
    		$line = fgets($fp);//ファイル$fpの上の１行を取る（２行目から）
    		$sample = explode(',',$line);//$lineを","毎に配列に入れる

    		$keys=array_keys($sample);
    		$keys[array_search('0',$keys)]='material_code';//名前の変更
    		$keys[array_search('1',$keys)]='name';
    		$keys[array_search('2',$keys)]='sakuin';
    		$keys[array_search('3',$keys)]='tanni';
    		$keys[array_search('4',$keys)]='tanni_kosu';
        $keys[array_search('5',$keys)]='material_supplier_id';
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

      for($j=0; $j<count($arrFp); $j++){

        $MaterialSuppliers = $this->MaterialSuppliers->find()
        ->where(['material_supplier_code' => (int)$arrFp[$j]["material_supplier_id"]])
        ->toArray();

        if(isset($MaterialSuppliers[0])){
          $arrFp[$j] = array_merge($arrFp[$j],array('material_supplier_id'=>$MaterialSuppliers[0]['id']));
        }else{
          $arrFp[$j] = array_merge($arrFp[$j],array('material_supplier_id'=>1));
        }

      }


      for($j=0; $j<count($arrFp); $j++){

        $tourokuarr = $arrFp[$j];

        $Materials = $this->Materials->patchEntity($this->Materials->newEntity(), $tourokuarr);
        if ($this->Materials->save($Materials)) {
/*
          echo "<pre>";
          print_r("ok");
          print_r($arrFp[$j]);
          echo "</pre>";
*/
        }else{

          echo "<pre>";
          print_r("ng ".$j);
          print_r($arrFp[$j]);
          echo "</pre>";

        }

      }
/*
    	echo "<pre>";
     	print_r($arrFp);
      echo "</pre>";
*/
  //    $Materials = $this->Materials->patchEntities($this->Materials->newEntity(), $arrFp);
  //    $this->Materials->saveMany($Materials);

    }

    public function torikomimaterialsuppliers()//http://localhost:5050/Zzzcsvs/torikomimaterialsuppliers
    {

      $fp = fopen("torikomicsvs/MaterialSuppliers.csv", "r");//csvファイルはwebrootに入れる
    	$fpcount = fopen("torikomicsvs/MaterialSuppliers.csv", 'r' );
    	for( $count = 0; fgets( $fpcount ); $count++ );
    	$this->set('count',$count);

    	$arrFp = array();//空の配列を作る
    	$line = fgets($fp);//ファイル$fpの上の１行を取る（１行目はカラム名）
    	for ($k=1; $k<=$count-1; $k++) {//行数分
    		$line = fgets($fp);//ファイル$fpの上の１行を取る（２行目から）
    		$sample = explode(',',$line);//$lineを","毎に配列に入れる

    		$keys=array_keys($sample);
    		$keys[array_search('0',$keys)]='material_supplier_code';//名前の変更
    		$keys[array_search('1',$keys)]='name';
    		$keys[array_search('2',$keys)]='department';
        $keys[array_search('3',$keys)]='yuubin';
        $keys[array_search('4',$keys)]='address';
        $keys[array_search('5',$keys)]='tel';
        $keys[array_search('6',$keys)]='fax';
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
        if(strpos($sample['name'],'□') === false){
          //'abcd'のなかに'□'が含まれていない場合
          $arrFp[] = $sample;//配列に追加する
        }

      }
    	$this->set('arrFp',$arrFp);//$arrFpをctpで使用できるようセット

    	echo "<pre>";
     	print_r($arrFp);
      echo "</pre>";

      $MaterialSuppliers = $this->MaterialSuppliers->patchEntities($this->MaterialSuppliers->newEntity(), $arrFp);
      $this->MaterialSuppliers->saveMany($MaterialSuppliers);

    }

    public function torikomicustomer()//http://localhost:5050/Zzzcsvs/torikomicustomer
    {

      $fp = fopen("torikomicsvs/customer210909.csv", "r");//csvファイルはwebrootに入れる
    	$fpcount = fopen("torikomicsvs/customer210909.csv", 'r' );
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
        if(strpos($sample['name'],'□') === false){
          //'abcd'のなかに'□'が含まれていない場合
          $arrFp[] = $sample;//配列に追加する
        }
    	}
    	$this->set('arrFp',$arrFp);//$arrFpをctpで使用できるようセット

    	echo "<pre>";
     	print_r($arrFp);
      echo "</pre>";

    //  $Customers = $this->Customers->patchEntities($this->Customers->newEntity(), $arrFp);
    //  $this->Customers->saveMany($Customers);

    }

    public function torikomiproduct()//http://localhost:5050/Zzzcsvs/torikomiproduct
    {

      $fp = fopen("torikomicsvs/products210908ng2.csv", "r");//csvファイルはwebrootに入れる
    	$fpcount = fopen("torikomicsvs/products210908ng2.csv", 'r' );
    	for( $count = 0; fgets( $fpcount ); $count++ );
    	$this->set('count',$count);

      $arrFp = array();//空の配列を作る
    	$line = fgets($fp);//ファイル$fpの上の１行を取る（１行目はカラム名）
    	for ($k=1; $k<=$count-1; $k++) {//行数分
    		$line = fgets($fp);//ファイル$fpの上の１行を取る（２行目から）
    		$sample = explode(',',$line);//$lineを","毎に配列に入れる

    		$keys=array_keys($sample);
    		$keys[array_search('0',$keys)]='status_kensahyou';//名前の変更
    		$keys[array_search('1',$keys)]='product_code';//名前の変更
    		$keys[array_search('2',$keys)]='name';
    		$keys[array_search('3',$keys)]='sakuin';
    		$keys[array_search('4',$keys)]='tanni';
        $keys[array_search('5',$keys)]='length';
        $keys[array_search('6',$keys)]='length_cut';
        $keys[array_search('7',$keys)]='customer_id';
        $keys[array_search('8',$keys)]='dammy';
   //     $keys[array_search('5',$keys)]='customer';//
    		$sample = array_combine($keys, $sample);

        $sample = array_merge($sample,array('created_at' => date("Y-m-d H:i:s")));
        $sample = array_merge($sample,array('created_staff' => 1));
        $sample = array_merge($sample,array('factory_id' => 1));
        $sample = array_merge($sample,array('is_active' => 0));
        $sample = array_merge($sample,array('delete_flag' => 0));

        unset($sample['dammy']);

        $Products = $this->Products->find()
        ->where(['product_code' => $sample["product_code"]])
        ->toArray();

        if(isset($Products[0])){

          echo "<pre>";
          print_r("登録済み　".$sample["product_code"]);
          echo "</pre>";

            }else{

              $arrFp[] = $sample;//配列に追加する

            }

    	}
    	$this->set('arrFp',$arrFp);//$arrFpをctpで使用できるようセット

      $count = 0;

      echo "<pre>";
      print_r($arrFp);
      echo "</pre>";

      $arrFpok = array();//問題なしのデータ
      $arrFpng = array();//顧客コードがないデータ
      for($j=0; $j<count($arrFp); $j++){

        if(strpos($arrFp[$j]["product_code"],'D') !== false){//'D'が含まれている場合大東工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>1));

        }elseif(strpos($arrFp[$j]["product_code"],'I') !== false){//石狩工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>2));

        }elseif(strpos($arrFp[$j]["product_code"],'B') !== false){//美唄工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>3));

        }else{//門真工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>4));

        }

        $Customers = $this->Customers->find()
        ->where(['customer_code' => (int)$arrFp[$j]["customer_id"]])
        ->toArray();

        if(isset($Customers[0])){
          $arrFp[$j] = array_merge($arrFp[$j],array('customer_id'=>$Customers[0]['id']));

          $Products = $this->Products->patchEntity($this->Products->newEntity(), $arrFp[$j]);
          $this->Products->save($Products);
          if ($this->Products->save($Products)) {

            $arrFpok[] = $arrFp[$j];

          }else{//品名にカンマが含まれる、セル内で改行されている

            $Customers = $this->Customers->find()
            ->where(['id' => (int)$arrFp[$j]["customer_id"]])
            ->toArray();

            $arrFp[$j] = array_merge($arrFp[$j],array('customer_id'=>$Customers[0]['customer_code']));

            unset($arrFp[$j]['created_at']);
            unset($arrFp[$j]['created_staff']);
            unset($arrFp[$j]['factory_id']);
            unset($arrFp[$j]['is_active']);
            unset($arrFp[$j]['delete_flag']);
  
            $arrFpng[] = $arrFp[$j];

            }

          $count = $count + 1;

        }else{

          unset($arrFp[$j]['created_at']);
          unset($arrFp[$j]['created_staff']);
          unset($arrFp[$j]['factory_id']);
          unset($arrFp[$j]['is_active']);
          unset($arrFp[$j]['delete_flag']);

          $arrFpng[] = $arrFp[$j];
        }

      }

  //    $arrFp = array_values($arrFp);
  
      echo "<pre>";
      print_r("ok ".count($arrFpok));
      echo "</pre>";
      /*
      echo "<pre>";
      print_r($arrFpok);
      echo "</pre>";
       */
      echo "<pre>";
      print_r("ng ".count($arrFpng));
      echo "</pre>";
      
      echo "<pre>";
      print_r($arrFpng);
      echo "</pre>";

      $fp = fopen('torikomicsvs/製品データng210908.csv', 'w');
        foreach ($arrFpng as $line) {
    //      $line = mb_convert_encoding($line, 'SJIS-win', 'UTF-8');//UTF-8の文字列をSJIS-winに変更する※文字列に使用、ファイルごとはできない
          fputcsv($fp, $line);
        }
        fclose($fp);

   //     $Products = $this->Products->patchEntities($this->Products->newEntity(), $arrFpok);
   //     $this->Products->saveMany($Products);

    }



}
