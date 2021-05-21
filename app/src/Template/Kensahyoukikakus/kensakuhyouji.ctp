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

 <br>
 <hr size="5" style="margin: 0rem">
 <br>
 <table>
   <tr>
     <td style='border: none'><?php echo $this->Html->image('/img/menus/kikakukensaku.gif',array('width'=>'145','height'=>'50'));?></td>
   </tr>
 </table>
 <br>

<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>


<?= $this->Form->create($product, ['url' => ['action' => 'editlogin']]) ?>

<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('inspection_standard_size_parent_id', array('type'=>'hidden', 'value'=>$inspection_standard_size_parent_id, 'label'=>false)) ?>

<?php
      echo $htmlkensahyouheader;
 ?>

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
      <td style="border:none"><?= $this->Form->submit(('編集・削除'), array('name' => 'kakuninn')) ?></td>
    </tr>
  </tbody>
</table>
<br>
