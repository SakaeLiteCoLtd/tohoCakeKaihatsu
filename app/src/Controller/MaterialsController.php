<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\Auth\DefaultPasswordHasher;//パスワードの更新時のハッシュ化

class MaterialsController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->MaterialTypes = TableRegistry::get('MaterialTypes');
     $this->Factories = TableRegistry::get('Factories');
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['MaterialTypes', 'Factories']
        ];
        $materials = $this->paginate($this->Materials->find()->where(['Materials.delete_flag' => 0]));

        $this->set(compact('materials'));
    }

    public function detail($id = null)
    {
      $materials = $this->Materials->newEntity();
      $this->set('materials', $materials);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materialdata'] = array();
        $_SESSION['materialdata'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materialdata'] = array();
        $_SESSION['materialdata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }
      $this->set('id', $id);

      $Materials = $this->Materials->find()->contain(["Factories", "MaterialTypes"])
      ->where(['Materials.id' => $id])->toArray();
/*
      echo "<pre>";
      print_r($Materials);
      echo "</pre>";
*/
      $factory_name = $Materials[0]["factory"]['name'];
      $this->set('factory_name', $factory_name);
      $type_name = $Materials[0]["material_type"]['type'];
      $this->set('type_name', $type_name);
      $material_code = $Materials[0]["material_code"];
      $this->set('material_code', $material_code);
      $grade = $Materials[0]["grade"];
      $this->set('grade', $grade);
      $color = $Materials[0]["color"];
      $this->set('color', $color);
      $maker = $Materials[0]["maker"];
      $this->set('maker', $maker);

    }

    public function view($id = null)
    {
        $material = $this->Materials->get($id, [
            'contain' => ['MaterialTypes', 'PriceMaterials', 'ProductMaterials']
        ]);

        $this->set('material', $material);
    }

    public function addform()
    {
      $material = $this->Materials->newEntity();
      $this->set('material', $material);

      $MaterialTypes = $this->MaterialTypes->find()
      ->where(['delete_flag' => 0])->toArray();

      $arrMaterialTypes = array();
      foreach ($MaterialTypes as $value) {
        $arrMaterialTypes[] = array($value->id=>$value->type);
      }
      $this->set('arrMaterialTypes', $arrMaterialTypes);

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
      $material = $this->Materials->newEntity();
      $this->set('material', $material);

      $data = $this->request->getData();

      $MaterialTypes = $this->MaterialTypes->find()
      ->where(['id' => $data['type_id']])->toArray();
      $type_name = $MaterialTypes[0]['type'];
      $this->set('type_name', $type_name);

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

    }

    public function adddo()
    {
      $material = $this->Materials->newEntity();
      $this->set('material', $material);

      $data = $this->request->getData();

      $MaterialTypes = $this->MaterialTypes->find()
      ->where(['id' => $data['type_id']])->toArray();
      $type_name = $MaterialTypes[0]['type'];
      $this->set('type_name', $type_name);

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokumaterial = array();
      $arrtourokumaterial = [
        'factory_id' => $data["factory_id"],
        'material_code' => $data["material_code"],
        'grade' => $data["grade"],
        'color' => $data["color"],
        'maker' => $data["maker"],
        'type_id' => $data["type_id"],
        'is_active' => 0,
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrtourokumaterial);
      echo "</pre>";
*/
      //新しいデータを登録
      $Materials = $this->Materials->patchEntity($this->Materials->newEntity(), $arrtourokumaterial);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Materials->save($Materials)) {

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

    public function add()
    {
        $material = $this->Materials->newEntity();
        if ($this->request->is('post')) {
            $material = $this->Materials->patchEntity($material, $this->request->getData());
            if ($this->Materials->save($material)) {
                $this->Flash->success(__('The material has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The material could not be saved. Please, try again.'));
        }
        $MaterialTypes = $this->Materials->MaterialTypes->find('list', ['limit' => 200]);
        $this->set(compact('material', 'MaterialTypes'));
    }

    public function editform($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['materialdata'];

      $material = $this->Materials->get($id, [
        'contain' => ['MaterialTypes']
      ]);
      $MaterialTypes = $this->Materials->MaterialTypes->find('list', ['limit' => 200]);
      $this->set(compact('material', 'MaterialTypes'));
      $this->set('id', $id);

      $MaterialTypes = $this->MaterialTypes->find()
      ->where(['delete_flag' => 0])->toArray();

      $arrMaterialTypes = array();
      foreach ($MaterialTypes as $value) {
        $arrMaterialTypes[] = array($value->id=>$value->type);
      }
      $this->set('arrMaterialTypes', $arrMaterialTypes);

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
      $material = $this->Materials->newEntity();
      $this->set('material', $material);

      $data = $this->request->getData();

      $MaterialTypes = $this->MaterialTypes->find()
      ->where(['id' => $data['type_id']])->toArray();
      $type_name = $MaterialTypes[0]['type'];
      $this->set('type_name', $type_name);

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

    }

    public function editdo()
    {
      $material = $this->Materials->newEntity();
      $this->set('material', $material);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $MaterialTypes = $this->MaterialTypes->find()
      ->where(['id' => $data['type_id']])->toArray();
      $type_name = $MaterialTypes[0]['type'];
      $this->set('type_name', $type_name);

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdatematerial = array();
      $arrupdatematerial = [
        'factory_id' => $data["factory_id"],
        'material_code' => $data["material_code"],
        'grade' => $data["grade"],
        'color' => $data["color"],
        'maker' => $data["maker"],
        'type_id' => $data["type_id"],
        'is_active' => 0,
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];
/*
      echo "<pre>";
      print_r($arrupdatematerial);
      echo "</pre>";
*/
      $Materials = $this->Materials->patchEntity($this->Materials->newEntity(), $arrupdatematerial);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Materials->save($Materials)) {

         $this->Materials->updateAll(
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


    public function edit($id = null)
    {
        $material = $this->Materials->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $material = $this->Materials->patchEntity($material, $this->request->getData());
            if ($this->Materials->save($material)) {
                $this->Flash->success(__('The material has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The material could not be saved. Please, try again.'));
        }
        $MaterialTypes = $this->Materials->MaterialTypes->find('list', ['limit' => 200]);
        $this->set(compact('material', 'MaterialTypes'));
    }

    public function deleteconfirm($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['materialdata'];

        $material = $this->Materials->get($id, [
          'contain' => ['MaterialTypes']
        ]);
        $this->set(compact('material'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $material = $this->Materials->get($data["id"], [
        'contain' => ['MaterialTypes']
      ]);
      $this->set(compact('material'));

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeletematerial = array();
      $arrdeletematerial = [
        'id' => $data["id"]
      ];
/*
      echo "<pre>";
      print_r($arrdeletematerial);
      echo "</pre>";
*/
      $Materials = $this->Materials->patchEntity($this->Materials->newEntity(), $arrdeletematerial);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Materials->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeletematerial['id']]
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
