<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlpositionmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlpositionmenu = new htmlpositionmenu();
 $htmlposition = $htmlpositionmenu->Positionmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlposition;
?>

<form method="post" action="/positions/index">

<?= $this->Form->create($position, ['url' => ['action' => 'index']]) ?>

<nav class="large-3 medium-4 columns" style="width:70%">
    <?= $this->Form->create($position) ?>
    <fieldset>

      <legend><strong style="font-size: 15pt; color:red"><?= __('役職情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>役職名</strong></td>
            <td width="280"><strong>工場・営業所名</strong></td>
        	</tr>
          <tr>
            <td><?= h($position['position']) ?></td>
            <td><?= h($position->office->name) ?></td>
        	</tr>
        </table>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('役職メニュートップへ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
