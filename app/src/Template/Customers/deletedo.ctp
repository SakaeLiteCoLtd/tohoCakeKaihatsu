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

<form method="post" action="/customers/index">

<?= $this->Form->create($customer, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<nav class="large-3 medium-4 columns">
    <?= $this->Form->create($customer) ?>
    <fieldset>

      <legend><strong style="font-size: 15pt; color:red"><?= __('得意先情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="280"><strong>得意先名</strong></td>
          <td width="280"><strong>電話番号</strong></td>
        </tr>
        <tr>
          <td><?= h($customer['name']) ?></td>
          <td><?= h($customer['tel']) ?></td>
        </tr>
      </table>
    <table>
    <tr>
      <td width="180"><strong>ファックス</strong></td>
      <td width="380"><strong>住所</strong></td>
    </tr>
    <tr>
      <td><?= h($customer['fax']) ?></td>
      <td><?= h($customer['address']) ?></td>
    </tr>
  </table>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('得意先メニュートップへ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
