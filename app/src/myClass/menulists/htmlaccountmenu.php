<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlaccountmenu extends AppController
{

     public function accountmenus()
  	{
        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
                      "<ul class='side-nav' >\n".
                      "<br>\n".
                      "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='5'>　管理者用メニュー</font>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/tanis/index' /><font size='4' color=black>単位登録</font></a>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/accounts/customercodeselect' /><font size='4' color=black>得意先・仕入先コード変更</font></a>\n".
                      "<br><br>\n".
                      /*
                      "<font size='4'>　・</font><a href='/accounts/productcodeselect' /><font size='4' color=black>製品コード変更</font></a>\n".
                      "<br><br>\n".
                      */
                      "<font size='4'>　・</font><a href='/accounts/productdeletedselect' /><font size='4' color=black>削除済み製品復元</font></a>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/accounts/materialdeletedselect' /><font size='4' color=black>削除済み仕入品復元</font></a>\n".
                      "<br><br>\n".
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

      public function customercodemenus()
  	{
        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
                      "<ul class='side-nav' >\n".
                      "<br>\n".
                      "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/accounts/index' /><font size='4' color=black>管理者用メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='5'>　得意先・仕入先コード変更</font>\n".
                      "<br><br>\n".
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

      public function productcodemenus()
  	{
        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
                      "<ul class='side-nav' >\n".
                      "<br>\n".
                      "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/accounts/index' /><font size='4' color=black>管理者用メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='5'>　製品コード変更</font>\n".
                      "<br><br>\n".
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

      public function productdeletemenus()
  	{
        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
                      "<ul class='side-nav' >\n".
                      "<br>\n".
                      "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/accounts/index' /><font size='4' color=black>管理者用メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='5'>　削除済み製品復元</font>\n".
                      "<br><br>\n".
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

      public function materialdeletemenus()
  	{
        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
                      "<ul class='side-nav' >\n".
                      "<br>\n".
                      "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/accounts/index' /><font size='4' color=black>管理者用メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='5'>　削除済み仕入品復元</font>\n".
                      "<br><br>\n".
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
