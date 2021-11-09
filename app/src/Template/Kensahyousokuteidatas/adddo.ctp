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
    <td style='border: none;'>
      <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/menu' /><font size='4' color=black>測定データ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/addformpre' /><font size='4' color=black>新規登録</font></a>
    </td>
  </tbody>
</table>

<br><br><br>
<?= $this->Form->create($product, ['url' => ['action' => 'addformpre']]) ?>

<?php
      echo $htmlkensahyouheader;
 ?>

<br>
<table class="top">
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
</table>
<br>

<table class="white">

  <tr>
    <td width="90" rowspan='7'>ロットNo.</td>
  </tr>
  <tr>
    <td width="100" rowspan='6'>時間</td>
  </tr>

<tr>
  <td style='width:130'>測定箇所</td>

  <?php for($i=1; $i<=9; $i++): ?>
    <td style='width:90'><?= h(${"size_name".$i}) ?></td>
  <?php endfor;?>

  <td width="100" rowspan='3'>外観</td>
  <td width="100" rowspan='3'>重量<br>（目安）</td>
  <td width="100" rowspan='5'>合否<br>判定</td>

</tr>
<tr>
  <td style='width:130'>規格</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:90'><?= h(${"size".$i}) ?></td>
    <?php endfor;?>
</tr>
<tr>
  <td style='width:130'>公差上限</td>

  <?php for($i=1; $i<=9; $i++): ?>
    <td style='width:90'><?= h(${"upper_limit".$i}) ?></td>
  <?php endfor;?>

</tr>
<tr>
  <td style='width:130'>公差下限</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:90'><?= h(${"lower_limit".$i}) ?></td>
    <?php endfor;?>

        <td width="100">良 ・ 不</td>
        <td width="100">g / 本</td>

</tr>
<tr>
  <td style='width:130'>検査機</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:90'><?= h(${"measuring_instrument".$i}) ?></td>
    <?php endfor;?>

    <td width="100">目視</td>
    <td width="100">デジタル秤</td>

</tr>

</table>

<?php for($k=1; $k<=$gyou; $k++): ?>

  <?php
     $j = $gyou + 1 - $k;
  ?>

  <table class="white">

  <td style='width:90; border-top-style:none'><?= h(${"lot_number".$j}) ?></td>
  <td style='width:100; border-top-style:none'><?= h(${"datetime".$j}) ?></td></td>
  <td style='width:130; border-top-style:none'><?= h(${"staff_hyouji".$j}) ?></td>

  <?php for($i=1; $i<=9; $i++): ?>
    <td style='width:90; border-top-style:none'><?= h(${"result_size".$j.$i}) ?></td>
  <?php endfor;?>

  <td style='width:100; border-top-style:none'><?= h(${"gaikan".$j}) ?></td>
  <td style='width:100; border-top-style:none'><?= h(${"weight".$j}) ?></td>
  <td style='width:100; border-top-style:none'><?= h(${"gouhi".$j}) ?></td>

</table>

<?php endfor;?>

<br><br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border-style: none;"><div><?= $this->Form->submit('続けて登録', array('name' => 'kettei')); ?></div></td>
    </tr>
  </tbody>
</table>
<br>
