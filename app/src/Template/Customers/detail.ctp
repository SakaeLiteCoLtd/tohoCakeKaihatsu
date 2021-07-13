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

<?= $this->Form->create($customer, ['url' => ['action' => 'detail']]) ?>

<?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
<br><br><br>

<nav class="large-3 medium-4 columns">
    <fieldset>
      <table>
      <tbody class='sample non-sample'>
        <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('詳細表示') ?></strong></td></tr>
      </tbody>
    </table>

    <table>
        <tr>
          <td width="280"><strong>自社工場</strong></td>
        </tr>
        <tr>
        <td><?= h($factory_name) ?></td>
        </tr>
      </table>
      <table>
      <tr>
        <td width="280"><strong>得意先名</strong></td>
        <td width="280"><strong>得意先コード</strong></td>
      </tr>
      <tr>
      <td><?= h($name) ?></td>
      <td><?= h($customer_code) ?></td>
    </tr>
    </table>
  <table>
    <tr>
      <td width="280"><strong>フリガナ</strong></td>
      <td width="280"><strong>部署</strong></td>
    </tr>
    <tr>
    <td><?= h($furigana) ?></td>
    <td><?= h($department) ?></td>
    </tr>
  </table>
  <table>
    <tr>
      <td width="280"><strong>電話番号</strong></td>
      <td width="280"><strong>ファックス</strong></td>
    </tr>
    <tr>
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

    <br><br><br>

    <?= $this->Form->end() ?>
  </nav>
