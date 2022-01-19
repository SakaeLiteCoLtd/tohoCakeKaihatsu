<?php
namespace App\myClass\menulists;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class htmlstaffmenu extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Menus = TableRegistry::get('Menus');
        $this->Groups = TableRegistry::get('Groups');
    }

     public function Staffmenus()
  	{
        $session = $this->request->getSession();
        $datasession = $session->read();

        if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合

            $Groups_departments = $this->Groups->find()->contain(["Menus"])
            ->where(['Groups.group_code' => $datasession['Auth']['User']['group_code'], 'Menus.id' => 30, 'Groups.delete_flag' => 0])
            ->toArray();
            if(isset($Groups_departments[0])){
                $check_departments = 1;
            }else{
                $check_departments = 0;
            }

            $Groups_positions = $this->Groups->find()->contain(["Menus"])
            ->where(['Groups.group_code' => $datasession['Auth']['User']['group_code'], 'Menus.id' => 31, 'Groups.delete_flag' => 0])
            ->toArray();
            if(isset($Groups_positions[0])){
                $check_positions = 1;
            }else{
                $check_positions = 0;
            }

            $Groups_groups = $this->Groups->find()->contain(["Menus"])
            ->where(['Groups.group_code' => $datasession['Auth']['User']['group_code'], 'Menus.id' => 32, 'Groups.delete_flag' => 0])
            ->toArray();
            if(isset($Groups_groups[0])){
                $check_groups = 1;
            }else{
                $check_groups = 0;
            }

            $Groups_staffs = $this->Groups->find()->contain(["Menus"])
            ->where(['Groups.group_code' => $datasession['Auth']['User']['group_code'], 'Menus.id' => 33, 'Groups.delete_flag' => 0])
            ->toArray();
            if(isset($Groups_staffs[0])){
                $check_staffs = 1;
            }else{
                $check_staffs = 0;
            }

            $Groups_customers = $this->Groups->find()->contain(["Menus"])
            ->where(['Groups.group_code' => $datasession['Auth']['User']['group_code'], 'Menus.id' => 34, 'Groups.delete_flag' => 0])
            ->toArray();
            if(isset($Groups_customers[0])){
                $check_customers = 1;
            }else{
                $check_customers = 0;
            }
/*
            $Groups_products = $this->Groups->find()->contain(["Menus"])
            ->where(['Groups.name_group' => $datasession['Auth']['User']['group_name'], 'Menus.name_menu' => "製品", 'Groups.delete_flag' => 0])
            ->toArray();
            if(isset($Groups_products[0])){
                $check_products = 1;
            }else{
                $check_products = 0;
            }
*/
            $Groups_materialTypes = $this->Groups->find()->contain(["Menus"])
            ->where(['Groups.group_code' => $datasession['Auth']['User']['group_code'], 'Menus.id' => 36, 'Groups.delete_flag' => 0])
            ->toArray();
            if(isset($Groups_materialTypes[0])){
                $check_materialTypes = 1;
            }else{
                $check_materialTypes = 0;
            }

            $Groups_materials = $this->Groups->find()->contain(["Menus"])
            ->where(['Groups.group_code' => $datasession['Auth']['User']['group_code'], 'Menus.id' => 37, 'Groups.delete_flag' => 0])
            ->toArray();
            if(isset($Groups_materials[0])){
                $check_materials = 1;
            }else{
                $check_materials = 0;
            }

        }else{//スーパーユーザーの場合

            $check_departments = 1;
            $check_positions = 1;
            $check_groups = 1;
            $check_staffs = 1;
            $check_customers = 1;
            $check_products = 1;
            $check_materialTypes = 1;
            $check_materials = 1;
            
        }

        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
    //    "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%;'>\n".
                      "<ul class='side-nav' >\n".
                      "<br>\n".
                          "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニュー</font></a>\n".
                          "<br><br>\n".
                          "<font size='5'>　業務メニュー</font>\n".
                          "<br><br>\n";

                        if($check_departments == 1){
                            $html = $html.
                            "<font size='4'>　・</font><a href='/departments/index' /><font size='4' color=black>部署メニュー</font></a>\n".
                            "<br><br>\n";
                          }

                          if($check_positions == 1){
                            $html = $html.
                            "<font size='4'>　・</font><a href='/positions/index' /><font size='4' color=black>職種メニュー</font></a>\n".
                            "<br><br>\n";
                            }

                            if($check_groups == 1){
                                $html = $html.
                                "<font size='4'>　・</font><a href='/groups/index' /><font size='4' color=black>権限グループメニュー</font></a>\n".
                                "<br><br>\n";
                            }
    
                            if($check_staffs == 1){
                                $html = $html.
                                "<font size='4'>　・</font><a href='/staffs/ichiran' /><font size='4' color=black>メンバーメニュー</font></a>\n".
                                "<br><br>\n";
                            }

                            if($check_customers == 1){
                                $html = $html.
                                "<font size='4'>　・</font><a href='/customers/index' /><font size='4' color=black>得意先・仕入先メニュー</font></a>\n".
                                "<br><br>\n";
                            }
/*
                            if($check_products == 1){
                                $html = $html.
                                "<font size='4'>　・</font><a href='/products/ichiran' /><font size='4' color=black>製品メニュー</font></a>\n".
                                "<br><br>\n";
                            }
*/
                            if($check_materialTypes == 1){
                                $html = $html.
                                "<font size='4'>　・</font><a href='/materialTypes/index' /><font size='4' color=black>仕入品種類メニュー</font></a>\n".
                                "<br><br>\n";
                            }

                            if($check_materials == 1){
                                $html = $html.
                                "<font size='4'>　・</font><a href='/materials/index' /><font size='4' color=black>仕入品メニュー</font></a>\n".
                                "<br><br>\n";
                                  }

                          $html = $html.
/*
                          "<font size='4'>　・</font><a href='/departments/addform' /><font size='4' color=black>部署新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/departments/index' /><font size='4' color=black>部署一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/positions/addform' /><font size='4' color=black>職種新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/positions/index' /><font size='4' color=black>職種一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/groups/addpre' /><font size='4' color=black>権限グループ新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/groups/index' /><font size='4' color=black>権限グループ一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/staffs/addform' /><font size='4' color=black>メンバー新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/staffs/index' /><font size='4' color=black>メンバー一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/users/addpre' /><font size='4' color=black>ユーザー新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/users/index' /><font size='4' color=black>ユーザー一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/staffAbilities/index' /><font size='4' color=black>メンバー権限一覧</font></a>\n".
*/
                          "<br><br><br><br><br><br><br>\n".
                          "<br><br><br><br><br><br><br>\n".
                          "<br><br><br><br><br><br><br>\n".
                          "<br><br><br><br><br><br><br>\n".
                          "<br><br><br><br>\n".
                      "</ul>\n".
                  "</nav>\n";

    		return $html;
    		$this->html = $html;
  	}

}
?>
