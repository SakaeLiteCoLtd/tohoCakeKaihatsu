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
    <td style='border: none;align: left'>
      <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
    <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
    <font size='4'>　>>　</font><a href='/Kensahyougenryous/menu' /><font size='4' color=black>原料登録</font></a>
    <font size='4'>　>>　</font><a href='/Kensahyougenryous/addlogin' /><font size='4' color=black>新規登録</font></a>
    </a></td>
  </tbody>
</table>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'kensakugouki']]) ?>

<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>

<br>
<div align="center"><font size="3"><?= __("号機番号を選択して「次へ」ボタンを押してください。") ?></font></div>
<br>

<table>
  <tbody style="background-color: #FFFFCC">
    <tr>
      <td width="100">号機番号</td>
    </tr>
    <tr>
    <td><?= $this->Form->control('machine_num', ['options' => $arrGouki, 'label'=>false]) ?></td>
    </tr>
  </tbody>
</table>

<br>

<table>
  <tbody class='sample non-sample'>
    <tr>
    <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('次へ'), array('name' => 'next')) ?></td>
    </tr>
  </tbody>
</table>
