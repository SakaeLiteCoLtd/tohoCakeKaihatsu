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

<form method="post" action="/materials/editconfirm">

<?= $this->Form->create($material, ['url' => ['action' => 'editconfirm']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($material) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('仕入品新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('データを編集してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="240"><strong>工場名（変更不可）</strong></td>
          <td width="240"><strong>仕入品コード（変更不可）</strong></td>
        </tr>
        <tr>
        <td><?= h($factory_name) ?></td>
        <td><?= h($material_code) ?></td>
        </tr>
      </table>
        <table>
        <tr>
        <td width="240"><strong>単位</strong></td>
        <td width="240"><strong>検査表</strong></td>
        </tr>
        <tr>
        <td><?= $this->Form->control('tanni', ['options' => $arrTanni, 'label'=>false]) ?></td>
        <td><?= $this->Form->control('status_kensahyou', ['options' => $arrStatusKensahyou, 'value'=>$status_kensahyou, 'label'=>false]) ?></td>
        </tr>
      </table>

      <table>
        <tr>
          <td width="480"><strong>仕入品名</strong></td>
        </tr>
        <tr>
          <td><?= $this->Form->control('name', array('type'=>'text', 'value'=>$name, 'label'=>false, 'size'=>40, 'autocomplete'=>"off")) ?></td>
        </tr>
      </table>

      <table>
        <tr>
          <td width="480"><strong>仕入品種類</strong></td>
        </tr>
        <tr>
        <td><?= $this->Form->control('material_type_id', ['options' => $arrMaterialTypes, 'value'=>$material_type_id, 'label'=>false]) ?></td>
        </tr>
      </table>

      <table>
      <tr>
      <td width="480"><strong>仕入品仕入先（変更不可）</strong></td>
      </tr>
      <tr>
      <td><?= h($supplier_name) ?></td>
      </tr>
    </table>

  <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
  <?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$factory_id, 'label'=>false)) ?>
  <?= $this->Form->control('material_code', array('type'=>'hidden', 'value'=>$material_code, 'label'=>false)) ?>
  <?= $this->Form->control('material_supplier_id', array('type'=>'hidden', 'value'=>$material_supplier_id, 'label'=>false)) ?>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
