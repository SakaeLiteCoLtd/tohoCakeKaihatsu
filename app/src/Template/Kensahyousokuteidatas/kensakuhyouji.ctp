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

<?= $this->Form->create($product, ['url' => ['action' => 'editform']]) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>
<?= $this->Form->control('datetimesta', array('type'=>'hidden', 'value'=>$datetimesta, 'label'=>false)) ?>
<?= $this->Form->control('datetimefin', array('type'=>'hidden', 'value'=>$datetimefin, 'label'=>false)) ?>
<?= $this->Form->control('datekensaku', array('type'=>'hidden', 'value'=>$datekensaku, 'label'=>false)) ?>

  <table class="white">

  <tr>
    <td style='font-size: 9pt' width="36" rowspan='8'>No.</td>
  </tr>
  <tr>
    <td width="130" rowspan='7'>時間</td>
  </tr>
  <td width="60" rowspan='6'>長さ</td>

<tr>
  <td style='width:105'>測定箇所</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <td style='width:80'><?= h(${"size_name".$i}) ?></td>
  <?php endfor;?>

  <td width="60" rowspan='3'>外観</td>
  <td style='font-size: 9pt' width="60" rowspan='3'>重量<br>（目安）</td>
  <td style='font-size: 9pt' width="50" rowspan='5'>合否<br>判定</td>

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
    <td style='width:60; border-top-style:none; font-size: 8pt'>デジタル秤</td>

</tr>

</table>

<?php for($j=1; $j<=$gyou; $j++): ?>

  <table class="form">

  <td style='width:36; border-top-style:none; font-size: 11pt'><?= h(${"lot_number".$j}) ?></td>
  <td style='width:130; border-top-style:none; font-size: 10pt'><?= h(${"datetime".$j}) ?></td></td>

  <td style='width:60; border-top-style:none'><?= h(${"length".$j}) ?></td>

  <td style='width:105; border-top-style:none'><?= h(${"staff_hyouji".$j}) ?></td>

  <?php for($i=1; $i<=11; $i++): ?>

    <?php if (${"input_type".$i} == "judge"): ?>

      <?php
     if(${"result_size".$j.$i} == 0){
       ${"judge".$j.$i} = "〇";
     }else{
      ${"judge".$j.$i} = "✕";
    }
  ?>

      <td style='width:80; border-top-style:none'><?= h(${"judge".$j.$i}) ?></td>
    <?php else : ?>
      <td style='width:80; border-top-style:none'><?= h(${"result_size".$j.$i}) ?></td>
      <?php endif; ?>

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
  <td style='width:60; border-top-style:none'><?= h(${"result_weight".$j}) ?></td>
  <td style='width:50; border-top-style:none'><?= h(${"gouhihyouji".$j}) ?></td>

</table>

<?php endfor;?>

<br>

<table>
  <tbody style="background-color: #FFFFCC">
    <tr>
    <td width="150">生産重量（kg）</td>
    <td width="400">備考</td>
    </tr>
    <tr>
    <td><?= h($total_amount) ?></td>
    <td><?= h($bik) ?></td>
    </tr>
  </tbody>
</table>

<br>

<?php if ($account_check == 0): ?>

<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
    </tr>
  </tbody>
</table>
<br>

<?php else : ?>

  <table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      <td style="border: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('編集・削除'), array('name' => 'kakuninn')) ?></td>
    </tr>
  </tbody>
</table>
<br>

<?php endif; ?>
