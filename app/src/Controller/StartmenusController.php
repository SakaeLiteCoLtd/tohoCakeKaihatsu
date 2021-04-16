<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Event\Event;//ログインに使用

class StartmenusController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Companies = TableRegistry::get('Companies');//productsテーブルを使う
     $this->Users = TableRegistry::get('Users');//productsテーブルを使う

     //loginをチェックしてlogoutしていたらログイン画面へ移動する
    }

    public function login()
    {
      $Users = $this->Users->newEntity();
      $this->set('Users',$Users);

      if ($this->request->is('post')) {
        $user = $this->Auth->identify();
        if ($user) {
          $this->Auth->setUser($user);
    //      return $this->redirect(['action' => 'menu']);
          return $this->redirect($this->Auth->redirectUrl());
        }
        $this->Flash->error(__('ユーザ名もしくはパスワードが間違っています'));
      }

    }

    public function logout()
    {
      return $this->redirect($this->Auth->logout());
    }

    public function menu()
    {
      $companies = $this->paginate($this->Companies);
      $this->set(compact('companies'));

      $session = $this->request->getSession();
      $datasession = $session->read();
/*
      echo "<pre>";
      print_r($datasession);
      echo "</pre>";
*/
    }




}
