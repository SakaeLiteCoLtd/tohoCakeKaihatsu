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
      <font size='4'>　>>　</font><a href='/Kensahyoukikakus/kensakupre' /><font size='4' color=black>規格呼出</font></a>
    </td>
  </tbody>
</table>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'editcomfirm']]) ?>

<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('inspection_standard_size_parent_id', array('type'=>'hidden', 'value'=>$inspection_standard_size_parent_id, 'label'=>false)) ?>

<?php for($i=1; $i<=9; $i++): ?>
  <?= $this->Form->control('id'.$i, array('type'=>'hidden', 'value'=>${"id".$i}, 'label'=>false)) ?>
<?php endfor;?>

<?php
      echo $htmlkensahyouheader;
 ?>

<br>

<table style='margin-top:500px'>

<tr>
  <td style='width:100'>測定箇所</td>

  <?php for($i=1; $i<=9; $i++): ?>
    <td style='width:110'><?= $this->Form->control('size_name'.$i, array('type'=>'text', 'value'=>${"size_name".$i}, 'label'=>false)) ?></td>
  <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>上限</td>

  <?php for($i=1; $i<=9; $i++): ?>
    <td style='width:110'><?= $this->Form->control('upper_limit'.$i, array('type'=>'text', 'value'=>${"upper_limit".$i}, 'label'=>false)) ?></td>
  <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>下限</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:110'><?= $this->Form->control('lower_limit'.$i, array('type'=>'text', 'value'=>${"lower_limit".$i}, 'label'=>false)) ?></td>
    <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>規格</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:110'><?= $this->Form->control('size'.$i, array('type'=>'text', 'value'=>${"size".$i}, 'label'=>false)) ?></td>
    <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>検査機</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:110'><?= $this->Form->control('measuring_instrument'.$i, array('type'=>'text', 'value'=>${"measuring_instrument".$i}, 'label'=>false)) ?></td>
    <?php endfor;?>

</tr>

</table>

<br>

<table>
  <tbody>
    <tr>
      <td style="border:none"><?= $this->Form->control('check', array('type'=>'checkbox', 'label'=>false)) ?></td>
      <td style="border:none"><div><strong style="font-size: 13pt; color:blue">データを削除する場合はチェックを入れてください。</strong></div></td>
    </tr>
  </tbody>
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
