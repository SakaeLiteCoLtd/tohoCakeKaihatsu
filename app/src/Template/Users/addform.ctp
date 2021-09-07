<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlstaffmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
$htmlstaffmenu = new htmlstaffmenu();
$htmlstaff = $htmlstaffmenu->Staffmenus();
$htmlloginmenu = new htmlloginmenu();
$htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlstaff;
?>

<br><br><br>

<nav class="sample non-sample">
  <?= $this->Form->create($user, ['url' => ['action' => 'addcomfirm']]) ?>
  <?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$this->request->getData('staff_id'), 'label'=>false)) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('ユーザー新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('登録するデータを入力してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>社員コード</strong></td>
            <td width="280"><strong>氏名</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('user_code', array('type'=>'text', 'label'=>false)) ?></td>
            <td><?= h($staffhyouji) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>グループ</strong></td>
            <td width="280"><strong>パスワード</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('group_name', ['options' => $Groupnames, 'label'=>false, "empty"=>"選択してください"]) ?></td>
            <td><?= $this->Form->control('password', array('type'=>'password', 'label'=>false, 'size'=>20)) ?></td>
        	</tr>
        </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
