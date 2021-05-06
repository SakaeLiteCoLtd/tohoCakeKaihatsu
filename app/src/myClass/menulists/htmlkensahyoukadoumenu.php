<?php
namespace App\myClass\menulists;

use App\Controller\AppController;

class htmlkensahyoukadoumenu extends AppController
{

     public function kensahyoukadoumenus()
  	{
        $html =
            "<table style='margin-bottom:0px' width='750' border='0' align='center' cellpadding='0' cellspacing='0' bordercolor=none>\n".
            "<td align='center' style='padding: 0.1rem 0.1rem;'>\n".
            "<a href='/Kensahyoukadous/kensahyoumenu'>\n".
            "<img src='/img/menus/kensahyoumenu.gif' width=145 height=50>\n".
            "</a></td>\n".
            "<td align='center' style='padding: 0.1rem 0.1rem;'>\n".
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
           "<table style='margin-bottom:0px' border='0' align='center' cellpadding='0' cellspacing='0' bordercolor=none>\n".
           "<td style='padding: 0.1rem 0.1rem;'>\n".
           "<a href='/menus/preform'>\n".
           "<img src='/img/menus/genryoumenu.gif' width=145 height=50>\n".
           "</a></td><td>　</td>\n".
           "<td style='padding: 0.1rem 0.1rem;'>\n".
           "<a href='/menus/preform'>\n".
           "<img src='/img/menus/seikeiondomenu.gif' width=145 height=50>\n".
           "</a></td><td>　</td>\n".
           "<td style='padding: 0.1rem 0.1rem;'>\n".
           "<a href='/menus/preform'>\n".
           "<img src='/img/menus/kikakutouroku.gif' width=145 height=50>\n".
           "</a></td><td>　</td>\n".
           "<td style='padding: 0.1rem 0.1rem;'>\n".
           "<a href='/menus/preform'>\n".
           "<img src='/img/menus/imtaioumenu.gif' width=145 height=50>\n".
           "</a></td><td>　</td>\n".
           "<td style='padding: 0.1rem 0.1rem;'>\n".
           "<a href='/menus/preform'>\n".
           "<img src='/img/menus/kikakukensaku.gif' width=145 height=50>\n".
           "</a></td>\n".
           "</table>\n";

       return $html;
       $this->html = $html;
   }

}
?>
