<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlmaterialSuppliermenu extends AppController
{

     public function MaterialSuppliersmenus()
  	{
        $html =
                  "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%'>\n".
                      "<ul class='side-nav' style='background-color:#afeeee'>\n".
                      "<br>\n".
                          "<font size='5'>　原料仕入先メニュー</font>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materialSuppliers/addform' /><font size='4' color=black>原料仕入先新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materialSuppliers/index' /><font size='4' color=black>原料仕入先メニュートップ</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニューへ戻る</font></a>\n".
                          "<br><br><br><br><br><br><br>\n".
                      "</ul>\n".
                  "</nav>\n";

    		return $html;
    		$this->html = $html;
  	}

}
?>
