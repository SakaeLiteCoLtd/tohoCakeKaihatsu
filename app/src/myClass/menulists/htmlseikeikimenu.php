<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlseikeikimenu extends AppController
{

     public function seikeikimenus()
  	{
        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
    //    "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%;'>\n".
                      "<ul class='side-nav' >\n".
                      "<br>\n".
                      "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/images/index' /><font size='4' color=black>製造メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='5'>　成形機メニュー</font>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/seikeikis/index' /><font size='4' color=black>成形機一覧</font></a>\n".
                          "<br><br><br><br><br><br><br>\n".
                          "<br><br><br><br><br><br><br>\n".
                          "<br><br><br><br><br><br><br>\n".
                          "<br><br><br><br><br><br><br>\n".
                      "</ul>\n".
                  "</nav>\n";

    		return $html;
    		$this->html = $html;
  	}

}
?>
