<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;//ログインに使用
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション

use App\myClass\classprograms\htmlproductcheck;//myClassフォルダに配置したクラスを使用
$htmlproductcheck = new htmlproductcheck();

class ImagesController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->InspectionStandardSizeParents = TableRegistry::get('InspectionStandardSizeParents');
     $this->Products = TableRegistry::get('Products');
     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

       return $this->redirect($this->Auth->logout());

     }
/*
     if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

       $Groups = $this->Groups->find()->contain(["Menus"])
       ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "検査表画像", 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }

     }
     */
    }

    public function index()
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);
    }

    public function detail($id = null)
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);

      $data = $this->request->getData();
      if(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['inspectionStandardSizeParentdata'] = array();
        $_SESSION['inspectionStandardSizeParentdata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }
      $this->set('id', $id);

      $InspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['id' => $id])->toArray();

      $product_code = $InspectionStandardSizeParents[0]["product"]["product_code"];
      $this->set('product_code', $product_code);

    }

    public function addpre()
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }
    }

    public function addform()
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);

        $session = $this->request->getSession();
        $_SESSION = $session->read();
        $product_code = $_SESSION["img_product_code"];
        $_SESSION['img_product_code'] = array();
        $this->set('product_code', $product_code);

      }else{
        $mess = "";
        $this->set('mess',$mess);

        $data = $this->request->getData();

        $product_code = $data["product_code"];
        $this->set('product_code', $product_code);
      }

      $htmlproductcheck = new htmlproductcheck();//クラスを使用
      $arrayproductdate = $htmlproductcheck->productcheckprogram($product_code);//クラスを使用

      if($arrayproductdate[0] === "no_product"){

        return $this->redirect(['action' => 'addpre',
        's' => ['mess' => "管理No.「".$product_code."」の製品は存在しません。"]]);

      }

    }

    public function addcomfirm()
    {
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->newEntity();
      $this->set('inspectionStandardSizeParents', $inspectionStandardSizeParents);

      $data = $this->request->getData();

      $product_code = $data["product_code"];
      $this->set('product_code', $product_code);

      $fileName =$_FILES['upfile']['tmp_name'];

      if(substr($_FILES['upfile']["name"], -4) !== ".gif"){

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['img_product_code'] = array();
        $_SESSION['img_product_code'] = $product_code;

        return $this->redirect(['action' => 'addform',
        's' => ['mess' => "拡張子が「.gif」でないファイルが選択されました。"]]);

      }

      if($_FILES['upfile']['size']>0){

        if($_FILES['upfile']['size']>1000000){

          if(!isset($_SESSION)){
            session_start();
          }
          $_SESSION['img_product_code'] = array();
          $_SESSION['img_product_code'] = $product_code;

          return $this->redirect(['action' => 'addform',
          's' => ['mess' => "画像サイズが大き過ぎます。"]]);

        }else{

          move_uploaded_file($_FILES['upfile']["tmp_name"],"img/kensahyouimg/".$_FILES['upfile']["name"]);

        }

      }
/*
			echo "<pre>";
			print_r($_FILES['upfile']["name"]);
			echo "</pre>";
*/
			$gif1 = "kensahyouimg/".$_FILES['upfile']["name"];//ローカル
			$this->set('gif1',$gif1);

    }

    public function adddo()
    {
      $Groups = $this->Groups->newEntity();
      $this->set('Groups', $Groups);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $data = $this->request->getData();

      $arrtourokugroup = array();
      for ($k=0; $k<=$data["num_menu"]; $k++){

        $Menus = $this->Menus->find()
        ->where(['name_menu' => $data['menu_name'.$k]])->toArray();

          $arrtourokugroup[] = [
            'name_group' => $data["name_group"],
            'menu_id' => $Menus[0]['id'],
            'delete_flag' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'created_staff' => $staff_id
          ];
      }
/*
      echo "<pre>";
      print_r($arrtourokugroup);
      echo "</pre>";
*/
      //新しいデータを登録
      $Groups = $this->Groups->patchEntities($this->Groups->newEntity(), $arrtourokugroup);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Groups->saveMany($Groups)) {

          $connection->commit();// コミット5
          $mes = "以下のように登録されました。";
          $this->set('mes',$mes);

        } else {

          $this->Flash->error(__('The data could not be saved. Please, try again.'));
          throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6
          $mes = "※登録されませんでした";
          $this->set('mes',$mes);

        }

      } catch (Exception $e) {//トランザクション7
      //ロールバック8
        $connection->rollback();//トランザクション9
      }//トランザクション10

    }

    public function deleteconfirm($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['groupdata'];

        $group = $this->Groups->get($id, [
          'contain' => ['Menus']
        ]);
        $this->set(compact('group'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $group = $this->Groups->get($data["id"], [
        'contain' => ['Menus']
      ]);
      $this->set(compact('group'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeletegroups = array();
      $arrdeletegroups = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletecompany);
      echo "</pre>";
*/
      $Groups = $this->Groups->patchEntity($this->Groups->newEntity(), $arrdeletegroups);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Groups->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['name_group'  => $group['name_group']]
         )){

           //スタッフ権限もこのタイミングで削除

         $mes = "※以下のデータが削除されました。";
         $this->set('mes',$mes);
         $connection->commit();// コミット5

       } else {

         $mes = "※削除されませんでした";
         $this->set('mes',$mes);
         $this->Flash->error(__('The data could not be saved. Please, try again.'));
         throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

       }

     } catch (Exception $e) {//トランザクション7
     //ロールバック8
       $connection->rollback();//トランザクション9
     }//トランザクション10

    }

}
