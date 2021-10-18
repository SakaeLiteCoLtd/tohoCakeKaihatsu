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

<?= $this->Form->create($customer, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<nav class="sample non-sample">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('得意先・仕入先コード修正') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
          <td width="280"><strong>新得意先・仕入先コード</strong></td>
            <td width="280"><strong>得意先・仕入先コード（変更前）</strong></td>
        	</tr>
          <tr>
          <td><?= h($this->request->getData('customer_code')) ?></td>
            <td><?= h($this->request->getData('customer_code_moto')) ?></td>
        	</tr>
        </table>

        <table>
      <tr>
        <td width="280"><strong>得意先・仕入先名</strong></td>
      </tr>
      <tr>
      <td><?= h($this->request->getData('name')) ?></td>
    </tr>
    </table>
    <table>
    <tr>
      <td width="280"><strong>フリガナ</strong></td>
      <td width="280"><strong>略称</strong></td>
    </tr>
    <tr>
    <td><?= h($this->request->getData('furigana')) ?></td>
    <td><?= h($this->request->getData('ryakusyou')) ?></td>
    </tr>
  </table>
  <table>
    <tr>
    <td width="200"><strong>部署</strong></td>
      <td width="180"><strong>電話番号</strong></td>
      <td width="180"><strong>ファックス</strong></td>
    </tr>
    <tr>
    <td><?= h($this->request->getData('department')) ?></td>
    <td><?= h($this->request->getData('tel')) ?></td>
    <td><?= h($this->request->getData('fax')) ?></td>
    </tr>
  </table>
  <table>
    <tr>
      <td width="150"><strong>郵便番号</strong></td>
      <td width="410"><strong>住所</strong></td>
    </tr>
    <tr>
    <td><?= h($this->request->getData('yuubin')) ?></td>
    <td><?= h($this->request->getData('address')) ?></td>
    </tr>
  </table>

    <table>
      <tr>
        <tbody class='sample non-sample'>
        <td style="border-style: none;"><div><?= $this->Form->submit('管理者用メニュートップへ戻る', array('name' => 'top')); ?></div></td>
      </tbody>
    </tr>
    </table>

    </fieldset>

    <?= $this->Form->end() ?>
  </nav>
