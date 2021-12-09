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

<?= $this->Form->create($linenames, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<nav class="sample non-sample">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('ライン情報編集') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
        <td width="200"><strong>ライン番号</strong></td>
        <td width="200"><strong>ライン名</strong></td>
        </tr>
        <tr>
        <td><?= h($this->request->getData('machine_num')) ?></td>
        <td><?= h($this->request->getData('name')) ?></td>
        </tr>
      </table>

    </fieldset>

    <table>
      <tr>
        <tbody class='sample non-sample'>
        <td style="border-style: none;"><div><?= $this->Form->submit('ラインメニュートップへ戻る', array('name' => 'top')); ?></div></td>
      </tbody>
    </tr>
    </table>

    <?= $this->Form->end() ?>
  </nav>
