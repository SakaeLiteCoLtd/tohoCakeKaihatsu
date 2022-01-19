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
    $this->Auth->allow(["jidoutest","torikomicustomer","tourokusuppliers","tourokucustomers",
    "torikomiproduct","torikomimaterialsuppliers","torikomiMaterials","torikomimaterialtypes",
    "torikomimaterialnamecheck"]);
  }

  public function initialize()
  {
   parent::initialize();
   $this->MaterialTypes = TableRegistry::get('MaterialTypes');
   $this->Materials = TableRegistry::get('Materials');
   $this->MaterialSuppliers = TableRegistry::get('MaterialSuppliers');
   $this->Products = TableRegistry::get('Products');
   $this->Customers = TableRegistry::get('Customers');
   $this->Factories = TableRegistry::get('Factories');
  }

      public function jidoutest()//http://localhost:5050/Zzzcsvs/jidoutest
    {
      /*
      //自動入力のテスト
      for($i=1; $i<=10; $i++){
        if($i < 4){
          ${"result_size".$i} = 8.5;
        }else{
          ${"result_size".$i} = "";
        }
        $this->set('result_size'.$i,${"result_size".$i});
      }
      $num_length = 3;
      $size_length = 4.1;
      $upper_length = 4.2;
      $lower_length = 4.3;
      $this->set('num_length',$num_length);
      $this->set('size_length1',$size_length);
      $this->set('upper_length',$upper_length);
      $this->set('lower_length',$lower_length);

      $arrLength_size0 = array();
      $arrLength_size0 = [
        "length" => 10,
        "size" => 10.1,
        "upper" => 10.2,
        "lower" => 10.3
      ];
      $this->set('arrLength_size0',$arrLength_size0);

      $arrLength_size1 = array();
      $arrLength_size1 = [
        "length" => 20,
        "size" => 20.1,
        "upper" => 20.2,
        "lower" => 20.3
      ];
      $this->set('arrLength_size1',$arrLength_size1);
      $count_length = 2;
      $this->set('count_length',$count_length);

      $Factories = $this->Factories->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrFactories = array();
      foreach ($Factories as $value) {
        $arrFactories[] = array($value->id=>$value->name);
      }
      $this->set('arrFactories', $arrFactories);

      $this->set('countFactories', count($Factories));
      for($i=0; $i<count($Factories); $i++){
        $this->set('factory_id'.$i, $Factories[$i]["id"]);
      }
*/
      $Product_name_list = $this->Products->find()
      ->where(['delete_flag' => 0])->toArray();

      $arrProduct_name_list = array();
      for($j=0; $j<count($Product_name_list); $j++){
        array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
      }
      $arrProduct_name_list = array_unique($arrProduct_name_list);
      $arrProduct_name_list = array_values($arrProduct_name_list);

      $this->set('arrProduct_name_list', $arrProduct_name_list);

            /*
      echo "<pre>";
      print_r($arrLength_size1);
      echo "</pre>";
*/

    }

    public function tourokucustomers()//http://localhost:5050/Zzzcsvs/tourokucustomers
    {
      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['delete_flag' => 0])->toArray();

      $datetimenow = date('Y-m-d H:i:s');
      $arrFp = array();//空の配列を作る
      for($j=0; $j<count($MaterialSuppliers); $j++){

        $Customers = $this->Customers->find()
        ->where(['customer_code' => $MaterialSuppliers[$j]["material_supplier_code"]])
        ->toArray();

        if(!isset($Customers[0])){
          $arrFp[] = [
            'factory_id' => $MaterialSuppliers[$j]["factory_id"],
            'customer_code' => $MaterialSuppliers[$j]["material_supplier_code"],
            'name' => $MaterialSuppliers[$j]["name"],
            'department' => $MaterialSuppliers[$j]["department"],
            'ryakusyou' => $MaterialSuppliers[$j]["ryakusyou"],
            'furigana' => $MaterialSuppliers[$j]["furigana"],
            'yuubin' => $MaterialSuppliers[$j]["yuubin"],
            'address' => $MaterialSuppliers[$j]["address"],
            'tel' => $MaterialSuppliers[$j]["tel"],
            'fax' => $MaterialSuppliers[$j]["fax"],
            'is_active' => 0,
            'delete_flag' => 0,
            'created_at' => $datetimenow,
            'created_staff' => $MaterialSuppliers[$j]["created_staff"]
          ];
        }



      }
