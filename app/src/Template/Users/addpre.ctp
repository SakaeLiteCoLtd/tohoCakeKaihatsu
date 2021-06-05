<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
use App\myClass\menulists\htmlusermenu;//myClassフォルダに配置したクラスを使用
use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
$htmlusermenu = new htmlusermenu();
$htmluser = $htmlusermenu->Usermenus();
$htmlloginmenu = new htmlloginmenu();
$htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmluser;
?>

<br><br><br>

<nav class="large-3 medium-4 columns">
  <?= $this->Form->create($user, ['url' => ['action' => 'addform']]) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('ユーザー新規登録') ?></strong></legend>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
          </tbody>
        </table>
        <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('社員を選択してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>氏名</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('staff_id', ['options' => $staffs, 'label'=>false, "empty"=>"選択してください"]) ?></td>
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
