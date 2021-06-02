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

<?= $this->Form->create($operation, ['url' => ['action' => 'addcomfirm']]) ?>
<br><br><br>

<nav class="large-3 medium-4 columns" style="width:70%">

    <?= $this->Form->create($operation) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('運用代表新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('登録するデータを入力してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="282"><strong>工場・営業所名</strong></td>
        </tr>

        <tr>
          <td><?= $this->Form->control('factory_id', ['options' => $arrFactories, 'label'=>false]) ?></td>
        </tr>
      </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
