<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlpriceProductmenu extends AppController
{

     public function priceProductsmenus()
  	{
        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
                      "<ul class='side-nav' style='background-color:#afeeee'>\n".
                      "<br>\n".
                          "<font size='5'>　単価関係メニュー</font>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/priceMaterials/addform' /><font size='4' color=black>原料単価新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/priceMaterials/index' /><font size='4' color=black>原料単価一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/priceProducts/addform' /><font size='4' color=black>製品単価新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/priceProducts/ichiran' /><font size='4' color=black>製品単価一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/priceProducts/index' /><font size='4' color=black>単価関係メニュートップ</font></a>\n".
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
