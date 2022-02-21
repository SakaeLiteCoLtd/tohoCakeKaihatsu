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

<?= $this->Form->create($product, ['url' => ['action' => 'addfinishdo']]) ?>

<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>
<?= $this->Form->control('total_amount', array('type'=>'hidden', 'value'=>$this->request->getData('total_amount'), 'label'=>false)) ?>
<?= $this->Form->control('bik', array('type'=>'hidden', 'value'=>$this->request->getData('bik'), 'label'=>false)) ?>
<?= $this->Form->control('date_sta', array('type'=>'hidden', 'value'=>$date_sta, 'label'=>false)) ?>
<?= $this->Form->control('date_fin', array('type'=>'hidden', 'value'=>$date_fin, 'label'=>false)) ?>
<?= $this->Form->control('num', array('type'=>'hidden', 'value'=>$this->request->getData('num'), 'label'=>false)) ?>

<br>
 <div align="center"><font size="3"><?= __("以下のように登録します。よろしければ決定ボタンを押してください。") ?></font></div>
<br>

<table align="center">
  <tbody class="login">
    <tr height="45">
    <td width="150"><strong>長さ（mm）</strong></td>
    <td width="150"><strong>生産数量（本）</strong></td>
    <td width="150"><strong>総重量（kg）</strong></td>
    <td width="150"><strong>総ロス重量（kg）</strong></td>
    <td width="150"><strong>ロス率（％）</strong></td>
    <td width="150"><strong>達成率（％）</strong></td>
    </tr>

    <?php for($k=0; $k<$this->request->getData('num'); $k++): ?>

      <?= $this->Form->control('length'.$k, array('type'=>'hidden', 'value'=>$this->request->getData('length'.$k), 'label'=>false)) ?>
      <?= $this->Form->control('amount'.$k, array('type'=>'hidden', 'value'=>$this->request->getData('amount'.$k), 'label'=>false)) ?>
      <?= $this->Form->control('sum_weight'.$k, array('type'=>'hidden', 'value'=>${"sum_weight".$k}, 'label'=>false)) ?>
      <?= $this->Form->control('total_loss_weight'.$k, array('type'=>'hidden', 'value'=>${"total_loss_weight".$k}, 'label'=>false)) ?>
      <?= $this->Form->control('lossritsu'.$k, array('type'=>'hidden', 'value'=>${"lossritsu".$k}, 'label'=>false)) ?>
      <?= $this->Form->control('tasseiritsu'.$k, array('type'=>'hidden', 'value'=>${"tasseiritsu".$k}, 'label'=>false)) ?>

    <tr height="45">
    <td><?= h($this->request->getData('length'.$k)) ?></td>
    <td><?= h($this->request->getData('amount'.$k)) ?></td>
    <td><?= h(${"sum_weight".$k}) ?></td>
    <td><?= h(${"total_loss_weight".$k}) ?></td>
    <td><?= h(${"lossritsu".$k}) ?></td>
    <td><?= h(${"tasseiritsu".$k}) ?></td>
    </tr>
    
    <?php endfor;?>

    </tbody>
</table>
<br>

    <table align="center">
    <tbody class="login">
    <tr height="45">
    <td width="500"><strong>備考</strong></td>
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
    <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(__('決定')) ?></td>
    </tr>
  </tbody>
</table>
