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

<?= $this->Form->create($product, ['url' => ['action' => 'addfinishconfirm']]) ?>

<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>
<?= $this->Form->control('date_sta', array('type'=>'hidden', 'value'=>$date_sta, 'label'=>false)) ?>
<?= $this->Form->control('date_fin', array('type'=>'hidden', 'value'=>$date_fin, 'label'=>false)) ?>
<?= $this->Form->control('num', array('type'=>'hidden', 'value'=>count($arrProducts), 'label'=>false)) ?>

<br>
 <div align="center"><font size="3"><?= __("生産数量と備考を入力してください。") ?></font></div>
<br>
<table align="center">
  <tbody class="login">
    <tr height="45">
    <td width="150"><strong>長さ（mm）</strong></td>
    <td width="150"><strong>生産数量（本）</strong></td>
    </tr>

    <?php for($k=0; $k<count($arrProducts); $k++): ?>

      <?= $this->Form->control('length'.$k, array('type'=>'hidden', 'value'=>$arrProducts[$k], 'label'=>false)) ?>

    <tr height="45">
    <td><?= h($arrProducts[$k]) ?></td>
    <td class="login" width="200"><?= $this->Form->control('amount'.$k, array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9A-Za-z.-]+$', 'title'=>'半角数字で入力して下さい。', 'required'=>true)) ?></td>
    </tr>
    
    <?php endfor;?>

    </tbody>
</table>
<br>

    <table align="center">
    <tbody class="login">
    <tr height="45">
    <td width="150"><strong>備考（※任意入力）</strong></td>
    </tr>
    <tr height="45">
    <td height="120" colspan="28" style="vertical-align: top; border-bottom: solid;border-width: 1px;text-align: left">
              <textarea name="bik"  cols="120" rows="10"></textarea>
          </td>
    </tr>
    </tbody>
</table>
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
