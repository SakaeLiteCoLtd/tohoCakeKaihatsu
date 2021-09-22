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
<br><br><br>

<nav class="sample non-sample">
  <?= $this->Form->create($Groups, ['url' => ['action' => 'addform']]) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('グループ新規登録') ?></strong></legend>
    </fieldset>

    <table>
      <tr>
        <td width="280"><strong>グループ名</strong></td>
      </tr>
      <tr>
        <td><?= $this->Form->control('name_group', array('type'=>'text', 'label'=>false, 'autofocus'=>true)) ?></td>
      </tr>
    </table>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
