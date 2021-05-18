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

<?= $this->Form->create($product, ['url' => ['controller'=>'KensahyoukadousController', 'action' => 'kensahyoumenu']]) ?>

<br><br>

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

<br>
<table>
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
</table>
<br>

<table>

<tr>
  <td style='width:100'>測定箇所</td>

  <?php for($i=1; $i<=9; $i++): ?>
    <td style='width:110'><?= h($this->request->getData('size_name'.$i)) ?></td>
  <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>上限</td>

  <?php for($i=1; $i<=9; $i++): ?>
    <td style='width:110'><?= h($this->request->getData('upper_limit'.$i)) ?></td>
  <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>下限</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:110'><?= h($this->request->getData('lower_limit'.$i)) ?></td>
    <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>規格</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:110'><?= h($this->request->getData('size'.$i)) ?></td>
    <?php endfor;?>

</tr>
<tr>
  <td style='width:100'>検査機</td>

    <?php for($i=1; $i<=9; $i++): ?>
      <td style='width:110'><?= h($this->request->getData('measuring_instrument'.$i)) ?></td>
    <?php endfor;?>

</tr>

</table>

<br><br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border-style: none;"><div><?= $this->Form->submit('TOP', array('name' => 'kettei')); ?></div></td>
    </tr>
  </tbody>
</table>
<br>
