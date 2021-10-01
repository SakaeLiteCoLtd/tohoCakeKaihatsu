<?php
namespace App\myClass\menulists;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class htmlkensahyoukadoumenu extends AppController
{

  public function initialize()
 {
     parent::initialize();
     $this->Products = TableRegistry::get('Products');
     $this->InspectionStandardSizeParents = TableRegistry::get('InspectionStandardSizeParents');
}

     public function kensahyoukadoumenus()
  	{
        $html =
            "<table>\n".
            "<td style='border: none'>\n".
            "<a href='/Kensahyoukadous/kensahyoumenu'>\n".
            "<img src='/img/menus/kensahyoumenu.gif' width=145 height=50>\n".
   //         "</a></td><td style='border: none'>　　　</td>\n".
     //       "<td style='border: none'>\n".
       //     "<a href='/Kensahyoukadous/kadoumenu'>\n".
         //   "<img src='/img/menus/kadoumenu.gif' width=145 height=50>\n".
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
           "<a href='/Kensahyoukadous/login'>\n".
           "<img src='/img/menus/imgkensahyou.gif' width=145 height=50>\n".
           "</a></td><td style='border: none'>　</td>\n".

           "<td style='border: none'>\n".
           "<a href='/Kensahyoukikakus/menu'>\n".
           "<img src='/img/menus/kikakutouroku.gif' width=145 height=50>\n".
           "</a></td><td style='border: none'>　</td>\n".

           "<td style='border: none'>\n".
           "<a href='/Kensahyougenryous/menu'>\n".
           "<img src='/img/menus/genryoumenu.gif' width=145 height=50>\n".
           "</a></td><td style='border: none'>　</td>\n".
           "<td style='border: none'>\n".
          "<td style='border: none'>\n".
           "<a href='/Kensahyoutemperatures/menu'>\n".
           "<img src='/img/menus/seikeiondomenu.gif' width=145 height=50>\n".
           "</a></td><td style='border: none'>　</td>\n".
           "<td style='border: none'>\n".
           /*
           "<a href='/Kensahyoukikakus/kensakupre'>\n".
           "<img src='/img/menus/kikakukensaku.gif' width=145 height=50>\n".
           "</a></td><td style='border: none'>　</td>\n".
           "<td style='border: none'>\n".
           */
           "<a href='/Kensahyousokuteidatas/menu'>\n".
           "<img src='/img/menus/sokuteidatatouroku.gif' width=145 height=50>\n".
           "</a></td>\n".
           "</table>\n";

       return $html;
       $this->html = $html;
   }

   public function kensahyouheader($product_code)
  {

    $Products= $this->Products->find()->contain(["Customers"])->where(['product_code' => $product_code])->toArray();

    if(isset($Products[0])){

      $name = $Products[0]["name"];
      $customer= $Products[0]["customer"]["name"];

    }else{

      $name = "no_product";
      $customer= "no_product";

    }

    $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
    ->where(['product_code' => $product_code, 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
    ->order(["version"=>"DESC"])->toArray();

    if(!isset($inspectionStandardSizeParents[0])){//長さ違いのデータがあればそれを持ってくる

      $product_code_ini = substr($product_code, 0, 11);
      $inspectionStandardSizeParents = $this->InspectionStandardSizeParents->find()->contain(["Products"])
      ->where(['product_code like' => $product_code_ini.'%', 'InspectionStandardSizeParents.is_active' => 0, 'InspectionStandardSizeParents.delete_flag' => 0])
     ->order(["version"=>"DESC"])->toArray();
 
    }

    if(isset($inspectionStandardSizeParents[0])){
      $date_kaitei = $inspectionStandardSizeParents[0]["created_at"]->format('Y-n-j');
      $image_file_name_dir = "/img/".$inspectionStandardSizeParents[0]["image_file_name_dir"];
      }else{
        $date_kaitei = "";
        $image_file_name_dir = "/img/kensahyouimg/noimag.png";
          }

      $html =
  //    "<table bgcolor='white' width='1000' style='position: fixed;top: 85px; left:20%; z-index:9999;'>\n".//固定
      "<table bgcolor='white' width='1436'>\n".
          "<tr>\n".
          "<td width='500' colspan='2' nowrap='nowrap' style='height: 40px'><strong>\n".
          "検査成績書</strong><br>（兼　成形条件表・梱包仕様書・作業手順書）\n".
          "</td>\n".
          "<td width='100' nowrap='nowrap' style='height: 20px'><strong>製品名</td>\n".
          "<td width='400' nowrap='nowrap' style='height: 20px'>$name</td>\n".
          "</tr>\n".
          "<tr>\n".
          "<td width='200' nowrap='nowrap' style='height: 20px'><strong>\n".
          "管理No\n".
          "</td>\n".
          "<td width='300' style='height: 30px'>$product_code</td>\n".
          "<td width='200' rowspan='2' style='height: 20px'><strong>得意先名</td>\n".
          "<td width='300' rowspan='2' style='height: 20px'>$customer</td>\n".
          "</tr>\n".
          "<tr>\n".
          "<td width='200' nowrap='nowrap' style='height: 20px'><strong>\n".
          "改訂日\n".
          "</td>\n".
          "<td width='300' style='height: 20px'>$date_kaitei</td>\n".
          "</tr>\n".
          "<tr>\n".
          "<td width='1000' colspan='4' nowrap='nowrap' style='height: 350px;'>\n".
          "<img src=$image_file_name_dir width=800></td>\n".
          "</tr>\n".
          "</table>\n";

      return $html;
      $this->html = $html;
  }

}
?>
