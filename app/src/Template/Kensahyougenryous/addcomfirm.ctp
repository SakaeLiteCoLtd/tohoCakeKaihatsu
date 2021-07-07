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

<?= $this->Form->create($product, ['url' => ['action' => 'adddo']]) ?>

<?= $this->Form->control('user_code', array('type'=>'hidden', 'value'=>$this->request->getData('user_code'), 'label'=>false)) ?>
<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('tuikaseikeiki', array('type'=>'hidden', 'value'=>$tuikaseikeiki, 'label'=>false)) ?>

<?php
      echo $htmlkensahyouheader;
 ?>

 <table class="top">
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下の内容で登録します。よろしければ「決定」ボタンを押してください。') ?></strong></td></tr>
</table>

<?php for($j=1; $j<=$tuikaseikeiki; $j++): ?>
<br>

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
        echo "<td>\n";
        echo ${"recycled_mixing_ratio".$j.$i};
        echo "</td>\n";
        echo "</tr>\n";

      }

?>

      <?php for($i=1; $i<=${"tuikagenryou".$j}; $i++): ?>
        <?= $this->Form->control('material_id'.$j.$i, array('type'=>'hidden', 'value'=>${"material_id".$j.$i}, 'label'=>false)) ?>
        <?= $this->Form->control('material_name'.$j.$i, array('type'=>'hidden', 'value'=>${"material_name".$j.$i}, 'label'=>false)) ?>
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
