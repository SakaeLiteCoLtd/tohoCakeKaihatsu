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
      <font size='4'>　>>　</font><a href='/Kensahyouyobidashies/index' /><font size='4' color=black>検査規格</font></a>
    </td>
  </tbody>
</table>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'editform']]) ?>

<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('inspection_standard_size_parent_id', array('type'=>'hidden', 'value'=>$inspection_standard_size_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('gif', array('type'=>'hidden', 'value'=>$gif, 'label'=>false)) ?>
<?= $this->Form->control('change_flag', array('type'=>'hidden', 'value'=>$change_flag, 'label'=>false)) ?>

<?php for($i=1; $i<=11; $i++): ?>
  <?= $this->Form->control('id'.$i, array('type'=>'hidden', 'value'=>${"id".$i}, 'label'=>false)) ?>
<?php endfor;?>

<?php
      echo $htmlkensahyouheader;
 ?>

<table class="form">

<tr>
  <td>入力型</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <td style='width:118'><?= $this->Form->control('inputtype'.$i, ['options' => $arrTypes, 'value'=>${"input_type".$i}, 'label'=>false]) ?></td>
    <?php endfor;?>

</tr>

<tr>
  <td style='width:102'>測定箇所</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <td><?= h(${"size_name".$i}) ?></td>
  <?php endfor;?>

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
    <?php if (${"input_type".$i} == "int" && strlen(${"upper_limit".$i}) > 0 && substr(${"upper_limit".$i}, 0, 1) != "+"): ?>
    <td><?= h("+".${"upper_limit".$i}) ?></td>
    <?php else : ?>
      <td><?= h(${"upper_limit".$i}) ?></td>
      <?php endif; ?>

    <?= $this->Form->control('upper_limit'.$i, array('type'=>'hidden', 'value'=>${"upper_limit".$i}, 'label'=>false)) ?>
  <?php endfor;?>

</tr>
<tr>
  <td>公差下限</td>

  <?php for($i=1; $i<=11; $i++): ?>
      <td><?= h(${"lower_limit".$i}) ?></td>
      <?= $this->Form->control('lower_limit'.$i, array('type'=>'hidden', 'value'=>${"lower_limit".$i}, 'label'=>false)) ?>
    <?php endfor;?>

</tr>
<tr>
  <td>検査器具</td>

  <?php for($i=1; $i<=11; $i++): ?>
      <td style='font-size: 8pt'><?= h(${"measuring_instrument".$i}) ?></td>
      <?= $this->Form->control('measuring_instrument'.$i, array('type'=>'hidden', 'value'=>${"measuring_instrument".$i}, 'label'=>false)) ?>
    <?php endfor;?>

</tr>

</table>

<br>

<table class="top">
    <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __("長さの規格は製品登録時に登録してください。") ?></strong></td></tr>
  </table>

<br><br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
    <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('次へ'), array('name' => 'kakuninn')) ?></td>
    </tr>
  </tbody>
</table>
<br>
