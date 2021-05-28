<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
?>
 <br>
<?php
      echo $htmlkensahyoukadou;
 ?>
 <br>
 <?php
      echo $htmlkensahyoumenu;
 ?>

<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<br>
<hr size="5" style="margin: 0rem">
<br>
<table>
  <tr>
    <td style='border: none'><?php echo $this->Html->image('/img/menus/seikeiondomenu.gif',array('width'=>'145','height'=>'50'));?></td>
  </tr>
</table>
<br>
<table>
  <tr>
    <td style='border: none'><?php echo $this->Html->image('/img/menus/subtouroku.gif',array('width'=>'145','height'=>'50'));?></td>
  </tr>
</table>
<br>

<?= $this->Form->create($product, ['url' => ['action' => 'adddo']]) ?>

<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('countseikeiki', array('type'=>'hidden', 'value'=>$countseikeiki, 'label'=>false)) ?>

<?php
      echo $htmlkensahyouheader;
 ?>

<br>
<table>
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下の内容で登録します。よろしければ「登録確定」ボタンを押してください。') ?></strong></td></tr>
</table>

<?= $this->Form->control('pickup_speed', array('type'=>'hidden', 'value'=>$pickup_speed, 'label'=>false)) ?>

<?php for($j=1; $j<=$countseikeiki; $j++): ?>

<br>

<?= $this->Form->control('product_material_machine_id'.$j, array('type'=>'hidden', 'value'=>${"product_material_machine_id".$j}, 'label'=>false)) ?>
<?= $this->Form->control('cylinder_name'.$j, array('type'=>'hidden', 'value'=>${"cylinder_name".$j}, 'label'=>false)) ?>
<?= $this->Form->control('extrude_roatation'.$j, array('type'=>'hidden', 'value'=>${"extrude_roatation".$j}, 'label'=>false)) ?>
<?= $this->Form->control('extrusion_load'.$j, array('type'=>'hidden', 'value'=>${"extrusion_load".$j}, 'label'=>false)) ?>
<?= $this->Form->control('screw_mesh_1'.$j, array('type'=>'hidden', 'value'=>${"screw_mesh_1".$j}, 'label'=>false)) ?>
<?= $this->Form->control('screw_number_1'.$j, array('type'=>'hidden', 'value'=>${"screw_number_1".$j}, 'label'=>false)) ?>
<?= $this->Form->control('screw_1'.$j, array('type'=>'hidden', 'value'=>${"screw_1".$j}, 'label'=>false)) ?>
<?= $this->Form->control('screw_mesh_2'.$j, array('type'=>'hidden', 'value'=>${"screw_mesh_2".$j}, 'label'=>false)) ?>
<?= $this->Form->control('screw_number_2'.$j, array('type'=>'hidden', 'value'=>${"screw_number_2".$j}, 'label'=>false)) ?>
<?= $this->Form->control('screw_2'.$j, array('type'=>'hidden', 'value'=>${"screw_2".$j}, 'label'=>false)) ?>
<?= $this->Form->control('screw_mesh_3'.$j, array('type'=>'hidden', 'value'=>${"screw_mesh_3".$j}, 'label'=>false)) ?>
<?= $this->Form->control('screw_number_3'.$j, array('type'=>'hidden', 'value'=>${"screw_number_3".$j}, 'label'=>false)) ?>
<?= $this->Form->control('screw_3'.$j, array('type'=>'hidden', 'value'=>${"screw_3".$j}, 'label'=>false)) ?>
<?= $this->Form->control('product_material_machine_id'.$j, array('type'=>'hidden', 'value'=>${"product_material_machine_id".$j}, 'label'=>false)) ?>

<?php for($n=1; $n<=7; $n++): ?>

  <?= $this->Form->control('temp_'.$n.$j, array('type'=>'hidden', 'value'=>${"temp_".$n.$j}, 'label'=>false)) ?>

<?php endfor;?>

<table>
<tr>
  <td style='width:80'>成形機</td>
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
  <td style='width:160' colspan="2">ｽｸﾘｰﾝﾒｯｼｭ : 枚数</td>
  <td style='width:100'>ｽｸﾘｭｳ</td>
</tr>

<?php
   for($i=1; $i<=3; $i++){

        echo "<tr>\n";

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
          echo "<td>\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "</td>\n";
          echo "<td style='border-right-style:none'>\n";
          echo "</td>\n";
          echo "<td style='border-left-style:none'>\n";
          echo "</td>\n";
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
              echo "<td>\n";
              echo "${"screw_1".$j}\n";
              echo "</td>\n";
            }elseif($i==2){
              echo "<td>\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_mesh_2".$j}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_number_2".$j}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_2".$j}\n";
              echo "</td>\n";
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
              echo "<td>\n";
              echo "${"screw_3".$j}\n";
              echo "</td>\n";
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
            echo "<td>\n";
            echo "${"screw_1".$j}\n";
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
            echo "<td>\n";
            echo "${"screw_2".$j}\n";
            echo "</td>\n";
          }else{
            echo "<td style='border-top-style:none;'>\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_mesh_3".$j}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_number_3".$j}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_3".$j}\n";
            echo "</td>\n";
          }
        }

        echo "</tr>\n";

      }
 ?>
</table>

<?php endfor;?>

<br><br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      <td style="border: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('登録確定'), array('name' => 'kakuninn')) ?></td>
    </tr>
  </tbody>
</table>
<br>
