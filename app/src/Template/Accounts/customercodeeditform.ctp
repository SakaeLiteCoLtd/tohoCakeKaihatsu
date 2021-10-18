<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlaccountmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlaccountmenu = new htmlaccountmenu();
 $htmlcustomercodemenus = $htmlaccountmenu->customercodemenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlcustomercodemenus;
?>

<?= $this->Form->create($customer, ['url' => ['action' => 'customercodeeditconfirm']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($customer) ?>
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('得意先・仕入先コード修正') ?></strong></legend>
      <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

      <table>
      <tr>
        <td width="280"><strong>得意先・仕入先名</strong></td>
        <td width="280"><strong>得意先・仕入先コード</strong></td>
      </tr>
      <tr>
      <td><?= h($name) ?></td>
      <td><?= $this->Form->control('customer_code', array('type'=>'text', 'value'=>$customer_code, 'label'=>false, 'required' => 'true', 'autocomplete'=>"off")) ?></td>
    </tr>
    </table>
    <table>
    <tr>
      <td width="280"><strong>フリガナ</strong></td>
      <td width="280"><strong>略称</strong></td>
    </tr>
    <tr>
    <td><?= h($furigana) ?></td>
    <td><?= h($ryakusyou) ?></td>
    </tr>
  </table>
  <table>
    <tr>
    <td width="200"><strong>部署</strong></td>
      <td width="180"><strong>電話番号</strong></td>
      <td width="180"><strong>ファックス</strong></td>
    </tr>
    <tr>
    <td><?= h($department) ?></td>
    <td><?= h($tel) ?></td>
    <td><?= h($fax) ?></td>
    </tr>
  </table>
  <table>
    <tr>
      <td width="150"><strong>郵便番号</strong></td>
      <td width="410"><strong>住所</strong></td>
    </tr>
    <tr>
    <td><?= h($yuubin) ?></td>
    <td><?= h($address) ?></td>
    </tr>
  </table>

    <table>
      <tbody class='sample non-sample'>
        <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border:none"><?= $this->Form->submit(__('確認')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>

</fieldset>

    <?= $this->Form->end() ?>
  </nav>
  <br><br><br>
