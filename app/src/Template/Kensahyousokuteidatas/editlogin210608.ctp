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
    <td style='border: none;align: left'>
      <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/menu' /><font size='4' color=black>測定データ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/kensakumenu' /><font size='4' color=black>登録データ呼出</font></a>
    </td>
  </tbody>
</table>

<br><br><br>
<?= $this->Form->create($product, ['url' => ['action' => 'editform']]) ?>

<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$this->request->getData('product_code'), 'label'=>false)) ?>
<?= $this->Form->control('datetimesta', array('type'=>'hidden', 'value'=>$this->request->getData('datetimesta'), 'label'=>false)) ?>
<?= $this->Form->control('datetimefin', array('type'=>'hidden', 'value'=>$this->request->getData('datetimefin'), 'label'=>false)) ?>
<?= $this->Form->control('datekensaku', array('type'=>'hidden', 'value'=>$this->request->getData('datekensaku'), 'label'=>false)) ?>

<br>
 <div align="center"><font color="red" size="3"><?= __($mess) ?></font></div>
 <div align="center"><font size="3"><?= __("データ登録者の社員コードを入力してください。") ?></font></div>
<br>


<?php
     echo $inputstaffctp;
?>

<br>

<table>
  <tbody class='sample non-sample'>
    <tr>
      <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
    </tr>
  </tbody>
</table>
