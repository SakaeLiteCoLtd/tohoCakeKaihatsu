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

<?= $this->Form->create($Groups, ['url' => ['action' => 'adddo']]) ?>

<?= $this->Form->control('name_group', array('type'=>'hidden', 'value'=>$this->request->getData('name_group'), 'label'=>false)) ?>
<?= $this->Form->control('name_menu', array('type'=>'hidden', 'value'=>$this->request->getData('name_menu'), 'label'=>false)) ?>
<?= $this->Form->control('menu_id', array('type'=>'hidden', 'value'=>$menu_id, 'label'=>false)) ?>

<nav class="large-3 medium-4 columns" style="width:70%">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('グループ新規登録') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを登録します。よろしければ「決定」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>グループ</strong></td>
            <td width="280"><strong>追加メニュー</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('name_group')) ?></td>
            <td><?= h($this->request->getData('name_menu')) ?></td>
        	</tr>
        </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border-style: none;"><div><?= $this->Form->submit('決定', array('name' => 'kettei')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
