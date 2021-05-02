<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlproductMaterialmenu extends AppController
{

     public function productMaterialsmenus()
  	{
        $html =
                  "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%'>\n".
                      "<ul class='side-nav' style='background-color:#afeeee'>\n".
                      "<br>\n".
                          "<font size='5'>　製品原料メニュー</font>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/productMaterials/addform' /><font size='4' color=black>製品原料新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/productMaterials/index' /><font size='4' color=black>製品原料メニュートップ</font></a>\n".
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
