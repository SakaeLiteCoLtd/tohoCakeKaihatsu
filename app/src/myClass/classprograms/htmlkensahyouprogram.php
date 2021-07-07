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

    public function genryouheader($product_code)
   {
     $ProductConditionParents = $this->ProductConditionParents->find()->contain(["Products"])
     ->where(['product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0])
     ->order(["version"=>"DESC"])->toArray();

     $version = $ProductConditionParents[0]["version"];

     $ProductMaterialMachines = $this->ProductMaterialMachines->find()
     ->contain(['ProductConditionParents' => ["Products"]])
     ->where(['version' => $version, 'product_code' => $product_code, 'ProductConditionParents.delete_flag' => 0, 'ProductMaterialMachines.delete_flag' => 0])
     ->order(["cylinder_number"=>"ASC"])->toArray();

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
         ${"recycled_mixing_ratio".$j.$i} = $ProductMachineMaterials[$i - 1]["recycled_mixing_ratio"];
         $this->set('recycled_mixing_ratio'.$j.$i,${"recycled_mixing_ratio".$j.$i});

       }

     }


       $html =

       "\n";

       for($j=1; $j<=$tuikaseikeiki; $j++){

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
               "</td>\n".
               "<td style='background-color: #FFFFCC'>\n".
               "${"recycled_mixing_ratio".$j.$i}\n".
               "</td>\n".
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
