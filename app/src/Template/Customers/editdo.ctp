<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlcustomermenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlcustomermenu = new htmlcustomermenu();
 $htmlcustomer = $htmlcustomermenu->Customersmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlcustomer;
?>

<?= $this->Form->create($customer, ['url' => ['action' => 'index']]) ?>

<nav class="large-3 medium-4 columns" style="width:70%">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('顧客新規登録') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>工場・営業所名</strong></td>
        	</tr>
          <tr>
            <td><?= h($factory_name) ?></td>
        	</tr>
        </table>
      <table>
        <tr>
          <td width="280"><strong>顧客名</strong></td>
          <td width="280"><strong>支店名</strong></td>
        </tr>
        <tr>
          <td><?= h($this->request->getData('name')) ?></td>
          <td><?= h($this->request->getData('office')) ?></td>
        </tr>
      </table>
      <table>
      <tr>
        <td width="280"><strong>部署名</strong></td>
        <td width="280"><strong>電話番号</strong></td>
      </tr>
      <tr>
        <td><?= h($this->request->getData('department')) ?></td>
        <td><?= h($this->request->getData('tel')) ?></td>
      </tr>
    </table>
    <table>
    <tr>
      <td width="180"><strong>ファックス</strong></td>
      <td width="380"><strong>住所</strong></td>
    </tr>
    <tr>
      <td><?= h($this->request->getData('fax')) ?></td>
      <td><?= h($this->request->getData('address')) ?></td>
    </tr>
  </table>

    </fieldset>

    <table>
      <tr>
        <tbody class='sample non-sample'>
        <td style="border-style: none;"><div><?= $this->Form->submit('顧客メニュートップへ戻る', array('name' => 'top')); ?></div></td>
      </tbody>
    </tr>
    </table>

    <?= $this->Form->end() ?>
  </nav>
