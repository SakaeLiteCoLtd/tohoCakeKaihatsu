<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

class AppController extends Controller
{
  public function initialize()
  {
      parent::initialize();

      $response =  $this->getResponse()->withDisabledCache();
      $this->setResponse($response);
      
      $this->loadComponent('RequestHandler');
      $this->loadComponent('Flash');
      $this->loadComponent('Auth', [
          'loginAction' => [
              'controller' => 'Startmenus',
              'action' => 'login'
          ],

          'loginRedirect' => [
            'controller' => 'Startmenus',
            'action' => 'menu'
      //      'controller' => 'Companies',
      //      'action' => 'index'
          ],

          'logoutRedirect' => [
              'controller' => 'Startmenus',
              'action' => 'menu',
      //        'home'
          ],
          'authenticate' => [
              'Form' => [
                  'fields' => ['username' => 'user_code', 'password' => 'password']
              ]
          ],
      ]);
  }

  // 認証を通さないアクションがある場合
  public function beforeFilter(Event $event)
  {
    $this->Auth->config('authError', "");
 //   $this->Auth->config('authError', "ログインしてください。");
//    $this->Auth->allow(['login']);

/*
    // PC/スマホのview切り替え
    if ($this->request->isMobile()) {
        // plugins/Sp/Template内のviewが読み込まれる
        $this->viewBuilder()->theme('Sp');
    }
*/

  }

}
