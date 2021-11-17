<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmllinenamemenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmllinenamemenu = new htmllinenamemenu();
 $htmllinename = $htmllinenamemenu->linenamemenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmllinename;
?>

<form method="post" action="/linenames/index">
<?= $this->Form->create($linename, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<nav class="sample non-sample">
    <?= $this->Form->create($linename) ?>
    <fieldset>

      <legend><strong style="font-size: 15pt; color:red"><?= __('ライン情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="280"><strong>ライン</strong></td>
        </tr>
        <tr>
          <td><?= h($linename['name']) ?></td>
        </tr>
      </table>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('ラインメニュートップへ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
