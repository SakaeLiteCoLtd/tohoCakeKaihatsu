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
    <font size='4'>　>>　</font><a href='/Kensahyougenryous/menu' /><font size='4' color=black>成形条件</font></a>
    <font size='4'>　>>　</font><a href='/Kensahyougenryous/kensakupre' /><font size='4' color=black>登録データ呼出</font></a>
    </a></td>
  </tbody>
</table>

<br><br><br>

<table>
  <tbody>
    <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('入力ミスの修正の場合はこのまま進んでください。新たな情報に更新する場合は新規登録から登録してください。') ?></strong></td></tr>
  </tbody>
</table>
<br>

<?= $this->Form->create($product, ['url' => ['action' => 'editform']]) ?>

<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>

<br>
 <div align="center"><font color="red" size="3"><?= __($mess) ?></font></div>
 <div align="center"><font size="3"><?= __("データ登録者のユーザーIDとパスワードを入力してください。") ?></font></div>
<br>
<table align="center">
  <tbody class="login">
    <tr height="45">
      <td width="150"><strong>ユーザーID</strong></td>
      <td class="login" width="200"><?= $this->Form->control('user_code', array('type'=>'text', 'label'=>false, 'autofocus'=>true, 'autocomplete'=>"off")) ?></td>
    </tr>
    <tr height="45">
      <td align="center"><strong>パスワード</strong></td>
      <td class="login" align="center"><?= $this->Form->control('password', array('type'=>'password', 'label'=>false, 'autocomplete'=>"off")) ?></td>
    </tr>
  </tbody>
</table>

<?php
//     echo $inputstaffctp;
?>

<br>

<table>
  <tbody class='sample non-sample'>
    <tr>
    <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
    </tr>
  </tbody>
</table>
