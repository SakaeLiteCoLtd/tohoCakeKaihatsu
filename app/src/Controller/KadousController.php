<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

use App\myClass\classprograms\kadouprogram;//myClassフォルダに配置したクラスを使用

class KadousController extends AppController
{

  public function initialize()
  {
   parent::initialize();
   $this->Users = TableRegistry::get('Users');
   $this->Staffs = TableRegistry::get('Staffs');
   $this->Factories = TableRegistry::get('Factories');
   $this->Products = TableRegistry::get('Products');
   $this->RelayLogs = TableRegistry::get('RelayLogs');
   $this->MachineRelays = TableRegistry::get('MachineRelays');
   $this->ShotdataBases = TableRegistry::get('ShotdataBases');
   $this->DailyReports = TableRegistry::get('DailyReports');
   $this->Linenames = TableRegistry::get('Linenames');
   $this->InspectionDataResultParents = TableRegistry::get('InspectionDataResultParents');

   if(!isset($_SESSION)){//フォーム再送信の確認対策
    session_start();
  }
  header('Expires:');
  header('Cache-Control:');
  header('Pragma:');

  }
  
    public function menu()
    {
      //大東工場でない人の場合はトップに戻す

      $session = $this->request->getSession();
      $datasession = $session->read();
      $staff_id = $datasession['Auth']['User']['staff_id'];
      $Staffs = $this->Staffs->find()
      ->where(['id' => $staff_id])
      ->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      if($factory_id != 1 && $factory_id != 5){

        return $this->redirect(['controller' => 'Kensahyoukadous', 'action' => 'index',
        's' => ['mess' => "大東工場のメンバーアカウントでログインしてください。"]]);
  
      }

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

      $arrSelects = [
        "0" => "のみ",
        "1" => "から"
      ];
      $this->set('arrSelects',$arrSelects);

      $arrYearsfin = array();
      $arrYearsfin["-"] ="-";
      for ($k=$dayyeini; $k<=$dayyey; $k++){
        $arrYearsfin[$k]=$k;
      }
      $this->set('arrYearsfin',$arrYearsfin);
  
      $arrMonthsfin = array();
      $arrMonthsfin["-"] ="-";
        for ($k=1; $k<=12; $k++){
          $arrMonthsfin[$k] =$k;
        }
      $this->set('arrMonthsfin',$arrMonthsfin);
  
      $arrDaysfin = array();
      $arrDaysfin["-"] ="-";
        for ($k=1; $k<=31; $k++){
          $arrDaysfin[$k] =$k;
        }
      $this->set('arrDaysfin',$arrDaysfin);

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

      $tyuukann_flag = 0;

      $Data = $this->request->query('s');
      if(isset($Data["num_max"])){
/*
        echo "<pre>";
        print_r($Data);
        echo "</pre>";
*/
        $factory_id = $Data["factory_id"];
        $date_sta = $Data["date_sta"];
        $date_fin = $Data["date_fin"];
        $date_fin_hyouji = $Data["date_fin_hyouji"];
        $this->set('date_sta', $date_sta);
        $this->set('date_fin', $date_fin);
        $this->set('date_fin_hyouji', $date_fin_hyouji);

        if($Data["button_name"] == "tyuukann"){
          $tyuukann_flag = 1;
        }

      }else{
        $data = $this->request->getData();
        $dateselect = $data["date_sta_year"]."-".$data["date_sta_month"]."-".$data["date_sta_date"];

        if($data["date_select_flag"] == 0){

          $date1 = strtotime($dateselect);
          $date_sta = $dateselect." 06:00:00";
          $date_fin = date('Y-m-d', strtotime('+1 day', $date1))." 06:00:00";
          $date_fin_hyouji = date('Y-m-d', strtotime('+1 day', $date1))." 05:59:59";
          $this->set('date_sta', $date_sta);
          $this->set('date_fin', $date_fin);
          $this->set('date_fin_hyouji', $date_fin_hyouji);
    
        }elseif($data["date_select_flag"] == 1){
  
          $date1 = strtotime($dateselect);
          $date_sta = $dateselect." 06:00:00";
  
          $dateselectfin = $data["date_sta_year_fin"]."-".$data["date_sta_month_fin"]."-".$data["date_sta_date_fin"];
          $date1fin = strtotime($dateselectfin);
  
          $date_fin = date('Y-m-d', strtotime('+1 day', $date1fin))." 06:00:00";
          $date_fin_hyouji = date('Y-m-d', strtotime('+1 day', $date1fin))." 05:59:59";
          $this->set('date_sta', $date_sta);
          $this->set('date_fin', $date_fin);
          $this->set('date_fin_hyouji', $date_fin_hyouji);
  
        }
  
      }

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
        ->order(["start_datetime"=>"ASC"])->toArray();

      }else{

        $Linenames = $this->Linenames->find()
        ->where(['delete_flag' => 0, 'factory_id' => $factory_id])->toArray();

        $DailyReportsData = $this->DailyReports->find()
        ->contain(['Products'])
        ->where(['start_datetime >=' => $date_sta, 'start_datetime <' => $date_fin,
        'DailyReports.delete_flag' => 0, 'factory_id' => $factory_id])
        ->order(["start_datetime"=>"ASC"])->toArray();
  
      }

