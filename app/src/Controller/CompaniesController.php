<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class CompaniesController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Companies = TableRegistry::get('Companies');
     $this->Users = TableRegistry::get('Users');

     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

       return $this->redirect($this->Auth->logout());

     }

     if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

       $Groups = $this->Groups->find()->contain(["Menus"])
       ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "会社", 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }

     }

    }

    public function top()
    {
      return $this->redirect(
       ['controller' => 'Startmenus', 'action' => 'menu']
      );
    }

    public function logout()
    {
      return $this->redirect($this->Auth->logout());
    }

    public function index()
    {
      $companies = $this->Companies->find()->where(['delete_flag' => 0]);
      $companies = $this->paginate($companies);
      $this->set(compact('companies'));

      $session = $this->request->getSession();
      $datasession = $session->read();
/*
      $Groups = $this->Groups->find()->contain(["Menus"])//GroupsテーブルとMenusテーブルを関連付ける
      ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "会社"])->toArray();

      if(count($Groups) < 1){
        return $this->redirect(
         ['controller' => 'Startmenus', 'action' => 'menu']
        );
      }
*/
    }

    public function detail($id = null)
    {
      $companies = $this->Companies->newEntity();
      $this->set('companies', $companies);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['companydata'] = array();
        $_SESSION['companydata'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['companydata'] = array();
        $_SESSION['companydata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }
      $this->set('id', $id);

      $Companies = $this->Companies->find()
      ->where(['id' => $id])->toArray();

      $name = $Companies[0]["name"];
      $this->set('name', $name);
      $address = $Companies[0]["address"];
      $this->set('address', $address);
      $tel = $Companies[0]["tel"];
      $this->set('tel', $tel);
      $fax = $Companies[0]["fax"];
      $this->set('fax', $fax);
      $president = $Companies[0]["president"];
      $this->set('president', $president);

    }

    public function view($id = null)
    {
        $company = $this->Companies->get($id, [
            'contain' => []
        ]);

        $this->set('company', $company);
    }

    public function addform()
    {
      $company = $this->Companies->newEntity();
      $this->set('company', $company);
    }

    public function addcomfirm()
    {
      $company = $this->Companies->newEntity();
      $this->set('company', $company);
    }

    public function adddo()
    {
      $company = $this->Companies->newEntity();
      $this->set('company', $company);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokucompany = array();
      $arrtourokucompany = [
        'name' => $data["name"],
        'address' => $data["address"],
        'tel' => $data["tel"],
        'fax' => $data["fax"],
        'president' => $data["president"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokucompany);
      echo "</pre>";
*/
      //新しいデータを登録
      $Companies = $this->Companies->patchEntity($this->Companies->newEntity(), $arrtourokucompany);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Companies->save($Companies)) {

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

    public function editform($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['companydata'];

        $company = $this->Companies->get($id, [
            'contain' => []
        ]);
        $this->set(compact('company'));
        $this->set('id', $id);
    }

    public function editconfirm()
    {
      $company = $this->Companies->newEntity();
      $this->set('company', $company);
    }

    public function editdo()
    {
      $company = $this->Companies->newEntity();
      $this->set('company', $company);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdatecompany = array();
      $arrupdatecompany = [
        'name' => $data["name"],
        'address' => $data["address"],
        'tel' => $data["tel"],
        'fax' => $data["fax"],
        'president' => $data["president"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrupdatecompany);
      echo "</pre>";
*/
      $Companies = $this->Companies->patchEntity($this->Companies->newEntity(), $arrupdatecompany);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Companies->save($Companies)) {

         $this->Companies->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $data['id']]);

         $mes = "※下記のように更新されました";
         $this->set('mes',$mes);
         $connection->commit();// コミット5

       } else {

         $mes = "※更新されませんでした";
         $this->set('mes',$mes);
         $this->Flash->error(__('The data could not be saved. Please, try again.'));
         throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

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

      $id = $_SESSION['companydata'];

        $company = $this->Companies->get($id, [
            'contain' => []
        ]);
        $this->set(compact('company'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $company = $this->Companies->get($data["id"], [
          'contain' => []
      ]);
      $this->set(compact('company'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeletecompany = array();
      $arrdeletecompany = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletecompany);
      echo "</pre>";
*/
      $Companies = $this->Companies->patchEntity($this->Companies->newEntity(), $arrdeletecompany);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Companies->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeletecompany['id']]
         )){

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
