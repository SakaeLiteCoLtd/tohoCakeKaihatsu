<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlstaffmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
$htmlstaffmenu = new htmlstaffmenu();
$htmlstaff = $htmlstaffmenu->Staffmenus();
$htmlloginmenu = new htmlloginmenu();
$htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;

 $super_useroptions = [
  '0' => 'いいえ',
  '1' => 'はい'
        ];

?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlstaff;
?>

<form method="post" action="/users/editconfirm">

<?= $this->Form->create($user, ['url' => ['action' => 'editconfirm']]) ?>

<br><br><br>

<nav class="sample non-sample">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('ユーザー情報編集') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('データを編集してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>
        <table>
          <tr>
            <td width="280"><strong>ユーザーID</strong></td>
            <td width="280"><strong>氏名</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('user_code', array('type'=>'text', 'label'=>false)) ?></td>
            <td><?= $this->Form->control('staff_id', ['options' => $staffs, 'label'=>false, "empty"=>"選択してください"]) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>グループ</strong></td>
            <td width="280"><strong>パスワード</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('group_name', ['options' => $Groupnames, 'label'=>false, "empty"=>"選択してください"]) ?></td>
            <td><?= $this->Form->control('password', array('type'=>'password', 'label'=>false, 'size'=>20,  'value'=>"")) ?></td>
        	</tr>
        </table>
    </fieldset>

    <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>
    <?= $this->Form->end() ?>
  </nav>
</form>
