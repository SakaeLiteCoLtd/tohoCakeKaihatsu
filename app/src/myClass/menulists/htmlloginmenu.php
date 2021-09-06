<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlloginmenu extends AppController
{

     public function Loginmenu()
  	{
        $html =
        "<table align='right' style='border:none; background-color:#E6FFFF'><tbody class='sample non-sample'><tr style='border:none'><td style='border:none'>\n";
        $html = $html."ログイン中：".$this->request->Session()->read('Auth.User.user_code')."　　";
        $html = $html.
        "</td>\n".
        "<td style='border:none'>\n".
        "<font size='4'>\n".
        "<a href='/Startmenus/logout' />ログアウト</link>\n".
        "</font>\n".
        "</td>\n".
        "<td style='border:none'>　　</td>\n".
        "</tr></tbody></table>\n";

    		return $html;
    		$this->html = $html;
  	}

}
?>