/*
      echo "<pre>";
      print_r($arrFp);
      echo "</pre>";
*/
/*
        for($j=0; $j<count($arrFp); $j++){
          $MaterialSuppliers = $this->MaterialSuppliers->patchEntity($this->MaterialSuppliers->newEntity(), $arrFp[$j]);
          if ($this->MaterialSuppliers->save($MaterialSuppliers)) {
            echo "<pre>";
            print_r("ok");
            print_r($arrFp[$j]);
            echo "</pre>";
          }
        }
*/
      $Customers = $this->Customers->patchEntities($this->Customers->newEntity(), $arrFp);
      $this->Customers->saveMany($Customers);

    }
  
    public function tourokusuppliers()//http://localhost:5050/Zzzcsvs/tourokusuppliers
    {
      $Customers = $this->Customers->find()
      ->where(['delete_flag' => 0])->toArray();

      $datetimenow = date('Y-m-d H:i:s');
      $arrFp = array();//空の配列を作る
      for($j=0; $j<count($Customers); $j++){

        $MaterialSuppliers = $this->MaterialSuppliers->find()
        ->where(['material_supplier_code' => $Customers[$j]["customer_code"]])
        ->toArray();

        if(!isset($MaterialSuppliers[0])){
          $arrFp[] = [
            'factory_id' => $Customers[$j]["factory_id"],
            'material_supplier_code' => $Customers[$j]["customer_code"],
            'name' => $Customers[$j]["name"],
            'department' => $Customers[$j]["department"],
            'ryakusyou' => $Customers[$j]["ryakusyou"],
            'furigana' => $Customers[$j]["furigana"],
            'yuubin' => $Customers[$j]["yuubin"],
            'address' => $Customers[$j]["address"],
            'tel' => $Customers[$j]["tel"],
            'fax' => $Customers[$j]["fax"],
            'is_active' => 0,
            'delete_flag' => 0,
            'created_at' => $datetimenow,
            'created_staff' => $Customers[$j]["created_staff"]
          ];
        }

      }
/*
      echo "<pre>";
      print_r($arrFp);
      echo "</pre>";
*/
/*
        for($j=0; $j<count($arrFp); $j++){
          $MaterialSuppliers = $this->MaterialSuppliers->patchEntity($this->MaterialSuppliers->newEntity(), $arrFp[$j]);
          if ($this->MaterialSuppliers->save($MaterialSuppliers)) {
            echo "<pre>";
            print_r("ok");
            print_r($arrFp[$j]);
            echo "</pre>";
          }
        }
*/
      $MaterialSuppliers = $this->MaterialSuppliers->patchEntities($this->MaterialSuppliers->newEntity(), $arrFp);
      $this->MaterialSuppliers->saveMany($MaterialSuppliers);

    }

    public function torikomimaterialnamecheck()//http://localhost:5050/Zzzcsvs/torikomimaterialnamecheck
    {//仕入品名がDBとスマイルで違うもの
      $fp = fopen("torikomicsvs/materials220112.csv", "r");//csvファイルはwebrootに入れる
    	$fpcount = fopen("torikomicsvs/materials220112.csv", 'r' );
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
    		$keys[array_search('2',$keys)]='tanni';
    		$keys[array_search('3',$keys)]='syubetsu';
        $keys[array_search('4',$keys)]='material_supplier_id';
        $keys[array_search('5',$keys)]='damy';
    		$sample = array_combine($keys, $sample);

        $sample = array_merge($sample,array('created_at' => "2022-01-18 10:00:00"));
        $sample = array_merge($sample,array('created_staff' => 1));
        $sample = array_merge($sample,array('is_active' => 0));
        $sample = array_merge($sample,array('delete_flag' => 0));

        unset($sample['damy']);

        $arrFp[] = $sample;//配列に追加する
    	}
    	$this->set('arrFp',$arrFp);//$arrFpをctpで使用できるようセット

      $arrFpcodeng = array();//空の配列を作る
      for($j=0; $j<count($arrFp); $j++){

        if(strpos($arrFp[$j]["material_code"],'D') !== false){//'D'が含まれている場合大東工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>1));
          $arrFp[$j] = array_merge($arrFp[$j],array('status_kensahyou'=>1));

        }elseif(strpos($arrFp[$j]["material_code"],'I') !== false){//石狩工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>2));
          $arrFp[$j] = array_merge($arrFp[$j],array('status_kensahyou'=>1));

        }elseif(strpos($arrFp[$j]["material_code"],'B') !== false){//美唄工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>3));
          $arrFp[$j] = array_merge($arrFp[$j],array('status_kensahyou'=>1));

        }else{//門真工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>4));
          $arrFp[$j] = array_merge($arrFp[$j],array('status_kensahyou'=>1));

        }

        $code = substr($arrFp[$j]["material_code"], 1, 6);

        $MaterialSuppliers = $this->MaterialSuppliers->find()
        ->where(['material_supplier_code' => $code])
        ->toArray();

        if(isset($MaterialSuppliers[0])){

          $arrFp[$j] = array_merge($arrFp[$j],array('material_supplier_id'=>$MaterialSuppliers[0]['id']));

        }else{

          $arrFpcodeng[] = $arrFp[$j];

        }

      }

      $count = 0;
      $arrFpname = array();//空の配列を作る
      $arrFpname[] = [
        "material_code" => "仕入品コード",
        "nameDB" => "仕入品名DB",
        "namesmile" => "仕入品名スマイル",
      ];
      for($j=0; $j<count($arrFp); $j++){

        $tourokuarr = $arrFp[$j];

        $Materials = $this->Materials->find()
        ->where(['material_code' => $arrFp[$j]["material_code"]])
        ->toArray();

        if(isset($Materials[0])){
  
          if($Materials[0]["name"] !== $arrFp[$j]["name"]){//DBとスマイルで名前が違うもの
  
            $count = $count + 1;
/*
            echo "<pre>";
            print_r($arrFp[$j]["material_code"]." DB=「".$Materials[0]["name"]."」　スマイル=「".$arrFp[$j]["name"]."」");
            echo "</pre>";
*/
            $arrFpname[] = [
              "material_code" => $arrFp[$j]["material_code"],
              "nameDB" => $Materials[0]["name"],
              "namesmile" => $arrFp[$j]["name"],
            ];

          }

          }

      }

      $fp = fopen('torikomicsvs/仕入品名前220118.csv', 'w');
      foreach ($arrFpname as $line) {
        $line = mb_convert_encoding($line, 'SJIS-win', 'UTF-8');//UTF-8の文字列をSJIS-winに変更する※文字列に使用、ファイルごとはできない
        fputcsv($fp, $line);
      }
      fclose($fp);
