<?php
use App\myClass\menulists\htmlusermenu;//myClassフォルダに配置したクラスを使用
use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
$htmlusermenu = new htmlusermenu();
$htmluser = $htmlusermenu->Usermenus();
$htmlloginmenu = new htmlloginmenu();
$htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmluser;
?>

<form method="post" action="/users/deletedo">

<?= $this->Form->create($user, ['url' => ['action' => 'deletedo']]) ?>

<nav class="large-3 medium-4 columns" style="width:70%">
    <?= $this->Form->create($user) ?>
    <fieldset>

      <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$user['id'], 'label'=>false)) ?>

      <legend><strong style="font-size: 15pt; color:red"><?= __('ユーザー情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを削除します。よろしければ「削除」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>ユーザー名</strong></td>
            <td width="280"><strong>スタッフ</strong></td>
        	</tr>
          <tr>
            <td><?= h($user['user_code']) ?></td>
            <td><?= h($user->staff->name) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>スーパーユーザー</strong></td>
            <td width="280"><strong>グループ</strong></td>
        	</tr>
          <tr>
            <td><?= h($super_userhyouji) ?></td>
            <td><?= h($user['group_name']) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>パスワード</strong></td>
        	</tr>
          <tr>
            <td><?= __("****") ?></td>
        	</tr>
        </table>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border-style: none;"><div><?= $this->Form->submit('削除', array('name' => 'kettei')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
</form>
