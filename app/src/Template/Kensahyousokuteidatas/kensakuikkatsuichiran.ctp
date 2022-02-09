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

<?= $this->Form->create($product, ['url' => ['action' => 'kensakuikkatsujouken']]) ?>

<?=$this->Form->hidden("product_code", array('type' => 'value', 'value' => $product_code)); ?>
<?=$this->Form->hidden("machine_num", array('type' => 'value', 'value' => $machine_num)); ?>

  <table class="white">

  <tr>

  <td colspan='4'>バンク：<?= h($mode) ?></td>

  <td style='width:96'>測定箇所</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <td style='width:78'><?= h(${"size_name".$i}) ?></td>
  <?php endfor;?>

  <td width="48" rowspan='4'>外観</td>
  <td width="62" rowspan='4'>重量<br>（目安）</td>
  <td width="46" rowspan='6'>合否<br>判定</td>

</tr>
<tr>

<td width="62" rowspan='9' style='font-size: 10pt'>画像<br>・<br>規格<br>条件</td>
  <td width="62" rowspan='8' style='font-size: 10pt'>原料<br>・<br>温度<br>条件</td>
  <td width="62" rowspan='7' style='font-size: 10pt'>当日<br>成形<br>条件</td>
    <td width="82" rowspan='6'>時間</td>

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

    <td width="48" style="font-size: 9pt">〇・✕</td>
        <td width="62">g / 本</td>

</tr>
<tr>
  <td>検査器具</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <td style='font-size: 8pt'><?= h(${"measuring_instrument".$i}) ?></td>
    <?php endfor;?>

    <td width="48">目視</td>
    <td style='width:62; border-top-style:none; font-size: 8pt'>デジタル秤</td>

</tr>

</table>


<table class="form">

<?php
  $numondocheck = 1;
  $numondocount = 0;
  $numkikakucheck = 1;
  $numkikakucount = 0;
  $numtoujitucheck = 1;
  $numtoujitucount = 0;
  ?>

<?php for($j=1; $j<=$gyou; $j++): ?>

  <tr>

<?php if($j == $numkikakucheck): ?>

<?php
$numkikakucheck = $numkikakucheck + $arr_numnakama_kikaku[$numkikakucount];
$kikaku_created_at = substr(${"kikaku_created_at".$j}, 2, 2).substr(${"kikaku_created_at".$j}, 5, 2).substr(${"kikaku_created_at".$j}, 8, 2);

echo "<td valign='top' style='padding-top: 8px; width:62; border-top-style:none; font-size: 10pt' rowspan=$arr_numnakama_kikaku[$numkikakucount]>";
echo $this->Form->submit($kikaku_created_at , ['action'=>'kensakusyousai', 'name' => "kikaku_".${"datetime".$j}]) ;
echo "</td>";

$numkikakucount = $numkikakucount + 1;
?>

<?php else: ?>
<?php endif; ?>

<?php if($j == $numondocheck): ?>

  <?php
  $numondocheck = $numondocheck + $arr_numnakama_ondo[$numondocount];
  $ondo_created_at = substr(${"ondo_created_at".$j}, 2, 2).substr(${"ondo_created_at".$j}, 5, 2).substr(${"ondo_created_at".$j}, 8, 2);

  echo "<td valign='top' style='padding-top: 8px; width:62; border-top-style:none; font-size: 10pt' rowspan=$arr_numnakama_ondo[$numondocount]>";
  echo $this->Form->submit($ondo_created_at , ['action'=>'kensakusyousai', 'name' => "ondo_".${"datetime".$j}]) ;
  echo "</td>";

  $numondocount = $numondocount + 1;
  ?>

<?php else: ?>
<?php endif; ?>

<?php if($j == $numtoujitucheck): ?>

<?php
$numtoujitucheck = $numtoujitucheck + $arr_numnakama_toujitu[$numtoujitucount];
$toujitu_created_at = substr(${"datetime".$j}, 2, 2).substr(${"datetime".$j}, 5, 2).substr(${"datetime".$j}, 8, 2);

echo "<td valign='top' style='padding-top: 8px; width:62; border-top-style:none; font-size: 10pt' rowspan=$arr_numnakama_toujitu[$numtoujitucount]>";
echo $this->Form->submit($toujitu_created_at , ['action'=>'kensakusyousai', 'name' => "toujitu_".${"datetime".$j}]) ;
echo "</td>";

$numtoujitucount = $numtoujitucount + 1;
?>

<?php else: ?>
<?php endif; ?>

<td style='width:82; border-top-style:none; font-size: 10pt'><?= h(${"datetime".$j}) ?></td></td>

<td style='width:96; border-top-style:none; font-size: 8pt'><?= h("長さ：".${"length".$j}) ?><br><?= h(${"staff_hyouji".$j}) ?></td>

<?php for($i=1; $i<=11; $i++): ?>

  <?php if (${"input_type".$i} == "judge"): ?>

<?php
if(${"result_size".$j."_".$i} == 0){
 ${"judge".$j."_".$i} = "〇";
}else{
${"judge".$j."_".$i} = "✕";
}
?>

<td style='width:78; border-top-style:none'><?= h(${"judge".$j."_".$i}) ?></td>
<?php elseif(strlen(${"result_size".$j."_".$i}) > 0) : ?>
<td style='width:78; border-top-style:none'><?= h(sprintf("%.1f", ${"result_size".$j."_".$i})) ?></td>
<?php else : ?>
<td style='width:78; border-top-style:none'><?= h(${"result_size".$j."_".$i}) ?></td>
<?php endif; ?>

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

<td style='width:48; border-top-style:none'><?= h(${"gaikanhyouji".$j}) ?></td>
<td style='width:62; border-top-style:none'><?= h(${"result_weight".$j}) ?></td>
<td style='width:46; border-top-style:none'><?= h(${"gouhihyouji".$j}) ?></td>

</tr>

<?php endfor;?>

</table>


<br><br>

<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
    </tr>
  </tbody>
</table>
<br>
