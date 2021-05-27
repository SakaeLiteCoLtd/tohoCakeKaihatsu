<?php
header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策
 ?>

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
 <br>
 <hr size="5" style="margin: 0rem">
 <br>
 <table>
   <tr>
     <td style='border: none'><?php echo $this->Html->image('/img/menus/genryoumenu.gif',array('width'=>'145','height'=>'50'));?></td>
   </tr>
 </table>
 <br>
 <br>

<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<?= $this->Form->create($product, ['url' => ['action' => 'editform']]) ?>

<?= $this->Form->control('user_code', array('type'=>'hidden', 'value'=>$user_code, 'label'=>false)) ?>
<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('tuikaseikeiki', array('type'=>'hidden', 'value'=>$tuikaseikeiki, 'label'=>false)) ?>

<?php
      echo $htmlkensahyouheader;
 ?>

<br>
<table>
  <tbody>
    <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('・データを編集してください。（データを全て削除する場合は「データ削除」ボタンを押してください）') ?></strong></td></tr>
    <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('・成形機ごとデータを削除する場合は「成形機削除」にチェックを入れてください。　　　　　　　　　 ') ?></strong></td></tr>
    <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('・原料データを削除する場合は「原料削除」にチェックを入れてください。　　　　　　　　　　　　　 ') ?></strong></td></tr>
  </tbody>
</table>
<br>

<table align="right">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border:none">　　</td>
      <td style="border:none"><?= $this->Form->submit(('データ削除'), array('name' => 'sakujo')) ?></td>
      <td style="border:none">　　</td>
      <td style="border:none"><?= $this->Form->submit(('成形機内原料追加'), array('name' => 'genryoutuika')) ?></td>
      <td style="border:none">　　</td>
      <td style="border:none"><?= $this->Form->submit(('成形機追加'), array('name' => 'seikeikituika')) ?></td>
      <td style="border:none">　　</td>
      <td style="border:none"><?= $this->Form->submit(('確認画面へ'), array('name' => 'kakuninn')) ?></td>
      <td style="border:none">　　　　　　　　　</td>
      <td style="border:none">　　　　　　　　　</td>
    </tr>
  </tbody>
</table>

<br><br>

<?php for($j=1; $j<=$tuikaseikeiki; $j++): ?>
<br>

<?= $this->Form->control('tuikagenryou'.$j, array('type'=>'hidden', 'value'=>${"tuikagenryou".$j}, 'label'=>false)) ?>

<table>
<tr>
  <td width="50"><font size='2'>成形機<br>削除</td>
  <td width="100">成形機</td>
  <td width="350">メーカー：材料名：グレードNo.：色</td>
  <td width="130">配合比</td>
  <td width="150">乾燥温度</td>
  <td width="150">乾燥時間</td>
  <td width="180">再生配合比</td>
  <td width="50"><font size='2'>原料<br>削除</td>
</tr>

<?php
   for($i=1; $i<=${"tuikagenryou".$j}; $i++){

        echo "<tr>\n";

        if($i==1){
          echo "<td rowspan=${"tuikagenryou".$j}>\n";
          echo "<input type='checkbox' name=delete_seikeiki".$j.">\n";
          echo "</td>\n";
          echo "<td rowspan=${"tuikagenryou".$j}>\n";
          echo "<input type='text' required name=cylinder_name".$j." value=${"cylinder_name".$j}>\n";
          echo "</td>\n";
        }

        echo "<td><div align='center'><select name=material_id".$j.$i." value=${"material_id".$j.$i}>\n";
        foreach ($arrMaterials as $key => $value){
          if($key == ${"material_id".$j.$i}){
            echo "<option value=$key selected>$value</option>";//入力値を初期値に持ってくる
          }else{
            echo "<option value=$key>$value</option>";
          }
        }
        echo "</select></div></td>\n";

        echo "<td>\n";
        echo "<input type='text' required name=mixing_ratio".$j.$i." value=${"mixing_ratio".$j.$i} >\n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='text' style='width:60px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=dry_temp".$j.$i." value=${"dry_temp".$j.$i} > ℃ \n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='text' style='width:60px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=dry_hour".$j.$i." value=${"dry_hour".$j.$i} > h以上\n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='text' required name=recycled_mixing_ratio".$j.$i." value=${"recycled_mixing_ratio".$j.$i} >\n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='checkbox' name=delete_genryou".$j.$i.">\n";
        echo "</td>\n";
        echo "</tr>\n";

      }
 ?>
</table>

<?php endfor;?>

<br><br><br>
