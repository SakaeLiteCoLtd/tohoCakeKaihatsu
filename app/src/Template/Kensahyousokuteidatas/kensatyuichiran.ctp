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
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/menu' /><font size='4' color=black>データ測定</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/kensakumenu' /><font size='4' color=black>登録データ呼出</font></a>
    </td>
  </tbody>
</table>

<br><br><br>

<?php
      echo $htmlkensahyouheader;
 ?>

<?= $this->Form->create($product, ['url' => ['action' => 'addform']]) ?>

<?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('product_condition_parent_id', array('type'=>'hidden', 'value'=>$product_condition_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('countseikeiki', array('type'=>'hidden', 'value'=>$countseikeiki, 'label'=>false)) ?>
<?= $this->Form->control('inspection_standard_size_parent_id', array('type'=>'hidden', 'value'=>$inspection_standard_size_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('count_seikeijouken', array('type'=>'hidden', 'value'=>$count_seikeijouken, 'label'=>false)) ?>
<?= $this->Form->control('inspection_data_conditon_parent_id', array('type'=>'hidden', 'value'=>$inspection_data_conditon_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('gyou', array('type'=>'hidden', 'value'=>$gyou, 'label'=>false)) ?>
<?= $this->Form->control('tuika', array('type'=>'hidden', 'value'=>1, 'label'=>false)) ?>

<?php if ($count_seikeijouken > 1): ?>
  <?= $this->Form->control('inspection_data_conditon_parent_id_moto', array('type'=>'hidden', 'value'=>$inspection_data_conditon_parent_id_moto, 'label'=>false)) ?>
<?php else : ?>
<?php endif; ?>

<?php for($j=1; $j<=$countseikeiki; $j++): ?>
  <?= $this->Form->control('product_conditon_child_id'.$j, array('type'=>'hidden', 'value'=>${"product_conditon_child_id".$j}, 'label'=>false)) ?>
<?php endfor;?>

  <table class="white">

  <tr>
    <td width="40" rowspan='8'>No.</td>
  </tr>
  <tr>
    <td width="130" rowspan='7'>時間</td>
  </tr>
  <td width="60" rowspan='6'>長さ</td>

<tr>
  <td style='width:110'>測定箇所</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <td style='width:78'><?= h(${"size_name".$i}) ?></td>
  <?php endfor;?>

  <td width="60" rowspan='3'>外観</td>
  <td width="70" rowspan='3'>重量<br>（目安）</td>
  <td width="53" rowspan='5'>合否<br>判定</td>

</tr>
<tr>
  <td>規格</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <td><?= h(${"size".$i}) ?></td>
    <?php endfor;?>
</tr>
<tr>
  <td>公差上限</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <?php if (${"input_type".$i} == "int" && strlen(${"upper_limit".$i}) > 0 && substr(${"upper_limit".$i}, 0, 1) != "+" && substr(${"upper_limit".$i}, 0, 1) != "-"): ?>
    <td><?= h("+".${"upper_limit".$i}) ?></td>
    <?php else : ?>
      <td><?= h(${"upper_limit".$i}) ?></td>
      <?php endif; ?>
  <?php endfor;?>

</tr>
<tr>
  <td>公差下限</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <td><?= h(${"lower_limit".$i}) ?></td>
    <?php endfor;?>

    <td width="60" style="font-size: 10pt">〇・✕</td>
        <td width="60">g / 本</td>

</tr>
<tr>
  <td>検査器具</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <td style='font-size: 8pt'><?= h(${"measuring_instrument".$i}) ?></td>
    <?php endfor;?>

    <td width="60">目視</td>
    <td style='width:60; border-top-style:none; font-size: 9pt'>デジタル秤</td>

</tr>

</table>

<?php for($j=1; $j<=$gyou; $j++): ?>

  <?= $this->Form->control('lot_number'.$j, array('type'=>'hidden', 'value'=>${"lot_number".$j}, 'label'=>false)) ?>
  <?= $this->Form->control('datetime_h_i'.$j, array('type'=>'hidden', 'value'=>${"datetime_h_i".$j}, 'label'=>false)) ?>
  <?= $this->Form->control('user_code'.$j, array('type'=>'hidden', 'value'=>${"user_code".$j}, 'label'=>false)) ?>

  <table class="form">

  <td style='width:40; border-top-style:none; font-size: 11pt'><?= h(${"lot_number".$j}) ?></td>
  <td style='width:130; border-top-style:none; font-size: 11pt'><?= h(${"datetime".$j}) ?></td></td>

  <td style='width:60; border-top-style:none'><?= h(${"length".$j}) ?></td>

  <td style='width:110; border-top-style:none'><?= h(${"staff_hyouji".$j}) ?></td>

  <?php for($i=1; $i<=11; $i++): ?>

    <?php if (${"input_type".$i} == "judge"): ?>

<?php
if(${"result_size".$j.$i} == 0){
 ${"judge".$j.$i} = "〇";
}else{
${"judge".$j.$i} = "✕";
}
?>

<td style='width:78; border-top-style:none'><?= h(${"judge".$j.$i}) ?></td>
<?php else : ?>
<td style='width:78; border-top-style:none'><?= h(${"result_size".$j.$i}) ?></td>
<?php endif; ?>

    <?= $this->Form->control('result_size'.$j.$i, array('type'=>'hidden', 'value'=>${"result_size".$j.$i}, 'label'=>false)) ?>

  <?php endfor;?>

  <?php
  if(${"appearance".$j} == 1){
    ${"gaikanhyouji".$j} = "不";
  }else{
    ${"gaikanhyouji".$j} = "良";
  }

  if(${"judge".$j} == 1){
    ${"gouhihyouji".$j} = "否";
  }else{
    ${"gouhihyouji".$j} = "合";
  }

  ?>

<td style='width:60; border-top-style:none'><?= h(${"gaikanhyouji".$j}) ?></td>
  <td style='width:70; border-top-style:none'><?= h(${"result_weight".$j}) ?></td>
  <td style='width:53; border-top-style:none'><?= h(${"gouhihyouji".$j}) ?></td>

</table>

<?= $this->Form->control('product_id'.$j, array('type'=>'hidden', 'value'=>${"product_id".$j}, 'label'=>false)) ?>
<?= $this->Form->control('gaikan'.$j, array('type'=>'hidden', 'value'=>${"appearance".$j}, 'label'=>false)) ?>
<?= $this->Form->control('weight'.$j, array('type'=>'hidden', 'value'=>${"result_weight".$j}, 'label'=>false)) ?>
<?= $this->Form->control('gouhi'.$j, array('type'=>'hidden', 'value'=>${"judge".$j}, 'label'=>false)) ?>

<?php endfor;?>
<br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('続きから登録する'), array('name' => 'tuzukikara')) ?></td>
    </tr>
  </tbody>
</table>
<br>