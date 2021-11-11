<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyouseihinyobidashimenus = $htmlkensahyoukadoumenu->seihinyobidashimenus();
?>

<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<br>
<table class='sample hesdermenu'>
  <tbody>
    <td style='border: none;'>
    <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyouyobidashies/index' /><font size='4' color=black>検査表呼出</font></a>
    </td>
  </tbody>
</table>

<?= $this->Form->create($product, ['url' => ['action' => 'deletedo']]) ?>

<br><br><br>

<?= $this->Form->control('product_name', array('type'=>'hidden', 'value'=>$product_name, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>

<table class="top_big">
  <tr><td style="border:none">
  <strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong>
  <br><br>
  <strong style="font-size: 13pt; color:red"><?= __("よろしければ決定ボタンを押してください。") ?></strong>
</td></tr>
</table>
<br><br><br>
  <table align="center">
    <tbody class='sample non-sample'>
      <tr>
        <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
        <td style="border-style: none;"><?= __("　") ?></td>
            <td style="border:none"><?= $this->Form->submit(__('決定'), array('name' => 'kettei')) ?></td>
      </tr>
    </tbody>
  </table>
<br><br>
