<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
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

<?= $this->Form->create($user, ['url' => ['action' => 'detail']]) ?>

<?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
<br><br><br>

<nav class="large-3 medium-4 columns" style="width:70%">
    <fieldset>
      <table>
      <tbody class='sample non-sample'>
        <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('詳細表示') ?></strong></td></tr>
      </tbody>
    </table>
    <table>
      <tr>
        <td width="280"><strong>社員コード</strong></td>
        <td width="280"><strong>氏名</strong></td>
      </tr>
      <tr>
        <td><?= h($user_code) ?></td>
        <td><?= h($staffhyouji) ?></td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="280"><strong>グループ</strong></td>
        <td width="280"><strong>パスワード</strong></td>
      </tr>
      <tr>
        <td><?= h($group_name) ?></td>
        <td><?= __("****") ?></td>
      </tr>
    </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('編集', array('name' => 'edit')); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border-style: none;"><div><?= $this->Form->submit('削除', array('name' => 'delete')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <br><br><br>

    <?= $this->Form->end() ?>
  </nav>
