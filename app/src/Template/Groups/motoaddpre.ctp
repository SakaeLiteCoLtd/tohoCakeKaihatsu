<?php
 use App\myClass\menulists\htmlgroupmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlgroupmenu = new htmlgroupmenu();
 $htmlgroup = $htmlgroupmenu->Groupmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlgroup;
?>

<nav class="large-3 medium-4 columns" style="width:70%">
  <?= $this->Form->create($Groups, ['url' => ['action' => 'addform']]) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('グループ新規登録') ?></strong></legend>
    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('登録済みグループ選択はこちら', array('name' => 'select')); ?></div></td>
        </tr>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('　新規グループ入力はこちら　', array('name' => 'text')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