      $arrlines_all = array();
      for($i=0; $i<count($Linenames); $i++){

        $arrlines_all[] = $Linenames[$i]["machine_num"];

      }

      $arrDaily_report = array();
      $arrline_shiyou = array();
      $arrproduct_code_ini_machine_num_datetime = array();
      for($i=0; $i<count($DailyReportsData); $i++){

        if((int)substr($DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"), 11, 2) < 6){//前日の検査

          $date_fin_relayLog = $DailyReportsData[$i]["start_datetime"]->format("Y-m-d")." 05:59:59";
          $date1fin = strtotime($DailyReportsData[$i]["start_datetime"]->format("Y-m-d"));
          $date_sta_relayLog = date('Y-m-d', strtotime('-1 day', $date1fin))." 06:00:00";
    
        }else{

          $date_sta_relayLog = $DailyReportsData[$i]["start_datetime"]->format("Y-m-d")." 06:00:00";
          $date1fin = strtotime($DailyReportsData[$i]["start_datetime"]->format("Y-m-d"));
          $date_fin_relayLog = date('Y-m-d', strtotime('+1 day', $date1fin))." 05:59:59";
  
        }

        $RelayLogsData = $this->RelayLogs->find()
        ->where([
          'datetime >=' => $date_sta_relayLog,
          'datetime <' => $date_fin_relayLog,
          'delete_flag' => 0,
          'factory_code' => $factory_id,
          'machine_num' => $DailyReportsData[$i]["machine_num"]
          ])
        ->order(["datetime"=>"ASC"])->toArray();

        if(isset($RelayLogsData[0])){
          $relay_start_datetime = $RelayLogsData[0]["datetime"]->format("Y-m-d H:i:s");
          $relay_finish_datetime = $RelayLogsData[count($RelayLogsData)-1]["datetime"]->format("Y-m-d H:i:s");
        }else{
          $relay_start_datetime = "";
          $relay_finish_datetime = "";
        }

        $riron_amount = $DailyReportsData[$i]["amount"] * ($DailyReportsData[$i]["total_loss_weight"]
         + $DailyReportsData[$i]["sum_weight"]) / $DailyReportsData[$i]["sum_weight"];
        $riron_amount = sprintf("%.1f", $riron_amount);

        $count = count(array_keys($arrline_shiyou, $DailyReportsData[$i]["machine_num"])) + 1;
        $countproduct_code_ini = count(array_keys($arrproduct_code_ini_machine_num_datetime,
         substr($DailyReportsData[$i]["product"]["product_code"], 0, 11)."_".$DailyReportsData[$i]["machine_num"]
         ."_".$DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"))) + 1;

        $arrline_shiyou[] = $DailyReportsData[$i]["machine_num"];

        $arrproduct_code_ini_machine_num_datetime[] = substr($DailyReportsData[$i]["product"]["product_code"], 0, 11)
        ."_".$DailyReportsData[$i]["machine_num"]."_".$DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s");

        //開始中間終了ロスの取得
        $InspectionDataResultParentDatas = $this->InspectionDataResultParents->find()
        ->contain(['ProductConditionParents', 'Products'])
        ->where([
        'machine_num' => $DailyReportsData[$i]["machine_num"],
        'product_code like' => substr($DailyReportsData[$i]["product"]["product_code"], 0, 11).'%',
        'InspectionDataResultParents.delete_flag' => 0,
        'datetime >=' => $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"),
        'datetime <=' => $DailyReportsData[$i]["finish_datetime"]->format("Y-m-d H:i:s")
        ])
        ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();

        $loss_sta = 0;
        $loss_mid = 0;
        $loss_fin = 0;
        for($j=0; $j<count($InspectionDataResultParentDatas); $j++){

          if($j == 0){
            $loss_sta = $InspectionDataResultParentDatas[$j]["loss_amount"];
          }elseif($j == count($InspectionDataResultParentDatas) - 1){
            $loss_fin = $InspectionDataResultParentDatas[$j]["loss_amount"];
          }else{
            $loss_mid = $loss_mid + (float)$InspectionDataResultParentDatas[$j]["loss_amount"];
          }

        }

        date_default_timezone_set('Asia/Tokyo');
        $from = strtotime($relay_start_datetime);
        $to = strtotime($relay_finish_datetime);
        $relay_time = gmdate("H:i:s", $to - $from);//時間の差をフォーマット

        if($tyuukann_flag == 0){//中間ではない時

          $arrDaily_report[] = [
            "machine_num" => $DailyReportsData[$i]["machine_num"],
            "start_datetime" => $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"),
            "relay_start_datetime" => $relay_start_datetime,
            "finish_datetime" => $DailyReportsData[$i]["finish_datetime"]->format("Y-m-d H:i:s"),
            "relay_finish_datetime" => $relay_finish_datetime,
            "relay_time" => $relay_time,
            "product_code" => $DailyReportsData[$i]["product"]["product_code"],
            "product_code_ini" => substr($DailyReportsData[$i]["product"]["product_code"], 0, 11),
            "name" => $DailyReportsData[$i]["product"]["name"],
            "length" => $DailyReportsData[$i]["product"]["length"],
            "amount" => $DailyReportsData[$i]["amount"],
            "loss_sta" => $loss_sta,
            "loss_mid" => $loss_mid,
            "loss_fin" => $loss_fin,
            "loss_total" => $loss_sta + $loss_mid + $loss_fin,
            "sum_weight" => sprintf("%.1f", $DailyReportsData[$i]["sum_weight"]),
            "count" => $count,
            "countproduct_code_ini" => $countproduct_code_ini,
          ];

        }elseif($loss_mid > 0){//中間のみの時

          $arrDaily_report[] = [
            "machine_num" => $DailyReportsData[$i]["machine_num"],
            "start_datetime" => $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"),
            "relay_start_datetime" => $relay_start_datetime,
            "finish_datetime" => $DailyReportsData[$i]["finish_datetime"]->format("Y-m-d H:i:s"),
            "relay_finish_datetime" => $relay_finish_datetime,
            "relay_time" => $relay_time,
            "product_code" => $DailyReportsData[$i]["product"]["product_code"],
            "product_code_ini" => substr($DailyReportsData[$i]["product"]["product_code"], 0, 11),
            "name" => $DailyReportsData[$i]["product"]["name"],
            "length" => $DailyReportsData[$i]["product"]["length"],
            "amount" => $DailyReportsData[$i]["amount"],
            "loss_sta" => $loss_sta,
            "loss_mid" => $loss_mid,
            "loss_fin" => $loss_fin,
            "loss_total" => $loss_sta + $loss_mid + $loss_fin,
            "sum_weight" => sprintf("%.1f", $DailyReportsData[$i]["sum_weight"]),
            "count" => $count,
            "countproduct_code_ini" => $countproduct_code_ini,
          ];

        }

      }

      $arrline_shiyou = array_unique($arrline_shiyou);
      $arrline_shiyou = array_values($arrline_shiyou);

      $arrlines_fushiyous = array();
      if($tyuukann_flag == 0){

        $arrline_fushiyou_moto = array_diff($arrlines_all, $arrline_shiyou);
        $arrline_fushiyou_moto = array_values($arrline_fushiyou_moto);
  
        for($i=0; $i<count($arrline_fushiyou_moto); $i++){
  
          $arrlines_fushiyous[] = [
            "machine_num" => $arrline_fushiyou_moto[$i],
            "start_datetime" => "",
            "finish_datetime" => "",
            "relay_start_datetime" => "",
            "relay_finish_datetime" => "",
            "relay_time" => "",
            "product_code" => "",
            "product_code_ini" => "",
            "name" => "",
            "length" => "",
            "amount" => "",
            "loss_sta" => "",
            "loss_mid" => "",
            "loss_fin" => "",
            "loss_total" => "",
            "sum_weight" => "",
            "count" => 1,
            "countproduct_code_ini" => 1,
          ];
  
        }
    
      }

    $arrAll = array_merge($arrDaily_report, $arrlines_fushiyous);

      foreach( $arrAll as $key => $row ) {
        $machine_num_array[$key] = $row["machine_num"];
        $count_array[$key] = $row["count"];
        $countproduct_code_ini_array[$key] = $row["countproduct_code_ini"];
      }

      array_multisort( $machine_num_array,
      $count_array, SORT_ASC, SORT_NUMERIC,
      $countproduct_code_ini_array, SORT_ASC,
      $arrAll );

      $this->set('arrAll', $arrAll);
/*
      echo "<pre>";
      print_r($arrAll);
      echo "</pre>";
*/
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
      for($i=0; $i<count($arrproduct_code_ini_machine_num_datetime); $i++){
        $arrCountProducts[$arrproduct_code_ini_machine_num_datetime[$i]] = count(array_keys($arrproduct_code_ini_machine_num_datetime, $arrproduct_code_ini_machine_num_datetime[$i]));
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
      $num_max = $data["num_max"];
      $this->set('num_max', $num_max);
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      if(!isset($data["date_sta"])){//ログインしなおして入ってきたとき

        return $this->redirect(['action' => 'yobidashidate']);

      }

      if(isset($data["kensahyou"])){

        return $this->redirect(['controller' => 'Kensahyousokuteidatas', 'action' => 'kensakuhyouji',
        's' => substr($data["date_sta"], 0, 10)."_".$data["machine_num"]."_".$data["product_code"]]);

      }elseif(isset($data["ichiran"])){
       
        return $this->redirect(['action' => 'yobidashidate']);

      }elseif(isset($data["checkbutton"]) || isset($data["tyuukann"])){
       
        $num_max = $data["num_max"];
        $factory_id = $data["factory_id"];
        $date_sta = $data["date_sta"];
        $date_fin = $data["date_fin"];
        $date_fin_hyouji = $data["date_fin_hyouji"];

        if(isset($data["checkbutton"])){
          $button_name = "checkbutton";
        }else{
          $button_name = "tyuukann";
        }
        return $this->redirect(['action' => 'view',
        's' => ['button_name' => $button_name, 'num_max' => $num_max, 'factory_id' => $factory_id,
         'date_fin' => $date_fin,'date_sta' => $date_sta,
         'date_fin_hyouji' => $date_fin_hyouji]]);

      }else{

      $arrsyousai = array_keys($data, '詳細');

      if(isset($arrsyousai[0])){
        $arrmachine_products_date = $arrsyousai[0];
        $machine_products_date = explode("_",$arrmachine_products_date);
        $machine_num = $machine_products_date[0];
        $this->set('machine_num', $machine_num);
        $product_code = $machine_products_date[1];
        $this->set('product_code', $product_code);
  
        if((int)substr($machine_products_date[3], 0, 2) < 6){//前日の検査

          $date_fin = $machine_products_date[2]." 05:59:59";
          $this->set('date_fin', $date_fin);
          $date1fin = strtotime($machine_products_date[2]);
          $date_sta = date('Y-m-d', strtotime('-1 day', $date1fin))." 06:00:00";
          $this->set('date_sta', $date_sta);
    
        }else{

          $date_sta = $machine_products_date[2]." 06:00:00";
          $this->set('date_sta', $date_sta);
          $date1fin = strtotime($machine_products_date[2]);
          $date_fin = date('Y-m-d', strtotime('+1 day', $date1fin))." 05:59:59";
          $this->set('date_fin', $date_fin);
  
        }

        for($i=0; $i<=$data["num_max"]; $i++){
          if($data["machine_num".$i] == $machine_num && $data["product_code".$i] == $product_code){
            $target_num = $i;
            break;
          }
        }

        for($i=0; $i<=$data["num_max"]; $i++){
          if(isset($data["machine_num".$i])){
            $num_max = $i;
          }
        }

        $this->set('num_max', $num_max);
        $this->set('target_num', $target_num);
  
      }else{

        $date_sta = $data["date_sta"];
        $this->set('date_sta', $date_sta);
        $date_fin = $data["date_fin"];
        $this->set('date_fin', $date_fin);

        $machine_num = $data["machine_num"];
        $this->set('machine_num', $machine_num);
        $product_code = $data["product_code"];
        $this->set('product_code', $product_code);

        if(isset($data["mae"])){
  
          $target_num = $data["target_num"];
          for($i=0; $i<=$data["num_max"]; $i++){

            if($target_num > 0){
              $target_num = $target_num - 1;
            }else{
              $target_num = $data["num_max"];
            }

            if(substr($data["start_datetime".$data["target_num"]], 0, 11) != substr($data["start_datetime".$target_num], 0, 11) 
            && $data["product_code".$target_num] != "-"){

              $machine_num = $data["machine_num".$target_num];
              $this->set('machine_num', $machine_num);
              $product_code = $data["product_code".$target_num];
              $this->set('product_code', $product_code);
  
              if((int)substr($data["start_datetime".$target_num], 11, 2) < 6){//前日の検査

                $date_fin = substr($data["start_datetime".$target_num], 0, 10)." 05:59:59";
                $this->set('date_fin', $date_fin);
                $date1fin = strtotime(substr($data["start_datetime".$target_num], 0, 10));
                $date_sta = date('Y-m-d', strtotime('-1 day', $date1fin))." 06:00:00";
                $this->set('date_sta', $date_sta);
          
              }else{
      
                $date_sta = substr($data["start_datetime".$target_num], 0, 10)." 06:00:00";
                $this->set('date_sta', $date_sta);
                $date1fin = strtotime(substr($data["start_datetime".$target_num], 0, 10));
                $date_fin = date('Y-m-d', strtotime('+1 day', $date1fin))." 05:59:59";
                $this->set('date_fin', $date_fin);
        
              }
      
              break;
            }
  
          }

          $this->set('target_num', $target_num);

        }elseif(isset($data["tugi"])){

          $date_sta = $data["date_sta"];
          $this->set('date_sta', $date_sta);
          $date_fin = $data["date_fin"];
          $this->set('date_fin', $date_fin);
  
          $target_num = $data["target_num"]-1;
          for($i=0; $i<=$data["num_max"]; $i++){

            if($target_num < $data["num_max"]){
              $target_num = $target_num + 1;
            }else{
              $target_num = 0;
            }

            if(substr($data["start_datetime".$data["target_num"]], 0, 11) != substr($data["start_datetime".$target_num], 0, 11) 
            && $data["product_code".$target_num] != "-"){

              $machine_num = $data["machine_num".$target_num];
              $this->set('machine_num', $machine_num);
              $product_code = $data["product_code".$target_num];
              $this->set('product_code', $product_code);
  
              if((int)substr($data["start_datetime".$target_num], 11, 2) < 6){//前日の検査

                $date_fin = substr($data["start_datetime".$target_num], 0, 10)." 05:59:59";
                $this->set('date_fin', $date_fin);
                $date1fin = strtotime(substr($data["start_datetime".$target_num], 0, 10));
                $date_sta = date('Y-m-d', strtotime('-1 day', $date1fin))." 06:00:00";
                $this->set('date_sta', $date_sta);
          
              }else{
      
                $date_sta = substr($data["start_datetime".$target_num], 0, 10)." 06:00:00";
                $this->set('date_sta', $date_sta);
                $date1fin = strtotime(substr($data["start_datetime".$target_num], 0, 10));
                $date_fin = date('Y-m-d', strtotime('+1 day', $date1fin))." 05:59:59";
                $this->set('date_fin', $date_fin);
        
              }
      
              break;
            }
  
          }

          $this->set('target_num', $target_num);
    
        }elseif(isset($data["shotdata"])){//ショットデータ出力
       
          $machine_num = $data["machine_num"];
          $this->set('machine_num', $machine_num);
          $product_code = $data["product_code"];
          $this->set('product_code', $product_code);
          $target_num = $data["num_max"];
          $this->set('target_num', $target_num);
  
          $date_sta = $data["date_sta"];
          $date_fin = $data["date_fin"];
          $this->set('date_sta', $date_sta);
          $this->set('date_fin', $date_fin);
        
          $machine_sta_fin = $machine_num."_".$date_sta."_".$date_fin;
          $kadouprograms = new kadouprogram();
          $shotdatacsv = $kadouprograms->shotdatacsv($machine_sta_fin);
      
        }

      }

      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);
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
      ->contain(['ProductConditionParents', 'Products', 'Staffs'])
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

    $machine_sta_fin = $machine_num."_".$date_sta."_".$date_fin;
    $kadouprograms = new kadouprogram();
    $arrRelayLogs = $kadouprograms->yobidashirelaylogs($machine_sta_fin);
    $this->set('arrRelayLogs', $arrRelayLogs);

    echo "<pre>";
    print_r("");
    echo "</pre>";

    }

}
