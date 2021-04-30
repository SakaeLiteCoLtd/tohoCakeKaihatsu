<?php
 use App\myClass\menulists\htmlproductMaterialmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlproductMaterialmenu = new htmlproductMaterialmenu();
 $htmlproductMaterial = $htmlproductMaterialmenu->productMaterialsmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlproductMaterial;
?>

<?= $this->Form->create($productMaterial, ['url' => ['action' => 'addcomfirm']]) ?>

<nav class="large-3 medium-4 columns" style="width:70%">

    <?= $this->Form->create($productMaterial) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('製品原料新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('登録するデータを入力してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="280"><strong>品番</strong></td>
          <td width="280"><strong>原料</strong></td>
        </tr>
        <tr>
          <td><?= $this->Form->control('product_id', ['options' => $arrProducts, 'label'=>false]) ?></td>
          <td><?= $this->Form->control('material_id', ['options' => $arrMaterials, 'label'=>false]) ?></td>
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
