<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlgroupmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlgroupmenu = new htmlgroupmenu();
 $htmlgroup = $htmlgroupmenu->Groupmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlgroup;
?>

<form method="post" action="/groups/deletedo">

<?= $this->Form->create($group, ['url' => ['action' => 'deletedo']]) ?>
<br><br><br>

<nav class="large-3 medium-4 columns">
    <?= $this->Form->create($group) ?>
    <fieldset>

      <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$group['id'], 'label'=>false)) ?>

      <legend><strong style="font-size: 15pt; color:red"><?= __('グループ情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを削除します。よろしければ「削除」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>グループ</strong></td>
        	</tr>
          <tr>
            <td><?= h($group['name_group']) ?></td>
        	</tr>
        </table>

        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red">
              <?= __('※上のグループが削除されます。削除後は該当ユーザーのグループを登録しなおしてください。　') ?><br>
              <?= __('（登録しなおされない場合は、該当ユーザーのグループは削除されたグループのままになります）') ?>
            </strong></td></tr>
          </tbody>
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
