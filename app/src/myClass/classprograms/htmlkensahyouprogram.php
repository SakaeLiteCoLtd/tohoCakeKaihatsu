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
        $this->Linenames = TableRegistry::get('Linenames');
        $this->InspectionDataConditonChildren = TableRegistry::get('InspectionDataConditonChildren');
        $this->InspectionDataConditonParents = TableRegistry::get('InspectionDataConditonParents');
        $this->InspectionDataResultParents = TableRegistry::get('InspectionDataResultParents');
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

     $ProductDatas = $this->Products->find()
     ->where(['product_code' => $product_code])->toArray();
     $LinenameDatas = $this->Linenames->find()
     ->where(['delete_flag' => 0, 'factory_id' => $ProductDatas[0]["factory_id"], 'machine_num' => $machine_num])->toArray();
     $linename = $LinenameDatas[0]["name"];

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
          "$linename\n".
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
               "</td>\n";

               if($i==1){

                $html = $html.

               "<td rowspan=${"tuikagenryou".$j} style='background-color: #FFFFCC'>\n".
               "${"dry_temp".$j.$i}\n".
               " ℃\n".
               "</td>\n".
               "<td rowspan=${"tuikagenryou".$j} style='background-color: #FFFFCC'>\n".
               "${"dry_hour".$j.$i}\n".
               " h以上\n".
               "</td>\n".
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

     $ProductDatas = $this->Products->find()
     ->where(['product_code' => $product_code])->toArray();
     $LinenameDatas = $this->Linenames->find()
     ->where(['delete_flag' => 0, 'factory_id' => $ProductDatas[0]["factory_id"], 'machine_num' => $machine_num])->toArray();
     $linename = $LinenameDatas[0]["name"];

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
          "$linename\n".
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
               "</td>\n";

               if($i==1){

                $html = $html.

                "<td rowspan=${"tuikagenryou".$j}  style='background-color: #FFFFCC'>\n".
                "${"dry_temp".$j.$i}\n".
                " ℃\n".
                "</td>\n".
                "<td rowspan=${"tuikagenryou".$j}  style='background-color: #FFFFCC'>\n".
                "${"dry_hour".$j.$i}\n".
                " h以上\n".
                "</td>\n".
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
               "</td>\n";

               if($i==1){

                $html = $html.

                "<td rowspan=${"tuikagenryou".$j}  style='background-color: #FFFFCC'>\n".
                "${"dry_temp".$j.$i}\n".
                " ℃\n".
                "</td>\n".
                "<td rowspan=${"tuikagenryou".$j}  style='background-color: #FFFFCC'>\n".
                "${"dry_hour".$j.$i}\n".
                " h以上\n".
                "</td>\n".
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

   public function seikeijouken($machine_product_datetime)
   {
    $arrmachine_product_datetime = explode("_",$machine_product_datetime);
    $machine_num = $arrmachine_product_datetime[0];
    $product_code = $arrmachine_product_datetime[1];
    $datetimetoujitu = $arrmachine_product_datetime[2];

    //ここから成形条件
    $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
    ->where(['machine_num' => $machine_num, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
    ->order(["version"=>"DESC"])->toArray();

    if(!isset($ProductConditionParents[0])){//長さ違いのデータがあればそれを持ってくる
    
      $product_code_ini = substr($product_code, 0, 11);
      $ProductConditionParents= $this->ProductConditionParents->find()->contain(["Products"])
      ->where(['machine_num' => $machine_num, 'product_code like' => $product_code_ini.'%', 'ProductConditionParents.delete_flag' => 0])
      ->order(["version"=>"DESC"])->toArray();

    }

    if(isset($ProductConditionParents[0])){

      $product_condition_parent_id = $ProductConditionParents[0]["id"];
      $this->set('product_condition_parent_id', $product_condition_parent_id);

      $version = $ProductConditionParents[0]["version"];

      $ProductMaterialMachines= $this->ProductMaterialMachines->find()
      ->contain(['ProductConditionParents' => ["Products"]])
      ->where(['Products.product_code' => $product_code,
      'ProductConditionParents.delete_flag' => 0,
      'ProductMaterialMachines.delete_flag' => 0,
      'ProductConditionParents.machine_num' => $machine_num,
      'ProductConditionParents.version' => $version])
      ->order(["cylinder_number"=>"ASC"])->toArray();

      if(!isset($ProductMaterialMachines[0])){//長さ違いのデータがあればそれを持ってくる

        $product_code_ini = substr($product_code, 0, 11);
        $ProductMaterialMachines= $this->ProductMaterialMachines->find()
        ->contain(['ProductConditionParents' => ["Products"]])
        ->where(['Products.product_code like' => $product_code_ini.'%',
        'ProductConditionParents.delete_flag' => 0,
        'ProductMaterialMachines.delete_flag' => 0,
        'ProductConditionParents.machine_num' => $machine_num,
        'ProductConditionParents.version' => $version])
        ->order(["cylinder_number"=>"ASC"])->toArray();

      }

      $countseikeiki = count($ProductMaterialMachines);
      $this->set('countseikeiki', $countseikeiki);

      for($k=0; $k<$countseikeiki; $k++){//基準値の呼び出し
    
        $j = $k + 1;
        ${"product_material_machine_id".$j} = $ProductMaterialMachines[$k]["id"];
        $this->set('product_material_machine_id'.$j, ${"product_material_machine_id".$j});
        ${"cylinder_name".$j} = $ProductMaterialMachines[$k]["cylinder_name"];
        $this->set('cylinder_name'.$j, ${"cylinder_name".$j});

        $ProductConditonChildren = $this->ProductConditonChildren->find()
        ->where(['product_material_machine_id' => ${"product_material_machine_id".$j},
         'cylinder_name' => ${"cylinder_name".$j}, 'delete_flag' => 0])
        ->toArray();

        if(isset($ProductConditonChildren[0])){

          ${"extrude_roatation".$j} = sprintf("%.1f", $ProductConditonChildren[0]["extrude_roatation"]);
          $this->set('extrude_roatation'.$j, ${"extrude_roatation".$j});
          ${"extrusion_load".$j} = sprintf("%.1f", $ProductConditonChildren[0]["extrusion_load"]);
          $this->set('extrusion_load'.$j, ${"extrusion_load".$j});
          ${"product_conditon_child_id".$j} = $ProductConditonChildren[0]["id"];
          $this->set('product_conditon_child_id'.$j, ${"product_conditon_child_id".$j});
    
          for($n=1; $n<8; $n++){
            ${"temp_".$n.$j} = sprintf("%.0f", $ProductConditonChildren[0]["temp_".$n]);
            $this->set('temp_'.$n.$j, ${"temp_".$n.$j});
          }

          $pickup_speed = sprintf("%.1f", $ProductConditonChildren[0]["pickup_speed"]);
          $this->set('pickup_speed', $pickup_speed);

          ${"screw_mesh_1".$j} = $ProductConditonChildren[0]['screw_mesh_1'];
          $this->set('screw_mesh_1'.$j, ${"screw_mesh_1".$j});
          ${"screw_number_1".$j} = $ProductConditonChildren[0]['screw_number_1'];
          $this->set('screw_number_1'.$j, ${"screw_number_1".$j});
          ${"screw_mesh_2".$j} = $ProductConditonChildren[0]['screw_mesh_2'];
          $this->set('screw_mesh_2'.$j, ${"screw_mesh_2".$j});
          ${"screw_number_2".$j} = $ProductConditonChildren[0]['screw_number_2'];
          $this->set('screw_number_2'.$j, ${"screw_number_2".$j});
          ${"screw_mesh_3".$j} = $ProductConditonChildren[0]['screw_mesh_3'];
          $this->set('screw_mesh_3'.$j, ${"screw_mesh_3".$j});
          ${"screw_number_3".$j} = $ProductConditonChildren[0]['screw_number_3'];
          $this->set('screw_number_3'.$j, ${"screw_number_3".$j});
          ${"screw".$j} = $ProductConditonChildren[0]['screw'];
          $this->set('screw'.$j, ${"screw".$j});

        }

      }

    }

    $product_code_ini = substr($product_code, 0, 11);
    for($k=0; $k<$countseikeiki; $k++){//各成型機の基準値の呼び出し
      
      $cylinder_name = $ProductMaterialMachines[$k]["cylinder_name"];

      //成形機毎に取り出し
      $InspectionDataConditonChildren = $this->InspectionDataConditonChildren->find()
      ->contain(['ProductConditonChildren', 'InspectionDataConditonParents'])
      ->where(['product_code like' => $product_code_ini.'%'
      , 'ProductConditonChildren.cylinder_name' => $cylinder_name
      , 'InspectionDataConditonChildren.created_at <=' => $datetimetoujitu])
      ->order(["InspectionDataConditonChildren.created_at"=>"DESC"])->limit(1)->toArray();

      $j = $k + 1;

        ${"inspection_extrude_roatation".$j} = sprintf("%.1f", $InspectionDataConditonChildren[0]['inspection_extrude_roatation']);
        $this->set('inspection_extrude_roatation'.$j, ${"inspection_extrude_roatation".$j});
        ${"inspection_extrusion_load".$j} = sprintf("%.1f", $InspectionDataConditonChildren[0]['inspection_extrusion_load']);
        $this->set('inspection_extrusion_load'.$j, ${"inspection_extrusion_load".$j});
        ${"inspection_pickup_speed".$j} = sprintf("%.1f", $InspectionDataConditonChildren[0]['inspection_pickup_speed']);
        $this->set('inspection_pickup_speed'.$j, ${"inspection_pickup_speed".$j});

        for($n=1; $n<8; $n++){

          ${"inspection_temp_".$n.$j} = $InspectionDataConditonChildren[0]['inspection_temp_'.$n];
          $this->set('inspection_temp_'.$n.$j, ${"inspection_temp_".$n.$j});

        }

    }


          $html =

          "\n";
   
          for($j=1; $j<=$countseikeiki; $j++){
   
            $html = $html.

            "<table>\n".
            "<tr class='parents'>\n".
            "<td width='80'>成形機</td>\n".
            "<td width='100'>温度条件</td>\n".
            "<td width='70'>C １</td>\n".
            "<td width='70'>C ２</td>\n".
            "<td width='70'>C ３</td>\n".
            "<td width='70'>C ４</td>\n".
            "<td width='70'>A D</td>\n".
            "<td width='70'>D １</td>\n".
            "<td width='70'>D ２</td>\n".
            "<td style='width:226' colspan='2'>押出回転(rpm)/負荷(A)</td>\n".
            "<td style='width:100'>引取速度<br>（m/min）</td>\n".
            "<td style='width:200' colspan='2'>ｽｸﾘｰﾝﾒｯｼｭ : 枚数</td>\n".
            "<td width='200'>ｽｸﾘｭｳ</td>\n".
            "</tr>\n";

            for($i=1; $i<=3; $i++){

              $html = $html.

              "<tr class='children'>\n";
         
                 if($i==1){

                  $html = $html.

                   "<td rowspan=3>\n".
                   "${"cylinder_name".$j}\n".
                   "</td>\n";

                 }
         
                 if($i==1){

                  $html = $html.

                   "<td style='width:50px'>\n".
                   "基 準 値\n".
                   "</td>\n";

                 }elseif($i==2){

                  $html = $html.

                   "<td style='width:50px'>\n".
                   "記    録\n".
                   "</td>\n";

                 }elseif($i==3){

                  $html = $html.

                   "<td style='width:50px'>\n".
                   "許容範囲\n".
                   "</td>\n";

                 }
         
                 if($i == 1){

                  $html = $html.
         
                   "<td>\n".
                   "${"temp_1".$j}\n".
                   "</td>\n".
                   "<td>\n".
                   "${"temp_2".$j}\n".
                   "</td>\n".
                   "<td>\n".
                   "${"temp_3".$j}\n".
                   "</td>\n".
                   "<td>\n".
                   "${"temp_4".$j}\n".
                   "</td>\n".
                   "<td>\n".
                   "${"temp_5".$j}\n".
                   "</td>\n".
                   "<td>\n".
                   "${"temp_6".$j}\n".
                   "</td>\n".
                   "<td>\n".
                   "${"temp_7".$j}\n".
                   "</td>\n".
                   "<td style='border-right-style:none; text-align:right'>\n".
                   "${"extrude_roatation".$j}(rpm)\n".
                   "</td>\n".
                   "<td style='border-left-style:none; text-align:left'>\n".
                   "/ ${"extrusion_load".$j}(A)\n".
                   "</td>\n";
         
                 }elseif($i == 2){
         
                  $html = $html.

                     "<td>\n".
                     "${"inspection_temp_1".$j}\n".
                     "</td>\n".
                     "<td>\n".
                     "${"inspection_temp_2".$j}\n".
                     "</td>\n".
                     "<td>\n".
                     "${"inspection_temp_3".$j}\n".
                     "</td>\n".
                     "<td>\n".
                     "${"inspection_temp_4".$j}\n".
                     "</td>\n".
                     "<td>\n".
                     "${"inspection_temp_5".$j}\n".
                     "</td>\n".
                     "<td>\n".
                     "${"inspection_temp_6".$j}\n".
                     "</td>\n".
                     "<td>\n".
                     "${"inspection_temp_7".$j}\n".
                     "</td>\n".
                     "<td style='border-right-style:none; text-align:right'>\n".
                     "${"inspection_extrude_roatation".$j}(rpm)\n".
                     "</td>\n".
                     "<td style='border-left-style:none; text-align:left'>\n".
                     "/ ${"inspection_extrusion_load".$j}(A)\n".
                     "</td>\n";
         
                 }else{

                  $html = $html.

                   "<td>\n".
                   "± 10\n".
                   "</td>\n".
                   "<td>\n".
                   "± 10\n".
                   "</td>\n".
                   "<td>\n".
                   "± 10\n".
                   "</td>\n".
                   "<td>\n".
                   "± 10\n".
                   "</td>\n".
                   "<td>\n".
                   "± 10\n".
                   "</td>\n".
                   "<td>\n".
                   "± 10\n".
                   "</td>\n".
                   "<td>\n".
                   "± 10\n".
                   "</td>\n".
                   "<td colspan=2>\n".
                   "± 5.0\n".
                   "</td>\n";

                 }
         
                 if($j==1){

                     if($i==1){

                      $html = $html.

                       "<td>\n".
                       "$pickup_speed\n".
                       "</td>\n".
                       "<td>\n".
                       "${"screw_mesh_1".$j}\n".
                       "</td>\n".
                       "<td>\n".
                       "${"screw_number_1".$j}\n".
                       "</td>\n".
                       "<td rowspan=3>\n".
                       "${"screw".$j}\n".
                       "</td>\n";

                     }elseif($i==2){

                      $html = $html.

                       "<td>\n".
                       "${"inspection_pickup_speed".$j}\n".
                       "</td>\n".
                       "<td>\n".
                       "${"screw_mesh_2".$j}\n".
                       "</td>\n".
                       "<td>\n".
                       "${"screw_number_2".$j}\n".
                       "</td>\n";

                     }else{

                      $html = $html.

                       "<td>\n".
                       "± 1.0\n".
                       "</td>\n".
                       "<td>\n".
                       "${"screw_mesh_3".$j}\n".
                       "</td>\n".
                       "<td>\n".
                       "${"screw_number_3".$j}\n".
                       "</td>\n";

                     }

                 }else{

                   if($i==1){

                    $html = $html.

                     "<td style='border-bottom-style:none;'>\n".
                     "</td>\n".
                     "<td>\n".
                     "${"screw_mesh_1".$j}\n".
                     "</td>\n".
                     "<td>\n".
                     "${"screw_number_1".$j}\n".
                     "</td>\n".
                     "<td rowspan=3>\n".
                     "${"screw".$j}\n".
                     "</td>\n";

                   }elseif($i==2){

                    $html = $html.

                     "<td style='border-bottom-style:none; border-top-style:none;'>\n".
                     "</td>\n".
                     "<td>\n".
                     "${"screw_mesh_2".$j}\n".
                     "</td>\n".
                     "<td>\n".
                     "${"screw_number_2".$j}\n".
                     "</td>\n";
                   }else{

                    $html = $html.

                     "<td style='border-top-style:none;'>\n".
                     "</td>\n".
                     "<td>\n".
                     "${"screw_mesh_3".$j}\n".
                     "</td>\n".
                     "<td>\n".
                     "${"screw_number_3".$j}\n".
                     "</td>\n";
                   }

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

   public function mikanryouichiran($datetimesta_factory)
   {
    $arrdatetimesta_factory = explode("_",$datetimesta_factory);
    $datetimesta = $arrdatetimesta_factory[0];
    $factory_id = $arrdatetimesta_factory[1];

    if($factory_id == 5){//本部の人がログインしている場合

      $InspectionDataResultParentsnotfin = $this->InspectionDataResultParents->find()
      ->contain(['InspectionStandardSizeParents', 'ProductConditionParents', 'Products'])
      ->where(['InspectionStandardSizeParents.delete_flag' => 0,
      'InspectionDataResultParents.delete_flag' => 0,
      'kanryou_flag IS' => NULL,
       'datetime <' => $datetimesta])
       ->order(["InspectionDataResultParents.datetime"=>"DESC"])->toArray();
  
    }else{

       $InspectionDataResultParentsnotfin = $this->InspectionDataResultParents->find()
       ->contain(['InspectionStandardSizeParents', 'ProductConditionParents', 'Products'])
       ->where(['InspectionStandardSizeParents.delete_flag' => 0,
       'InspectionDataResultParents.delete_flag' => 0,
       'kanryou_flag IS' => NULL,
        'datetime <' => $datetimesta,
        'OR' => [['Products.factory_id' => $factory_id], ['Products.factory_id' => 5]]])
        ->order(["InspectionDataResultParents.datetime"=>"DESC"])->toArray();
 
    }

     $arrInspectionDataResultParentnotfin = array();
     for($i=0; $i<count($InspectionDataResultParentsnotfin); $i++){

      $check_proini = 0;
      $product_code_ini = substr($InspectionDataResultParentsnotfin[$i]["product"]["product_code"], 0, 11);
      $machine_num = $InspectionDataResultParentsnotfin[$i]["product_condition_parent"]["machine_num"];

      for($j=0; $j<count($arrInspectionDataResultParentnotfin); $j++){//同じ製品が既に登録されていたら登録しない
        
        if($arrInspectionDataResultParentnotfin[$j]["product_code_ini_machine_num"] == $product_code_ini."_".$machine_num){
          $check_proini = $check_proini + 1;
        }

      }

      if($check_proini == 0){

        $arrInspectionDataResultParentnotfin[] = [
          "machine_num" => $InspectionDataResultParentsnotfin[$i]["product_condition_parent"]["machine_num"],
          "product_code" => $InspectionDataResultParentsnotfin[$i]["product"]["product_code"],
          "product_code_ini_machine_num" => $product_code_ini."_".$InspectionDataResultParentsnotfin[$i]["product_condition_parent"]["machine_num"],
          "name" => $InspectionDataResultParentsnotfin[$i]["product"]["name"],
          "datetime" => $InspectionDataResultParentsnotfin[$i]["datetime"]->format('Y-m-d'),
        ];

        }

    }

    $tmp = array();
    $array_result = array();
   
    foreach( $arrInspectionDataResultParentnotfin as $key => $value ){
   
     // 配列に値が見つからなければ$tmpに格納
     if( !in_array( $value['product_code_ini_machine_num'], $tmp ) ) {
      $tmp[] = $value['product_code_ini_machine_num'];
      $array_result[] = $value;
     }
   
    }
    $arrInspectionDataResultParentnotfin = $array_result;

    return $arrInspectionDataResultParentnotfin;

   }

   public function toujitsuichiran($datetimesta_factory)
   {
    $arrdatetimesta_factory = explode("_",$datetimesta_factory);
    $datetimesta = $arrdatetimesta_factory[0];
    $factory_id = $arrdatetimesta_factory[1];

    if($factory_id == 5){//本部の人がログインしている場合

      $InspectionDataResultParents = $this->InspectionDataResultParents->find()
      ->contain(['InspectionStandardSizeParents', 'ProductConditionParents', 'Products'])
      ->where(['InspectionStandardSizeParents.delete_flag' => 0,
       'InspectionDataResultParents.delete_flag' => 0,
       'datetime >=' => $datetimesta])
       ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();

    }else{

      $InspectionDataResultParents = $this->InspectionDataResultParents->find()
      ->contain(['InspectionStandardSizeParents', 'ProductConditionParents', 'Products'])
      ->where(['InspectionStandardSizeParents.delete_flag' => 0,
       'InspectionDataResultParents.delete_flag' => 0,
       'datetime >=' => $datetimesta,
       'OR' => [['Products.factory_id' => $factory_id], ['Products.factory_id' => 5]]])
       ->order(["InspectionDataResultParents.datetime"=>"ASC"])->toArray();

    }

     $arrInspectionDataResultParents = array();
     for($i=0; $i<count($InspectionDataResultParents); $i++){

      $check_proini = 0;
      $product_code_ini = substr($InspectionDataResultParents[$i]["product"]["product_code"], 0, 11);
      $machine_num = $InspectionDataResultParents[$i]["product_condition_parent"]["machine_num"];

      for($j=0; $j<count($arrInspectionDataResultParents); $j++){//同じ製品が既に登録されていたら登録しない
        
        if($arrInspectionDataResultParents[$j]["product_code_ini_machine_num"] == $product_code_ini."_".$machine_num){
          $check_proini = $check_proini + 1;
        }

      }

      if($check_proini == 0){

        $arrInspectionDataResultParents[] = [
          "machine_num" => $InspectionDataResultParents[$i]["product_condition_parent"]["machine_num"],
          "product_code" => $InspectionDataResultParents[$i]["product"]["product_code"],
          "product_code_ini_machine_num" => $product_code_ini."_".$InspectionDataResultParents[$i]["product_condition_parent"]["machine_num"],
          "name" => $InspectionDataResultParents[$i]["product"]["name"],
        ];

        }

    }

    $tmp = array();
    $array_result = array();
   
    foreach( $arrInspectionDataResultParents as $key => $value ){
   
     // 配列に値が見つからなければ$tmpに格納
     if( !in_array( $value['product_code_ini_machine_num'], $tmp ) ) {
      $tmp[] = $value['product_code_ini_machine_num'];
      $array_result[] = $value;
     }
   
    }
    $arrInspectionDataResultParents = $array_result;

    return $arrInspectionDataResultParents;

   }

}

?>
