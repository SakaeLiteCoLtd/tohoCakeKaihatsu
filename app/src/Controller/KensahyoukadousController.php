<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class KensahyoukadousController extends AppController
{

  public function initialize()
  {
   parent::initialize();
   $this->Users = TableRegistry::get('Users');
   $this->LoginStaffs = TableRegistry::get('LoginStaffs');
  }

  public function beforeFilter(Event $event){
    parent::beforeFilter($event);

    // 認証なしでアクセスできるアクションの指定
    $this->Auth->allow(["login","index","kensahyoumenu"]);
  }

    public function index()
    {
    }

    public function kensahyoumenu()
    {
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

}