/*
      echo "<pre>";
      print_r($count);
      echo "</pre>";
*/
      $this->render('/Zzzcsvs/torikomimaterialtypes');
    }

    public function torikomimaterialtypes()//http://localhost:5050/Zzzcsvs/torikomimaterialtypes
    {
//typeの修正materials登録後に更新
      $Materials = $this->Materials->find()
      ->where(['delete_flag' => 0])
      ->toArray();

      for($j=0; $j<count($Materials); $j++){

        $type = substr($Materials[$j]["material_code"], 8, 3);
   
        $MaterialTypes = $this->MaterialTypes->find()
        ->where(['type' => $type])
        ->toArray();

        $this->Materials->updateAll(
            ['material_type_id' => $MaterialTypes[0]["id"]],
            ['id'  => $Materials[$j]['id']]
          );
  
        }

    }

    public function torikomimaterials()//http://localhost:5050/Zzzcsvs/torikomimaterials
    {
      $fp = fopen("torikomicsvs/materials220112.csv", "r");//csvファイルはwebrootに入れる
    	$fpcount = fopen("torikomicsvs/materials220112.csv", 'r' );
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
    		$keys[array_search('2',$keys)]='tanni';
    		$keys[array_search('3',$keys)]='syubetsu';
        $keys[array_search('4',$keys)]='material_supplier_id';
        $keys[array_search('5',$keys)]='damy';
    		$sample = array_combine($keys, $sample);

        $sample = array_merge($sample,array('created_at' => "2022-01-18 10:00:00"));
        $sample = array_merge($sample,array('created_staff' => 1));
        $sample = array_merge($sample,array('is_active' => 0));
        $sample = array_merge($sample,array('delete_flag' => 0));

        unset($sample['damy']);

        $arrFp[] = $sample;//配列に追加する
    	}
    	$this->set('arrFp',$arrFp);//$arrFpをctpで使用できるようセット

      $arrFpcodeng = array();//空の配列を作る
      for($j=0; $j<count($arrFp); $j++){

        if(strpos($arrFp[$j]["material_code"],'D') !== false){//'D'が含まれている場合大東工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>1));
          $arrFp[$j] = array_merge($arrFp[$j],array('status_kensahyou'=>1));

        }elseif(strpos($arrFp[$j]["material_code"],'I') !== false){//石狩工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>2));
          $arrFp[$j] = array_merge($arrFp[$j],array('status_kensahyou'=>1));

        }elseif(strpos($arrFp[$j]["material_code"],'B') !== false){//美唄工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>3));
          $arrFp[$j] = array_merge($arrFp[$j],array('status_kensahyou'=>1));

        }else{//門真工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>4));
          $arrFp[$j] = array_merge($arrFp[$j],array('status_kensahyou'=>1));

        }

        $code = substr($arrFp[$j]["material_code"], 1, 6);

        $MaterialSuppliers = $this->MaterialSuppliers->find()
  //      ->where(['material_supplier_code' => (int)$arrFp[$j]["material_supplier_id"]])
        ->where(['material_supplier_code' => $code])
        ->toArray();

        if(isset($MaterialSuppliers[0])){

          $arrFp[$j] = array_merge($arrFp[$j],array('material_supplier_id'=>$MaterialSuppliers[0]['id']));

        }else{

          $arrFpcodeng[] = $arrFp[$j];

        }

      }
