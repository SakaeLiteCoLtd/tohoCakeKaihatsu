<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlmaterialmenu extends AppController
{

     public function materialmenus()
  	{
        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
                      "<ul class='side-nav' >\n".
                      "<br>\n".
                          "<font size='5'>　仕入品関係メニュー</font>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materialTypes/addform' /><font size='4' color=black>仕入品種類新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materialTypes/editpreform' /><font size='4' color=black>仕入品種類検索</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materialTypes/index' /><font size='4' color=black>仕入品種類一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materials/addform' /><font size='4' color=black>仕入品新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materials/editpreform' /><font size='4' color=black>仕入品検索</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materials/index' /><font size='4' color=black>仕入品一覧</font></a>\n".
                          "<br><br>\n".
                          /*
                          "<font size='4'>　・</font><a href='/materialSuppliers/addform' /><font size='4' color=black>仕入品仕入先新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materialSuppliers/editpreform' /><font size='4' color=black>仕入品仕入先検索</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materialSuppliers/index' /><font size='4' color=black>仕入品仕入先一覧</font></a>\n".
                          "<br><br>\n".
                          */
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
