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
      <font size='4'>　>>　</font><a href='/Kensahyougenryous/menu' /><font size='4' color=black>検査規格登録</font></a>
    <font size='4'>　>>　</font><a href='/Kensahyougenryous/kensakupre' /><font size='4' color=black>登録データ呼出</font></a>
    </td>
  </tbody>
</table>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'editdo']]) ?>

<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('inspection_standard_size_parent_id', array('type'=>'hidden', 'value'=>$inspection_standard_size_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('change_flag', array('type'=>'hidden', 'value'=>$change_flag, 'label'=>false)) ?>
<?= $this->Form->control('gif', array('type'=>'hidden', 'value'=>$gif, 'label'=>false)) ?>

<?php for($i=1; $i<=10; $i++): ?>
  <?= $this->Form->control('id'.$i, array('type'=>'hidden', 'value'=>$this->request->getData('id'.$i), 'label'=>false)) ?>
<?php endfor;?>

<?php
      echo $htmlkensahyouheader;
 ?>

<?php if ($formcheck < 1): ?>

<table class="form">

<tr>
  <td style='width:102'>測定箇所</td>

  <?php for($i=1; $i<=10; $i++): ?>
    <td style='width:130'><?= h(${"size_name".$i}) ?></td>
    <?= $this->Form->control('size_name'.$i, array('type'=>'hidden', 'value'=>${"size_name".$i}, 'label'=>false)) ?>
  <?php endfor;?>

</tr>
<tr>
  <td>規格</td>

    <?php for($i=1; $i<=10; $i++): ?>
      <td><?= h(${"size".$i}) ?></td>
      <?= $this->Form->control('size'.$i, array('type'=>'hidden', 'value'=>${"size".$i}, 'label'=>false)) ?>
    <?php endfor;?>

</tr>
<tr>
  <td>公差上限</td>

  <?php for($i=1; $i<=10; $i++): ?>
    <td><?= h(${"upper_limit".$i}) ?></td>
    <?= $this->Form->control('upper_limit'.$i, array('type'=>'hidden', 'value'=>${"upper_limit".$i}, 'label'=>false)) ?>
  <?php endfor;?>

</tr>
<tr>
  <td>公差下限</td>

    <?php for($i=1; $i<=10; $i++): ?>
      <td><?= h(${"lower_limit".$i}) ?></td>
      <?= $this->Form->control('lower_limit'.$i, array('type'=>'hidden', 'value'=>${"lower_limit".$i}, 'label'=>false)) ?>
    <?php endfor;?>

</tr>
<tr>
  <td>検査器具</td>
    <?php for($i=1; $i<=10; $i++): ?>
      <td style='font-size: 8pt'><?= h(${"measuring_instrument".$i}) ?></td>
      <?= $this->Form->control('measuring_instrument'.$i, array('type'=>'hidden', 'value'=>${"measuring_instrument".$i}, 'label'=>false)) ?>
    <?php endfor;?>

</tr>

</table>
<br>
<table class="top">
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
</table>

<br><br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      <td style="border: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('決定'), array('name' => 'kakuninn')) ?></td>
    </tr>
  </tbody>
</table>
<br>

<?php else : ?>

  <br><br>
  <table class="top">
    <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($formcheckmess) ?></strong></td></tr>
  </table>
  <br><br>
  <table align="center">
    <tbody class='sample non-sample'>
      <tr>
        <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      </tr>
    </tbody>
  </table>

<?php endif; ?>
