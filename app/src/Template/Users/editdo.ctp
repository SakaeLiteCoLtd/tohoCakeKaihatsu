<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlstaffmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
$htmlstaffmenu = new htmlstaffmenu();
$htmlstaff = $htmlstaffmenu->Staffmenus();
$htmlloginmenu = new htmlloginmenu();
$htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlstaff;
?>

<?= $this->Form->create($user, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<nav class="sample non-sample">
    <?= $this->Form->create($user) ?>
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('ユーザー情報編集') ?></strong></legend>
      <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>社員コード</strong></td>
            <td width="280"><strong>氏名</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('user_code')) ?></td>
            <td><?= h($staffhyouji) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>グループ</strong></td>
            <td width="280"><strong>パスワード</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('group_name')) ?></td>
            <td><?= __("****") ?></td>
        	</tr>
        </table>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('ユーザーメニュートップへ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
