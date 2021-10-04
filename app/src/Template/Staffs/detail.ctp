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

<?= $this->Form->create($staff, ['url' => ['action' => 'detail']]) ?>

<?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
<br><br><br>

<nav class="sample non-sample">
    <fieldset>
      <table>
      <tbody class='sample non-sample'>
        <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('詳細表示') ?></strong></td></tr>
      </tbody>
    </table>
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
