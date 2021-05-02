<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmloccupationmenu extends AppController
{

     public function Occupationmenus()
  	{
        $html =
                  "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%'>\n".
                      "<ul class='side-nav' style='background-color:#afeeee'>\n".
                      "<br>\n".
                          "<font size='5'>　職種メニュー</font>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/occupations/addform' /><font size='4' color=black>職種新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/occupations/index' /><font size='4' color=black>職種メニュートップ</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニューへ戻る</font></a>\n".
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
