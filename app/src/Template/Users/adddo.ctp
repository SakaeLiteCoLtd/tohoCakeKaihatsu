<?php
use App\myClass\menulists\htmlusermenu;//myClassフォルダに配置したクラスを使用
use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
$htmlusermenu = new htmlusermenu();
$htmluser = $htmlusermenu->Usermenus();
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
     echo $htmluser;
?>


<nav class="large-3 medium-4 columns" style="width:70%">
  <?= $this->Form->create($Users, ['url' => ['action' => 'index']]) ?>

    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('ユーザー新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>ユーザー名</strong></td>
            <td width="280"><strong>スタッフ</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('user_code')) ?></td>
            <td><?= h($staffhyouji) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>スーパーユーザー</strong></td>
            <td width="280"><strong>グループ</strong></td>
        	</tr>
          <tr>
            <td><?= h($super_userhyouji) ?></td>
            <td><?= h($this->request->getData('group_name')) ?></td>
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

    <table>
      <tr>
        <tbody class='sample non-sample'>
          <td style="border-style: none;"><div><?= $this->Form->submit('ユーザーメニュートップへ戻る', array('name' => 'top')); ?></div></td>
        </tbody>
      </tr>
    </table>

    <?= $this->Form->end() ?>
  </nav>
