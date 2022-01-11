<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class KadousController extends AppController
{

  public function initialize()
  {
   parent::initialize();
   $this->Users = TableRegistry::get('Users');
   $this->Staffs = TableRegistry::get('Staffs');
   $this->Factories = TableRegistry::get('Factories');
   $this->Products = TableRegistry::get('Products');

   $this->DailyReports = TableRegistry::get('DailyReports');
   $this->Linenames = TableRegistry::get('Linenames');
   $this->InspectionDataResultParents = TableRegistry::get('InspectionDataResultParents');
   $this->RelayLogs = TableRegistry::get('RelayLogs');

   if(!isset($_SESSION)){//フォーム再送信の確認対策
    session_start();
  }
  header('Expires:');
  header('Cache-Control:');
  header('Pragma:');

  }

  public function beforeFilter(Event $event){
    parent::beforeFilter($event);

    // 認証なしでアクセスできるアクションの指定
//    $this->Auth->allow([
//      "index", "yobidashidate", "view"
//    ]);
  }

    public function index()
    {
    }
  
    public function yobidashidate()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $dateYMD = date('Y-m-d');
      $dateYMD1 = strtotime($dateYMD);
      $dayyey = date('Y', strtotime('-1 day', $dateYMD1));
      $dayyeini = date('Y', strtotime('-5 year', $dateYMD1));
      $arrYears = array();
      for ($k=$dayyeini; $k<=$dayyey; $k++){
        $arrYears[$k]=$k;
      }
      $this->set('arrYears',$arrYears);
  
      $arrMonths = array();
        for ($k=1; $k<=12; $k++){
          $arrMonths[$k] =$k;
        }
      $this->set('arrMonths',$arrMonths);
  
      $arrDays = array();
        for ($k=1; $k<=31; $k++){
          $arrDays[$k] =$k;
        }
      $this->set('arrDays',$arrDays);

      $session = $this->request->getSession();
      $datasession = $session->read();
      $staff_id = $datasession['Auth']['User']['staff_id'];

      $Staffs = $this->Staffs->find()
      ->where(['id' => $staff_id])
      ->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      if($factory_id == 5){
  
        $this->set('usercheck', 1);
        $Factories = $this->Factories->find('list');
        $this->set(compact('Factories'));

      }else{
  
        $this->set('usercheck', 0);

      }

    }

    public function view()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $dateselect = $data["date_sta_year"]."-".$data["date_sta_month"]."-".$data["date_sta_date"];
      $date1 = strtotime($dateselect);
      $date_sta = $dateselect." 06:00:00";
      $date_fin = date('Y-m-d', strtotime('+1 day', $date1))." 06:00:00";
      $date_fin_hyouji = date('Y-m-d', strtotime('+1 day', $date1))." 05:59:59";
      $this->set('date_sta', $date_sta);
      $this->set('date_fin', $date_fin);
      $this->set('date_fin_hyouji', $date_fin_hyouji);

      $date_kensa = substr($date_sta, 0, 10);
      $this->set('date_kensa', $date_kensa);

      $session = $this->request->getSession();
      $datasession = $session->read();
      $staff_id = $datasession['Auth']['User']['staff_id'];

      $Staffs = $this->Staffs->find()
      ->where(['id' => $staff_id])
      ->toArray();
      $factory_id = $Staffs[0]["factory_id"];
      $this->set('factory_id', $factory_id);

      if(isset($data["factory_id"])){

        $this->set('factory_id', $data["factory_id"]);

        $Linenames = $this->Linenames->find()
        ->where(['delete_flag' => 0, 'factory_id' => $data["factory_id"]])->toArray();

        $DailyReportsData = $this->DailyReports->find()
        ->contain(['Products'])
        ->where(['start_datetime >=' => $date_sta, 'start_datetime <' => $date_fin,
        'DailyReports.delete_flag' => 0, 'factory_id' => $data["factory_id"]])
        ->order(["machine_num"=>"ASC"])->toArray();
  
      }else{

        $Linenames = $this->Linenames->find()
        ->where(['delete_flag' => 0, 'factory_id' => $factory_id])->toArray();

        $DailyReportsData = $this->DailyReports->find()
        ->contain(['Products'])
        ->where(['start_datetime >=' => $date_sta, 'start_datetime <' => $date_fin,
        'DailyReports.delete_flag' => 0, 'factory_id' => $factory_id])
        ->order(["machine_num"=>"ASC"])->toArray();
  
      }

      $arrlines_all = array();
      for($i=0; $i<count($Linenames); $i++){

        $arrlines_all[] = $Linenames[$i]["machine_num"];

      }

      $arrDaily_report = array();
      $arrline_shiyou = array();
      $arrproduct_code_ini_machine_num = array();
      for($i=0; $i<count($DailyReportsData); $i++){

        $RelayLogsData = $this->RelayLogs->find()
        ->where(['datetime >=' => $date_sta, 'datetime <' => $date_fin,
        'delete_flag' => 0, 'factory_code' => $factory_id, 'machine_num' => $DailyReportsData[$i]["machine_num"]])
        ->order(["datetime"=>"ASC"])->toArray();
        if(isset($RelayLogsData[0])){
          $relay_start_datetime = $RelayLogsData[0]["datetime"]->format("Y-m-d H:i:s");
          $relay_finish_datetime = $RelayLogsData[count($RelayLogsData)-1]["datetime"]->format("Y-m-d H:i:s");
        }else{
          $relay_start_datetime = "";
          $relay_finish_datetime = "";
        }
        
        $riron_amount = $DailyReportsData[$i]["amount"] * ($DailyReportsData[$i]["total_loss_weight"] + $DailyReportsData[$i]["sum_weight"]) / $DailyReportsData[$i]["sum_weight"];
        $riron_amount = sprintf("%.1f", $riron_amount);
        $count = count(array_keys($arrline_shiyou, $DailyReportsData[$i]["machine_num"])) + 1;
        $countproduct_code_ini = count(array_keys($arrproduct_code_ini_machine_num, substr($DailyReportsData[$i]["product"]["product_code"], 0, 11)."_".$DailyReportsData[$i]["machine_num"])) + 1;
        $arrline_shiyou[] = $DailyReportsData[$i]["machine_num"];
        $arrproduct_code_ini_machine_num[] = substr($DailyReportsData[$i]["product"]["product_code"], 0, 11)."_".$DailyReportsData[$i]["machine_num"];
        $arrDaily_report[] = [
          "machine_num" => $DailyReportsData[$i]["machine_num"],
          "start_datetime" => $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"),
          "relay_start_datetime" => $relay_start_datetime,
          "finish_datetime" => $DailyReportsData[$i]["finish_datetime"]->format("Y-m-d H:i:s"),
          "relay_finish_datetime" => $relay_finish_datetime,
          "product_code" => $DailyReportsData[$i]["product"]["product_code"],
          "product_code_ini" => substr($DailyReportsData[$i]["product"]["product_code"], 0, 11),
          "name" => $DailyReportsData[$i]["product"]["name"],
          "length" => $DailyReportsData[$i]["product"]["length"],
          "amount" => $DailyReportsData[$i]["amount"],
          "riron_amount" => "",
          "sum_weight" => sprintf("%.1f", $DailyReportsData[$i]["sum_weight"]),
          "count" => $count,
          "countproduct_code_ini" => $countproduct_code_ini,
        ];

      }

      $arrline_shiyou = array_unique($arrline_shiyou);
      $arrline_shiyou = array_values($arrline_shiyou);

      $arrline_fushiyou_moto = array_diff($arrlines_all, $arrline_shiyou);
      $arrline_fushiyou_moto = array_values($arrline_fushiyou_moto);

      $arrlines_fushiyous = array();
      for($i=0; $i<count($arrline_fushiyou_moto); $i++){

        $arrlines_fushiyous[] = [
          "machine_num" => $arrline_fushiyou_moto[$i],
          "start_datetime" => "-",
          "finish_datetime" => "-",
          "relay_start_datetime" => "-",
          "relay_finish_datetime" => "-",
          "product_code" => "-",
          "product_code_ini" => "-",
          "name" => "-",
          "length" => "-",
          "amount" => "-",
          "riron_amount" => "-",
          "sum_weight" => "-",
          "count" => 1,
          "countproduct_code_ini" => 1,
        ];

      }

      $arrAll = array_merge($arrDaily_report, $arrlines_fushiyous);

      foreach( $arrAll as $key => $row ) {
        $machine_num_array[$key] = $row["machine_num"];
        $count_array[$key] = $row["count"];
        $product_code_array[$key] = $row["product_code"];
        $countproduct_code_ini_array[$key] = $row["countproduct_code_ini"];
      }

      array_multisort( $machine_num_array,
      $count_array, SORT_ASC, SORT_NUMERIC,
      $product_code_array, SORT_ASC, SORT_NUMERIC,
      $countproduct_code_ini_array, SORT_ASC,
      $arrAll );

      $this->set('arrAll', $arrAll);

      $arrAllMachine = array();
      for($i=0; $i<count($arrAll); $i++){

        $arrAllMachine[] = $arrAll[$i]["machine_num"];

      }

      $arrCountMachine = array();
      for($i=0; $i<count($arrAllMachine); $i++){

        $arrCountMachine[$arrAllMachine[$i]] = count(array_keys($arrAllMachine, $arrAllMachine[$i]));

      }
      $this->set('arrCountMachine', $arrCountMachine);

      $arrCountProducts = array();
      $arrCountProducts["-"] = 1;
      for($i=0; $i<count($arrproduct_code_ini_machine_num); $i++){

        $arrCountProducts[$arrproduct_code_ini_machine_num[$i]] = count(array_keys($arrproduct_code_ini_machine_num, $arrproduct_code_ini_machine_num[$i]));

      }
      $this->set('arrCountProducts', $arrCountProducts);

      echo "<pre>";
      print_r("");
      echo "</pre>";

    }

    public function details()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();

      echo "<pre>";
      print_r($data);
      echo "</pre>";

      if(isset($data["kensahyou"])){

        return $this->redirect(['controller' => 'Kensahyousokuteidatas', 'action' => 'kensakuhyouji',
        's' => substr($data["date_sta"], 0, 10)."_".$data["machine_num"]."_".$data["product_code"]]);

      }else{

      $arrsyousai = array_keys($data, '詳細');

      if(isset($arrsyousai[0])){
        $arrmachine_products = $arrsyousai[0];
        $machine_products = explode("_",$arrmachine_products);
        $machine_num = $machine_products[0];
        $this->set('machine_num', $machine_num);
        $product_code = $machine_products[1];
        $this->set('product_code', $product_code);

        $target_num = 0;
        $this->set('target_num', $target_num);

        echo "<pre>";
        print_r("if");
        echo "</pre>";
  
      }else{

        if(isset($data["machine_num"])){

        }elseif(){

        }
        $machine_num = $data["machine_num"];
        $this->set('machine_num', $machine_num);
        $product_code = $data["product_code"];
        $this->set('product_code', $product_code);

        echo "<pre>";
        print_r("else");
        echo "</pre>";

      }

      echo "<pre>";
      print_r($machine_num);
      echo "</pre>";

      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);
      $date_sta = $data["date_sta"];
      $date_fin = $data["date_fin"];
      $this->set('date_sta', $date_sta);
      $this->set('date_fin', $date_fin);
      $date_fin_hyouji = substr($date_fin, 0, 10)." 05:59:59";
      $this->set('date_fin_hyouji', $date_fin_hyouji);

      $product_code_ini = substr($product_code, 0, 11);
      $DailyReportsData = $this->DailyReports->find()
      ->contain(['Products'])
      ->where(['product_code like' => $product_code_ini.'%', 
      'start_datetime >=' => $date_sta, 'start_datetime <' => $date_fin,
      'DailyReports.delete_flag' => 0, 'DailyReports.machine_num' => $machine_num,
      'factory_id' => $factory_id])
      ->order(["machine_num"=>"ASC"])->toArray();

      $date_kensa = substr($date_sta, 0, 10);
      $this->set('date_kensa', $date_kensa);

      $arrProdcts = array();
      for($i=0; $i<count($DailyReportsData); $i++){
      
        $tasseiritsu = $DailyReportsData[$i]["sum_weight"] * 100 / ($DailyReportsData[$i]["sum_weight"] + $DailyReportsData[$i]["total_loss_weight"]);
        $tasseiritsu = sprintf("%.1f", $tasseiritsu);

        $arrProdcts[] = [
          "product_code" => $DailyReportsData[$i]["product"]["product_code"],
          "name" => $DailyReportsData[$i]["product"]["name"],
          "length" => $DailyReportsData[$i]["product"]["length"],
          "amount" => $DailyReportsData[$i]["amount"],
          "sum_weight" => $DailyReportsData[$i]["sum_weight"],
          "total_loss_weight" => $DailyReportsData[$i]["total_loss_weight"],
          "tasseiritsu" => $tasseiritsu,
        ];

        $this->set('bik', $DailyReportsData[$i]["bik"]);

      }
      $this->set('arrProdcts', $arrProdcts);

      $InspectionDataResultParentDatas = $this->InspectionDataResultParents->find()
      ->contain(['ProductConditionParents', 'Products'])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%',
      'InspectionDataResultParents.delete_flag' => 0,
      'datetime >=' => $date_sta, 'datetime <=' => $date_fin])
      ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();

      $arrIjous = array();
      for($i=0; $i<count($InspectionDataResultParentDatas); $i++){

        if(strlen($InspectionDataResultParentDatas[$i]["loss_amount"]) > 0){

          $arrIjous[] = [
            "datetime" => $InspectionDataResultParentDatas[$i]["datetime"]->format("Y-m-d H:i:s"),
            "length" => $InspectionDataResultParentDatas[$i]["product"]["length"],
            "loss_amount" => $InspectionDataResultParentDatas[$i]["loss_amount"],
            "bik" => $InspectionDataResultParentDatas[$i]["bik"],
            "staff_name" => $InspectionDataResultParentDatas[$i]["staff"]["name"],
          ];
  
        }

      }
      $this->set('arrIjous', $arrIjous);

    }
/*
      echo "<pre>";
      print_r($arrIjous);
      echo "</pre>";
*/
      echo "<pre>";
      print_r("");
      echo "</pre>";

    }

}
