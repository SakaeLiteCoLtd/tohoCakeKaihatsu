<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlproductmenu extends AppController
{

     public function productmenus($check_gyoumu)
  	{
        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
                      "<ul class='side-nav' >\n".
                      "<br>\n";

                      if($check_gyoumu == 0){//成形条件のみの権限

                        $html = $html.
                        "<font size='4'>　・</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>成形メニュートップ</font></a>\n".
                        "<br><br>\n".
                        "<font size='4'>　・</font><a href='/products/kensakupreform' /><font size='4' color=black>製品検索</font></a>\n".
                        "<br><br>\n".
                        "<font size='4'>　・</font><a href='/products/kikakueditpreform' /><font size='4' color=black>製品長さ規格編集</font></a>\n".
                        "<br><br>\n";

                      }else{//製品の権限がある

                        $html = $html.
                        "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニュー</font></a>\n".
                        "<br><br>\n".
                        "<font size='4'>　・</font><a href='/staffs/index' /><font size='4' color=black>業務メニュー</font></a>\n".
                        "<br><br>\n".
                        "<font size='4'>　・</font><a href='/images/index' /><font size='4' color=black>製造メニュー</font></a>\n".
                        "<br><br>\n".
                        "<font size='5'>　製品メニュー</font>\n".
                        "<br><br>\n".
                        "<font size='4'>　・</font><a href='/products/ichiran' /><font size='4' color=black>製品一覧</font></a>\n".
                        "<br><br>\n".
                        "<font size='4'>　・</font><a href='/products/editlengthpreform' /><font size='4' color=black>製品長さ追加</font></a>\n".
                        "<br><br>\n".
                        "<font size='4'>　・</font><a href='/products/editpreform' /><font size='4' color=black>製品編集</font></a>\n".
                        "<br><br>\n".
                        "<font size='4'>　・</font><a href='/products/kensakupreform' /><font size='4' color=black>製品検索</font></a>\n".
                        "<br><br>\n".
                        "<font size='4'>　・</font><a href='/products/kikakueditpreform' /><font size='4' color=black>製品長さ規格編集</font></a>\n".
                        "<br><br>\n";

                      }
                      $html = $html.
                      "<br><br><br><br><br><br><br>\n".
                      "<br><br><br><br><br><br><br>\n".
                      "<br><br><br><br><br><br><br>\n".
                      "<br><br><br><br><br><br><br>\n".
                      "<br><br><br><br>\n".
                  "</ul>\n".
              "</nav>\n";
        
/*
                          "<font size='5'>　製品・仕入品メニュー</font>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニューへ戻る</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/products/ichiran' /><font size='4' color=black>製品一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/products/addform' /><font size='4' color=black>製品新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/products/editlengthpreform' /><font size='4' color=black>製品長さ追加</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/products/editpreform' /><font size='4' color=black>製品編集</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/products/kensakupreform' /><font size='4' color=black>製品検索</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materialTypes/index' /><font size='4' color=black>仕入品種類一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materialTypes/addform' /><font size='4' color=black>仕入品種類新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materialTypes/editpreform' /><font size='4' color=black>仕入品種類編集</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materials/index' /><font size='4' color=black>仕入品一覧</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materials/addform' /><font size='4' color=black>仕入品新規登録</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materials/editpreform' /><font size='4' color=black>仕入品編集</font></a>\n".
                          "<br><br>\n".
                          "<font size='4'>　・</font><a href='/materials/kensakupreform' /><font size='4' color=black>仕入品検索</font></a>\n".
*/

    		return $html;
    		$this->html = $html;
  	}

}
?>
