<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();

 use Cake\ORM\TableRegistry;//独立したテーブルを扱う
 $this->Products = TableRegistry::get('Products');

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

<?php if ($check_num == 0): ?>

<?php
      echo $htmlkensahyouheader;
 ?>

<?= $this->Form->create($product, ['url' => ['action' => 'kensatyuichiran']]) ?>

<?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('product_condition_parent_id', array('type'=>'hidden', 'value'=>$product_condition_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('countseikeiki', array('type'=>'hidden', 'value'=>$countseikeiki, 'label'=>false)) ?>
<?= $this->Form->control('inspection_standard_size_parent_id', array('type'=>'hidden', 'value'=>$inspection_standard_size_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('count_seikeijouken', array('type'=>'hidden', 'value'=>$count_seikeijouken, 'label'=>false)) ?>
<?= $this->Form->control('inspection_data_conditon_parent_id', array('type'=>'hidden', 'value'=>$inspection_data_conditon_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('gyou', array('type'=>'hidden', 'value'=>$gyou, 'label'=>false)) ?>
<?= $this->Form->control('tuika', array('type'=>'hidden', 'value'=>1, 'label'=>false)) ?>
<?= $this->Form->control('mikan_check', array('type'=>'hidden', 'value'=>$mikan_check, 'label'=>false)) ?>
<?= $this->Form->control('date_sta', array('type'=>'hidden', 'value'=>$date_sta, 'label'=>false)) ?>
<?= $this->Form->control('date_fin', array('type'=>'hidden', 'value'=>$date_fin, 'label'=>false)) ?>

<?php if ($count_seikeijouken > 1): ?>
  <?= $this->Form->control('inspection_data_conditon_parent_id_moto', array('type'=>'hidden', 'value'=>$inspection_data_conditon_parent_id_moto, 'label'=>false)) ?>
<?php else : ?>
<?php endif; ?>

<?php for($j=1; $j<=$countseikeiki; $j++): ?>
  <?= $this->Form->control('product_conditon_child_id'.$j, array('type'=>'hidden', 'value'=>${"product_conditon_child_id".$j}, 'label'=>false)) ?>
<?php endfor;?>

  <table class="white">

  <tr>
    <td colspan='3'>バンク：<?= h($mode) ?></td>
  <td style='width:98'>測定箇所</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <?php if (${"size_name".$i} == "長さ"): ?>
      <td style='width:78'>切断長</td>
    <?php else : ?>
      <td style='width:78'><?= h(${"size_name".$i}) ?></td>
    <?php endif; ?>
  <?php endfor;?>

  <td width="56" rowspan='3'>外観</td>
  <td width="70" rowspan='3'>重量<br>（目安）</td>
  <td width="35" rowspan='5' style='font-size: 10pt'>合<br>否<br>判<br>定</td>
  <td width="35" rowspan='5' style='font-size: 10pt'>工<br>程<br>異<br>常<br>登<br>録<br>済</td>

</tr>
<tr>

<td width="36" rowspan='8' style='font-size: 10pt'>No.</td>
<td width="130" rowspan='7'>時間</td>
<td width="60" rowspan='6'>規格<br>長さ<br>(mm)</td>

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

    <td width="56" style="font-size: 10pt">〇・✕</td>
        <td width="60">g / 本</td>

</tr>
<tr>
  <td>検査器具</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <td style='font-size: 8pt'><?= h(${"measuring_instrument".$i}) ?></td>
    <?php endfor;?>

    <td width="56" style='font-size: 10pt'>目視</td>
    <td style='width:60; border-top-style:none; font-size: 9pt'>デジタル秤</td>

</tr>

</table>

<?php for($j=1; $j<=$gyou; $j++): ?>

  <?= $this->Form->control('InspectionDataResultParent_id'.$j, array('type'=>'hidden', 'value'=>${"InspectionDataResultParent_id".$j}, 'label'=>false)) ?>
  <?= $this->Form->control('lot_number'.$j, array('type'=>'hidden', 'value'=>${"lot_number".$j}, 'label'=>false)) ?>
  <?= $this->Form->control('datetime_h_i'.$j, array('type'=>'hidden', 'value'=>${"datetime_h_i".$j}, 'label'=>false)) ?>
  <?= $this->Form->control('user_code'.$j, array('type'=>'hidden', 'value'=>${"user_code".$j}, 'label'=>false)) ?>

  <table class="form">

  <?php if ($j == 1): ?>
  <td style='width:36; border-top-style:none; font-size: 11pt'>S</td>
  <?php elseif ($j == $gyou) : ?>
    <td style='width:36; border-top-style:none; font-size: 11pt'>E</td>
  <?php else : ?>
    <td style='width:36; border-top-style:none; font-size: 11pt'><?= h(${"lot_number".$j}) ?></td>
  <?php endif; ?>

  <td style='width:130; border-top-style:none; font-size: 10pt'><?= h(${"datetime".$j}) ?></td></td>

  <td style='width:60; border-top-style:none'><?= h(${"length".$j}) ?></td>

  <td style='width:98; border-top-style:none; font-size: 10pt'><?= h(${"staff_hyouji".$j}) ?></td>

  <?php for($i=1; $i<=11; $i++): ?>

<?php if (${"input_type".$i} == "judge"): ?>

  <?php
  if(${"result_size".$j."_".$i} == 0){
  ${"judge".$j."_".$i} = "〇";
  }else{
  ${"judge".$j."_".$i} = "✕";
  }
  ?>

    <?php if (${"result_size".$j."_".$i} == 1): ?>
      <td style='width:78; border-top-style:none'><font color="red"><?= h(${"judge".$j."_".$i}) ?></td>
    <?php else : ?>
      <td style='width:78; border-top-style:none'><?= h(${"judge".$j."_".$i}) ?></td>
    <?php endif; ?>

<?php else : ?>

  <?php
  if(${"size_name".$i} == "長さ"){//長さ列の場合
    $Products= $this->Products->find()->where(['product_code like' => $product_code_ini.'%',
     'length' => ${"length".$j}, 'delete_flag' => 0, 'status_kensahyou' => 0])->toArray();
    ${"size".$i} = $Products[0]["length_cut"];
    ${"upper_limit".$i} = $Products[0]["length_upper_limit"];
    ${"lower_limit".$i} = $Products[0]["length_lower_limit"];
  }
  ?>

      <?php if (${"result_size".$j."_".$i} <= (float)${"size".$i} + (float)${"upper_limit".$i}
      && ${"result_size".$j."_".$i} >= (float)${"size".$i} + (float)${"lower_limit".$i}): ?>
        <td style='width:78; border-top-style:none'><?= h(${"result_size".$j."_".$i}) ?></td>
      <?php else : ?>
        <td style='width:78; border-top-style:none'><font color="red">
        <?= h(${"result_size".$j."_".$i}) ?>
      </td>
      <?php endif; ?>

<?php endif; ?>

    <?= $this->Form->control('result_size'.$j."_".$i, array('type'=>'hidden', 'value'=>${"result_size".$j."_".$i}, 'label'=>false)) ?>

  <?php endfor;?>

  <?php
  if(${"appearance".$j} == 1){
    ${"gaikanhyouji".$j} = "✕";
  }else{
    ${"gaikanhyouji".$j} = "〇";
  }

  if(${"judge".$j} == 1){
    ${"gouhihyouji".$j} = "否";
  }else{
    ${"gouhihyouji".$j} = "合";
  }
  ?>


    <?php if (${"appearance".$j} == 1): ?>
      <td style='width:56; border-top-style:none'><font color="red"><?= h(${"gaikanhyouji".$j}) ?></td>
    <?php else : ?>
      <td style='width:56; border-top-style:none'><?= h(${"gaikanhyouji".$j}) ?></td>
    <?php endif; ?>


  <td style='width:70; border-top-style:none'><?= h(${"result_weight".$j}) ?></td>
  <td style='width:35; border-top-style:none'><?= h(${"gouhihyouji".$j}) ?></td>

  <?php if (${"loss_check".$j} == 0): ?>
    <td style='width:35; border-top-style:none'>-</td>
  <?php else : ?>
    <td style='width:35; border-top-style:none'><?= $this->Form->submit(('異'), array('name' => $j)) ?></td>
    <?= $this->Form->control('loss'.$j, array('type'=>'hidden', 'value'=>${"loss".$j}, 'label'=>false)) ?>
    <?= $this->Form->control('loss_flag', array('type'=>'hidden', 'value'=>1, 'label'=>false)) ?>
    <?php endif; ?>

</table>

<?= $this->Form->control('product_id'.$j, array('type'=>'hidden', 'value'=>${"product_id".$j}, 'label'=>false)) ?>
<?= $this->Form->control('gaikan'.$j, array('type'=>'hidden', 'value'=>${"appearance".$j}, 'label'=>false)) ?>
<?= $this->Form->control('weight'.$j, array('type'=>'hidden', 'value'=>${"result_weight".$j}, 'label'=>false)) ?>
<?= $this->Form->control('gouhi'.$j, array('type'=>'hidden', 'value'=>${"judge".$j}, 'label'=>false)) ?>

<?php endfor;?>
<br>

<?php
       echo $htmlgenryouheader;
  ?>

<?php
       echo $htmlseikeijouken;
  ?>

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

<?php else : ?>

  <table align="center">
  <tbody class="login">
    <tr height="45">
      <td width="150"><strong>ロス重量（kg）</strong></td>
    </tr>
    <tr height="45">
    <td style='width:78; border-top-style:none'><?= h($loss_amount) ?></td>
    </tr>
    </tbody>
</table>
<br>

<table align="center">
    <tbody class="login">
    <tr height="45">
    <td width="500"><strong>備考</strong></td>
    </tr>
    <tr>
    <td><?= h($bik) ?></td>
    </tr>
    </tbody>
</table>
<br>

  <table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
    </tr>
  </tbody>
</table>
<br>
<?php endif; ?>
