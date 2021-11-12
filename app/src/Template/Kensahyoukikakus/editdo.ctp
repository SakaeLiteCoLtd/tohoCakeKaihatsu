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

<?= $this->Form->create($product, ['url' => ['action' => 'menu']]) ?>

<?php
      echo $htmlkensahyouheader;
 ?>

<table class="form">

<tr>
  <td style='width:102'>測定箇所</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <td style='width:118'><?= h($this->request->getData('size_name'.$i)) ?></td>
  <?php endfor;?>

</tr>
<tr>
  <td>規格</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <td><?= h($this->request->getData('size'.$i)) ?></td>
    <?php endfor;?>

</tr>
<tr>
  <td>公差上限</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <?php if ($this->request->getData('inputtype'.$i) == "int" && strlen($this->request->getData('upper_limit'.$i)) > 0 && substr($this->request->getData('upper_limit'.$i), 0, 1) != "+"): ?>
    <td><?= h("+".$this->request->getData('upper_limit'.$i)) ?></td>
    <?php else : ?>
      <td><?= h($this->request->getData('upper_limit'.$i)) ?></td>
      <?php endif; ?>
  <?php endfor;?>

</tr>
<tr>
  <td>公差下限</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <td><?= h($this->request->getData('lower_limit'.$i)) ?></td>
    <?php endfor;?>

</tr>
<tr>
  <td>検査器具</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <td style='font-size: 8pt'><?= h($this->request->getData('measuring_instrument'.$i)) ?></td>
    <?php endfor;?>

</tr>

</table>

<br>
<table class="top">
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
</table>

<br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border-style: none;"><div><?= $this->Form->submit('TOP', array('name' => 'kettei')); ?></div></td>
    </tr>
  </tbody>
</table>
<br>
