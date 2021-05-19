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
 <table>
   <tr>
     <td style='border: none'><?php echo $this->Html->image('/img/menus/subtouroku.gif',array('width'=>'145','height'=>'50'));?></td>
   </tr>
 </table>
 <br>

<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<?= $this->Form->create($product, ['url' => ['action' => 'addform']]) ?>

<?= $this->Form->control('user_code', array('type'=>'hidden', 'value'=>$user_code, 'label'=>false)) ?>
<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('tuikaseikeiki', array('type'=>'hidden', 'value'=>$tuikaseikeiki, 'label'=>false)) ?>

<table width="1000">
    <tr>
      <td width="500" colspan="2" nowrap="nowrap" style="height: 60px"><strong>検査成績書</strong><br>（兼　成形条件表・梱包仕様書・作業手順書）</td>
      <td width="100" nowrap="nowrap" style="height: 30px">製品名</td>
      <td width="400" nowrap="nowrap" style="height: 30px"><?= h($name) ?></td>
    </tr>
    <tr>
      <td width="200" nowrap="nowrap" style="height: 30px">管理No</td>
      <td width="300" style="height: 30px"><?= h($product_code) ?></td>
      <td width="200" rowspan='2' style="height: 30px">顧客名</td>
      <td width="300" rowspan='2' style="height: 30px"><?= h($customer) ?></td>
    </tr>
    <tr>
      <td width="200" nowrap="nowrap" style="height: 30px">改訂日</td>
      <td width="300" style="height: 30px"><?= h("（仮）".$today); ?></td>
    </tr>
    <tr>
      <td width="1000" colspan="4" nowrap="nowrap" style="height: 400px">画像</td>
    </tr>
</table>

<br>

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

<br><br>

<?php for($j=1; $j<=$tuikaseikeiki; $j++): ?>
<br>

<?= $this->Form->control('tuikagenryou'.$j, array('type'=>'hidden', 'value'=>${"tuikagenryou".$j}, 'label'=>false)) ?>

<table>
<tr>
  <td width="100">成形機</td>
  <td width="350">グレードNo.：メーカー：材料名</td>
  <td width="130">配合比</td>
  <td width="150">乾燥温度</td>
  <td width="150">乾燥時間</td>
  <td width="180">再生配合比</td>
</tr>

<?php
   for($i=1; $i<=${"tuikagenryou".$j}; $i++){

        echo "<tr>\n";

        if($i==1){
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
        echo "</tr>\n";

      }
 ?>
</table>

<?php endfor;?>

<br><br><br>
