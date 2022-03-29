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
   $this->InspectionDataConditonChildren = TableRegistry::get('InspectionDataConditonChildren');

   if(!isset($_SESSION)){//フォーム再送信の確認対策
    session_start();
  }
  header('Expires:');
  header('Cache-Control:');
  header('Pragma:');

  }
  
    public function menu()
    {
      //大東工場でない人の場合はトップに戻す（日報画面は大東工場のみ見られる）

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
        $button_name = $Data["button_name"];
        $this->set('button_name', $button_name);

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
        if(!isset($data["product_name"])){
          return $this->redirect(['action' => 'yobidashidate',
          's' => ['mess' => ""]]);
        }

        $Products = $this->Products->find()
        ->where(['name' => $data["product_name"], 'delete_flag' => 0])->toArray();
        $product_name = $data["product_name"];
        $this->set('product_name', $product_name);
        $machine_num = $data["machine_num"];
        $this->set('machine_num', $machine_num);
        $button_name = "-";
        $this->set('button_name', $button_name);

        if(strlen($data["product_name"]) > 0 && !isset($Products[0])){
        
          return $this->redirect(['action' => 'yobidashidate',
          's' => ['mess' => "入力された製品名は登録されていません。"]]);
  
        }
        $factory_id = $data["factory_id"];
        $dateselect = $data["date_sta_year"]."-".$data["date_sta_month"]."-".$data["date_sta_date"];

        $date1 = strtotime($dateselect);
        $date_sta = $dateselect." 00:00:00";

        $dateselectfin = $data["date_sta_year_fin"]."-".$data["date_sta_month_fin"]."-".$data["date_sta_date_fin"];
        $date1fin = strtotime($dateselectfin);

        $date_fin = date('Y-m-d', strtotime('+1 day', $date1fin))." 00:00:00";
        $date_fin_hyouji = $dateselectfin." 23:59:59";
        $this->set('date_sta', $date_sta);
        $this->set('date_fin', $date_fin);
        $this->set('date_fin_hyouji', $date_fin_hyouji);

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
      $arrCsvs = array();
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

        if($DailyReportsData[$i]["sum_weight"] > 0){
          $riron_amount = $DailyReportsData[$i]["amount"] * ($DailyReportsData[$i]["total_loss_weight"]
          + $DailyReportsData[$i]["sum_weight"]) / $DailyReportsData[$i]["sum_weight"];
         $riron_amount = sprintf("%.1f", $riron_amount);
         }else{
          $riron_amount = 0;
        }

        $arrproduct_code_ini_machine_num_datetime[] = substr($DailyReportsData[$i]["product"]["product_code"], 0, 11)
        ."_".$DailyReportsData[$i]["machine_num"]."_".$DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s");

        //開始中間終了ロスの取得
        $InspectionDataResultParentDatas = $this->InspectionDataResultParents->find()
        ->contain(['ProductConditionParents', 'Products', 'Staffs'])
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
        $staff_sta_loss = "";
        $staff_fin_loss = "";
        $staff_mid_loss = "";
        $bik_sta = "";
        $bik_fin = "";
        $bik_mid = "";
        for($j=0; $j<count($InspectionDataResultParentDatas); $j++){

          if($j == 0){
            $loss_sta = $InspectionDataResultParentDatas[$j]["loss_amount"];
            $staff_sta_loss = $InspectionDataResultParentDatas[$j]["staff"]["name"];
            if(strlen($InspectionDataResultParentDatas[$j]["bik"]) > 0){
              $bik_sta = $InspectionDataResultParentDatas[$j]["bik"];
            }
          }elseif($j == count($InspectionDataResultParentDatas) - 1){
            $loss_fin = $InspectionDataResultParentDatas[$j]["loss_amount"];
            $staff_fin_loss = $InspectionDataResultParentDatas[$j]["staff"]["name"];
            if(strlen($InspectionDataResultParentDatas[$j]["bik"]) > 0){
              $bik_fin = $InspectionDataResultParentDatas[$j]["bik"];
            }
          }else{
            $loss_mid = $loss_mid + (float)$InspectionDataResultParentDatas[$j]["loss_amount"];
            $staff_mid_loss = $InspectionDataResultParentDatas[$j]["staff"]["name"];
            if(strlen($InspectionDataResultParentDatas[$j]["bik"]) > 0){
              $bik_mid = $bik_mid.$InspectionDataResultParentDatas[$j]["bik"]."(".$InspectionDataResultParentDatas[$j]["staff"]["name"].")"." ";
            }
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

        //ロス時間の取得
        $loss_time = "";
        $from1 = 0;
        $to1 = 0;
        $from2 = 0;
        $to2 = 0;
        if(strlen($relay_start_datetime) > 0){
          date_default_timezone_set('Asia/Tokyo');
          $from1 = strtotime($relay_start_datetime);
          $to1 = strtotime($DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"));
        }
        if(strlen($relay_finish_datetime) > 0){
          date_default_timezone_set('Asia/Tokyo');
          $from2 = strtotime($DailyReportsData[$i]["finish_datetime"]->format("Y-m-d H:i:s"));
          $to2 = strtotime($relay_finish_datetime);
        }
        if(($to1 - $from1 + $to2 - $from2) > 0){
          $loss_time = gmdate("G時間i分", $to1 - $from1 + $to2 - $from2);//時間の差をフォーマット
        }

        $date = $DailyReportsData[$i]["start_datetime"]->format("Y-m-d");
        if($tyuukann_flag == 0){//中間ではない時
          
          $tasseiritsu = sprintf("%.1f", $sum_weight) * 100 / (sprintf("%.1f", $sum_weight) + $loss_sta + $loss_mid + $loss_fin);
          $tasseiritsu = sprintf("%.1f", $tasseiritsu);
    
          $lossritsu = ($loss_sta + $loss_mid + $loss_fin) * 100 / (sprintf("%.1f", $sum_weight) + $loss_sta + $loss_mid + $loss_fin);
          $lossritsu = sprintf("%.1f", $lossritsu);
  
          if($count_check == -1){//最初は必ず配列に追加
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
              "loss_time" => $loss_time,
              "loss_sta" => $loss_sta,
              "loss_mid" => $loss_mid,
              "loss_fin" => $loss_fin,
              "loss_total" => $loss_sta + $loss_mid + $loss_fin,
              "sum_weight" => sprintf("%.1f", $DailyReportsData[$i]["sum_weight"]),
              "tasseiritsu" => $tasseiritsu,
              "lossritsu" => $lossritsu,
            ];
            
            $arrCsvs[] = [
              "start_datetime_check" => $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"),
              "machine_num" => $DailyReportsData[$i]["machine_num"],
              "date" => $date,
              "name" => $DailyReportsData[$i]["product"]["name"],
              "start_datetime" => $DailyReportsData[$i]["start_datetime"]->format("H:i"),
              "relay_start_datetime" => $relay_start_datetime_hyouji,
              "finish_datetime" => $DailyReportsData[$i]["finish_datetime"]->format("H:i"),
              "relay_finish_datetime" => $relay_finish_datetime_hyouji,
              "relay_time" => $relay_time,
              "loss_sta" => $loss_sta,
              "staff_sta_loss" => $staff_sta_loss,
              "bik_sta" => $bik_sta,
              "loss_mid" => $loss_mid,
              "staff_mid_loss" => $staff_mid_loss,
              "bik_mid" => $bik_mid,
              "loss_fin" => $loss_fin,
              "staff_fin_loss" => $staff_fin_loss,
              "bik_fin" => $bik_fin,
              "loss_time" => $loss_time,
              "tasseiritsu" => $tasseiritsu,
              "lossritsu" => $lossritsu,
            ];

            //１つ前と同一のデータであれば配列に追加しない
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
              "loss_time" => $loss_time,
              "loss_sta" => $loss_sta,
              "loss_mid" => $loss_mid,
              "loss_fin" => $loss_fin,
              "loss_total" => $loss_sta + $loss_mid + $loss_fin,
              "sum_weight" => sprintf("%.1f", $DailyReportsData[$i]["sum_weight"]),
              "tasseiritsu" => $tasseiritsu,
              "lossritsu" => $lossritsu,
            ];

            $arrCsvs[] = [
              "start_datetime_check" => $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"),
              "machine_num" => $DailyReportsData[$i]["machine_num"],
              "date" => $date,
              "name" => $DailyReportsData[$i]["product"]["name"],
              "start_datetime" => $DailyReportsData[$i]["start_datetime"]->format("H:i"),
              "relay_start_datetime" => $relay_start_datetime_hyouji,
              "finish_datetime" => $DailyReportsData[$i]["finish_datetime"]->format("H:i"),
              "relay_finish_datetime" => $relay_finish_datetime_hyouji,
              "relay_time" => $relay_time,
              "loss_sta" => $loss_sta,
              "staff_sta_loss" => $staff_sta_loss,
              "bik_sta" => $bik_sta,
              "loss_mid" => $loss_mid,
              "staff_mid_loss" => $staff_mid_loss,
              "bik_mid" => $bik_mid,
              "loss_fin" => $loss_fin,
              "staff_fin_loss" => $staff_fin_loss,
              "bik_fin" => $bik_fin,
              "loss_time" => $loss_time,
              "tasseiritsu" => $tasseiritsu,
              "lossritsu" => $lossritsu,
            ];

          }

        }elseif($loss_mid > 0){//中間のみの時

          $tasseiritsu = sprintf("%.1f", $DailyReportsData[$i]["sum_weight"]) * 100 / (sprintf("%.1f", $DailyReportsData[$i]["sum_weight"]) + $loss_sta + $loss_mid + $loss_fin);
          $tasseiritsu = sprintf("%.1f", $tasseiritsu);
    
          $lossritsu = ($loss_sta + $loss_mid + $loss_fin) * 100 / (sprintf("%.1f", $DailyReportsData[$i]["sum_weight"]) + $loss_sta + $loss_mid + $loss_fin);
          $lossritsu = sprintf("%.1f", $lossritsu);

          if($count_check == -1){//最初は必ず配列に追加
            
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
              "loss_time" => $loss_time,
              "loss_sta" => $loss_sta,
              "loss_mid" => $loss_mid,
              "loss_fin" => $loss_fin,
              "loss_total" => $loss_sta + $loss_mid + $loss_fin,
              "sum_weight" => sprintf("%.1f", $DailyReportsData[$i]["sum_weight"]),
              "tasseiritsu" => $tasseiritsu,
              "lossritsu" => $lossritsu,
            ];

            $arrCsvs[] = [
              "start_datetime_check" => $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"),
              "machine_num" => $DailyReportsData[$i]["machine_num"],
              "date" => $date,
              "name" => $DailyReportsData[$i]["product"]["name"],
              "start_datetime" => $DailyReportsData[$i]["start_datetime"]->format("H:i"),
              "relay_start_datetime" => $relay_start_datetime_hyouji,
              "finish_datetime" => $DailyReportsData[$i]["finish_datetime"]->format("H:i"),
              "relay_finish_datetime" => $relay_finish_datetime_hyouji,
              "relay_time" => $relay_time,
              "loss_sta" => $loss_sta,
              "staff_sta_loss" => $staff_sta_loss,
              "bik_sta" => $bik_sta,
              "loss_mid" => $loss_mid,
              "staff_mid_loss" => $staff_mid_loss,
              "bik_mid" => $bik_mid,
              "loss_fin" => $loss_fin,
              "staff_fin_loss" => $staff_fin_loss,
              "bik_fin" => $bik_fin,
              "loss_time" => $loss_time,
              "tasseiritsu" => $tasseiritsu,
              "lossritsu" => $lossritsu,
            ];

            //１つ前と同一のデータであれば配列に追加しない
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
              "loss_time" => $loss_time,
              "loss_sta" => $loss_sta,
              "loss_mid" => $loss_mid,
              "loss_fin" => $loss_fin,
              "loss_total" => $loss_sta + $loss_mid + $loss_fin,
              "sum_weight" => sprintf("%.1f", $DailyReportsData[$i]["sum_weight"]),
              "tasseiritsu" => $tasseiritsu,
              "lossritsu" => $lossritsu,
            ];

            $arrCsvs[] = [
              "start_datetime_check" => $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"),
              "machine_num" => $DailyReportsData[$i]["machine_num"],
              "date" => $date,
              "name" => $DailyReportsData[$i]["product"]["name"],
              "start_datetime" => $DailyReportsData[$i]["start_datetime"]->format("H:i"),
              "relay_start_datetime" => $relay_start_datetime_hyouji,
              "finish_datetime" => $DailyReportsData[$i]["finish_datetime"]->format("H:i"),
              "relay_finish_datetime" => $relay_finish_datetime_hyouji,
              "relay_time" => $relay_time,
              "loss_sta" => $loss_sta,
              "staff_sta_loss" => $staff_sta_loss,
              "bik_sta" => $bik_sta,
              "loss_mid" => $loss_mid,
              "staff_mid_loss" => $staff_mid_loss,
              "bik_mid" => $bik_mid,
              "loss_fin" => $loss_fin,
              "staff_fin_loss" => $staff_fin_loss,
              "bik_fin" => $bik_fin,
              "loss_time" => $loss_time,
              "tasseiritsu" => $tasseiritsu,
              "lossritsu" => $lossritsu,
            ];

          }

        }

      }

      $arrAll = $arrDaily_report;
