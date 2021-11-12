<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
$htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
$htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
$htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();

use App\myClass\classprograms\htmlLogin;//myClassフォルダに配置したクラスを使用
$htmlinputstaffctp = new htmlLogin();
$inputstaffctp = $htmlinputstaffctp->inputstaffctp();
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
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/menu' /><font size='4' color=black>データ測定</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/addformpre' /><font size='4' color=black>新規登録</font></a>
    </td>
  </tbody>
</table>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'menu']]) ?>

<br>
 <div align="center"><font size="3">
 <strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong>
  </font></div>
<br>
<table align="center">
  <tbody class="login">
    <tr height="45">
      <td width="150"><strong>生産数量</strong></td>
    </tr>
    <tr height="45">
    <td><?= h($this->request->getData('total_amount')) ?></td>
    </tr>
    </tbody>
</table>
<br>

    <table align="center">
    <tbody class="login">
    <tr height="45">
    <td width="150"><strong>備考</strong></td>
    </tr>
    <tr>
    <td><?= h($this->request->getData('bik')) ?></td>
    </tr>
    </tbody>
</table>
<br>

<table>
  <tbody class='sample non-sample'>
    <tr>
      <td style="border:none"><?= $this->Form->submit(__('TOPへ戻る')) ?></td>
    </tr>
  </tbody>
</table>
