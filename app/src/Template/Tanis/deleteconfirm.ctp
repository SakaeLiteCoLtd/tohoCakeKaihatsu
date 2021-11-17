<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmltanimenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmltanimenu = new htmltanimenu();
 $htmltani = $htmltanimenu->tanimenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmltani;
?>

<form method="post" action="/tanis/deletedo">

<?= $this->Form->create($tani, ['url' => ['action' => 'deletedo']]) ?>
<br><br><br>

<nav class="sample non-sample">
    <?= $this->Form->create($tani) ?>
    <fieldset>

      <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$tani['id'], 'label'=>false)) ?>

      <legend><strong style="font-size: 15pt; color:red"><?= __('単位情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを削除します。よろしければ「削除」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="280"><strong>単位</strong></td>
        </tr>
        <tr>
          <td><?= h($tani['name']) ?></td>
        </tr>
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
