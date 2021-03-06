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

<form method="post" action="/staffs/index">

<?= $this->Form->create($staff, ['url' => ['action' => 'ichiran']]) ?>
<br><br><br>

<nav class="sample non-sample">
    <?= $this->Form->create($staff) ?>
    <fieldset>

      <legend><strong style="font-size: 15pt; color:red"><?= __('メンバー情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>工場名</strong></td>
        	</tr>
          <tr>
            <td><?= h($factory_name) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>氏名</strong></td>
            <td width="280"><strong>性別</strong></td>
        	</tr>
          <tr>
            <td><?= h($name) ?></td>
            <td><?= h($sexhyouji) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
          <td width="280"><strong>部署</strong></td>
            <td width="280"><strong>職種</strong></td>
        	</tr>
          <tr>
          <td><?= h($department_name) ?></td>
            <td><?= h($position_name) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>電話番号</strong></td>
            <td width="280"><strong>メール</strong></td>
        	</tr>
          <tr>
            <td><?= h($tel) ?></td>
            <td><?= h($mail) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="560"><strong>住所</strong></td>
        	</tr>
          <tr>
            <td><?= h($address) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>生年月日</strong></td>
            <td width="280"><strong>入社日</strong></td>
        	</tr>
          <tr>
            <td><?= h($birth) ?></td>
            <td><?= h($date_start) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>退社日</strong></td>
        	</tr>
          <tr>
            <td><?= h($date_finish) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>ユーザーID</strong></td>
            <td width="280"><strong>メンバーコード</strong></td>
        	</tr>
          <tr>
            <td><?= h($user_code) ?></td>
            <td><?= h($staff_code) ?></td>
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

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('メンバー一覧へ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
