<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

use App\myClass\classprograms\kadouprogram;//myClassフォルダに配置したクラスを使用

class RelayLogsController extends AppController
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

      $Linenames = $this->Linenames->find()
      ->where(['delete_flag' => 0, 'factory_id' => 1])->toArray();

      $arrGouki = array();
      for($j=0; $j<count($Linenames); $j++){
        $array = array($Linenames[$j]["machine_num"] => $Linenames[$j]["name"]);
        $arrGouki = $arrGouki + $array;
      }
      $this->set('arrGouki', $arrGouki);

    }

    public function details()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
/*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $this->set('date_sta_year', $data["date_sta_year"]);
      $this->set('date_sta_month', $data["date_sta_month"]);
      $this->set('date_sta_date', $data["date_sta_date"]);

      $factory_id = $data["factory_id"];
      $this->set('factory_id', $factory_id);

      $machine_num = $data["machine_num"];

      if(isset($data["date_sta_year"])){
        $dateselect = $data["date_sta_year"]."-".$data["date_sta_month"]."-".$data["date_sta_date"];

        $date1 = strtotime($dateselect);
        $date_sta = $dateselect." 00:00:00";
        $date_fin = date('Y-m-d', strtotime('+1 day', $date1))." 00:00:00";
        $date_fin_hyouji = date('Y-m-d', strtotime('+1 day', $date1))." 00:00:00";
        $this->set('date_sta', $date_sta);
        $this->set('date_fin', $date_fin);
        $this->set('date_fin_hyouji', $date_fin_hyouji);
      }

      if(isset($data["shotdata"])){//ショットデータ出力
       
        $date_sta = $data["date_sta"];
        $this->set('date_sta', $date_sta);
        $date_fin = $data["date_fin"];
        $this->set('date_fin', $date_fin);
        $date_fin_hyouji = $data["date_fin_hyouji"];
        $this->set('date_fin_hyouji', $date_fin_hyouji);

        $machine_sta_fin = $machine_num."_".$date_sta."_".$date_fin;
        $kadouprograms = new kadouprogram();
        $shotdatacsv = $kadouprograms->shotdatacsv($machine_sta_fin);
        
      }

      if(isset($data["mae"])){

        $Linenames = $this->Linenames->find()
        ->where(['delete_flag' => 0, 'factory_id' => $factory_id])->order(["machine_num"=>"ASC"])->toArray();

        for($i=0; $i<count($Linenames); $i++){
          if(($Linenames[$i]["machine_num"] == $machine_num) && ($i == 0)){
            $machine_num = $Linenames[count($Linenames) - 1]["machine_num"];
            break;
          }elseif($Linenames[$i]["machine_num"] == $machine_num){
            $machine_num = $Linenames[$i - 1]["machine_num"];
            break;
          }
        }

      }

      if(isset($data["tugi"])){

        $Linenames = $this->Linenames->find()
        ->where(['delete_flag' => 0, 'factory_id' => $factory_id])->order(["machine_num"=>"ASC"])->toArray();

        for($i=0; $i<count($Linenames); $i++){
          if(($Linenames[$i]["machine_num"] == $machine_num) && ($i == count($Linenames) - 1)){
            $machine_num = $Linenames[0]["machine_num"];
            break;
          }elseif($Linenames[$i]["machine_num"] == $machine_num){
            $machine_num = $Linenames[$i + 1]["machine_num"];
            break;
          }
        }
  
      }

      $this->set('machine_num', $machine_num);

      $machine_sta_fin = $machine_num."_".$date_sta."_".$date_fin;
      $kadouprograms = new kadouprogram();
      $arrRelayLogs = $kadouprograms->yobidashirelaylogs($machine_sta_fin);
      $this->set('arrRelayLogs', $arrRelayLogs);
  
      echo "<pre>";
      print_r("");
      echo "</pre>";
  
    }

}
