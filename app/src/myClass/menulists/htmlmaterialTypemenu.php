<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlmaterialTypemenu extends AppController
{

     public function materialTypemenus()
  	{
        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
                      "<ul class='side-nav' >\n".
                      "<br>\n".
                      "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/staffs/index' /><font size='4' color=black>業務メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='5'>　仕入品種類メニュー</font>\n".
                      "<br><br>\n".

                      "<font size='4'>　・</font><a href='/materialTypes/index' /><font size='4' color=black>仕入品種類一覧</font></a>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/materialTypes/editpreform' /><font size='4' color=black>仕入品種類編集</font></a>\n".
                      "<br><br>\n".
                  
                      "</ul>\n".
                  "</nav>\n";

    		return $html;
    		$this->html = $html;
  	}

}
?>
