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

<?= $this->Form->create($Staffs, ['url' => ['action' => 'index']]) ?>

<br><br><br>

<nav class="sample non-sample">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('メンバー新規登録') ?></strong></legend>
      <br>
      <table>
        <tbody class='sample non-sample'>
          <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
        </tbody>
      </table>
      <br>

      <table>
          <tr>
            <td width="280"><strong>工場・営業所</strong></td>
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
            <td><?= h($this->request->getData('name')) ?></td>
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
            <td><?= h($this->request->getData('tel')) ?></td>
            <td><?= h($this->request->getData('mail')) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="560"><strong>住所</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('address')) ?></td>
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
          <td width="280"><strong>ユーザーID</strong></td>
          <td width="280"><strong>メンバーコード</strong></td>
        	</tr>
          <tr>
          <td><?= h($this->request->getData('user_code')) ?></td>
          <td><?= h($this->request->getData('staff_code')) ?></td>
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

    <table>
      <tr>
        <tbody class='sample non-sample'>
        <td style="border-style: none;"><div><?= $this->Form->submit('メンバーメニュートップへ戻る', array('name' => 'top')); ?></div></td>
      </tbody>
    </tr>
    </table>

    <?= $this->Form->end() ?>
  </nav>
