<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class KensahyoukadousController extends AppController
{

  public function beforeFilter(Event $event){
    parent::beforeFilter($event);

    // 認証なしでアクセスできるアクションの指定
    $this->Auth->allow(["index","kensahyoumenu"]);
  }

    public function index()
    {
    }

    public function kensahyoumenu()
    {
    }

}
