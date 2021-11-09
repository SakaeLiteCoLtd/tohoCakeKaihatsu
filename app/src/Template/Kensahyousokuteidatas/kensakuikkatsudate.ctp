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
<?php
$dateYMD = date('Y-m-d');
$dateYMD1 = strtotime($dateYMD);
$dayye = date('Y-m-d', strtotime('-1 day', $dateYMD1));
?>

<?= $this->Form->create($product, ['url' => ['action' => 'kensakuikkatsuichiran']]) ?>

<?=$this->Form->hidden("product_code", array('type' => 'value', 'value' => $product_code)); ?>
<?=$this->Form->hidden("machine_num", array('type' => 'value', 'value' => $machine_num)); ?>
<br>
<div align="center"><strong><font color="blue"><?php echo "測定日絞り込み";?></font></strong></div>
<table>
  <tr>
    <td style="border:none"><strong>開始</strong></td>
    <td style="border:none"><strong>　　　</strong></td>
    <td style="border:none"><strong>終了</strong></td>
  </tr>
  <tr>
    <td style="border:none">
      <?= $this->Form->input('start', array('type'=>'date', 'minYear' => date('Y') - 20,
       'maxYear' => date('Y'), 'monthNames' => false, 'value' => $dayye, 'label'=>false)) ?></td>
    <td style="border:none">　～　</td>
    <td style="border:none">
      <?= $this->Form->input('end', array('type'=>'date', 'minYear' => date('Y') - 20,
       'maxYear' => date('Y'), 'monthNames' => false, 'value' => $dateYMD, 'label'=>false)) ?></td>
   </tr>
</table>
<br>
<table>
  <tbody class='sample non-sample'>
    <tr>
    <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('絞り込み'), array('name' => 'saerch')) ?></td>
    </tr>
  </tbody>
</table>

<br><br>
