<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlmaterialmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlmaterialmenu = new htmlmaterialmenu();
 $htmlmaterial = $htmlmaterialmenu->materialmenus();
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
<?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$this->request->getData('name'), 'label'=>false)) ?>
<?= $this->Form->control('tanni', array('type'=>'hidden', 'value'=>$this->request->getData('tanni'), 'label'=>false)) ?>
<?= $this->Form->control('material_supplier_id', array('type'=>'hidden', 'value'=>$this->request->getData('material_supplier_id'), 'label'=>false)) ?>
<?= $this->Form->control('type_id', array('type'=>'hidden', 'value'=>$this->request->getData('type_id'), 'label'=>false)) ?>
<?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$this->request->getData('factory_id'), 'label'=>false)) ?>
<br><br><br>

<nav class="large-3 medium-4 columns">
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
          <td width="280"><strong>自社工場</strong></td>
        </tr>
        <tr>
        <td><?= h($factory_name) ?></td>
        </tr>
      </table>
        <table>
        <tr>
        <td width="260"><strong>原料コード</strong></td>
        <td width="200"><strong>単位</strong></td>
        </tr>
        <tr>
        <td><?= h($this->request->getData('material_code')) ?></td>
        <td><?= h($this->request->getData('tanni')) ?></td>
        </tr>
      </table>

      <table>
        <tr>
          <td width="480"><strong>原料名</strong></td>
        </tr>
        <tr>
        <td><?= h($this->request->getData('name')) ?></td>
        </tr>
      </table>

      <table>
        <tr>
          <td width="480"><strong>原料種類</strong></td>
        </tr>
        <tr>
        <td><?= h($type_name) ?></td>
        </tr>
      </table>

      <table>
      <tr>
      <td width="480"><strong>原料仕入先</strong></td>
      </tr>
      <tr>
      <td><?= h($supplier_name) ?></td>
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
