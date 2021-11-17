<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensakigumenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlkensakigumenu = new htmlkensakigumenu();
 $htmlkensakigu = $htmlkensakigumenu->kensakigumenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlkensakigu;
?>

<form method="post" action="/kensakigus/index">
<?= $this->Form->create($kensakigu, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<nav class="sample non-sample">
    <?= $this->Form->create($kensakigu) ?>
    <fieldset>

      <legend><strong style="font-size: 15pt; color:red"><?= __('検査器具情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="280"><strong>検査器具</strong></td>
        </tr>
        <tr>
          <td><?= h($kensakigu['name']) ?></td>
        </tr>
      </table>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('検査器具メニュートップへ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
