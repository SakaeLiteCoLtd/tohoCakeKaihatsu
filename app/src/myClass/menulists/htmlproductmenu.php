<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlproductmenu extends AppController
{

     public function productmenus()
  	{
        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
                      "<ul class='side-nav' style='background-color:#afeeee'>\n".
                      "<br>\n".
                          "<font size='5'>　製品・原料関係メニュー</font>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materialTypes/addform' /><font size='4' color=black>原料種類新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materialTypes/index' /><font size='4' color=black>原料種類一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materials/addform' /><font size='4' color=black>原料新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materials/index' /><font size='4' color=black>原料一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/products/addform' /><font size='4' color=black>製品新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/products/ichiran' /><font size='4' color=black>製品一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materialSuppliers/addform' /><font size='4' color=black>原料仕入先新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materialSuppliers/index' /><font size='4' color=black>原料仕入先一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/products/index' /><font size='4' color=black>製品・原料関係メニュートップ</font></a>\n".
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
