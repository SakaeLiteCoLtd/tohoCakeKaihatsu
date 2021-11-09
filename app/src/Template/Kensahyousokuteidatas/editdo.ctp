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
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/menu' /><font size='4' color=black>測定データ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/kensakumenu' /><font size='4' color=black>登録データ呼出</font></a>
    </td>
  </tbody>
</table>

<br><br><br>
<?= $this->Form->create($product, ['url' => ['action' => 'menu']]) ?>

<?php
      echo $htmlkensahyouheader;
 ?>


 <table class="white">

  <tr>
    <td width="43" rowspan='8'>No.</td>
  </tr>
  <tr>
  <td width="65" rowspan='7'><font size='2'><br></font>日付<br><font size='2'>
  <br><?= h(substr($datekensaku, 0, 4)) ?><br>
  <?= h("/".substr($datekensaku, 5, 2)) ?><br>
  <?= h("/".substr($datekensaku, 8, 2)) ?><br>
  </font><br>時間</td>
  </tr>
  <td width="67" rowspan='6'>長さ</td>

<tr>
  <td style='width:110'>測定箇所</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <td style='width:84'><?= h(${"size_name".$i}) ?></td>
  <?php endfor;?>

  <td width="65" rowspan='3'>外観</td>
  <td width="65" rowspan='3'>重量<br>（目安）</td>
  <td width="45" rowspan='5' style="font-size: 10pt">合否<br>判定</td>

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
    <td><div class="upper"></div><?= h("+".${"upper_limit".$i}) ?></td>
    <?php else : ?>
      <td><div class="upper"></div><?= h(${"upper_limit".$i}) ?></td>
      <?php endif; ?>
  <?php endfor;?>

</tr>
<tr>
  <td>公差下限</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <td><?= h(${"lower_limit".$i}) ?></td>
    <?php endfor;?>

    <td width="65" style="font-size: 10pt">〇・✕</td>
        <td width="65">g / 本</td>

</tr>
<tr>
  <td>検査器具</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <td style='font-size: 8pt'><?= h(${"measuring_instrument".$i}) ?></td>
    <?php endfor;?>

    <td width="65">目視</td>
    <td style='width:55; border-top-style:none; font-size: 9pt'>デジタル秤</td>

</tr>

</table>

<?php for($j=1; $j<=$gyou; $j++): ?>

  <table class="form">

  <td style='width:43; border-top-style:none'><?= h(${"lot_number".$j}) ?></td>
  <td style='width:65; border-top-style:none'><?= h(${"datetime".$j}) ?></td></td>
  <td style='width:67; border-top-style:none'><?= h(${"lengthhyouji".$j}) ?></td>
  <td style='width:110; border-top-style:none'><?= h(${"staff_hyouji".$j}) ?></td>

<?php for($i=1; $i<=11; $i++): ?>
    <?php
    if(${"size_name".$i} !== "長さ" && ${"input_type".$i} !== "judge" 
    && ${"result_size".$j.$i} <= (int)${"size".$i} + (int)${"upper_limit".$i}
    && ${"result_size".$j.$i} >= (int)${"size".$i} + (int)${"lower_limit".$i}){
      echo '<td style="width:84; border-top-style:none">';
      echo ${"result_size".$j.$i} ;
      echo '</td>';
    } elseif(${"input_type".$i} == "judge") {

      if(${"result_size".$j.$i} == 0){
        ${"judge".$j.$i} = "〇";
        echo '<td style="width:84; border-top-style:none">';
        echo ${"judge".$j.$i};
        echo '</td>';
        }else{
       ${"judge".$j.$i} = "✕";
       echo '<td style="width:84; border-top-style:none"><font color="red">';
       echo ${"judge".$j.$i};
       echo '</td>';
      }
 
    } elseif(${"size_name".$i} == "長さ") {
      echo '<td style="width:84; border-top-style:none">';
      echo ${"result_size".$j.$i};
      echo '</td>';
    } else {
      echo '<td style="width:84; border-top-style:none"><font color="red">';
      echo ${"result_size".$j.$i};
      echo '</td>';
    }

    ?>

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

<td style='width:65; border-top-style:none'><?= h(${"gaikanhyouji".$j}) ?></td>
  <td style='width:65; border-top-style:none'><?= h(${"result_weight".$j}) ?></td>
  <td style='width:45; border-top-style:none'><?= h(${"gouhihyouji".$j}) ?></td>

</table>

<?php endfor;?>

<?= $this->Form->control('gyou', array('type'=>'hidden', 'value'=>$gyou, 'label'=>false)) ?>

 <table class="top">
   <tr><td style="border:none"><strong style="font-size: 12pt; color:red"><?= __($mes) ?></strong></td></tr>
 </table>
 <br>

<br><br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border-style: none;"><div><?= $this->Form->submit('TOP', array('name' => 'kettei')); ?></div></td>
    </tr>
  </tbody>
</table>
<br>
