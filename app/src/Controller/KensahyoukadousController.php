<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

use App\myClass\classprograms\htmlkensahyouprogram;//myClassフォルダに配置したクラスを使用

class KensahyoukadousController extends AppController
{

  public function initialize()
  {
   parent::initialize();
   $this->Users = TableRegistry::get('Users');
   $this->LoginStaffs = TableRegistry::get('LoginStaffs');
   $this->Factories = TableRegistry::get('Factories');
   $this->Products = TableRegistry::get('Products');
   $this->Customers = TableRegistry::get('Customers');
   $this->Staffs = TableRegistry::get('Staffs');
  }

  public function beforeFilter(Event $event){
    parent::beforeFilter($event);

    // 認証なしでアクセスできるアクションの指定
  //  $this->Auth->allow(["login","kikakunagasalogin",
   // "seihinkensakuform","seihinkensakusyousai",
    //"seihinyobidashimenu","seihinyobidashiichiran",
    //"kensahyouseihinmenu","index","kensahyoumenu"]);
  }

    public function index()
    {
      
      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }

    }

    public function kensahyoumenu()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();
      $staff_id = $datasession['Auth']['User']['staff_id'];
      $Staffs = $this->Staffs->find()->where(['id' => $staff_id])->toArray();
      $factory_id = $Staffs[0]["factory_id"];

      $datetimesta = date('Y-m-d 00:00:00');//今日の0時が境目

      //検査中一覧のクラス
      $datetimesta_factory = $datetimesta."_".$factory_id;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $arrInspectionDataResultParents = $htmlkensahyougenryouheader->toujitsuichiran($datetimesta_factory);
      $this->set('arrInspectionDataResultParents', $arrInspectionDataResultParents);

      //未検査一覧のクラス
      $datetimesta_factory = $datetimesta."_".$factory_id;
      $htmlkensahyougenryouheader = new htmlkensahyouprogram();
      $arrInspectionDataResultParentnotfin = $htmlkensahyougenryouheader->mikanryouichiran($datetimesta_factory);
      $this->set('arrInspectionDataResultParentnotfin', $arrInspectionDataResultParentnotfin);
    }

    public function login()
    {
      $Users = $this->Users->newEntity();
      $this->set('Users',$Users);

      if ($this->request->is('post')) {
        $user = $this->Auth->identify();
        if ($user) {
          $this->Auth->setUser($user);

          $arrtourokulogin  = array();
          $arrtourokulogin  = [
            'staff_id' => $user["staff_id"],
            'login_datetime' => date("Y-m-d H:i:s"),
          ];

          $LoginStaffs = $this->LoginStaffs->patchEntity($this->LoginStaffs->newEntity(), $arrtourokulogin);
          $this->LoginStaffs->save($LoginStaffs);

  //        return $this->redirect($this->Auth->redirectUrl());

          return $this->redirect(['controller' => 'Images', 'action' => 'addpre']);
  
        }
        $this->Flash->error(__('ユーザ名もしくはパスワードが間違っています'));
      }

    }

    public function kikakunagasalogin()
    {
      $Users = $this->Users->newEntity();
      $this->set('Users',$Users);

      if ($this->request->is('post')) {
        $user = $this->Auth->identify();
        if ($user) {
          $this->Auth->setUser($user);

          $arrtourokulogin  = array();
          $arrtourokulogin  = [
            'staff_id' => $user["staff_id"],
            'login_datetime' => date("Y-m-d H:i:s"),
          ];

          $LoginStaffs = $this->LoginStaffs->patchEntity($this->LoginStaffs->newEntity(), $arrtourokulogin);
          $this->LoginStaffs->save($LoginStaffs);

          return $this->redirect(['controller' => 'Products', 'action' => 'kikakueditpreform']);
  
        }
        $this->Flash->error(__('ユーザ名もしくはパスワードが間違っています'));
      }

    }

    public function kensahyouseihinmenu()
    {
    }

    public function seihinyobidashimenu()
    {
    }

    public function seihinyobidashiichiran($id = null)
    {

      if(strlen($id) > 0){

        $this->paginate = [
          'limit' => 13,
          'contain' => ['Customers'],
          'order' => [//'Products.updated_at' => 'desc',
          'Products.created_at' => 'desc']
        ];
        $products = $this->paginate($this->Products->find()->where(['Products.delete_flag' => 0]));

        $this->set(compact('products'));

      }else{

        $this->paginate = [
          'limit' => 13,
          'contain' => ['Customers'],
        ];
        $products = $this->paginate($this->Products->find()->where(['Products.delete_flag' => 0]));

        $this->set(compact('products'));

      }

    }

    public function seihinkensakuform()
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

        ${"Product_name_list".$i} = $this->Products->find()
        ->where(['factory_id' => $Factories[$i]["id"], 'delete_flag' => 0])->toArray();
  
        ${"arrProduct_name_list".$i} = array();
        for($j=0; $j<count(${"Product_name_list".$i}); $j++){
          array_push(${"arrProduct_name_list".$i},${"Product_name_list".$i}[$j]["name"]);
        }
        ${"arrProduct_name_list".$i} = array_unique(${"arrProduct_name_list".$i});
        ${"arrProduct_name_list".$i} = array_values(${"arrProduct_name_list".$i});
  
        $this->set('arrProduct_name_list'.$i, ${"arrProduct_name_list".$i});
      }

    }

      public function seihinkensakusyousai()
      {
        $product = $this->Products->newEntity();
        $this->set('product', $product);
  
        $Data=$this->request->query('s');
        if(isset($Data["mess"])){
          $mess = $Data["mess"];
          $this->set('mess',$mess);
  
          $session = $this->request->getSession();
          $_SESSION = $session->read();
    
          $data = $_SESSION['editproductcheck'];
    
        }elseif(isset($_SESSION['product_detail'][0])){
  
          $mess = "";
          $this->set('mess',$mess);
          $data = $_SESSION['product_detail'];
          $_SESSION['product_detail'] = array();
  
        }elseif(isset($this->request->getData()["factory_id"])){
  
          $mess = "";
          $this->set('mess',$mess);
          $data = $this->request->getData();
  
        }else{
  
          return $this->redirect(['action' => 'ichiran']);
  
        }
  
        $Factories = $this->Factories->find()
        ->where(['id' => $data['factory_id']])->toArray();
        $factory_name = $Factories[0]['name'];
        $this->set('factory_name', $factory_name);
  
        $ProductName = $this->Products->find()
        ->where(['factory_id' => $data['factory_id'], 'name' => $data['name']])->toArray();
  
        if(!isset($ProductName[0])){
  
          return $this->redirect(['action' => 'seihinkensakuform',
          's' => ['mess' => "自社工場：".$factory_name."、製品名：「".$data['name']."」の製品は存在しません。"]]);
  
        }
  
        $product_code_ini = substr($ProductName[0]["product_code"], 0, 11);
  
        $ProductName = $this->Products->find()
        ->where(['product_code like' => $product_code_ini.'%', 'delete_flag' => 0])->toArray();
  
        $this->set('ProductName', $ProductName);
  
        $factory_id = $data["factory_id"];
        $this->set('factory_id', $factory_id);
        $name = $data["name"];
        $this->set('name', $name);
        $tanni = $ProductName[0]["tanni"];
        $this->set('tanni', $tanni);
        $weight = $ProductName[0]["weight"];
        $this->set('weight', $weight);
        $status_kensahyou = $ProductName[0]["status_kensahyou"];
        $this->set('status_kensahyou', $status_kensahyou);
        $ig_bank_modes = $ProductName[0]["ig_bank_modes"];
        $this->set('ig_bank_modes', $ig_bank_modes);
        
        $status_kensahyou = $ProductName[0]["status_kensahyou"];
        if($status_kensahyou == 0){
          $status_kensahyou_name = "表示";
        }else{
          $status_kensahyou_name = "非表示";
        }
        $this->set('status_kensahyou_name', $status_kensahyou_name);
  
        $Customers = $this->Customers->find()
        ->where(['id' => $ProductName[0]['customer_id']])->toArray();
        $customer_name = $Customers[0]["name"];
        $this->set('customer_name', $customer_name);
  
      }
  
}
