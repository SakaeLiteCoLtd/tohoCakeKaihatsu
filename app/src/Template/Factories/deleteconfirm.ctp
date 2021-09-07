<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlfactorymenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlfactorymenu = new htmlfactorymenu();
 $htmlfactory = $htmlfactorymenu->factorymenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlfactory;
?>

<form method="post" action="/Factories/deletedo">

<?= $this->Form->create($factory, ['url' => ['action' => 'deletedo']]) ?>
<br><br><br>

<nav class="sample non-sample">
    <?= $this->Form->create($factory) ?>
    <fieldset>

      <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$factory['id'], 'label'=>false)) ?>

      <legend><strong style="font-size: 15pt; color:red"><?= __('工場・営業所情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを削除します。よろしければ「削除」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="200"><strong>工場・営業所名</strong></td>
            <td width="200"><strong>会社名</strong></td>
            <td width="200"><strong>代表（担当）</strong></td>
        	</tr>
          <tr>
            <td><?= h($factory['name']) ?></td>
            <td><?= h($factory->company->name) ?></td>
            <td><?= h($factory->staff->name) ?></td>
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
