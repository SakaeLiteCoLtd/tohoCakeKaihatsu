<?php
header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策
 ?>

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
    <font size='4'>　>>　</font><a href='/Kensahyougenryous/addlogin' /><font size='4' color=black>新規登録</font></a>
    </a></td>
  </tbody>
</table>

<?php
  //   echo $htmlkensahyoukadou;
?>

<br><br><br>
<?php
  //   echo $htmlkensahyoumenu;
?>

<?= $this->Form->create($product, ['url' => ['action' => 'addform']]) ?>

<?= $this->Form->control('user_code', array('type'=>'hidden', 'value'=>$user_code, 'label'=>false)) ?>
<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('tuikaseikeiki', array('type'=>'hidden', 'value'=>$tuikaseikeiki, 'label'=>false)) ?>

  <?php
        echo $htmlkensahyouheader;
   ?>

<table class="top">
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
</table>

<table align="right">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border:none">　　</td>
      <td style="border:none"><?= $this->Form->submit(('成形機内原料追加'), array('name' => 'genryoutuika')) ?></td>
      <td style="border:none">　　</td>
      <td style="border:none"><?= $this->Form->submit(('成形機追加'), array('name' => 'seikeikituika')) ?></td>
      <td style="border:none">　　</td>
      <td style="border:none"><?= $this->Form->submit(('登録確認へ'), array('name' => 'kakuninn')) ?></td>
      <td style="border:none">　　　　　　　　　</td>
      <td style="border:none">　　　　　　　　　</td>
    </tr>
  </tbody>
</table>

<br>

<?php for($j=1; $j<=$tuikaseikeiki; $j++): ?>
<br>

<?= $this->Form->control('tuikagenryou'.$j, array('type'=>'hidden', 'value'=>${"tuikagenryou".$j}, 'label'=>false)) ?>

<table>
<tr class="parents">
  <td width="150">成形機</td>
  <td width="190">メーカー</td>
  <td width="300">材料名：グレードNo.：色</td>
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
          echo "<input type='text' name=cylinder_name".$j." value=${"cylinder_name".$j}>\n";
          echo "</td>\n";
        }

        if(${"makercheck".$j.$i}==0){

          echo "<td><div align='center'><select name=material_maker".$j.$i." value=${"material_maker".$j.$i}>\n";
          foreach ($arrMaterialmakers as $key => $value){
            if($key == ${"material_maker".$j.$i}){
              echo "<option value=$key selected>$value</option>";//入力値を初期値に持ってくる
            }else{
              echo "<option value=$key>$value</option>";
            }
          }
          echo "</select></div></td>\n";

        }else{

          echo "<td>\n";
          echo "${"material_maker".$j.$i}\n";
          echo "</td>\n";
?>

<?= $this->Form->control('material_maker'.$j.$i, array('type'=>'hidden', 'value'=>${"material_maker".$j.$i}, 'label'=>false)) ?>
<?= $this->Form->control('makercheck'.$j.$i, array('type'=>'hidden', 'value'=>${"makercheck".$j.$i}, 'label'=>false)) ?>

<?php
        }

        if(${"makercheck".$j.$i}==1){

          echo "<td><div align='center'><select name=material_id".$j.$i." value=${"material_id".$j.$i}>\n";
          foreach ($arrMaterials as $key => $value){
            if($key == ${"material_id".$j.$i}){
              echo "<option value=$key selected>$value</option>";//入力値を初期値に持ってくる
            }else{
              echo "<option value=$key>$value</option>";
            }
          }
          echo "</select></div></td>\n";

        }elseif(${"makercheck".$j.$i}==2){

          echo "<td>\n";
          echo "${"material_houji".$j.$i}\n";
          echo "</td>\n";

?>

<?= $this->Form->control('material_id'.$j.$i, array('type'=>'hidden', 'value'=>${"material_id".$j.$i}, 'label'=>false)) ?>

<?php

        }else{

?>

          <td><?= $this->Form->submit(('メーカー絞り込み'), array('name' => 'siborikomi')) ?></td>

<?php

        }

        echo "<td>\n";
        echo "<input type='text' name=mixing_ratio".$j.$i." value=${"mixing_ratio".$j.$i} >\n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='text' style='width:60px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' name=dry_temp".$j.$i." value=${"dry_temp".$j.$i} > ℃ \n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='text' style='width:60px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' name=dry_hour".$j.$i." value=${"dry_hour".$j.$i} > h以上\n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='text' name=recycled_mixing_ratio".$j.$i." value=${"recycled_mixing_ratio".$j.$i} >\n";
        echo "</td>\n";
        echo "</tr>\n";

      }
 ?>
</table>

<?php endfor;?>

<br><br><br>
