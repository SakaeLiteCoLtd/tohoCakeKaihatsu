<?php
namespace App\myClass\classprograms;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class htmlkensahyouprogram extends AppController
{
     public function initialize()
    {
        parent::initialize();
        $this->Users = TableRegistry::get('Users');
        $this->Staffs = TableRegistry::get('Staffs');
        $this->Products = TableRegistry::get('Products');
        $this->Customers = TableRegistry::get('Customers');
        $this->Materials = TableRegistry::get('Materials');
        $this->ProductConditionParents = TableRegistry::get('ProductConditionParents');
        $this->ProductMaterialMachines = TableRegistry::get('ProductMaterialMachines');
        $this->ProductMachineMaterials = TableRegistry::get('ProductMachineMaterials');
        $this->ProductConditonChildren = TableRegistry::get('ProductConditonChildren');
    }

    public function genryouheader($product_code_machine_num)
   {
    $arrproduct_code_machine_num = explode("_",$product_code_machine_num);
    $product_code = $arrproduct_code_machine_num[0];
    $machine_num = $arrproduct_code_machine_num[1];

     $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
     ->where(['machine_num' => $machine_num, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
     ->order(["version"=>"DESC"])->toArray();

     if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる

      $product_code_ini = substr($product_code, 0, 11);
      $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();
 
    }

     $version = $ProductConditionParents[0]["version"];
     $machine_num = $ProductConditionParents[0]["machine_num"];

     $ProductMaterialMachines = $this->ProductMaterialMachines->find()
     ->contain(['ProductConditionParents' => ["Products"]])
     ->where(['machine_num' => $machine_num, 'version' => $version, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0, 'ProductMaterialMachines.delete_flag' => 0])
     ->order(["cylinder_number"=>"ASC"])->toArray();

     if(!isset($ProductMaterialMachines[0])){//長さ違いのデータがあればそれを持ってくる

      $product_code_ini = substr($product_code, 0, 11);
      $ProductMaterialMachines = $this->ProductMaterialMachines->find()
     ->contain(['ProductConditionParents' => ["Products"]])
     ->where(['machine_num' => $machine_num, 'version' => $version, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0, 'ProductMaterialMachines.delete_flag' => 0])
     ->order(["cylinder_number"=>"ASC"])->toArray();

    }

     $tuikaseikeiki = count($ProductMaterialMachines);
     $this->set('tuikaseikeiki', $tuikaseikeiki);

     for($j=1; $j<=$tuikaseikeiki; $j++){

       ${"cylinder_name".$j} = $ProductMaterialMachines[$j - 1]["cylinder_name"];
       $this->set('cylinder_name'.$j,${"cylinder_name".$j});

       $ProductMachineMaterials = $this->ProductMachineMaterials->find()
       ->where(['product_material_machine_id' => $ProductMaterialMachines[$j - 1]["id"], 'delete_flag' => 0])
       ->order(["material_number"=>"ASC"])->toArray();

       ${"tuikagenryou".$j} = count($ProductMachineMaterials);
       $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

       for($i=1; $i<=${"tuikagenryou".$j}; $i++){

         $Materials = $this->Materials->find()
         ->where(['id' => $ProductMachineMaterials[$i - 1]["material_id"]])->toArray();

         ${"material_hyouji".$j.$i} = $Materials[0]["name"];
         $this->set('material_hyouji'.$j.$i,${"material_hyouji".$j.$i});

         ${"mixing_ratio".$j.$i} = $ProductMachineMaterials[$i - 1]["mixing_ratio"];
         $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
         ${"dry_temp".$j.$i} = $ProductMachineMaterials[$i - 1]["dry_temp"];
         $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
         ${"dry_hour".$j.$i} = $ProductMachineMaterials[$i - 1]["dry_hour"];
         $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
         ${"recycled_mixing_ratio".$j} = $ProductMachineMaterials[$i - 1]["recycled_mixing_ratio"];
         $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});

       }

     }


       $html =

       "\n";

