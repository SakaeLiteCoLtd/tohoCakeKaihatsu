<?php
namespace App\myClass\menulists;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class htmlimgmenutop extends AppController
{

        public function initialize()
    {
        parent::initialize();
        $this->Menus = TableRegistry::get('Menus');
        $this->Groups = TableRegistry::get('Groups');
    }

     public function Imgmenus()
  	{
        $session = $this->request->getSession();
        $datasession = $session->read();

        if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合

            $Groups_products = $this->Groups->find()->contain(["Menus"])
            ->where(['Groups.group_code' => $datasession['Auth']['User']['group_code'], 'Menus.id' => 35, 'Groups.delete_flag' => 0])
            ->toArray();
            if(isset($Groups_products[0])){
                $check_products = 1;
            }else{
                $check_products = 0;
            }

            $Groups_images = $this->Groups->find()->contain(["Menus"])
            ->where(['Groups.group_code' => $datasession['Auth']['User']['group_code'], 'Menus.id' => 38, 'Groups.delete_flag' => 0])
            ->toArray();
            if(isset($Groups_images[0])){
                $check_images = 1;
            }else{
                $check_images = 0;
            }

            $Groups_seikeikis = $this->Groups->find()->contain(["Menus"])
            ->where(['Groups.group_code' => $datasession['Auth']['User']['group_code'], 'Menus.id' => 40, 'Groups.delete_flag' => 0])
            ->toArray();
            if(isset($Groups_seikeikis[0])){
                $check_seikeikis = 1;
            }else{
                $check_seikeikis = 0;
            }

        }else{//スーパーユーザーの場合

            $check_products = 1;
            $check_images = 1;
            $check_seikeikis = 1;
            
        }

        $html =
        "<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; position: fixed;top: 0px; left:0%'>\n".
                      "<ul class='side-nav' >\n".
                      "<br>\n".

                      "<font size='4'>　・</font><a href='/Startmenus/menu' /><font size='4' color=black>総合メニュー</font></a>\n".
                      "<br><br>\n".
                      "<font size='5'>　製造メニュー</font>\n".
                      "<br><br>\n";

                      if($check_products == 1){
                        $html = $html.
                        "<font size='4'>　・</font><a href='/products/ichiran' /><font size='4' color=black>製品メニュー</font></a>\n".
                        "<br><br>\n";
                        }
/*
                        if($check_images == 1){
                            $html = $html.
                            "<font size='4'>　・</font><a href='/images/ichiran' /><font size='4' color=black>検査表画像メニュー</font></a>\n".
                            "<br><br>\n";
                                }
*/
                            if($check_seikeikis == 1){
                                $html = $html.
                                "<font size='4'>　・</font><a href='/linenames/index' /><font size='4' color=black>ラインメニュー</font></a>\n".
                                "<br><br>\n";
                                }
                            if($check_seikeikis == 1){
                                $html = $html.
                                "<font size='4'>　・</font><a href='/seikeikis/index' /><font size='4' color=black>成形機メニュー</font></a>\n".
                                "<br><br>\n";
                                }
                                    if($check_seikeikis == 1){
                                        $html = $html.
                                        "<font size='4'>　・</font><a href='/kensakigus/index' /><font size='4' color=black>検査器具メニュー</font></a>\n".
                                        "<br><br>\n";
                                        }
                                        /*
                                        if($check_seikeikis == 1){
                                            $html = $html.
                                            "<font size='4'>　・</font><a href='/tanis/index' /><font size='4' color=black>単位メニュー</font></a>\n".
                                            "<br><br>\n";
                                            }
                */
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
