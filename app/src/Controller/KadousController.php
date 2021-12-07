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

  }

  public function beforeFilter(Event $event){
    parent::beforeFilter($event);

    // 認証なしでアクセスできるアクションの指定
    $this->Auth->allow([
      "index", "yobidashidate", "view"
    ]);
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
    }

    public function view()
    {
      $product = $this->Products->newEntity();
      $this->set('product', $product);

      $data = $this->request->getData();
      /*
      echo "<pre>";
      print_r($date_sta);
      echo "</pre>";
*/
      $dateselect = $data["date_sta_year"]."-".$data["date_sta_month"]."-".$data["date_sta_date"];
      $date1 = strtotime($dateselect);
      $date_sta = $dateselect." 06:00:00";
      $date_fin = date('Y-m-d', strtotime('+1 day', $date1))." 06:00:00";

      $session = $this->request->getSession();
      $datasession = $session->read();
      $staff_id = $datasession['Auth']['User']['staff_id'];

      $Staffs = $this->Staffs->find()
      ->where(['id' => $staff_id])
      ->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      if($factory_id == 5){
        $Linenames = $this->Linenames->find()
        ->where(['delete_flag' => 0])->toArray();

        $DailyReportsData = $this->DailyReports->find()
        ->contain(['Products'])
        ->where(['start_datetime >=' => $date_sta, 'start_datetime <' => $date_fin,
        'DailyReports.delete_flag' => 0])
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

        $arrlines_all[] = $Linenames[$i]["name"];

      }

      $arrDaily_report = array();
      $arrline_shiyou = array();
      for($i=0; $i<count($DailyReportsData); $i++){

        $arrline_shiyou[] = $DailyReportsData[$i]["machine_num"];
        $arrDaily_report[] = [
          "machine_num" => $DailyReportsData[$i]["machine_num"],
          "start_datetime" => $DailyReportsData[$i]["start_datetime"]->format("Y-m-d H:i:s"),
          "finish_datetime" => $DailyReportsData[$i]["finish_datetime"]->format("Y-m-d H:i:s"),
          "product_code" => $DailyReportsData[$i]["product"]["product_code"],
          "name" => $DailyReportsData[$i]["product"]["name"],
          "length" => $DailyReportsData[$i]["product"]["length"],
          "amount" => $DailyReportsData[$i]["amount"],
          "sum_weight" => $DailyReportsData[$i]["sum_weight"],
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
          "product_code" => "-",
          "name" => "-",
          "length" => "-",
          "amount" => "-",
          "sum_weight" => "-",
        ];

      }

      $arrAll = array_merge($arrDaily_report, $arrlines_fushiyous);

      foreach($arrAll as $key => $value)
      {
        $sort_keys[$key] = $value['machine_num'];
      }
      array_multisort($sort_keys, SORT_ASC, $arrAll);

      $this->set('arrAll', $arrAll);
/*
      echo "<pre>";
      print_r($arrAll);
      echo "</pre>";
*/
    }

}
