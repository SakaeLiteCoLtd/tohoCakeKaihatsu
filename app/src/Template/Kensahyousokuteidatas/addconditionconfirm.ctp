<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
 use App\myClass\classprograms\htmlkensahyouprogram;//myClassフォルダに配置したクラスを使用
 $htmlkensahyougenryouheader = new htmlkensahyouprogram();
?>
<?php
$this->layout = false;
echo $this->Html->css('kensahyou');

$mes = "";

?>

<table class='sample hesdermenu'>
  <tbody>
    <td style='border: none;'>
      <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/menu' /><font size='4' color=black>測定データ登録</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/addformpre' /><font size='4' color=black>新規登録</font></a>
    </td>
  </tbody>
</table>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'addconditiondo']]) ?>

<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('inspection_standard_size_parent_id', array('type'=>'hidden', 'value'=>$inspection_standard_size_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('product_condition_parent_id', array('type'=>'hidden', 'value'=>$product_condition_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>

<?php
      echo $htmlkensahyouheader;
 ?>
 <?php
       echo $htmlgenryouheader;
  ?>

<?php for($j=1; $j<=$countseikeiki; $j++): ?>

  <?= $this->Form->control('inspection_extrude_roatation'.$j, array('type'=>'hidden', 'value'=>${"inspection_extrude_roatation".$j}, 'label'=>false)) ?>
  <?= $this->Form->control('inspection_extrusion_load'.$j, array('type'=>'hidden', 'value'=>${"inspection_extrusion_load".$j}, 'label'=>false)) ?>
  <?= $this->Form->control('inspection_pickup_speed', array('type'=>'hidden', 'value'=>$inspection_pickup_speed, 'label'=>false)) ?>

  <?php for($n=1; $n<=7; $n++): ?>

    <?= $this->Form->control('inspection_temp_'.$n.$j, array('type'=>'hidden', 'value'=>${"inspection_temp_".$n.$j}, 'label'=>false)) ?>

  <?php endfor;?>

<table>
  <tr class="parents">
  <td style='width:106'>成形機</td>
  <td width="100">温度条件</td>
  <td style='width:70'>C １</td>
  <td style='width:70'>C ２</td>
  <td style='width:70'>C ３</td>
  <td style='width:70'>C ４</td>
  <td style='width:70'>A D</td>
  <td style='width:70'>D １</td>
  <td style='width:70'>D ２</td>
  <td style='width:200' colspan="2">押出回転(rpm)/負荷(A)</td>
  <td style='width:100'>引取速度<br>（m/min）</td>
  <td style='width:200' colspan="2">ｽｸﾘｰﾝﾒｯｼｭ : 枚数</td>
  <td style='width:200'>ｽｸﾘｭｳ</td>
</tr>

<?php
   for($i=1; $i<=3; $i++){

     echo "<tr class='children'>\n";

        if($i==1){
          echo "<td rowspan=3>\n";
          echo "${"cylinder_name".$j}\n";
          echo "</td>\n";
        }

        if($i==1){
          echo "<td style='width:50px'>\n";
          echo "基 準 値\n";
          echo "</td>\n";
        }elseif($i==2){
          echo "<td style='width:50px'>\n";
          echo "記    録\n";
          echo "</td>\n";
        }elseif($i==3){
          echo "<td style='width:50px'>\n";
          echo "許容範囲\n";
          echo "</td>\n";
        }

        if($i == 1){
          echo "<td>\n";
          echo "${"temp_1".$j}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"temp_2".$j}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"temp_3".$j}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"temp_4".$j}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"temp_5".$j}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"temp_6".$j}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"temp_7".$j}\n";
          echo "</td>\n";
          echo "<td style='border-right-style:none; text-align:right'>\n";
          echo "${"extrude_roatation".$j}(rpm)\n";
          echo "</td>\n";
          echo "<td style='border-left-style:none; text-align:left'>\n";
          echo "/ ${"extrusion_load".$j}(A)\n";
          echo "</td>\n";
        }elseif($i == 2){

          for($n=1; $n<8; $n++){

            if(${"inspection_temp_".$n.$j} <= (int)${"temp_".$n.$j} + (int)${"temp_".$n."_upper_limit".$j}
            && ${"inspection_temp_".$n.$j} >= (int)${"temp_".$n.$j} + (int)${"temp_".$n."_lower_limit".$j}){
              echo '<td>';
              echo ${"inspection_temp_".$n.$j} ;
              echo '</td>';
            } else {
              echo '<td><font color="red">';
              echo ${"inspection_temp_".$n.$j};
              echo '</td>';
              $mes = "規格から外れたデータがあります。入力間違いがないか確認し、正しければそのまま登録してください。".'<br>';
            }

          }

          if(${"inspection_extrude_roatation".$j}/${"inspection_extrusion_load".$j} <= (int)${"extrude_roatation".$j}/(int)${"extrusion_load".$j} + (int)${"extrusion_upper_limit".$j}
          && ${"inspection_extrude_roatation".$j}/${"inspection_extrusion_load".$j} >= (int)${"extrude_roatation".$j}/(int)${"extrusion_load".$j} + (int)${"extrusion_lower_limit".$j}){
            echo "<td style='border-right-style:none; text-align:right'>\n";
            echo "${"inspection_extrude_roatation".$j}(rpm)\n";
            echo "</td>\n";
            echo "<td style='border-left-style:none; text-align:left'>\n";
            echo "/ ${"inspection_extrusion_load".$j}(A)\n";
            echo "</td>\n";
          } else {
            echo "<td style='border-right-style:none; text-align:right'><font color='red'>\n";
            echo "${"inspection_extrude_roatation".$j}(rpm)\n";
            echo "</td>\n";
            echo "<td style='border-left-style:none; text-align:left'><font color='red'>\n";
            echo "/ ${"inspection_extrusion_load".$j}(A)\n";
            echo "</td>\n";
            $mes = "規格から外れたデータがあります。入力間違いがないか確認し、正しければそのまま登録してください。".'<br>';
          }
        }else{
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td colspan=2>\n";
          echo "± 5.0\n";
          echo "</td>\n";
        }

        if($j==1){
            if($i==1){
              echo "<td>\n";
              echo "$pickup_speed\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_mesh_1".$j}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_number_1".$j}\n";
              echo "</td>\n";
              echo "<td rowspan=3>\n";
              echo "${"screw".$j}\n";
              echo "</td>\n";
            }elseif($i==2){

              if($inspection_pickup_speed <= (int)$pickup_speed + (int)$pickup_speed_upper_limit
              && $inspection_pickup_speed >= (int)$pickup_speed + (int)$pickup_speed_lower_limit){
                echo '<td>';
                echo "$inspection_pickup_speed\n";
                echo '</td>';
              } else {
                echo '<td><font color="red">';
                echo "$inspection_pickup_speed\n";
                echo '</td>';
                $mes = "規格から外れたデータがあります。入力間違いがないか確認し、正しければそのまま登録してください。".'<br>';
              }

              echo "<td>\n";
              echo "${"screw_mesh_2".$j}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_number_2".$j}\n";
              echo "</td>\n";
              /*
              echo "<td>\n";
              echo "${"screw_2".$j}\n";
              echo "</td>\n";
              */
            }else{
              echo "<td>\n";
              echo "± 1.0\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_mesh_3".$j}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_number_3".$j}\n";
              echo "</td>\n";
              /*
              echo "<td>\n";
              echo "${"screw_3".$j}\n";
              echo "</td>\n";
              */
            }
        }else{
          if($i==1){
            echo "<td style='border-bottom-style:none;'>\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_mesh_1".$j}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_number_1".$j}\n";
            echo "</td>\n";
            echo "<td rowspan=3>\n";
            echo "${"screw".$j}\n";
            echo "</td>\n";
          }elseif($i==2){
            echo "<td style='border-bottom-style:none; border-top-style:none;'>\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_mesh_2".$j}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_number_2".$j}\n";
            echo "</td>\n";
/*
            echo "<td>\n";
            echo "${"screw_2".$j}\n";
            echo "</td>\n";
*/
          }else{
            echo "<td style='border-top-style:none;'>\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_mesh_3".$j}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_number_3".$j}\n";
            echo "</td>\n";
            /*
            echo "<td>\n";
            echo "${"screw_3".$j}\n";
            echo "</td>\n";
            */
          }
        }

        echo "</tr>\n";

      }
 ?>
</table>

<?php endfor;?>

<br><br>
<table class="top">
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
  <tr><td style="border:none; color:red"><?= __('成形条件を上記の内容で登録します。よろしければ「登録確定」ボタンを押してください。') ?></td></tr>
</table>
<br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      <td style="border: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('登録確定'), array('name' => 'tuika')) ?></td>
    </tr>
  </tbody>
</table>
<br><br><br>
