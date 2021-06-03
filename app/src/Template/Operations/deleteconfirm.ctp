<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmloperationmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmloperationmenu = new htmloperationmenu();
 $htmloperationmenu = $htmloperationmenu->Operationmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmloperationmenu;
?>

<form method="post" action="/operations/deletedo">

<?= $this->Form->create($operation, ['url' => ['action' => 'deletedo']]) ?>
<br><br><br>

<nav class="large-3 medium-4 columns">
    <?= $this->Form->create($operation) ?>
    <fieldset>

      <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$operation['id'], 'label'=>false)) ?>

      <legend><strong style="font-size: 15pt; color:red"><?= __('運用代表情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを削除します。よろしければ「削除」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>工場・営業所名</strong></td>
        	</tr>
          <tr>
            <td><?= h($operation->factory->name) ?></td>
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
