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

<form method="post" action="/staffs/deletedo">

<?= $this->Form->create($staff, ['url' => ['action' => 'deletedo']]) ?>

<nav class="large-3 medium-4 columns" style="width:70%">
    <?= $this->Form->create($staff) ?>
    <fieldset>

      <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$staff['id'], 'label'=>false)) ?>

      <legend><strong style="font-size: 15pt; color:red"><?= __('スタッフ情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを削除します。よろしければ「削除」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>氏名</strong></td>
            <td width="280"><strong>性別</strong></td>
        	</tr>
          <tr>
            <td><?= h($staff['name']) ?></td>
            <td><?= h($sexhyouji) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>工場・営業所</strong></td>
            <td width="280"><strong>部署</strong></td>
        	</tr>
          <tr>
            <td><?= h($staff->factory->name) ?></td>
            <td><?= h($staff->department->name) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>職種</strong></td>
            <td width="280"><strong>役職</strong></td>
        	</tr>
          <tr>
            <td><?= h($staff->occupation->name) ?></td>
            <td><?= h($staff->position->name) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>電話番号</strong></td>
            <td width="280"><strong>メール</strong></td>
        	</tr>
          <tr>
            <td><?= h($staff['tel']) ?></td>
            <td><?= h($staff['mail']) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="560"><strong>住所</strong></td>
        	</tr>
          <tr>
            <td><?= h($staff['address']) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="180"><strong>生年月日</strong></td>
            <td width="180"><strong>入社日</strong></td>
            <td width="180"><strong>退社日</strong></td>
        	</tr>
          <tr>
            <td><?= h($staff['birth']) ?></td>
            <td><?= h($staff['date_start']) ?></td>
            <td><?= h($staff['date_finish']) ?></td>
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
