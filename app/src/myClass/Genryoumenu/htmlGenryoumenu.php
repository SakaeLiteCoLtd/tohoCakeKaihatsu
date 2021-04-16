<?php
namespace App\myClass\Genryoumenu;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション

class htmlGenryoumenu extends AppController
{
     public function initialize()
    {
        parent::initialize();
         $this->Staffs = TableRegistry::get('staffs');//staffsテーブルを使う
         $this->Products = TableRegistry::get('products');//productsテーブルを使う
    }

     public function Genryoumenus()
  	{
        $html =
                  "<td style='padding: 0.1rem 0.1rem;'><a href='/Genryous/tourokuzumisearch'>\n".
                  "<img src='/img/Labelimg/gen_yobisumi.gif' width=115 height=40>\n".
                  "</a>\n".
                  "<td style='padding: 0.1rem 0.1rem;'><a href='/Genryous/nyuukomenu'>\n".
                  "<img src='/img/Labelimg/gen_nyuuko.gif' width=115 height=40>\n".
                  "</a>\n".
                  "<td style='padding: 0.1rem 0.1rem;'><a href='/Genryous/csvtest'>\n".
                  "<img src='/img/Labelimg/testcsv.gif' width=115 height=40>\n".
                  "</a>\n".
                  "<td style='padding: 0.1rem 0.1rem;'><a href='/Genryous/gazoutest'>\n".
                  "<img src='/img/Labelimg/testgazou.gif' width=115 height=40>\n".
                  "</a>\n";

    		return $html;
    		$this->html = $html;
  	}

    public function nyuukomenus()
   {
       $html =
                 "<td style='padding: 0.1rem 0.1rem;'><a href='/Genryous/nyuukotyouka'>\n".
                 "<img src='/img/Labelimg/nyuukokigen.gif' width=100 height=40>\n".
                 "</a>\n".
                 "<td style='padding: 0.1rem 0.1rem;'><a href='/Genryous/nyuukominyuuka'>\n".
                 "<img src='/img/Labelimg/nyuukominyuka.gif' width=100 height=40>\n".
                 "</a>\n".
                 "<td style='padding: 0.1rem 0.1rem;'><a href='/Genryous/nyuukonouki'>\n".
                 "<img src='/img/Labelimg/nyuukonouki.gif' width=100 height=40>\n".
                 "</a>\n";

       return $html;
       $this->html = $html;
   }

   public function csvmenus()
  {
      $html =
                "<td style='padding: 0.1rem 0.1rem;'><a href='/Genryous/csvtest1d'>\n".
                "<img src='/img/Labelimg/test1d.gif' width=100 height=40>\n".
                "</a>\n".
                "<td style='padding: 0.1rem 0.1rem;'><a href='/Genryous/csvtest1w'>\n".
                "<img src='/img/Labelimg/test1w.gif' width=100 height=40>\n".
                "</a>\n";

      return $html;
      $this->html = $html;
  }

}

?>
