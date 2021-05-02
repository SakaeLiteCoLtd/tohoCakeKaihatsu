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
     $this->Companies = TableRegistry::get('Companies');
     $this->Users = TableRegistry::get('Users');
     $this->Menus = TableRegistry::get('Menus');
     $this->Groups = TableRegistry::get('Groups');
     $this->LoginStaffs = TableRegistry::get('LoginStaffs');

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

          $arrtourokulogin  = array();
          $arrtourokulogin  = [
            'staff_id' => $user["staff_id"],
            'login_datetime' => date("Y-m-d H:i:s"),
          ];

          $LoginStaffs = $this->LoginStaffs->patchEntity($this->LoginStaffs->newEntity(), $arrtourokulogin);
          $this->LoginStaffs->save($LoginStaffs);

          return $this->redirect($this->Auth->redirectUrl());
        }
        $this->Flash->error(__('ユーザ名もしくはパスワードが間違っています'));
      }

    }

    public function logout()
    {
      return $this->redirect($this->Auth->logout());
    }

    public function menu()//メニューが追加された時ここを追加する
    {
      $Menus = $this->Menus->find()->where(['delete_flag' => 0])->order(["id"=>"ASC"])->toArray();

      $session = $this->request->getSession();
      $datasession = $session->read();
/*
      echo "<pre>";
      print_r($datasession['Auth']['User']['super_user']);
      echo "</pre>";
*/
      if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合

        $Groups = $this->Groups->find()->contain(["Menus"])//GroupsテーブルとMenusテーブルを関連付ける
        ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Groups.delete_flag' => 0])->order(["menu_id"=>"ASC"])->toArray();

      }else{//スーパーユーザーの場合（全メニュー表示）

        $Menus = $this->Menus->find()
        ->where(['delete_flag' => 0])->order(["id"=>"ASC"])->toArray();

        for($k=0; $k<count($Menus); $k++){
          $Groups[]['menu'] = array('name_menu'=>$Menus[$k]['name_menu']);
        }

      }

      $arrMenus = array();
      $arrController = array();
      for($k=0; $k<count($Groups); $k++){//表示可能なコントローラーの名前を配列に追加する

        if($Groups[$k]['menu']['name_menu'] == "会社"){

          $arrMenus[] = $Groups[$k]['menu']['name_menu'];
          $arrController[] = "companies";

        }elseif($Groups[$k]['menu']['name_menu'] == "工場・営業所"){

          $arrMenus[] = $Groups[$k]['menu']['name_menu'];
          $arrController[] = "offices";

        }elseif($Groups[$k]['menu']['name_menu'] == "部署"){

          $arrMenus[] = $Groups[$k]['menu']['name_menu'];
          $arrController[] = "departments";

        }elseif($Groups[$k]['menu']['name_menu'] == "職種"){

          $arrMenus[] = $Groups[$k]['menu']['name_menu'];
          $arrController[] = "occupations";

        }elseif($Groups[$k]['menu']['name_menu'] == "役職"){

          $arrMenus[] = $Groups[$k]['menu']['name_menu'];
          $arrController[] = "positions";

        }elseif($Groups[$k]['menu']['name_menu'] == "スタッフ"){

          $arrMenus[] = $Groups[$k]['menu']['name_menu'];
          $arrController[] = "staffs";

        }elseif($Groups[$k]['menu']['name_menu'] == "メニュー"){

          $arrMenus[] = $Groups[$k]['menu']['name_menu'];
          $arrController[] = "menus";

        }elseif($Groups[$k]['menu']['name_menu'] == "グループ"){

          $arrMenus[] = $Groups[$k]['menu']['name_menu'];
          $arrController[] = "groups";

        }elseif($Groups[$k]['menu']['name_menu'] == "ユーザー"){

          $arrMenus[] = $Groups[$k]['menu']['name_menu'];
          $arrController[] = "users";

        }elseif($Groups[$k]['menu']['name_menu'] == "スタッフ権限"){

          $arrMenus[] = $Groups[$k]['menu']['name_menu'];
          $arrController[] = "staffAbilities";

        }elseif($Groups[$k]['menu']['name_menu'] == "顧客"){

          $arrMenus[] = $Groups[$k]['menu']['name_menu'];
          $arrController[] = "customers";

        }elseif($Groups[$k]['menu']['name_menu'] == "製品・原料関係"){

          $arrMenus[] = $Groups[$k]['menu']['name_menu'];
          $arrController[] = "products";

        }

      }
      $this->set('arrMenus', $arrMenus);
      $this->set('arrController', $arrController);
    }

}
