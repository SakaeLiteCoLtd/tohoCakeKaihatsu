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

<br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'adddo']]) ?>

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
<table>
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下の内容で登録します。よろしければ「決定」ボタンを押してください。') ?></strong></td></tr>
</table>
<br>

<?php for($j=1; $j<=$tuikaseikeiki; $j++): ?>
<br>

<?= $this->Form->control('tuikagenryou'.$j, array('type'=>'hidden', 'value'=>${"tuikagenryou".$j}, 'label'=>false)) ?>
<?= $this->Form->control('cylinder_name'.$j, array('type'=>'hidden', 'value'=>${"cylinder_name".$j}, 'label'=>false)) ?>

<table>
<tr>
  <td width="100">成形機</td>
  <td width="350">グレードNo.：メーカー：材料名</td>
  <td width="130">配合比</td>
  <td width="130">乾燥温度</td>
  <td width="130">乾燥時間</td>
  <td width="180">再生配合比</td>
</tr>

<?php
   for($i=1; $i<=${"tuikagenryou".$j}; $i++){

        echo "<tr>\n";

        if($i==1){
          echo "<td rowspan=${"tuikagenryou".$j}>\n";
          echo ${"cylinder_name".$j};
          echo "</td>\n";
        }

        echo "<td>\n";
        echo ${"material_hyouji".$j.$i};
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
        echo "<td>\n";
        echo ${"recycled_mixing_ratio".$j.$i};
        echo "</td>\n";
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
<table>
  <tbody class='sample non-sample'>
    <tr>
      <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border-style: none;"><div><?= $this->Form->submit('決定', array('name' => 'kettei')); ?></div></td>
    </tr>
  </tbody>
</table>

<br><br><br>