/*
      echo "<pre>";
      print_r($arrFpcodeng);
      echo "</pre>";
*/
      $fp = fopen('torikomicsvs/仕入品ng220118.csv', 'w');
      foreach ($arrFpcodeng as $line) {
        $line = mb_convert_encoding($line, 'SJIS-win', 'UTF-8');//UTF-8の文字列をSJIS-winに変更する※文字列に使用、ファイルごとはできない
        fputcsv($fp, $line);
      }
      fclose($fp);

      $countok = 0;
      $countng = 0;
      $arrFptourokung = array();//空の配列を作る
      for($j=0; $j<count($arrFp); $j++){

        $tourokuarr = $arrFp[$j];

        $Materials = $this->Materials->find()
        ->where(['material_code' => $arrFp[$j]["material_code"]])
        ->toArray();

        if(!isset($Materials[0])){//データベースになければ登録
          $Materials = $this->Materials->patchEntity($this->Materials->newEntity(), $tourokuarr);
          if ($this->Materials->save($Materials)) {
  
            $countok = $countok + 1;

          }else{
  
            $countng = $countng + 1;
            $arrFptourokung[] = $arrFp[$j];

            echo "<pre>";
            print_r("ng ".$j);
            print_r($arrFp[$j]);
            echo "</pre>";
  
          }

          }

      }

      $fp = fopen('torikomicsvs/仕入品登録ng220112.csv', 'w');
      foreach ($arrFptourokung as $line) {
        $line = mb_convert_encoding($line, 'SJIS-win', 'UTF-8');//UTF-8の文字列をSJIS-winに変更する※文字列に使用、ファイルごとはできない
        fputcsv($fp, $line);
      }
      fclose($fp);

      echo "<pre>";
      print_r("all ".count($arrFp));
      echo "</pre>";
      echo "<pre>";
      print_r("ok ".$countok);
      echo "</pre>";
      echo "<pre>";
      print_r("ng ".$countng);
      echo "</pre>";

  //    $Materials = $this->Materials->patchEntities($this->Materials->newEntity(), $arrFp);//登録は個別で行う
  //    $this->Materials->saveMany($Materials);

    }

    public function torikomimaterialsuppliers()//http://localhost:5050/Zzzcsvs/torikomimaterialsuppliers
    {

      $fp = fopen("torikomicsvs/materialsuplires220111.csv", "r");//csvファイルはwebrootに入れる
    	$fpcount = fopen("torikomicsvs/materialsuplires220111.csv", 'r' );
    	for( $count = 0; fgets( $fpcount ); $count++ );
    	$this->set('count',$count);

    	$arrFp = array();//空の配列を作る
    	$line = fgets($fp);//ファイル$fpの上の１行を取る（１行目はカラム名）
    	for ($k=1; $k<=$count-1; $k++) {//行数分
    		$line = fgets($fp);//ファイル$fpの上の１行を取る（２行目から）
    		$sample = explode(',',$line);//$lineを","毎に配列に入れる

    		$keys=array_keys($sample);
    		$keys[array_search('0',$keys)]='material_supplier_code';//コードの最後が1,5は大東、2,6は石狩
    		$keys[array_search('1',$keys)]='name';
    		$keys[array_search('2',$keys)]='department';
    		$keys[array_search('3',$keys)]='ryakusyou';
    		$keys[array_search('4',$keys)]='furigana';
        $keys[array_search('5',$keys)]='yuubin';
        $keys[array_search('6',$keys)]='address';
        $keys[array_search('7',$keys)]='tel';
        $keys[array_search('8',$keys)]='fax';
    		$sample = array_combine($keys, $sample);

        $sample = array_merge($sample,array('created_at' => "2021-12-21 08:00:00"));
        $sample = array_merge($sample,array('created_staff' => 1));
        $sample = array_merge($sample,array('factory_id' => 1));
        $sample = array_merge($sample,array('is_active' => 0));
        $sample = array_merge($sample,array('delete_flag' => 0));
/*
        unset($sample['office']);
        unset($sample['customercode_local']);
        unset($sample['department']);
*/
        if(strpos($sample['name'],'□') === false && strpos($sample['name'],'■') === false){
          //'abcd'のなかに'□'が含まれていない場合
          $arrFp[] = $sample;//配列に追加する
        }

      }
    	$this->set('arrFp',$arrFp);//$arrFpをctpで使用できるようセット

      for($j=0; $j<count($arrFp); $j++){

        if(strlen($arrFp[$j]["furigana"]) < 1){

          $arrFp[$j] = array_merge($arrFp[$j],array('furigana'=>0));

        }

      }

      echo "<pre>";
      print_r($arrFp);
      echo "</pre>";
      /*
      $MaterialSuppliers = $this->MaterialSuppliers->patchEntity($this->MaterialSuppliers->newEntity(), $arrFp[0]);
      $this->MaterialSuppliers->save($MaterialSuppliers);
*/
/*
      $MaterialSuppliers = $this->MaterialSuppliers->patchEntities($this->MaterialSuppliers->newEntity(), $arrFp);
      $this->MaterialSuppliers->saveMany($MaterialSuppliers);
  */    
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
      $fp = fopen("torikomicsvs/products220111.csv", "r");//csvファイルはwebrootに入れる
    	$fpcount = fopen("torikomicsvs/products220111.csv", 'r' );
    	for( $count = 0; fgets( $fpcount ); $count++ );
    	$this->set('count',$count);

      $arrFp = array();//空の配列を作る
      $arrFptourokuzumi = array();//空の配列を作る
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
   //     $keys[array_search('8',$keys)]='dammy';
   //     $keys[array_search('5',$keys)]='customer';//
    		$sample = array_combine($keys, $sample);

        $sample = array_merge($sample,array('created_at' => date("Y-m-d H:i:s")));
        $sample = array_merge($sample,array('created_staff' => 1));
        $sample = array_merge($sample,array('factory_id' => 1));
        $sample = array_merge($sample,array('is_active' => 0));
        $sample = array_merge($sample,array('delete_flag' => 0));

    //    unset($sample['dammy']);

        $Products = $this->Products->find()
        ->where(['product_code' => $sample["product_code"]])
        ->toArray();

        if(isset($Products[0])){

          $arrFptourokuzumi[] = $sample;//配列に追加する
/*
          echo "<pre>";
          print_r("登録済み　".$sample["product_code"]);
          echo "</pre>";
*/
            }else{

              $arrFp[] = $sample;//配列に追加する

            }

    	}
    	$this->set('arrFp',$arrFp);//$arrFpをctpで使用できるようセット

      $count = 0;
