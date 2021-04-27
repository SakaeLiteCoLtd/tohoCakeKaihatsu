<?php
 use App\myClass\menulists\htmlmaterialmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlmaterialmenu = new htmlmaterialmenu();
 $htmlmaterial = $htmlmaterialmenu->Materialsmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlmaterial;
?>

<?= $this->Form->create($material, ['url' => ['action' => 'adddo']]) ?>

<?= $this->Form->control('material_code', array('type'=>'hidden', 'value'=>$this->request->getData('material_code'), 'label'=>false)) ?>
<?= $this->Form->control('grade', array('type'=>'hidden', 'value'=>$this->request->getData('grade'), 'label'=>false)) ?>
<?= $this->Form->control('color', array('type'=>'hidden', 'value'=>$this->request->getData('color'), 'label'=>false)) ?>
<?= $this->Form->control('maker', array('type'=>'hidden', 'value'=>$this->request->getData('maker'), 'label'=>false)) ?>
<?= $this->Form->control('type_id', array('type'=>'hidden', 'value'=>$this->request->getData('type_id'), 'label'=>false)) ?>

<nav class="large-3 medium-4 columns" style="width:70%">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('原料新規登録') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを登録します。よろしければ「決定」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="280"><strong>原料コード</strong></td>
          <td width="280"><strong>グレード</strong></td>
        </tr>
        <tr>
          <td><?= h($this->request->getData('material_code')) ?></td>
          <td><?= h($this->request->getData('grade')) ?></td>
        </tr>
      </table>

      <table>
      <tr>
        <td width="280"><strong>色</strong></td>
        <td width="280"><strong>メーカー</strong></td>
      </tr>
      <tr>
        <td><?= h($this->request->getData('color')) ?></td>
        <td><?= h($this->request->getData('maker')) ?></td>
      </tr>
    </table>

    <table>
    <tr>
      <td width="280"><strong>原料種類</strong></td>
    </tr>
    <tr>
      <td><?= h($type_name) ?></td>
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
