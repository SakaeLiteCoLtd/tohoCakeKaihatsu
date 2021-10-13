<?php
namespace App\myClass\classprograms;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class htmlkensahyoulogincheck extends AppController
{
     public function initialize()
    {
        parent::initialize();
        $this->Staffs = TableRegistry::get('Staffs');
        $this->Users = TableRegistry::get('Users');
        $this->Menus = TableRegistry::get('Menus');
        $this->Groups = TableRegistry::get('Groups');
      }

    public function kensahyoulogincheckprogram($user_code)
   {

    $Users = $this->Users->find()
    ->where(['user_code' => $user_code, 'delete_flag' => 0])->toArray();

    if(isset($Users[0])){

      if($Users[0]['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

        $Groups = $this->Groups->find()->contain(["Menus"])
        ->where(['Groups.name_group' => $Users[0]['group_name'], 'Menus.name_menu' => "成形条件表", 'Groups.delete_flag' => 0])
        ->toArray();

        if(isset($Groups[0])){

          $logincheck = 0;//ログインOK

        }else{

          $logincheck = 1;//権限なし

        }

      }else{

        $logincheck = 0;//ログインOK
      
      }
  
    }else{

      $logincheck = 1;//権限なし
 
    }
 
     return $logincheck;

   }

}

?>
