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

<?= $this->Form->create($customer, ['url' => ['action' => 'customercodeeditdo']]) ?>

<?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
<?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$name, 'label'=>false)) ?>
<?= $this->Form->control('customer_code', array('type'=>'hidden', 'value'=>$customer_code, 'label'=>false)) ?>
<?= $this->Form->control('customer_code_moto', array('type'=>'hidden', 'value'=>$customer_code_moto, 'label'=>false)) ?>
<?= $this->Form->control('furigana', array('type'=>'hidden', 'value'=>$furigana, 'label'=>false)) ?>
<?= $this->Form->control('ryakusyou', array('type'=>'hidden', 'value'=>$ryakusyou, 'label'=>false)) ?>
<?= $this->Form->control('department', array('type'=>'hidden', 'value'=>$department, 'label'=>false)) ?>
<?= $this->Form->control('tel', array('type'=>'hidden', 'value'=>$tel, 'label'=>false)) ?>
<?= $this->Form->control('fax', array('type'=>'hidden', 'value'=>$fax, 'label'=>false)) ?>
<?= $this->Form->control('yuubin', array('type'=>'hidden', 'value'=>$yuubin, 'label'=>false)) ?>
<?= $this->Form->control('address', array('type'=>'hidden', 'value'=>$address, 'label'=>false)) ?>
<br><br><br>

<nav class="sample non-sample">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('得意先・仕入先コード修正') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __("以下の内容でよろしければ「決定」ボタンを押してください") ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
          <td width="280"><strong>新得意先・仕入先コード</strong></td>
            <td width="280"><strong>得意先・仕入先コード（変更前）</strong></td>
        	</tr>
          <tr>
          <td><?= h($customer_code) ?></td>
            <td><?= h($customer_code_moto) ?></td>
        	</tr>
        </table>

        <table>
      <tr>
        <td width="280"><strong>得意先・仕入先名</strong></td>
      </tr>
      <tr>
      <td><?= h($name) ?></td>
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

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border-style: none;"><div><?= $this->Form->submit('決定', array('name' => 'kettei')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
