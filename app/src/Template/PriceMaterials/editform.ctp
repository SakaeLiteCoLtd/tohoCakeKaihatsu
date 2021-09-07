<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlpriceProductmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlpriceProductmenu = new htmlpriceProductmenu();
 $htmlpriceProduct = $htmlpriceProductmenu->priceProductsmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlpriceProduct;
?>

<form method="post" action="/priceMaterials/editconfirm">

<?= $this->Form->create($priceMaterial, ['url' => ['action' => 'editconfirm']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($priceMaterial) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('原料単価情報編集') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('データを編集してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="150"><strong>原料</strong></td>
          <td width="150"><strong>原料仕入先</strong></td>
          <td width="160"><strong>単価</strong></td>
        </tr>
        <tr>
          <td><?= $this->Form->control('material_id', ['options' => $arrMaterials, 'label'=>false]) ?></td>
          <td><?= $this->Form->control('material_supplier_id', ['options' => $arrMaterialSuppliers, 'label'=>false]) ?></td>
          <td><?= $this->Form->control('price', array('type'=>'text', 'label'=>false)) ?></td>
        </tr>
      </table>

      <table>
      <tr>
        <td width="270"><strong>取引開始日</strong></td>
        <td width="270"><strong>取引終了日</strong></td>
      </tr>
      <tr>
        <td><?= $this->Form->input("start_deal", array('type' => 'date', 'minYear' => date('Y') - 70, 'monthNames' => false, 'label'=>false)); ?></td>
        <td><?= $this->Form->input("finish_deal", array('type' => 'date', 'monthNames' => false, 'label'=>false, 'empty' => true)); ?></td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="560"><strong>ロット説明</strong></td>
      </tr>
      <tr>
        <td><?= $this->Form->control('lot_remarks', array('type'=>'text', 'label'=>false, 'size'=>50)) ?></td>
      </tr>
    </table>

    <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>

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
