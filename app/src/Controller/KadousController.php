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

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }

      $dateYMD = date('Y-m-d');
      $dateYMD1 = strtotime($dateYMD);
      $dayyey = date('Y', strtotime('-1 day', $dateYMD1));
      $arrYears = array();
      for ($k=0; $k<=5; $k++){
        $year = $dayyey - $k;
        $arrYears[$year]=$year;
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

      $arrYearsfin = array();
      $arrYearsfin["-"] ="-";
      for ($k=0; $k<=5; $k++){
        $year = $dayyey - $k;
        $arrYearsfin[$year]=$year;
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

      $Linenames = $this->Linenames->find()
      ->where(['delete_flag' => 0, 'factory_id' => 1])->toArray();

      $arrGouki = array();
      $arrGouki["-"] = "選択なし";
      for($j=0; $j<count($Linenames); $j++){
        $array = array($Linenames[$j]["machine_num"] => $Linenames[$j]["name"]);
        $arrGouki = $arrGouki + $array;
      }
      $this->set('arrGouki', $arrGouki);

      $Product_name_list = $this->Products->find()
      ->where(['status_kensahyou' => 0, 'delete_flag' => 0])->toArray();

      $arrProduct_name_list = array();
      for($j=0; $j<count($Product_name_list); $j++){
        array_push($arrProduct_name_list,$Product_name_list[$j]["name"]);
      }
      $this->set('arrProduct_name_list', $arrProduct_name_list);
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
        $product_name = $Data["product_name"];
        $machine_num = $Data["machine_num"];
        $this->set('machine_num', $machine_num);
        $this->set('product_name', $product_name);
        $this->set('date_sta', $date_sta);
        $this->set('date_fin', $date_fin);
        $this->set('date_fin_hyouji', $date_fin_hyouji);

        if($Data["button_name"] == "tyuukann"){
          $tyuukann_flag = 1;
        }

      }else{

        $data = $this->request->getData();
        /*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
  */
        $Products = $this->Products->find()
        ->where(['name' => $data["product_name"], 'delete_flag' => 0])->toArray();
        $product_name = $data["product_name"];
        $this->set('product_name', $product_name);
        $machine_num = $data["machine_num"];
        $this->set('machine_num', $machine_num);

        if(strlen($data["product_name"]) > 0 && !isset($Products[0])){
        
          return $this->redirect(['action' => 'yobidashidate',
          's' => ['mess' => "入力された製品名は登録されていません。"]]);
  
        }
        $factory_id = $data["factory_id"];
        $dateselect = $data["date_sta_year"]."-".$data["date_sta_month"]."-".$data["date_sta_date"];

        if($data["date_sta_year_fin"] != "-" && $data["date_sta_month_fin"] != "-" && $data["date_sta_date_fin"] != "-"){
  
          $date1 = strtotime($dateselect);
          $date_sta = $dateselect." 06:00:00";
  
          $dateselectfin = $data["date_sta_year_fin"]."-".$data["date_sta_month_fin"]."-".$data["date_sta_date_fin"];
          $date1fin = strtotime($dateselectfin);
  
          $date_fin = date('Y-m-d', strtotime('+1 day', $date1fin))." 06:00:00";
          $date_fin_hyouji = date('Y-m-d', strtotime('+1 day', $date1fin))." 05:59:59";
          $this->set('date_sta', $date_sta);
          $this->set('date_fin', $date_fin);
          $this->set('date_fin_hyouji', $date_fin_hyouji);

        }else{

          $date1 = strtotime($dateselect);
          $date_sta = $dateselect." 06:00:00";
          $date_fin = date('Y-m-d', strtotime('+1 day', $date1))." 06:00:00";
          $date_fin_hyouji = date('Y-m-d', strtotime('+1 day', $date1))." 05:59:59";
          $this->set('date_sta', $date_sta);
          $this->set('date_fin', $date_fin);
          $this->set('date_fin_hyouji', $date_fin_hyouji);

        }
  
      }

      $this->set('factory_id', $factory_id);

      $Linenames = $this->Linenames->find()
      ->where(['delete_flag' => 0, 'factory_id' => $factory_id])->toArray();

      if($machine_num != "-"){
        $DailyReportsData = $this->DailyReports->find()
        ->contain(['Products'])
        ->where([
        'Products.name like' => '%'.$product_name.'%',
        'start_datetime >=' => $date_sta, 
        'start_datetime <' => $date_fin,
        'DailyReports.delete_flag' => 0, 
        'machine_num' => $machine_num,
        'factory_id' => $factory_id
        ])
        ->order(["start_datetime"=>"ASC"])->toArray();
      }else{
        $DailyReportsData = $this->DailyReports->find()
        ->contain(['Products'])
        ->where([
        'Products.name like' => '%'.$product_name.'%',
        'start_datetime >=' => $date_sta, 
        'start_datetime <' => $date_fin,
        'DailyReports.delete_flag' => 0, 
        'factory_id' => $factory_id
        ])
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

        $RelayLogsDataSta = $this->RelayLogs->find()
        ->where([
          'machine_relay_id' => 5,
          'status' => 1,
          'datetime <' => $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"),
          'delete_flag' => 0,
          'factory_code' => $factory_id,
          'machine_num' => $DailyReportsData[$i]["machine_num"]
          ])
          ->order(["datetime"=>"DESC"])->limit('1')->toArray();

        if(isset($RelayLogsDataSta[0])){
          $relay_start_datetime = $RelayLogsDataSta[0]["datetime"]->format("Y-m-d H:i:s");
          $relay_start_datetime_hyouji = $RelayLogsDataSta[0]["datetime"]->format("H:i");
        }else{
          $relay_start_datetime = "";
          $relay_start_datetime_hyouji = "";
        }

        $RelayLogsDataFin = $this->RelayLogs->find()
        ->where([
          'machine_relay_id' => 5,
          'status' => 0,
          'datetime >' => $DailyReportsData[$i]["finish_datetime"]->format("Y-m-d H:i:s"),
          'delete_flag' => 0,
          'factory_code' => $factory_id,
          'machine_num' => $DailyReportsData[$i]["machine_num"]
          ])
          ->order(["datetime"=>"ASC"])->limit('1')->toArray();
  
        if(isset($RelayLogsDataFin[0])){
          $relay_finish_datetime = $RelayLogsDataFin[0]["datetime"]->format("Y-m-d H:i:s");
          $relay_finish_datetime_hyouji = $RelayLogsDataFin[0]["datetime"]->format("H:i");
        }else{
          $relay_finish_datetime_hyouji = "";
          $relay_finish_datetime = "";
        }

        $riron_amount = $DailyReportsData[$i]["amount"] * ($DailyReportsData[$i]["total_loss_weight"]
         + $DailyReportsData[$i]["sum_weight"]) / $DailyReportsData[$i]["sum_weight"];
        $riron_amount = sprintf("%.1f", $riron_amount);

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

        $DailyReportssum_weight = $this->DailyReports->find()//総重量の取得
        ->contain(['Products'])
        ->where(['start_datetime' => $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"), 
        'finish_datetime' => $DailyReportsData[$i]["finish_datetime"]->format("Y-m-d H:i:s"),
        'product_code like' => substr($DailyReportsData[$i]["product"]["product_code"], 0, 11).'%',
        'DailyReports.delete_flag' => 0, 'factory_id' => $factory_id])
        ->order(["start_datetime"=>"ASC"])->toArray();
  
        $sum_weight = 0;
        for($j=0; $j<count($DailyReportssum_weight); $j++){
          $sum_weight = $sum_weight + $DailyReportssum_weight[$j]["sum_weight"];
        }

        if(strlen($relay_start_datetime) > 0 && strlen($relay_finish_datetime) > 0){
          date_default_timezone_set('Asia/Tokyo');
          $from = strtotime($relay_start_datetime);
          $to = strtotime($relay_finish_datetime);
          $relay_time = gmdate("G時間i分", $to - $from);//時間の差をフォーマット
        }else{
          $relay_time = "";
        }

        $count_check = count($arrDaily_report) - 1;

        $date = $DailyReportsData[$i]["start_datetime"]->format("Y-m-d");
        if($tyuukann_flag == 0){//中間ではない時
          
          $tasseiritsu = sprintf("%.1f", $sum_weight) * 100 / (sprintf("%.1f", $sum_weight) + $loss_sta + $loss_mid + $loss_fin);
          $tasseiritsu = sprintf("%.1f", $tasseiritsu);
    
          $lossritsu = ($loss_sta + $loss_mid + $loss_fin) * 100 / (sprintf("%.1f", $sum_weight) + $loss_sta + $loss_mid + $loss_fin);
          $lossritsu = sprintf("%.1f", $lossritsu);
  
          if($count_check == -1){

            $arrDaily_report[] = [
              "machine_num" => $DailyReportsData[$i]["machine_num"],
              "date" => $date,
              "start_datetime_check" => $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"),
              "start_datetime" => $DailyReportsData[$i]["start_datetime"]->format("H:i"),
              "relay_start_datetime" => $relay_start_datetime_hyouji,
              "finish_datetime" => $DailyReportsData[$i]["finish_datetime"]->format("H:i"),
              "relay_finish_datetime" => $relay_finish_datetime_hyouji,
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
              "tasseiritsu" => $tasseiritsu,
              "lossritsu" => $lossritsu,
            ];
          }elseif($count_check >= 0 && ($arrDaily_report[$count_check]["start_datetime_check"] != $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s") 
          || $arrDaily_report[$count_check]["product_code_ini"] != substr($DailyReportsData[$i]["product"]["product_code"], 0, 11))){

            $arrDaily_report[] = [
              "machine_num" => $DailyReportsData[$i]["machine_num"],
              "date" => $date,
              "start_datetime_check" => $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"),
              "start_datetime" => $DailyReportsData[$i]["start_datetime"]->format("H:i"),
              "relay_start_datetime" => $relay_start_datetime_hyouji,
              "finish_datetime" => $DailyReportsData[$i]["finish_datetime"]->format("H:i"),
              "relay_finish_datetime" => $relay_finish_datetime_hyouji,
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
              "tasseiritsu" => $tasseiritsu,
              "lossritsu" => $lossritsu,
            ];
          }

        }elseif($loss_mid > 0){//中間のみの時

          $tasseiritsu = sprintf("%.1f", $DailyReportsData[$i]["sum_weight"]) * 100 / (sprintf("%.1f", $DailyReportsData[$i]["sum_weight"]) + $loss_sta + $loss_mid + $loss_fin);
          $tasseiritsu = sprintf("%.1f", $tasseiritsu);
    
          $lossritsu = ($loss_sta + $loss_mid + $loss_fin) * 100 / (sprintf("%.1f", $DailyReportsData[$i]["sum_weight"]) + $loss_sta + $loss_mid + $loss_fin);
          $lossritsu = sprintf("%.1f", $lossritsu);

          if($count_check == -1){
            $arrDaily_report[] = [
              "machine_num" => $DailyReportsData[$i]["machine_num"],
              "date" => $date,
              "start_datetime_check" => $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"),
              "start_datetime" => $DailyReportsData[$i]["start_datetime"]->format("H:i"),
              "relay_start_datetime" => $relay_start_datetime_hyouji,
              "finish_datetime" => $DailyReportsData[$i]["finish_datetime"]->format("H:i"),
              "relay_finish_datetime" => $relay_finish_datetime_hyouji,
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
              "tasseiritsu" => $tasseiritsu,
              "lossritsu" => $lossritsu,
            ];
          }elseif($count_check >= 0 && ($arrDaily_report[$count_check]["start_datetime_check"] != $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s") 
          || $arrDaily_report[$count_check]["product_code_ini"] != substr($DailyReportsData[$i]["product"]["product_code"], 0, 11))){
            $arrDaily_report[] = [
              "machine_num" => $DailyReportsData[$i]["machine_num"],
              "date" => $date,
              "start_datetime_check" => $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"),
              "start_datetime" => $DailyReportsData[$i]["start_datetime"]->format("H:i"),
              "relay_start_datetime" => $relay_start_datetime_hyouji,
              "finish_datetime" => $DailyReportsData[$i]["finish_datetime"]->format("H:i"),
              "relay_finish_datetime" => $relay_finish_datetime_hyouji,
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
              "tasseiritsu" => $tasseiritsu,
              "lossritsu" => $lossritsu,
            ];
          }

        }

      }

      $arrAll = $arrDaily_report;

      $machine_num_array = array();
      $start_datetime_array = array();
      foreach($arrAll as $key => $row ) {
        $machine_num_array[$key] = $row["machine_num"];
        $start_datetime_array[$key] = $row["start_datetime_check"];
      }

      if(count($start_datetime_array) > 0){
        array_multisort($machine_num_array, SORT_ASC,
        array_map( "strtotime", $start_datetime_array ), SORT_ASC,
        $arrAll);
      }

      $this->set('arrAll', $arrAll);
/*
      echo "<pre>";
      print_r(count($arrAll));
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
        $product_name = $data["product_name"];
        $machine_num = $data["machine_num"];

        if(isset($data["checkbutton"])){
          $button_name = "checkbutton";
        }else{
          $button_name = "tyuukann";
        }
        return $this->redirect(['action' => 'view',
        's' => ['button_name' => $button_name, 'num_max' => $num_max, 'factory_id' => $factory_id,
         'date_fin' => $date_fin,'date_sta' => $date_sta,'product_name' => $product_name,'machine_num' => $machine_num,
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
        $start_datetime = $machine_products_date[2]." ".$machine_products_date[3];

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
          if($data["machine_num".$i] == $machine_num
           && $data["product_code".$i] == $product_code
           && $data["start_datetime".$i] == $start_datetime){
            $target_num = $i;
            break;
          }
        }

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
  
          $target_num = $data["target_num"] - 1;
          if($target_num < 0){
            $target_num = $data["num_max"] - 1;
          }
    
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

          $this->set('target_num', $target_num);

        }elseif(isset($data["tugi"])){
    
          $date_sta = $data["date_sta"];
          $this->set('date_sta', $date_sta);
          $date_fin = $data["date_fin"];
          $this->set('date_fin', $date_fin);

          $target_num = $data["target_num"] + 1;
          if($target_num >= $data["num_max"]){
            $target_num = 0;
          }

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

          $this->set('target_num', $target_num);
    
        }elseif(isset($data["shotdata"])){//ショットデータ出力
       
          $machine_num = $data["machine_num"];
          $this->set('machine_num', $machine_num);
          $product_code = $data["product_code"];
          $this->set('product_code', $product_code);
          $num_max = $data["num_max"];
          $this->set('num_max', $num_max);
  
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
        $lossritsu = $DailyReportsData[$i]["total_loss_weight"] * 100 / ($DailyReportsData[$i]["sum_weight"] + $DailyReportsData[$i]["total_loss_weight"]);
        $lossritsu = sprintf("%.1f", $lossritsu);

        $arrProdcts[] = [
          "product_code" => $DailyReportsData[$i]["product"]["product_code"],
          "name" => $DailyReportsData[$i]["product"]["name"],
          "length" => $DailyReportsData[$i]["product"]["length"],
          "amount" => $DailyReportsData[$i]["amount"],
          "sum_weight" => $DailyReportsData[$i]["sum_weight"],
          "total_loss_weight" => $DailyReportsData[$i]["total_loss_weight"],
          "lossritsu" => $lossritsu,
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
