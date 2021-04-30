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

    public function index()
    {
        $materialTypes = $this->paginate($this->MaterialTypes);

        $this->set(compact('materialTypes'));
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
    }

    public function addcomfirm()
    {
      $materialType = $this->MaterialTypes->newEntity();
      $this->set('materialType', $materialType);
    }

    public function adddo()
    {
      $materialType = $this->MaterialTypes->newEntity();
      $this->set('materialType', $materialType);

      $data = $this->request->getData();

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrtourokumaterialType = array();
      $arrtourokumaterialType = [
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
      $materialType = $this->MaterialTypes->get($id, [
        'contain' => []
      ]);
      $this->set(compact('materialType'));
      $this->set('id', $id);
    }

    public function editconfirm()
    {
      $materialType = $this->MaterialTypes->newEntity();
      $this->set('materialType', $materialType);
    }

    public function editdo()
    {
      $materialType = $this->MaterialTypes->newEntity();
      $this->set('materialType', $materialType);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrupdatematerialType = array();
      $arrupdatematerialType = [
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
        $materialType = $this->MaterialTypes->get($id, [
            'contain' => []
        ]);
        $this->set(compact('materialType'));
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $materialType = $this->MaterialTypes->get($data["id"], [
          'contain' => []
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