       for($j=1; $j<=$tuikaseikeiki; $j++){

        if($j == 1){

          $html = $html.
          "<br><table align='left'>\n".
          "<tbody>\n".
          "<tr style='background-color: #FFFFCC'>\n".
          "<td style='border:none; background-color:#E6FFFF'>　　　　　　　　　　</td>\n".
          "<td width='100'><strong>\n".
          "$machine_num\n".
          "号ライン\n".
          "</strong></td>\n".
          "</tr></tbody></table><br><br>\n";
 
         }

         $html = $html.

         "<table>\n".
         "<tr class='parents'>\n".
         "<td width='150'>成形機</td>\n".
         "<td width='490'>原料名</td>\n".
         "<td width='200'>配合比</td>\n".
         "<td width='189'>乾燥温度</td>\n".
         "<td width='189'>乾燥時間</td>\n".
         "<td width='200'>再生配合比</td>\n".
         "</tr>\n";

         for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            "<tr class='children'>\n";

               if($i==1){

                 $html = $html.

                 "<td style='background-color: #FFFFCC' rowspan=${"tuikagenryou".$j}>\n".
                 "${"cylinder_name".$j}\n".
                 "</td>\n";
               }

               $html = $html.

               "<td style='background-color: #FFFFCC'>\n".
               "${"material_hyouji".$j.$i}\n".
               "</td>\n".
               "<td style='background-color: #FFFFCC'>\n".
               "${"mixing_ratio".$j.$i}\n".
               "</td>\n".
               "<td style='background-color: #FFFFCC'>\n".
               "${"dry_temp".$j.$i}\n".
               " ℃\n".
               "</td>\n".
               "<td style='background-color: #FFFFCC'>\n".
               "${"dry_hour".$j.$i}\n".
               " h以上\n".
               "</td>\n";

               if($i==1){

                $html = $html.

                "<td style='background-color: #FFFFCC' rowspan=${"tuikagenryou".$j}>\n".
                "${"recycled_mixing_ratio".$j}\n".
                "</td>\n";
              }

              $html = $html.

               "</tr>\n";

             }

             $html = $html.

             "</table>\n";

           }

       return $html;
       $this->html = $html;
   }

   public function genryouheaderkensaku($machine_datetime_product)
   {

    $arrmachine_datetime_product = explode("_",$machine_datetime_product);
    $machine_num = $arrmachine_datetime_product[0];
    $datetime = $arrmachine_datetime_product[1]." ".$arrmachine_datetime_product[2];
    $product_code = $arrmachine_datetime_product[3];
    if(isset($arrmachine_datetime_product[4])){
      $product_code = $arrmachine_datetime_product[3]."_".$arrmachine_datetime_product[4];
    }

    $product_code_ini = substr($product_code, 0, 11);
    $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
    ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0
    , 'ProductConditionParents.created_at <=' => $datetime])
    ->order(["ProductConditionParents.created_at"=>"DESC"])->toArray();
