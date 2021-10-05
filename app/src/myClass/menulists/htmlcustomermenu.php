<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlcustomermenu extends AppController
{

     public function Customersmenus()
  	{
        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
                      "<ul class='side-nav' >\n".
                      "<br>\n".
                      "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/staffs/index' /><font size='4' color=black>業務メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='5'>　得意先・仕入先メニュー</font>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/customers/index' /><font size='4' color=black>得意先・仕入先一覧</font></a>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/customers/editpreform' /><font size='4' color=black>得意先・仕入先検索（編集）</font></a>\n".
                      "<br><br>\n".
                          "<font size='4'>　・</font><a href='/customers/kensakupreform' /><font size='4' color=black>得意先・仕入先検索（一覧）</font></a>\n".
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
