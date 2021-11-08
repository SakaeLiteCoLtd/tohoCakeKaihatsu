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
      <font size='4'>　>>　</font><a href='/Kensahyoukikakus/menu' /><font size='4' color=black>検査規格登録</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukikakus/addformpre' /><font size='4' color=black>新規登録</font></a>
 </td>
  </tbody>
</table>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'addform']]) ?>

<?= $this->Form->control('gif', array('type'=>'hidden', 'value'=>$gif, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>

<?php
      echo $htmlkensahyouheader;
 ?>

<table class="form">

<tr>
  <td>入力型</td>

    <?php for($i=1; $i<=10; $i++): ?>
      <td style='width:130'><?= $this->Form->control('inputtype'.$i, ['options' => $arrTypes, 'label'=>false]) ?></td>
    <?php endfor;?>

</tr>

<tr>
  <td style='width:102'>測定箇所</td>

  <?php for($i=1; $i<=10; $i++): ?>
    <td></td>
  <?php endfor;?>

</tr>
<tr>
  <td>規格</td>

    <?php for($i=1; $i<=10; $i++): ?>
      <td></td>
    <?php endfor;?>

</tr>
<tr>
  <td>公差上限</td>

  <?php for($i=1; $i<=10; $i++): ?>
    <td></td>
  <?php endfor;?>

</tr>
<tr>
  <td>公差下限</td>

    <?php for($i=1; $i<=10; $i++): ?>
      <td></td>
    <?php endfor;?>

</tr>
<tr>
  <td>検査器具</td>

    <?php for($i=1; $i<=10; $i++): ?>
      <td></td>
    <?php endfor;?>

</tr>

</table>
<br>
<table class="top">
    <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __("入力する値の型を選択してください。") ?></strong></td></tr>
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