/*
    echo "<pre>";
    print_r($ProductConditionParents);
    echo "</pre>";
*/
     $product_condition_parent_id = $ProductConditionParents[0]["id"];
     $machine_num = $ProductConditionParents[0]["machine_num"];

     $product_code_ini = substr($product_code, 0, 11);
     $ProductMaterialMachines = $this->ProductMaterialMachines->find()
    ->contain(['ProductConditionParents' => ["Products"]])
    ->where(['product_condition_parent_id' => $product_condition_parent_id, 'product_code like' => $product_code_ini.'%'
    , 'ProductConditionParents.delete_flag' => 0, 'ProductMaterialMachines.delete_flag' => 0])
    ->order(["cylinder_number"=>"ASC"])->toArray();

     $tuikaseikeiki = count($ProductMaterialMachines);
     $this->set('tuikaseikeiki', $tuikaseikeiki);

     for($j=1; $j<=$tuikaseikeiki; $j++){

       ${"cylinder_name".$j} = $ProductMaterialMachines[$j - 1]["cylinder_name"];
       $this->set('cylinder_name'.$j,${"cylinder_name".$j});

       $ProductMachineMaterials = $this->ProductMachineMaterials->find()
       ->where(['product_material_machine_id' => $ProductMaterialMachines[$j - 1]["id"]
       , 'delete_flag' => 0])
       ->order(["material_number"=>"ASC"])->toArray();

       ${"tuikagenryou".$j} = count($ProductMachineMaterials);
       $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

       for($i=1; $i<=${"tuikagenryou".$j}; $i++){

         $Materials = $this->Materials->find()
         ->where(['id' => $ProductMachineMaterials[$i - 1]["material_id"]])->toArray();

         ${"material_hyouji".$j.$i} = $Materials[0]["name"];
         $this->set('material_hyouji'.$j.$i,${"material_hyouji".$j.$i});

         ${"mixing_ratio".$j.$i} = $ProductMachineMaterials[$i - 1]["mixing_ratio"];
         $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
         ${"dry_temp".$j.$i} = $ProductMachineMaterials[$i - 1]["dry_temp"];
         $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
         ${"dry_hour".$j.$i} = $ProductMachineMaterials[$i - 1]["dry_hour"];
         $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
         ${"recycled_mixing_ratio".$j.$i} = $ProductMachineMaterials[$i - 1]["recycled_mixing_ratio"];
         $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});
         ${"recycled_mixing_ratio".$j} = $ProductMachineMaterials[$i - 1]["recycled_mixing_ratio"];
         $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});

       }

     }

       $html =

       "\n";

       for($j=1; $j<=$tuikaseikeiki; $j++){

         if($j == 1){

          $html = $html.
          "<br><table align='left'>\n".
          "<tbody>\n".
          "<tr style='background-color: #FFFFCC'>\n".
          "<td style='border:none; background-color:#E6FFFF'>　　　　　　　　　　</td>\n".
          "<td width='100'><strong>\n".
          "$machine_num\n".
          "号ライン\n".
          "</strong></td>\n".
          "</tr></tbody></table><br><br>\n";
 
         }

         $html = $html.
         "<table>\n".
         "<tr class='parents'>\n".
         "<td width='150'>成形機</td>\n".
         "<td width='490'>原料名</td>\n".
         "<td width='200'>配合比</td>\n".
         "<td width='189'>乾燥温度</td>\n".
         "<td width='189'>乾燥時間</td>\n".
         "<td width='200'>再生配合比</td>\n".
         "</tr>\n";

         for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            "<tr class='children'>\n";

               if($i==1){

                 $html = $html.

                 "<td style='background-color: #FFFFCC' rowspan=${"tuikagenryou".$j}>\n".
                 "${"cylinder_name".$j}\n".
                 "</td>\n";
               }

               $html = $html.

               "<td style='background-color: #FFFFCC'>\n".
               "${"material_hyouji".$j.$i}\n".
               "</td>\n".
               "<td style='background-color: #FFFFCC'>\n".
               "${"mixing_ratio".$j.$i}\n".
               "</td>\n".
               "<td style='background-color: #FFFFCC'>\n".
               "${"dry_temp".$j.$i}\n".
               " ℃\n".
               "</td>\n".
               "<td style='background-color: #FFFFCC'>\n".
               "${"dry_hour".$j.$i}\n".
               " h以上\n".
               "</td>\n";

               if($i==1){

                $html = $html.

                "<td style='background-color: #FFFFCC' rowspan=${"tuikagenryou".$j}>\n".
                "${"recycled_mixing_ratio".$j}\n".
                "</td>\n";
              }

              $html = $html.
               
               "</tr>\n";

             }

             $html = $html.

             "</table>\n";

           }

       return $html;
       $this->html = $html;
   }

   public function genryouheaderrireki($created_at_machine_num)
   {
    $arrcreated_at_machine_num = explode("_",$created_at_machine_num);
    $created_at = $arrcreated_at_machine_num[0];
    $machine_num = $arrcreated_at_machine_num[1];

     $ProductConditonChildren = $this->ProductConditonChildren->find()
     ->where(['created_at' => $created_at])
     ->order(["cylinder_number"=>"ASC"])->toArray();
/*
     echo "<pre>";
     print_r($ProductConditonChildren);
     echo "</pre>";
*/
     $countProductConditonChildren = count($ProductConditonChildren);
     $ProductMaterialMachines = array();

     for($j=0; $j<$countProductConditonChildren; $j++){//$ProductMaterialMachinesを全部合わせる

      $ProductMaterialMachinesrireki = $this->ProductMaterialMachines->find()
      ->where(['id' => $ProductConditonChildren[$j]["product_material_machine_id"]])
      ->order(["cylinder_number"=>"ASC"])->toArray();
 
      $ProductMaterialMachines = array_merge($ProductMaterialMachines, $ProductMaterialMachinesrireki);

     }
/*
     echo "<pre>";
     print_r($ProductMaterialMachines);
     echo "</pre>";
*/
     $tuikaseikeiki = count($ProductMaterialMachines);
     $this->set('tuikaseikeiki', $tuikaseikeiki);

     for($j=1; $j<=$tuikaseikeiki; $j++){

       ${"cylinder_name".$j} = $ProductMaterialMachines[$j - 1]["cylinder_name"];
       $this->set('cylinder_name'.$j,${"cylinder_name".$j});

       $ProductMachineMaterials = $this->ProductMachineMaterials->find()
       ->where(['product_material_machine_id' => $ProductMaterialMachines[$j - 1]["id"]])
       ->order(["material_number"=>"ASC"])->toArray();

       ${"tuikagenryou".$j} = count($ProductMachineMaterials);
       $this->set('tuikagenryou'.$j, ${"tuikagenryou".$j});

       for($i=1; $i<=${"tuikagenryou".$j}; $i++){

         $Materials = $this->Materials->find()
         ->where(['id' => $ProductMachineMaterials[$i - 1]["material_id"]])->toArray();

         ${"material_hyouji".$j.$i} = $Materials[0]["name"];
         $this->set('material_hyouji'.$j.$i,${"material_hyouji".$j.$i});

         ${"mixing_ratio".$j.$i} = $ProductMachineMaterials[$i - 1]["mixing_ratio"];
         $this->set('mixing_ratio'.$j.$i,${"mixing_ratio".$j.$i});
         ${"dry_temp".$j.$i} = $ProductMachineMaterials[$i - 1]["dry_temp"];
         $this->set('dry_temp'.$j.$i,${"dry_temp".$j.$i});
         ${"dry_hour".$j.$i} = $ProductMachineMaterials[$i - 1]["dry_hour"];
         $this->set('dry_hour'.$j.$i,${"dry_hour".$j.$i});
         ${"recycled_mixing_ratio".$j} = $ProductMachineMaterials[$i - 1]["recycled_mixing_ratio"];
         $this->set('recycled_mixing_ratio'.$j,${"recycled_mixing_ratio".$j});

       }

     }

       $html =

       "\n";

       for($j=1; $j<=$tuikaseikeiki; $j++){

        if($j == 1){

          $html = $html.
          "<br><table align='left'>\n".
          "<tbody>\n".
          "<tr style='background-color: #FFFFCC'>\n".
          "<td style='border:none; background-color:#E6FFFF'>　　　　　　　　　　</td>\n".
          "<td width='100'><strong>\n".
          "$machine_num\n".
          "号ライン\n".
          "</strong></td>\n".
          "</tr></tbody></table><br><br>\n";
 
         }

         $html = $html.

         "<table>\n".
         "<tr class='parents'>\n".
         "<td width='150'>成形機</td>\n".
         "<td width='490'>原料名</td>\n".
         "<td width='200'>配合比</td>\n".
         "<td width='189'>乾燥温度</td>\n".
         "<td width='189'>乾燥時間</td>\n".
         "<td width='200'>再生配合比</td>\n".
         "</tr>\n";

         for($i=1; $i<=${"tuikagenryou".$j}; $i++){

            "<tr class='children'>\n";

               if($i==1){

                 $html = $html.

                 "<td style='background-color: #FFFFCC' rowspan=${"tuikagenryou".$j}>\n".
                 "${"cylinder_name".$j}\n".
                 "</td>\n";
               }

               $html = $html.

               "<td style='background-color: #FFFFCC'>\n".
               "${"material_hyouji".$j.$i}\n".
               "</td>\n".
               "<td style='background-color: #FFFFCC'>\n".
               "${"mixing_ratio".$j.$i}\n".
               "</td>\n".
               "<td style='background-color: #FFFFCC'>\n".
               "${"dry_temp".$j.$i}\n".
               " ℃\n".
               "</td>\n".
               "<td style='background-color: #FFFFCC'>\n".
               "${"dry_hour".$j.$i}\n".
               " h以上\n";

               if($i==1){

                $html = $html.

                "<td style='background-color: #FFFFCC' rowspan=${"tuikagenryou".$j}>\n".
                "${"recycled_mixing_ratio".$j}\n".
                "</td>\n";
              }

              $html = $html.
               
               "</tr>\n";

             }

             $html = $html.

             "</table>\n";

           }

       return $html;
       $this->html = $html;
   }

}

?>
