<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlstaffmenu extends AppController
{

     public function Staffmenus()
  	{
        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
    //    "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%;'>\n".
                      "<ul class='side-nav' >\n".
                      "<br>\n".
                          "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニュー</font></a>\n".
                          "<br><br>\n".
                          "<font size='5'>　業務メニュー</font>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/departments/index' /><font size='4' color=black>部署メニュー</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/positions/index' /><font size='4' color=black>職種メニュー</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/groups/index' /><font size='4' color=black>権限グループメニュー</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/staffs/ichiran' /><font size='4' color=black>メンバーメニュー</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/customers/index' /><font size='4' color=black>得意先・仕入先メニュー</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/products/ichiran' /><font size='4' color=black>製品メニュー</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materialTypes/index' /><font size='4' color=black>仕入品種類メニュー</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materials/index' /><font size='4' color=black>仕入品メニュー</font></a>\n".
                          "<br><br>\n".
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
