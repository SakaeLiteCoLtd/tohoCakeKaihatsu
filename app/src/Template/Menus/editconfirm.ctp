<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlmenumenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlmenumenu = new htmlmenumenu();
 $htmlmenu = $htmlmenumenu->Menumenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlmenu;
?>

<form method="post" action="/menus/editdo">

<?= $this->Form->create($menu, ['url' => ['action' => 'editdo']]) ?>

<?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$this->request->getData('id'), 'label'=>false)) ?>
<?= $this->Form->control('name_menu', array('type'=>'hidden', 'value'=>$this->request->getData('name_menu'), 'label'=>false)) ?>
<br><br><br>

<nav class="large-3 medium-4 columns" style="width:70%">
    <?= $this->Form->create($menu) ?>
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('メニュー情報編集') ?></strong></legend>
      <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを登録します。よろしければ「決定」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>
        <table>
          <tr>
            <td width="280"><strong>メニュー名</strong></td>
        	</tr>
          <tr>
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
