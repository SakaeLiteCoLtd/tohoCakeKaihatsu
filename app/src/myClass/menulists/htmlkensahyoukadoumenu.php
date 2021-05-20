<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlkensahyoukadoumenu extends AppController
{

     public function kensahyoukadoumenus()
  	{
        $html =
            "<table>\n".
            "<td style='border: none'>\n".
            "<a href='/Kensahyoukadous/kensahyoumenu'>\n".
            "<img src='/img/menus/kensahyoumenu.gif' width=145 height=50>\n".
            "</a></td><td style='border: none'>　　　</td>\n".
            "<td style='border: none'>\n".
            "<a href='/Kensahyoukadous/kadoumenu'>\n".
            "<img src='/img/menus/kadoumenu.gif' width=145 height=50>\n".
            "</a></td>\n".
            "</table>\n";

    		return $html;
    		$this->html = $html;
  	}

    public function kensahyoumenus()
   {
       $html =
           "<table>\n".
           "<td style='border: none'>\n".
           "<a href='/Kensahyougenryous/menu'>\n".
           "<img src='/img/menus/genryoumenu.gif' width=145 height=50>\n".
           "</a></td><td style='border: none'>　</td>\n".
           "<td style='border: none'>\n".
           "<a href='/Kensahyoutemperatures/menu'>\n".
           "<img src='/img/menus/seikeiondomenu.gif' width=145 height=50>\n".
           "</a></td><td style='border: none'>　</td>\n".
           "<td style='border: none'>\n".
           "<a href='/Kensahyoukikakus/addlogin'>\n".
           "<img src='/img/menus/kikakutouroku.gif' width=145 height=50>\n".
           "</a></td><td style='border: none'>　</td>\n".
           "<td style='border: none'>\n".
           "<a href='/Kensahyoukikakus/kensakupre'>\n".
           "<img src='/img/menus/kikakukensaku.gif' width=145 height=50>\n".
           "</a></td><td style='border: none'>　</td>\n".
           "<td style='border: none'>\n".
           "<a href='/menus/preform'>\n".
           "<img src='/img/menus/imtaioumenu.gif' width=145 height=50>\n".
           "</a></td><td style='border: none'>　</td>\n".
           "<td style='border: none'>\n".
           "<a href='/Kensahyousokuteidatas/menu'>\n".
           "<img src='/img/menus/sokuteidatatouroku.gif' width=145 height=50>\n".
           "</a></td>\n".
           "</table>\n";

       return $html;
       $this->html = $html;
   }

}
?>
