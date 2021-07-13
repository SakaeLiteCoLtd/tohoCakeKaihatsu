<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\Auth\DefaultPasswordHasher;//パスワードの更新時のハッシュ化

class MaterialTypesController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Factories = TableRegistry::get('Factories');
     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

       return $this->redirect($this->Auth->logout());

     }

     if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

       $Groups = $this->Groups->find()->contain(["Menus"])
       ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "製品・原料関係", 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }

     }
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Factories']
        ];
        $materialTypes = $this->paginate($this->MaterialTypes->find()->where(['MaterialTypes.delete_flag' => 0]));

        $this->set(compact('materialTypes'));
    }

    public function editpreform()
    {
      $materialType = $this->MaterialTypes->newEntity();
      $this->set('materialType', $materialType);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }

      $MaterialTypes_name_list = $this->MaterialTypes->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrMaterialTypes_name_list = array();
      for($j=0; $j<count($MaterialTypes_name_list); $j++){
        array_push($arrMaterialTypes_name_list,$MaterialTypes_name_list[$j]["type"]);
      }
      $arrMaterialTypes_name_list = array_unique($arrMaterialTypes_name_list);
      $arrMaterialTypes_name_list = array_values($arrMaterialTypes_name_list);
      $this->set('arrMaterialTypes_name_list', $arrMaterialTypes_name_list);
    }

    public function detail($id = null)
    {
      $materialType = $this->MaterialTypes->newEntity();
      $this->set('materialType', $materialType);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materialTypedata'] = array();
        $_SESSION['materialTypedata'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materialTypedata'] = array();
        $_SESSION['materialTypedata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }elseif(isset($data["kensaku"])){
  
        $MaterialTypes = $this->MaterialTypes->find()->where(['type' => $data['type']])->toArray();

        if(!isset($MaterialTypes[0])){

          return $this->redirect(['action' => 'editpreform',
          's' => ['mess' => "原料種類：「".$data['type']."」は存在しません。"]]);
  
        }else{
          $id = $MaterialTypes[0]["id"];
        }

      }
      $this->set('id', $id);

      $MaterialTypes = $this->MaterialTypes->find()->contain(["Factories"])
      ->where(['MaterialTypes.id' => $id])->toArray();

      $type = $MaterialTypes[0]["type"];
      $this->set('type', $type);
      $factory_name = $MaterialTypes[0]["factory"]['name'];
      $this->set('factory_name', $factory_name);

    }

    public function view($id = null)
    {
        $materialType = $this->MaterialTypes->get($id, [
            'contain' => []
        ]);

        $this->set('materialType', $materialType);
    }

    public function add()
    {
        $materialType = $this->MaterialTypes->newEntity();
        if ($this->request->is('post')) {
            $materialType = $this->MaterialTypes->patchEntity($materialType, $this->request->getData());
            if ($this->MaterialTypes->save($materialType)) {
                $this->Flash->success(__('The material type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The material type could not be saved. Please, try again.'));
        }
        $this->set(compact('materialType'));
    }

    public function addform()
    {
      $materialType = $this->MaterialTypes->newEntity();
      $this->set('materialType', $materialType);

      $Factories = $this->Factories->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrFactories = array();
      foreach ($Factories as $value) {
        $arrFactories[] = array($value->id=>$value->name);
      }
      $this->set('arrFactories', $arrFactories);

    }

    public function addcomfirm()
    {
      $materialType = $this->MaterialTypes->newEntity();
      $this->set('materialType', $materialType);

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

    }

    public function adddo()
    {
      $materialType = $this->MaterialTypes->newEntity();
      $this->set('materialType', $materialType);

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokumaterialType = array();
      $arrtourokumaterialType = [
        'factory_id' => $data["factory_id"],
        'type' => $data["type"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokumaterialType);
      echo "</pre>";
*/
      //新しいデータを登録
      $MaterialTypes = $this->MaterialTypes->patchEntity($this->MaterialTypes->newEntity(), $arrtourokumaterialType);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->MaterialTypes->save($MaterialTypes)) {

          $connection->commit();// コミット5
          $mes = "以下のように登録されました。";
          $this->set('mes',$mes);

        } else {

          $mes = "※登録されませんでした";
          $this->set('mes',$mes);
          $this->Flash->error(__('The data could not be saved. Please, try again.'));
          throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

        }

      } catch (Exception $e) {//トランザクション7
      //ロールバック8
         $connection->rollback();//トランザクション9
      }//トランザクション10

    }

    public function edit($id = null)
    {
        $materialType = $this->MaterialTypes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $materialType = $this->MaterialTypes->patchEntity($materialType, $this->request->getData());
            if ($this->MaterialTypes->save($materialType)) {
                $this->Flash->success(__('The material type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The material type could not be saved. Please, try again.'));
        }
        $this->set(compact('materialType'));
    }

    public function editform($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['materialTypedata'];

      $materialType = $this->MaterialTypes->get($id, [
        'contain' => []
      ]);
      $this->set(compact('materialType'));
      $this->set('id', $id);

      $Factories = $this->Factories->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrFactories = array();
      foreach ($Factories as $value) {
        $arrFactories[] = array($value->id=>$value->name);
      }
      $this->set('arrFactories', $arrFactories);

    }

    public function editconfirm()
    {
      $materialType = $this->MaterialTypes->newEntity();
      $this->set('materialType', $materialType);

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

    }

    public function editdo()
    {
      $materialType = $this->MaterialTypes->newEntity();
      $this->set('materialType', $materialType);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdatematerialType = array();
      $arrupdatematerialType = [
        'factory_id' => $data["factory_id"],
        'type' => $data["type"],
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrupdatematerialType);
      echo "</pre>";
*/
      $MaterialTypes = $this->MaterialTypes->patchEntity($this->MaterialTypes->newEntity(), $arrupdatematerialType);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->MaterialTypes->save($MaterialTypes)) {

         $this->MaterialTypes->updateAll(
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

      $id = $_SESSION['materialTypedata'];

        $materialType = $this->MaterialTypes->get($id, [
            'contain' => ["Factories"]
        ]);
        $this->set(compact('materialType'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $materialType = $this->MaterialTypes->get($data["id"], [
          'contain' => ["Factories"]
      ]);
      $this->set(compact('materialType'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeletematerialType = array();
      $arrdeletematerialType = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletematerialType);
      echo "</pre>";
*/
      $MaterialTypes = $this->MaterialTypes->patchEntity($this->MaterialTypes->newEntity(), $arrdeletematerialType);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->MaterialTypes->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeletematerialType['id']]
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