/*
      //並べ替え元
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
*/

      //並べ替え
      $machine_num_array = array();
      $date_array = array();
      foreach($arrAll as $key => $row ) {
        $machine_num_array[$key] = $row["machine_num"];
        $date_array[$key] = $row["date"];
      }

      if(count($date_array) > 0){
        array_multisort(array_map("strtotime", $date_array), SORT_ASC,
        $machine_num_array, SORT_ASC,
        $arrAll);
      }
      $this->set('arrAll', $arrAll);

      $csv_machine_num_array = array();
      $csv_start_datetime_array = array();
      foreach($arrCsvs as $key => $row ) {
        $csv_machine_num_array[$key] = $row["machine_num"];
        $csv_start_datetime_array[$key] = $row["start_datetime_check"];
      }

      if(count($csv_start_datetime_array) > 0){
        array_multisort($csv_machine_num_array, SORT_ASC,
        array_map( "strtotime", $csv_start_datetime_array ), SORT_ASC,
        $arrCsvs);
      }

      for($i=0; $i<count($arrCsvs); $i++){
        unset($arrCsvs[$i]['start_datetime_check']);
      }
/*
      echo "<pre>";
      print_r($arrCsvs);
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

      if(isset($Data["csv"])){
  
        $arrCsvhead[0] = [
          "machine_num" => "成形機",
          "date" => "日付",
          "name" => "製品名",
          "start_datetime" => "生産開始時間",
          "relay_start_datetime" => "検査表開始時間",
          "finish_datetime" => "検査表終了時間",
          "relay_finish_datetime" => "生産終了時間",
          "relay_time" => "生産時間",
          "loss_sta" => "開始ロス（kg）",
          "staff_sta_loss" => "開始ロス報告者",
          "bik_sta" => "開始ロス備考",
          "loss_mid" => "中間ロス（kg）",
          "staff_mid_loss" => "中間ロス報告者",
          "bik_mid" => "中間ロス備考",
          "loss_fin" => "終了ロス（kg）",
          "staff_fin_loss" => "終了ロス報告者",
          "bik_fin" => "終了ロス備考",
          "loss_time" => "ロス時間	",
          "tasseiritsu" => "ロス率（％）",
          "lossritsu" => "達成率（％）",
        ];
        $arrCsvs = array_merge($arrCsvhead,$arrCsvs);

       //CSV形式で情報をファイルに出力のための準備
          $csvFileName = '/tmp/' . time() . rand() . '.csv';
          $res = fopen($csvFileName, 'w');
          if ($res === FALSE) {
            throw new Exception('ファイルの書き込みに失敗しました。');
          }

          foreach($arrCsvs as $dataInfo) {
            // 文字コード変換。エクセルで開けるようにする
            mb_convert_variables('SJIS', 'UTF-8', $dataInfo);
            fputcsv($res, $dataInfo);
          }
          fclose($res);

          header('Content-Type: application/octet-stream');

          $filename = "日報出力".substr($date_sta, 0, 10)."～".substr($date_fin_hyouji, 0, 10).".csv";
          header("Content-Disposition: attachment; filename=${filename}"); 
          header('Content-Transfer-Encoding: binary');
          header('Content-Length: ' . filesize($csvFileName));
          readfile($csvFileName);

          exit;//exitをいれておかないとhtmlのソースを含んだCSVファイルになってしまう

      }

      echo "<pre>";
      print_r("");
      echo "</pre>";

    }

    public function details()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
      if(!isset($data["num_max"])){//ログインしなおして入ってきたとき
        return $this->redirect(['action' => 'yobidashidate']);
      }
      $num_max = $data["num_max"];
      $this->set('num_max', $num_max);
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      if(isset($data["kensahyou"])){

        return $this->redirect(['controller' => 'Kensahyousokuteidatas', 'action' => 'kensakuhyouji',
        's' => substr($data["date_sta"], 0, 10)."_".$data["machine_num"]."_".$data["product_code"]]);

      }elseif(isset($data["ichiran"])){
       
        return $this->redirect(['action' => 'yobidashidate']);

      }elseif(isset($data["outputcsv"]) || isset($data["tyuukann"])){
       
        $num_max = $data["num_max"];
        $factory_id = $data["factory_id"];
        $date_sta = $data["date_sta"];
        $date_fin = $data["date_fin"];
        $date_fin_hyouji = $data["date_fin_hyouji"];
        $product_name = $data["product_name"];
        $machine_num = $data["machine_num"];

        if(isset($data["outputcsv"])){

          $button_name = $data["button_name"];
          return $this->redirect(['action' => 'view',
          's' => ['csv' => 1, 'button_name' => $button_name, 'num_max' => $num_max, 'factory_id' => $factory_id,
          'date_fin' => $date_fin,'date_sta' => $date_sta,'product_name' => $product_name,'machine_num' => $machine_num,
          'date_fin_hyouji' => $date_fin_hyouji]]);

        }else{

          $button_name = "tyuukann";

          return $this->redirect(['action' => 'view',
          's' => ['button_name' => $button_name, 'num_max' => $num_max, 'factory_id' => $factory_id,
          'date_fin' => $date_fin,'date_sta' => $date_sta,'product_name' => $product_name,'machine_num' => $machine_num,
          'date_fin_hyouji' => $date_fin_hyouji]]);
        }

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

        $date_sta = $machine_products_date[2]." 00:00:00";
        $this->set('date_sta', $date_sta);
        $date1fin = strtotime($machine_products_date[2]);
        $date_fin = date('Y-m-d', strtotime('+1 day', $date1fin))." 00:00:00";
        $this->set('date_fin', $date_fin);

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

          $date_sta = substr($data["start_datetime".$target_num], 0, 10)." 00:00:00";
          $this->set('date_sta', $date_sta);
          $date1fin = strtotime(substr($data["start_datetime".$target_num], 0, 10));
          $date_fin = date('Y-m-d', strtotime('+1 day', $date1fin))." 00:00:00";
          $this->set('date_fin', $date_fin);

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

          $date_sta = substr($data["start_datetime".$target_num], 0, 10)." 00:00:00";
          $this->set('date_sta', $date_sta);
          $date1fin = strtotime(substr($data["start_datetime".$target_num], 0, 10));
          $date_fin = date('Y-m-d', strtotime('+1 day', $date1fin))." 00:00:00";
          $this->set('date_fin', $date_fin);

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
      $date_fin_hyouji = substr($date_sta, 0, 10)." 23:59:59";
      $this->set('date_fin_hyouji', $date_fin_hyouji);

      $product_code_ini = substr($product_code, 0, 11);
      $DailyReportsData = $this->DailyReports->find()
      ->contain(['Products'])
      ->where(['product_code like' => $product_code_ini.'%', 
      'start_datetime >=' => $date_sta, 'start_datetime <' => $date_fin,
      'DailyReports.delete_flag' => 0, 'DailyReports.machine_num' => $machine_num,
      'factory_id' => $factory_id])
      ->order(["machine_num"=>"ASC"])->toArray();

      $start_datetime = $DailyReportsData[0]["start_datetime"]->format("Y-n-j G:i:s");
      $finish_datetime = $DailyReportsData[0]["finish_datetime"]->format("Y-n-j G:i:s");
      $this->set('start_datetime', $start_datetime);
      $this->set('finish_datetime', $finish_datetime);

      //データ取得リレーのON、OFF時間を取得（生産開始、終了）
      $RelayLogsDataSta = $this->RelayLogs->find()
      ->where([
        'machine_relay_id' => 5,
        'status' => 1,
        'datetime <' => $start_datetime,
        'delete_flag' => 0,
        'factory_code' => $factory_id,
        'machine_num' => $machine_num
        ])
        ->order(["datetime"=>"DESC"])->limit('1')->toArray();

      if(isset($RelayLogsDataSta[0])){
        $relay_start_datetime = $RelayLogsDataSta[0]["datetime"]->format("Y-n-j G:i:s");
      }else{
        $relay_start_datetime = "";
      }
      $this->set('relay_start_datetime', $relay_start_datetime);

      $RelayLogsDataFin = $this->RelayLogs->find()
      ->where([
        'machine_relay_id' => 5,
        'status' => 0,
        'datetime >' => $start_datetime,
        'delete_flag' => 0,
        'factory_code' => $factory_id,
        'machine_num' => $machine_num
        ])
        ->order(["datetime"=>"ASC"])->limit('1')->toArray();

      if(isset($RelayLogsDataFin[0])){
        $relay_finish_datetime = $RelayLogsDataFin[0]["datetime"]->format("Y-n-j G:i:s");
      }else{
        $relay_finish_datetime = "";
      }
      $this->set('relay_finish_datetime', $relay_finish_datetime);

      //ヒーターのON、OFF時間を取得
      $RelayLogsDataSta = $this->RelayLogs->find()
      ->where([
        'machine_relay_id' => 1,
        'status' => 1,
        'datetime <' => $start_datetime,
        'delete_flag' => 0,
        'factory_code' => $factory_id,
        'machine_num' => $machine_num
        ])
      ->order(["datetime"=>"DESC"])->limit('2')->toArray();

      //１秒差以内で時限リレーOFFがあれば無視する
      $RelayLogsDataCheck = $this->RelayLogs->find()
      ->where([
        'machine_relay_id' => 0,
        'status' => 0,
        'datetime >=' => date("Y-m-d H:i:s",strtotime($RelayLogsDataSta[0]["datetime"]->format("Y-n-j G:i:s") . "-1 second")),
        'datetime <=' => date("Y-m-d H:i:s",strtotime($RelayLogsDataSta[0]["datetime"]->format("Y-n-j G:i:s") . "+1 second")),
        'delete_flag' => 0,
        'factory_code' => $factory_id,
        'machine_num' => $machine_num
        ])
      ->toArray();
  
      if(isset($RelayLogsDataCheck[0])){//存在する時は２つ前のONを持ってくる
        if(isset($RelayLogsDataSta[1])){
          $heater_start_datetime = $RelayLogsDataSta[1]["datetime"]->format("Y-n-j G:i:s");
        }else{
          $heater_start_datetime = "";
        }
      }else{
        if(isset($RelayLogsDataSta[0])){
          $heater_start_datetime = $RelayLogsDataSta[0]["datetime"]->format("Y-n-j G:i:s");
        }else{
          $heater_start_datetime = "";
        }
      }
      $this->set('heater_start_datetime', $heater_start_datetime);

      $RelayLogsDataFin = $this->RelayLogs->find()
      ->where([
        'machine_relay_id' => 1,
        'status' => 0,
        'datetime >' => $finish_datetime,
        'delete_flag' => 0,
        'factory_code' => $factory_id,
        'machine_num' => $machine_num
        ])
        ->order(["datetime"=>"ASC"])->limit('1')->toArray();

      if(isset($RelayLogsDataFin[0])){
        $heater_finish_datetime = $RelayLogsDataFin[0]["datetime"]->format("Y-n-j G:i:s");
      }else{
        $heater_finish_datetime = "";
      }
      $this->set('heater_finish_datetime', $heater_finish_datetime);

      //それぞれの時間の間隔を計算
      $interval_heater_relay_on = 0;
      $interval_relay_start = 0;
      $interval_start_finish = 0;
      $interval_finish_relay = 0;
      $interval_relay_heater_off = 0;

      $from_heater_start_datetime = strtotime($heater_start_datetime);
      $to_relay_start_datetime = strtotime($relay_start_datetime);
      if($to_relay_start_datetime - $from_heater_start_datetime > 0){
        $interval_heater_relay_on = ($to_relay_start_datetime - $from_heater_start_datetime) / 60;
        $interval_heater_relay_on = sprintf("%.1f", $interval_heater_relay_on);
      }
      $this->set('interval_heater_relay_on', $interval_heater_relay_on);

      $from_relay_start_datetime = strtotime($relay_start_datetime);
      $to_start_datetime = strtotime($start_datetime);
      if($to_start_datetime - $from_relay_start_datetime > 0){
        $interval_relay_start = ($to_start_datetime - $from_relay_start_datetime) / 60;
        $interval_relay_start = sprintf("%.1f", $interval_relay_start);
      }
      $this->set('interval_relay_start', $interval_relay_start);

      $from_start_datetime = strtotime($start_datetime);
      $to_finish_datetime = strtotime($finish_datetime);
      if($to_finish_datetime - $from_start_datetime > 0){
        $interval_start_finish = ($to_finish_datetime - $from_start_datetime) / 60;
        $interval_start_finish = sprintf("%.1f", $interval_start_finish);
      }
      $this->set('interval_start_finish', $interval_start_finish);

      $from_finish_datetime = strtotime($finish_datetime);
      $to_relay_finish_datetime = strtotime($relay_finish_datetime);
      if($to_relay_finish_datetime - $from_finish_datetime > 0){
        $interval_finish_relay = ($to_relay_finish_datetime - $from_finish_datetime) / 60;
        $interval_finish_relay = sprintf("%.1f", $interval_finish_relay);
      }elseif($from_finish_datetime - $to_relay_finish_datetime > 0){
        $interval_finish_relay = -1 * ($from_finish_datetime - $to_relay_finish_datetime) / 60;
        $interval_finish_relay = sprintf("%.1f", $interval_finish_relay);
      }
      $this->set('interval_finish_relay', $interval_finish_relay);

      $from_relay_finish_datetime = strtotime($relay_finish_datetime);
      $to_heater_finish_datetime = strtotime($heater_finish_datetime);
      if($to_heater_finish_datetime - $from_relay_finish_datetime > 0){
        $interval_relay_heater_off = ($to_heater_finish_datetime - $from_relay_finish_datetime) / 60;
        $interval_relay_heater_off = sprintf("%.1f", $interval_relay_heater_off);
      }
      $this->set('interval_relay_heater_off', $interval_relay_heater_off);

      //ここから理論と実数
      //量産時間と引き取り速度を取得
      $from_relay_start_datetime = strtotime($relay_start_datetime);
      $to_relay_finish_datetime = strtotime($relay_finish_datetime);
      $time_ryousan = ($to_relay_finish_datetime - $from_relay_start_datetime) / 60;
      $time_ryousan = sprintf("%.1f", $time_ryousan);

      $InspectionDataResultParentSpeed = $this->InspectionDataResultParents->find()
      ->contain(['InspectionDataConditonParents' => ["InspectionDataConditonChildren"], 'ProductConditionParents', 'Products'])
      ->where([
      'machine_num' => $machine_num,
      'Products.product_code like' => $product_code_ini.'%', 
      'InspectionDataResultParents.delete_flag' => 0,
      'InspectionDataResultParents.datetime >=' => $start_datetime,
      'InspectionDataResultParents.datetime <=' => $finish_datetime//検査終了時間まで
      ])
      ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();

      $arrdatetimespeeds = array();
      $arrlength = array();
      for($i=0; $i<count($InspectionDataResultParentSpeed); $i++){
        $arrdatetimespeeds[] = [
          "product_id" => $InspectionDataResultParentSpeed[$i]["product_id"],
          "length_cut" => $InspectionDataResultParentSpeed[$i]["product"]["length_cut"],
          "length" => $InspectionDataResultParentSpeed[$i]["product"]["length"],
          "datetime" => $InspectionDataResultParentSpeed[$i]["datetime"]->format("Y-m-d H:i:s"),
          "inspection_pickup_speed" => $InspectionDataResultParentSpeed[$i]["inspection_data_conditon_parent"]["inspection_data_conditon_children"][0]["inspection_pickup_speed"],
        ];
        $arrlength[] = [
          "product_id" => $InspectionDataResultParentSpeed[$i]["product_id"],
          "length_cut" => $InspectionDataResultParentSpeed[$i]["product"]["length_cut"],
          "length" => $InspectionDataResultParentSpeed[$i]["product"]["length"],
        ];
      }
      $arrlength = array_unique($arrlength, SORT_REGULAR);
      $arrlength = array_values($arrlength);
      $this->set('arrlength', $arrlength);

      for($i=0; $i<count($arrlength); $i++){//長さ毎の器を作る
        ${"time_".$arrlength[$i]["product_id"]} = 0;
        ${"riron_total_length_".$arrlength[$i]["product_id"]} = 0;
        ${"riron_total_length_kensatyu_".$arrlength[$i]["product_id"]} = 0;
      }

      for($i=0; $i<=count($arrdatetimespeeds); $i++){//長さ毎の計測時間

        $interval = 0;
        $riron_length = 0;

        if($i == 0){//生産開始～検査開始まで

          $from_datetime = strtotime($relay_start_datetime);
          $to__datetime = strtotime($arrdatetimespeeds[$i]["datetime"]);
          $interval = ($to__datetime - $from_datetime) / 60;
          $interval = sprintf("%.1f", $interval);
          if($interval > 0){
            ${"time_".$arrdatetimespeeds[$i]["product_id"]} = ${"time_".$arrdatetimespeeds[$i]["product_id"]} + $interval;

            $riron_length = $interval * $arrdatetimespeeds[$i]["inspection_pickup_speed"];
            ${"riron_total_length_".$arrdatetimespeeds[$i]["product_id"]} = ${"riron_total_length_".$arrdatetimespeeds[$i]["product_id"]} + $riron_length;
          }

        }elseif($i == count($arrdatetimespeeds)){//検査終了～生産終了まで

          $from_datetime = strtotime($arrdatetimespeeds[$i - 1]["datetime"]);
          $to__datetime = strtotime($relay_finish_datetime);
          $interval = ($to__datetime - $from_datetime) / 60;
          $interval = sprintf("%.1f", $interval);
          if($interval > 0){
            ${"time_".$arrdatetimespeeds[$i - 1]["product_id"]} = ${"time_".$arrdatetimespeeds[$i - 1]["product_id"]} + $interval;

            $riron_length = $interval * $arrdatetimespeeds[$i - 1]["inspection_pickup_speed"];
            ${"riron_total_length_".$arrdatetimespeeds[$i - 1]["product_id"]} = ${"riron_total_length_".$arrdatetimespeeds[$i - 1]["product_id"]} + $riron_length;
          }

        }else{//検査中

          $from_datetime = strtotime($arrdatetimespeeds[$i - 1]["datetime"]);
          $to__datetime = strtotime($arrdatetimespeeds[$i]["datetime"]);
          $interval = ($to__datetime - $from_datetime) / 60;
          $interval = sprintf("%.1f", $interval);
          if($interval > 0){
            ${"time_".$arrdatetimespeeds[$i]["product_id"]} = ${"time_".$arrdatetimespeeds[$i]["product_id"]} + $interval;

            $riron_length = $interval * $arrdatetimespeeds[$i]["inspection_pickup_speed"];
            ${"riron_total_length_".$arrdatetimespeeds[$i]["product_id"]} = ${"riron_total_length_".$arrdatetimespeeds[$i]["product_id"]} + $riron_length;
            ${"riron_total_length_kensatyu_".$arrdatetimespeeds[$i]["product_id"]} = ${"riron_total_length_kensatyu_".$arrdatetimespeeds[$i]["product_id"]} + $riron_length;
          }

        }

      }

      $arrRironlength = array();
      $total_length_riron = 0;
      $total_length_kensa_riron = 0;
      for($i=0; $i<count($arrlength); $i++){

        //量産～量産
        ${"riron_amount".$arrlength[$i]["product_id"]} = ${"riron_total_length_".$arrlength[$i]["product_id"]} * 1000 / $arrlength[$i]["length_cut"];
        ${"riron_amount".$arrlength[$i]["product_id"]} = sprintf("%.0f", ${"riron_amount".$arrlength[$i]["product_id"]});
        $this->set('riron_amount'.$arrlength[$i]["product_id"],${"riron_amount".$arrlength[$i]["product_id"]});

        ${"riron_total_length_".$arrlength[$i]["product_id"]} = sprintf("%.1f", ${"riron_total_length_".$arrlength[$i]["product_id"]});
        $this->set('riron_total_length_'.$arrlength[$i]["product_id"],${"riron_total_length_".$arrlength[$i]["product_id"]});

        $total_length_riron = $total_length_riron + ${"riron_total_length_".$arrlength[$i]["product_id"]};

        //検査～検査
        ${"riron_amount_kensatyu".$arrlength[$i]["product_id"]} = ${"riron_total_length_kensatyu_".$arrlength[$i]["product_id"]} * 1000 / $arrlength[$i]["length_cut"];
        ${"riron_amount_kensatyu".$arrlength[$i]["product_id"]} = sprintf("%.0f", ${"riron_amount_kensatyu".$arrlength[$i]["product_id"]});
        $this->set('riron_amount_kensatyu'.$arrlength[$i]["product_id"],${"riron_amount_kensatyu".$arrlength[$i]["product_id"]});

        ${"riron_total_length_kensatyu_".$arrlength[$i]["product_id"]} = sprintf("%.1f", ${"riron_total_length_kensatyu_".$arrlength[$i]["product_id"]});
        $this->set('riron_total_length_kensatyu_'.$arrlength[$i]["product_id"],${"riron_total_length_kensatyu_".$arrlength[$i]["product_id"]});

        $total_length_kensa_riron = $total_length_kensa_riron + ${"riron_total_length_kensatyu_".$arrlength[$i]["product_id"]};

      }
      $this->set('total_length_riron', $total_length_riron);
      $this->set('total_length_kensa_riron', $total_length_kensa_riron);
/*
      echo "<pre>";
      print_r($arrlength);
      echo "</pre>";
*/
      $arrProdcts = array();
      $total_length_jissai = 0;
      for($i=0; $i<count($DailyReportsData); $i++){

        ${"total_length_".$DailyReportsData[$i]["product_id"]} = $DailyReportsData[$i]["product"]["length_cut"] * $DailyReportsData[$i]["amount"] / 1000;//トータルの長さ＝カット長さ×本数
        ${"amount".$DailyReportsData[$i]["product_id"]} = $DailyReportsData[$i]["amount"];
        $this->set('total_length_'.$DailyReportsData[$i]["product_id"],${"total_length_".$DailyReportsData[$i]["product_id"]});
        $this->set('amount'.$DailyReportsData[$i]["product_id"],${"amount".$DailyReportsData[$i]["product_id"]});

        $total_length_jissai = $total_length_jissai + ${"total_length_".$DailyReportsData[$i]["product_id"]};

        $arrProdcts[] = [
          "product_code" => $DailyReportsData[$i]["product"]["product_code"],
          "name" => $DailyReportsData[$i]["product"]["name"],
          "length" => $DailyReportsData[$i]["product"]["length"],
          "amount" => $DailyReportsData[$i]["amount"],
          "sum_weight" => $DailyReportsData[$i]["sum_weight"],
          "total_loss_weight" => $DailyReportsData[$i]["total_loss_weight"],
        ];
        $this->set('bik', $DailyReportsData[$i]["bik"]);
      }
      $this->set('arrProdcts', $arrProdcts);
      $this->set('total_length_jissai', $total_length_jissai);

      if($total_length_riron > 0){
        $riron_lossritsu = 100 * ($total_length_riron - $total_length_jissai) / $total_length_riron;
        $riron_lossritsu = sprintf("%.1f", $riron_lossritsu);
      }else{
        $riron_lossritsu = "";
      }
      $this->set('riron_lossritsu', $riron_lossritsu);

      if($total_length_kensa_riron > 0){
        $kensa_riron_lossritsu = 100 * ($total_length_kensa_riron - $total_length_jissai) / $total_length_kensa_riron;
        $kensa_riron_lossritsu = sprintf("%.1f", $kensa_riron_lossritsu);
      }else{
        $kensa_riron_lossritsu = "";
      }
      $this->set('kensa_riron_lossritsu', $kensa_riron_lossritsu);

      $total_sum_weight = 0;
      $total_loss_weight = 0;
      for($j=0; $j<count($arrProdcts); $j++){
        $total_sum_weight = $total_sum_weight + $arrProdcts[$j]["sum_weight"];
        $total_loss_weight = $total_loss_weight + $arrProdcts[$j]["total_loss_weight"];
      }

      $tasseiritsu = $total_sum_weight * 100 / ($total_sum_weight + $total_loss_weight);
      $tasseiritsu = sprintf("%.1f", $tasseiritsu);
      $this->set('tasseiritsu', $tasseiritsu);
      $lossritsu = $total_loss_weight * 100 / ($total_sum_weight + $total_loss_weight);
      $lossritsu = sprintf("%.1f", $lossritsu);
      $this->set('lossritsu', $lossritsu);

      $InspectionDataResultParentDatas = $this->InspectionDataResultParents->find()
      ->contain(['ProductConditionParents', 'Products', 'Staffs'])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%',
      'InspectionDataResultParents.delete_flag' => 0,
      'datetime >=' => $date_sta, 'datetime <=' => $date_fin])
      ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();

      $loss_mid = 0;
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
  
          if($i != 0 && $i != count($InspectionDataResultParentDatas) - 1){
            $loss_mid = $loss_mid + (float)$InspectionDataResultParentDatas[$i]["loss_amount"];
          }

        }

      }
      $this->set('arrIjous', $arrIjous);

      if($loss_mid > 0){
        $mid_lossritsu = 100 * $loss_mid / ($loss_mid + $total_sum_weight);
        $mid_lossritsu = sprintf("%.1f", $mid_lossritsu);
      }else{
        $mid_lossritsu = "";
      }
      $this->set('mid_lossritsu', $mid_lossritsu);

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