/*
      echo "<pre>";
      print_r($arrFp);
      echo "</pre>";
*/
      $arrFpok = array();//問題なしのデータ
      $arrFpng = array();//顧客コードがないデータ
      for($j=0; $j<count($arrFp); $j++){

        if(strpos($arrFp[$j]["product_code"],'D') !== false){//'D'が含まれている場合大東工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>1));
          $arrFp[$j] = array_merge($arrFp[$j],array('status_length'=>0));

        }elseif(strpos($arrFp[$j]["product_code"],'I') !== false){//石狩工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>2));
          $arrFp[$j] = array_merge($arrFp[$j],array('status_length'=>0));

        }elseif(strpos($arrFp[$j]["product_code"],'B') !== false){//美唄工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>3));
          $arrFp[$j] = array_merge($arrFp[$j],array('status_length'=>0));

        }else{//門真工場

          $arrFp[$j] = array_merge($arrFp[$j],array('factory_id'=>4));
          $arrFp[$j] = array_merge($arrFp[$j],array('status_length'=>0));

        }

        $Customers = $this->Customers->find()
        ->where(['customer_code' => (int)$arrFp[$j]["customer_id"]])
        ->toArray();

        if(isset($Customers[0])){
          $arrFp[$j] = array_merge($arrFp[$j],array('customer_id'=>$Customers[0]['id']));

          echo "<pre>";
          print_r($arrFp[$j]);
          echo "</pre>";

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
      
      echo "<pre>";
      print_r($arrFpok);
      echo "</pre>";
       
      echo "<pre>";
      print_r("ng ".count($arrFpng));
      echo "</pre>";
      
      echo "<pre>";
      print_r($arrFpng);
      echo "</pre>";

      $fp = fopen('torikomicsvs/製品データng220111.csv', 'w');
        foreach ($arrFpng as $line) {
    //      $line = mb_convert_encoding($line, 'SJIS-win', 'UTF-8');//UTF-8の文字列をSJIS-winに変更する※文字列に使用、ファイルごとはできない
          fputcsv($fp, $line);
        }
        fclose($fp);

   //     $Products = $this->Products->patchEntities($this->Products->newEntity(), $arrFpok);
   //     $this->Products->saveMany($Products);

    }



}
