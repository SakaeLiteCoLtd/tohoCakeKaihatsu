<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlimgmenutop extends AppController
{

     public function Imgmenus()
  	{
        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
                      "<ul class='side-nav' >\n".
                      "<br>\n".

                      "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='5'>　製造メニュー</font>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/images/ichiran' /><font size='4' color=black>検査表画像メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/products/ichiran' /><font size='4' color=black>製品メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='4'>　・</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>成形メニュートップへ</font></a>\n".

/*
                          "<font size='5'>　検査表画像メニュー</font>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニューへ戻る</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/images/index' /><font size='4' color=black>検査表画像一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/images/addpre' /><font size='4' color=black>検査表画像新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/images/kensakupreform' /><font size='4' color=black>検査表画像検索</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>成形メニュートップ</font></a>\n".
*/
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
