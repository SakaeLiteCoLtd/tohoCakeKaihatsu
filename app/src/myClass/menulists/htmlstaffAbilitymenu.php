<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlstaffAbilitymenu extends AppController
{

     public function StaffAbilitymenus()
  	{
        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
                      "<ul class='side-nav' >\n".
                      "<br>\n".
                      "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/staffs/index' /><font size='4' color=black>業務メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/staffs/ichiran' /><font size='4' color=black>メンバーメニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='5'>　メンバー権限一覧</font>\n".
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
