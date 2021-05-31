<?php
header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策
 ?>

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

<?= $this->Form->create($product, ['url' => ['action' => 'addcomfirm']]) ?>

<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>
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
    <td style='width:110'><?= $this->Form->control('size_name'.$i, array('type'=>'text', 'label'=>false)) ?></td>
  <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>上限</td>

  <?php for($i=1; $i<=9; $i++): ?>
    <td style='width:110'><?= $this->Form->control('upper_limit'.$i, array('type'=>'text', 'label'=>false)) ?></td>
  <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>下限</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:110'><?= $this->Form->control('lower_limit'.$i, array('type'=>'text', 'label'=>false)) ?></td>
    <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>規格</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:110'><?= $this->Form->control('size'.$i, array('type'=>'text', 'label'=>false)) ?></td>
    <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>検査機</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:110'><?= $this->Form->control('measuring_instrument'.$i, array('type'=>'text', 'label'=>false)) ?></td>
    <?php endfor;?>

</tr>

</table>

<br><br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border:none"><?= $this->Form->submit(('登録確認へ'), array('name' => 'kakuninn')) ?></td>
    </tr>
  </tbody>
</table>
<br>
