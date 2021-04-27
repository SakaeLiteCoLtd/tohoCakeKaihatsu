<?php
 use App\myClass\menulists\htmlproductMaterialmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlproductMaterialmenu = new htmlproductMaterialmenu();
 $htmlproductMaterial = $htmlproductMaterialmenu->productMaterialsmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlproductMaterial;
?>

<form method="post" action="/productMaterials/index">

<?= $this->Form->create($productMaterial, ['url' => ['action' => 'index']]) ?>

<nav class="large-3 medium-4 columns" style="width:70%">
    <?= $this->Form->create($productMaterial) ?>
    <fieldset>

      <legend><strong style="font-size: 15pt; color:red"><?= __('製品原料情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="280"><strong>品番</strong></td>
          <td width="280"><strong>原料</strong></td>
        </tr>
        <tr>
          <td><?= h($productMaterial->product->product_code."（".$productMaterial->product->name."）") ?></td>
          <td><?= h($productMaterial->material->material_code) ?></td>
        </tr>
      </table>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('製品原料メニュートップへ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
