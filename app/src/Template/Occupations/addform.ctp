<?php
 use App\myClass\menulists\htmloccupationmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmloccupationmenu = new htmloccupationmenu();
 $htmloccupation = $htmloccupationmenu->Occupationmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmloccupation;
?>

<?= $this->Form->create($occupation, ['url' => ['action' => 'addcomfirm']]) ?>

<nav class="large-3 medium-4 columns" style="width:70%">

    <?= $this->Form->create($occupation) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('職種新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('登録するデータを入力してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="280"><strong>職種名</strong></td>
          <td width="282"><strong>工場・営業所名</strong></td>
        </tr>

        <tr>
          <td><?= $this->Form->control('occupation', array('type'=>'text', 'label'=>false, 'autofocus'=>true)) ?></td>
          <td><?= $this->Form->control('office_id', ['options' => $arrOffices, 'label'=>false]) ?></td>
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
