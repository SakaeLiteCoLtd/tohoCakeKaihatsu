<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
?>
<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<table class='sample hesdermenu'>
  <tbody>
    <td style='border: none;align: left'>
      <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
    <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
    <font size='4'>　>>　</font><a href='/Kensahyougenryous/menu' /><font size='4' color=black>原料登録</font></a>
    <font size='4'>　>>　</font><a href='/Kensahyougenryous/kensakupre' /><font size='4' color=black>登録データ呼出</font></a>
    </a></td>
  </tbody>
</table>

<?= $this->Form->create($product, ['url' => ['action' => 'editalldo']]) ?>

<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('tuikaseikeiki', array('type'=>'hidden', 'value'=>$tuikaseikeiki, 'label'=>false)) ?>
<?= $this->Form->control('delete_flag', array('type'=>'hidden', 'value'=>$delete_flag, 'label'=>false)) ?>
<?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>
<?= $this->Form->control('machine_num_moto', array('type'=>'hidden', 'value'=>$machine_num_moto, 'label'=>false)) ?>
<?= $this->Form->control('countseikeiki', array('type'=>'hidden', 'value'=>$countseikeiki, 'label'=>false)) ?>
<br> <br> <br>

<?php
      echo $htmlkensahyouheader;
 ?>
<br>
 <table class="top_big">
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
</table>

<table align="left">
  <tbody class='sample non-sample'>
    <tr>
    <td style="border:none">　　　　　　　　　</td>
    <td style="border:none"><font size="4"><strong><?= __($machine_num." 号機") ?></strong></font></td>
    </tr>
  </tbody>
</table>
<br>
<br>
<?php for($j=1; $j<=$tuikaseikeiki; $j++): ?>

<?= $this->Form->control('tuikagenryou'.$j, array('type'=>'hidden', 'value'=>${"tuikagenryou".$j}, 'label'=>false)) ?>
<?= $this->Form->control('cylinder_name'.$j, array('type'=>'hidden', 'value'=>${"cylinder_name".$j}, 'label'=>false)) ?>

<table>
  <tr class="parents">
    <td width="150">成形機</td>
    <td width="490">原料名</td>
    <td width="190">配合比</td>
    <td width="190">乾燥温度</td>
    <td width="190">乾燥時間</td>
    <td width="200">再生配合比</td>
</tr>

<?php
   for($i=1; $i<=${"tuikagenryou".$j}; $i++){

     echo "<tr class='children'>\n";

        if($i==1){
          echo "<td rowspan=${"tuikagenryou".$j}>\n";
          echo ${"cylinder_name".$j};
          echo "</td>\n";
        }

        echo "<td>\n";
        echo ${"material_name".$j.$i};
        echo "</td>\n";
        echo "<td>\n";
        echo ${"mixing_ratio".$j.$i};
        echo "</td>\n";
        echo "<td>\n";
        echo ${"dry_temp".$j.$i}." ℃";
        echo "</td>\n";
        echo "<td>\n";
        echo ${"dry_hour".$j.$i}." h以上";
        echo "</td>\n";

        if($i==1){
          echo "<td rowspan=${"tuikagenryou".$j}>\n";
          echo ${"recycled_mixing_ratio".$j.$i};
          echo "</td>\n";
          }

        echo "</tr>\n";

      }

?>

      <?php for($i=1; $i<=${"tuikagenryou".$j}; $i++): ?>
        <?= $this->Form->control('material_id'.$j.$i, array('type'=>'hidden', 'value'=>${"material_id".$j.$i}, 'label'=>false)) ?>
        <?= $this->Form->control('mixing_ratio'.$j.$i, array('type'=>'hidden', 'value'=>${"mixing_ratio".$j.$i}, 'label'=>false)) ?>
        <?= $this->Form->control('dry_temp'.$j.$i, array('type'=>'hidden', 'value'=>${"dry_temp".$j.$i}, 'label'=>false)) ?>
        <?= $this->Form->control('dry_hour'.$j.$i, array('type'=>'hidden', 'value'=>${"dry_hour".$j.$i}, 'label'=>false)) ?>
        <?= $this->Form->control('recycled_mixing_ratio'.$j.$i, array('type'=>'hidden', 'value'=>${"recycled_mixing_ratio".$j.$i}, 'label'=>false)) ?>
      <?php endfor;?>

</table>

<?php endfor;?>

<br>
<?= $this->Form->control('pickup_speed', array('type'=>'hidden', 'value'=>$pickup_speed, 'label'=>false)) ?>
<?= $this->Form->control('check', array('type'=>'hidden', 'value'=>$this->request->getData('check'), 'label'=>false)) ?>

<?php for($j=1; $j<=$countseikeiki; $j++): ?>

<?= $this->Form->control('cylinder_name'.$j, array('type'=>'hidden', 'value'=>${"cylinder_name".$j}, 'label'=>false)) ?>
<?= $this->Form->control('extrude_roatation'.$j, array('type'=>'hidden', 'value'=>${"extrude_roatation".$j}, 'label'=>false)) ?>
<?= $this->Form->control('extrusion_load'.$j, array('type'=>'hidden', 'value'=>${"extrusion_load".$j}, 'label'=>false)) ?>
<?= $this->Form->control('idmoto'.$j, array('type'=>'hidden', 'value'=>$this->request->getData('idmoto'.$j), 'label'=>false)) ?>
<?= $this->Form->control('screw'.$j, array('type'=>'hidden', 'value'=>${"screw".$j}, 'label'=>false)) ?>

<?php for($n=1; $n<=7; $n++): ?>

  <?= $this->Form->control('temp_'.$n.$j, array('type'=>'hidden', 'value'=>${"temp_".$n.$j}, 'label'=>false)) ?>

<?php endfor;?>

<?php for($n=1; $n<=3; $n++): ?>

  <?= $this->Form->control('screw_mesh_'.$n.$j, array('type'=>'hidden', 'value'=>${"screw_mesh_".$n.$j}, 'label'=>false)) ?>
  <?= $this->Form->control('screw_number_'.$n.$j, array('type'=>'hidden', 'value'=>${"screw_number_".$n.$j}, 'label'=>false)) ?>

<?php endfor;?>

<table>
  <tr class="parents">
  <td style='width:80'>成形機</td>
  <td width="100">温度条件</td>
  <td style='width:70'>C １</td>
  <td style='width:70'>C ２</td>
  <td style='width:70'>C ３</td>
  <td style='width:70'>C ４</td>
  <td style='width:70'>A D</td>
  <td style='width:70'>D １</td>
  <td style='width:70'>D ２</td>
  <td style='width:226' colspan="2">押出回転(rpm)/負荷(A)</td>
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
          echo "${"extrude_roatation".$j}\n";
          echo "(rpm)/</td>\n";
          echo "<td style='border-left-style:none; text-align:left'>\n";
          echo "${"extrusion_load".$j}\n";
          echo "(A)</td>\n";
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
          echo "<td colspan=2>\n";
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
              echo "<td rowspan=3>\n";
              echo "${"screw".$j}\n";
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
          }else{
            echo "<td style='border-top-style:none;'>\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_mesh_3".$j}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_number_3".$j}\n";
            echo "</td>\n";
          }
        }

        echo "</tr>\n";

      }
 ?>
</table>

<?php endfor;?>

<br>
<table>
  <tbody class='sample non-sample'>
    <tr>
    <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('決定'), array('name' => 'kakuninn')) ?></td>
    </tr>
  </tbody>
</table>

<br><br><br>
