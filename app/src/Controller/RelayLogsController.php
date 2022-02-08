<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

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

    }

}
