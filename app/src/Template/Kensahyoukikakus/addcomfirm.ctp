<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
?>
 <br>
<?php
      echo $htmlkensahyoukadou;
 ?>
 <br>
 <?php
      echo $htmlkensahyoumenu;
 ?>

<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<br>
<hr size="5" style="margin: 0rem">
<br>
<table>
  <tr>
    <td style='border: none'><?php echo $this->Html->image('/img/menus/kikakutouroku.gif',array('width'=>'145','height'=>'50'));?></td>
  </tr>
</table>
<br>

<?= $this->Form->create($product, ['url' => ['action' => 'adddo']]) ?>

<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('inspection_standard_size_parent_id', array('type'=>'hidden', 'value'=>$inspection_standard_size_parent_id, 'label'=>false)) ?>

<table width="1000">
    <tr>
      <td width="500" colspan="2" nowrap="nowrap" style="height: 50px"><strong>検査成績書</strong><br>（兼　成形条件表・梱包仕様書・作業手順書）</td>
      <td width="100" nowrap="nowrap" style="height: 30px">製品名</td>
      <td width="400" nowrap="nowrap" style="height: 30px"><?= h($name) ?></td>
    </tr>
    <tr>
      <td width="200" nowrap="nowrap" style="height: 30px">管理No</td>
      <td width="300" style="height: 30px"><?= h($product_code) ?></td>
      <td width="200" rowspan='2' style="height: 30px">顧客名</td>
      <td width="300" rowspan='2' style="height: 30px"><?= h($customer) ?></td>
    </tr>
    <tr>
      <td width="200" nowrap="nowrap" style="height: 30px">改訂日</td>
      <td width="300" style="height: 30px"><?= h("（仮）".$today); ?></td>
    </tr>
    <tr>
      <td width="1000" colspan="4" nowrap="nowrap" style="height: 400px">画像</td>
    </tr>
</table>

<?php if ($formcheck < 1): ?>

<br>
<table>
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下の内容で登録します。よろしければ「登録確定」ボタンを押してください。') ?></strong></td></tr>
</table>

<br>

<table>

<tr>
  <td style='width:100'>測定箇所</td>

  <?php for($i=1; $i<=9; $i++): ?>
    <td style='width:110'><?= h(${"size_name".$i}) ?></td>
    <?= $this->Form->control('size_name'.$i, array('type'=>'hidden', 'value'=>${"size_name".$i}, 'label'=>false)) ?>
  <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>上限</td>

  <?php for($i=1; $i<=9; $i++): ?>
    <td style='width:110'><?= h(${"upper_limit".$i}) ?></td>
    <?= $this->Form->control('upper_limit'.$i, array('type'=>'hidden', 'value'=>${"upper_limit".$i}, 'label'=>false)) ?>
  <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>下限</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:110'><?= h(${"lower_limit".$i}) ?></td>
      <?= $this->Form->control('lower_limit'.$i, array('type'=>'hidden', 'value'=>${"lower_limit".$i}, 'label'=>false)) ?>
    <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>規格</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:110'><?= h(${"size".$i}) ?></td>
      <?= $this->Form->control('size'.$i, array('type'=>'hidden', 'value'=>${"size".$i}, 'label'=>false)) ?>
    <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>検査機</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:110'><?= h(${"measuring_instrument".$i}) ?></td>
      <?= $this->Form->control('measuring_instrument'.$i, array('type'=>'hidden', 'value'=>${"measuring_instrument".$i}, 'label'=>false)) ?>
    <?php endfor;?>

</tr>

</table>

<br><br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      <td style="border: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('登録確定'), array('name' => 'kakuninn')) ?></td>
    </tr>
  </tbody>
</table>
<br>

<?php else : ?>

  <br><br>
  <table align="center">
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
