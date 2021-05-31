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
<br><br><br>
<br>
<hr size="5" style="margin: 0rem">
<br>
<table>
  <tr>
    <td style='border: none'><?php echo $this->Html->image('/img/menus/sokuteidatatouroku.gif',array('width'=>'145','height'=>'50'));?></td>
  </tr>
</table>
<br>
<table>
  <tr>
    <td style='border: none'><?php echo $this->Html->image('/img/menus/subtouroku.gif',array('width'=>'145','height'=>'50'));?></td>
  </tr>
</table>
<br>

<?= $this->Form->create($product, ['url' => ['action' => 'addform']]) ?>

<?= $this->Form->control('user_code', array('type'=>'hidden', 'value'=>$user_code, 'label'=>false)) ?>
<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('inspection_standard_size_parent_id', array('type'=>'hidden', 'value'=>$inspection_standard_size_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('product_conditon_parent_id', array('type'=>'hidden', 'value'=>$product_conditon_parent_id, 'label'=>false)) ?>

<?php
      echo $htmlkensahyouheader;
 ?>

<br>

<table>
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
</table>

<table>

  <tr>
    <td width="50" rowspan='7'>No.</td>
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
  <td style='width:130'>上限</td>

  <?php for($i=1; $i<=9; $i++): ?>
    <td style='width:90'><?= h(${"upper_limit".$i}) ?></td>
  <?php endfor;?>

</tr>
<tr>
  <td style='width:130'>下限</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:90'><?= h(${"lower_limit".$i}) ?></td>
    <?php endfor;?>

</tr>
<tr>
  <td style='width:130'>規格</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:90'><?= h(${"size".$i}) ?></td>
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
     if($gyou - $k == 0){
       ${"lot_number".$j} = "S";
     }else{
       ${"lot_number".$j} = $gyou - $k;
     }
  ?>

  <?php if ($j == $gyou): ?>

<table>

  <td style='width:50; border-top-style:none'><?= h(${"lot_number".$j}) ?></td>
  <?= $this->Form->control('lot_number'.$j, array('type'=>'hidden', 'value'=>${"lot_number".$j}, 'label'=>false)) ?>
  <td style='width:100; border-top-style:none'><?= $this->Form->control('datetime'.$j, array('type'=>'time', 'value'=>${"datetime".$j}, 'label'=>false)) ?></td>
  <td style='width:130; border-top-style:none'><font size='1.8'><?= h("社員コード：") ?></font><?= $this->Form->control('user_code'.$j, array('type'=>'text', 'label'=>false, 'pattern' => '^[0-9A-Za-z-]+$', 'title'=>'半角英数字で入力して下さい。', 'required' => 'true')) ?></td>

  <?php for($i=1; $i<=9; $i++): ?>
    <td style='width:90; border-top-style:none'><?= $this->Form->control('result_size'.$j.$i, array('type'=>'text', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。')) ?></td>
  <?php endfor;?>

  <td style='width:100; border-top-style:none'><?= $this->Form->control('gaikan'.$j, ['options' => $arrGaikan, 'label'=>false]) ?></td>
  <td style='width:100; border-top-style:none'><?= $this->Form->control('weight'.$j, array('type'=>'text', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'required' => 'true')) ?></td>
  <td style='width:100; border-top-style:none'><?= $this->Form->control('gouhi'.$j, ['options' => $arrGouhi, 'label'=>false]) ?></td>

</table>

<?php else : ?>

  <table>

    <td style='width:50; border-top-style:none'><?= h(${"lot_number".$j}) ?></td>
    <td style='width:100; border-top-style:none'><?= h(${"datetime".$j}) ?></td></td>
    <td style='width:130; border-top-style:none'><?= h(${"user_code".$j}) ?></td>

    <?= $this->Form->control('lot_number'.$j, array('type'=>'hidden', 'value'=>${"lot_number".$j}, 'label'=>false)) ?>
    <?= $this->Form->control('datetime'.$j, array('type'=>'hidden', 'value'=>${"datetime".$j}, 'label'=>false)) ?>
    <?= $this->Form->control('user_code'.$j, array('type'=>'hidden', 'value'=>${"user_code".$j}, 'label'=>false)) ?>

    <?php for($i=1; $i<=9; $i++): ?>
      <?php
      if(${"result_size".$j.$i} <= (int)${"size".$i} + (int)${"upper_limit".$i}
      && ${"result_size".$j.$i} >= (int)${"size".$i} + (int)${"lower_limit".$i}){
        echo '<td style="width:90; border-top-style:none">';
        echo ${"result_size".$j.$i} ;
        echo '</td>';
      } else {
        echo '<td style="width:90; border-top-style:none"><font color="red">';
        echo ${"result_size".$j.$i};
        echo '</td>';
      }
      ?>
      <?= $this->Form->control('result_size'.$j.$i, array('type'=>'hidden', 'value'=>${"result_size".$j.$i}, 'label'=>false)) ?>

    <?php endfor;?>

    <?php
    if(${"gaikan".$j} == 1){
      ${"gaikanhyouji".$j} = "不";
    }else{
      ${"gaikanhyouji".$j} = "良";
    }

    if(${"gouhi".$j} == 1){
      ${"gouhihyouji".$j} = "否";
    }else{
      ${"gouhihyouji".$j} = "合";
    }
    ?>

    <td style='width:100; border-top-style:none'><?= h(${"gaikanhyouji".$j}) ?></td>
    <td style='width:100; border-top-style:none'><?= h(${"weight".$j}) ?></td>
    <td style='width:100; border-top-style:none'><?= h(${"gouhihyouji".$j}) ?></td>

    <?= $this->Form->control('gaikan'.$j, array('type'=>'hidden', 'value'=>${"gaikan".$j}, 'label'=>false)) ?>
    <?= $this->Form->control('weight'.$j, array('type'=>'hidden', 'value'=>${"weight".$j}, 'label'=>false)) ?>
    <?= $this->Form->control('gouhi'.$j, array('type'=>'hidden', 'value'=>${"gouhi".$j}, 'label'=>false)) ?>

  </table>

<?php endif; ?>


<?php endfor;?>

<?= $this->Form->control('gyou', array('type'=>'hidden', 'value'=>$gyou, 'label'=>false)) ?>

<br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border:none"><?= $this->Form->submit(('登録確認'), array('name' => 'kakuninn')) ?></td>
    </tr>
  </tbody>
</table>
<br><br><br>
