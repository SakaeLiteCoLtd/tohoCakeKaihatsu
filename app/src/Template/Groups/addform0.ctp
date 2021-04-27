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

<?= $this->Form->create($Groups, ['url' => ['action' => 'addcomfirm']]) ?>

<nav class="large-3 medium-4 columns" style="width:70%">

    <?= $this->Form->create($Groups) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('グループ新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('登録するデータを入力してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>グループ</strong></td>
            <td width="282"><strong>追加メニュー</strong></td>
        	</tr>

          <?php if ($selectcheck == 1): ?>

          <tr>
            <td><?= $this->Form->control('name_group', ['options' => $arrname_group, 'label'=>false]) ?></td>
            <td><?= $this->Form->control('name_menu', ['options' => $arrmenu_id, 'label'=>false]) ?></td>
        	</tr>

        <?php else : ?>

          <tr>
            <td><?= $this->Form->control('name_group', array('type'=>'text', 'label'=>false)) ?></td>
            <td><?= $this->Form->control('name_menu', ['options' => $arrmenu_id, 'label'=>false]) ?></td>
        	</tr>

        <?php endif; ?>

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
