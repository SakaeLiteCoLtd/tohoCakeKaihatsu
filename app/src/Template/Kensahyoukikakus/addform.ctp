<?php
header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策
 ?>

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

<?= $this->Form->create($product, ['url' => ['action' => 'addcomfirm']]) ?>

<?= $this->Form->control('gif', array('type'=>'hidden', 'value'=>$gif, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>

<?php
      echo $htmlkensahyouheader;
 ?>

<table class="form">

<tr>
  <td>入力型</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <?= $this->Form->control('inputtype'.$i, array('type'=>'hidden', 'value'=>$this->request->getData('inputtype'.$i), 'label'=>false)) ?>

      <?php
      if($this->request->getData('inputtype'.$i) == "int"){
        $inputtype = "数値";
      }else{
        $inputtype = "〇✕";
      }
      ?>

      <td style='width:118'><?= h($inputtype) ?></td>
    <?php endfor;?>

</tr>

<tr>
  <td style='width:99'>測定箇所</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <td style='width:118'><?= $this->Form->control('size_name'.$i, array('type'=>'text', 'label'=>false)) ?></td>
  <?php endfor;?>

</tr>
<tr>
  <td>規格</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <?php if($this->request->getData('inputtype'.$i) == "int"): ?>
        <td><?= $this->Form->control('size'.$i, array('type'=>'tel', 'pattern' => '^[0-9.-+]+$', 'title'=>'半角数字で入力して下さい。', 'label'=>false)) ?></td>
      <?php else : ?>
        <td><?= $this->Form->control('size'.$i, array('type'=>'text', 'label'=>false)) ?></td>
      <?php endif; ?>
    <?php endfor;?>

</tr>
<tr>
  <td>公差上限</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <?php if($this->request->getData('inputtype'.$i) == "int"): ?>
        <td><?= $this->Form->control('upper_limit'.$i, array('type'=>'tel', 'pattern' => '^[0-9.-+]+$', 'title'=>'半角数字で入力して下さい。', 'label'=>false)) ?></td>
      <?php else : ?>
        <td><?= $this->Form->control('upper_limit'.$i, array('type'=>'text', 'label'=>false)) ?></td>
      <?php endif; ?>
  <?php endfor;?>

</tr>
<tr>
  <td>公差下限</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <?php if($this->request->getData('inputtype'.$i) == "int"): ?>
        <td><?= $this->Form->control('lower_limit'.$i, array('type'=>'tel', 'pattern' => '^[0-9.-+]+$', 'title'=>'半角数字で入力して下さい。', 'label'=>false)) ?></td>
      <?php else : ?>
        <td><?= $this->Form->control('lower_limit'.$i, array('type'=>'text', 'label'=>false)) ?></td>
      <?php endif; ?>
    <?php endfor;?>

</tr>
<tr>
  <td>検査器具</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <td><?= $this->Form->control('measuring_instrument'.$i, ['options' => $arrkensakigu, 'label'=>false]) ?></td>
    <?php endfor;?>

</tr>

</table>
<br>
<table class="top">
    <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __("長さの規格は製品登録時に登録してください。") ?></strong></td></tr>
  </table>

<br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
    <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('登録確認へ'), array('name' => 'kakuninn')) ?></td>
    </tr>
  </tbody>
</table>
<br>
