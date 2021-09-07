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

<form method="post" action="/priceMaterials/index">

<?= $this->Form->create($priceMaterial, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<nav class="sample non-sample">
    <?= $this->Form->create($priceMaterial) ?>
    <fieldset>

      <legend><strong style="font-size: 15pt; color:red"><?= __('原料単価情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="170"><strong>原料</strong></td>
          <td width="170"><strong>原料仕入先</strong></td>
          <td width="170"><strong>単価</strong></td>
        </tr>
        <tr>
          <td><?= h($priceMaterial->material->material_code) ?></td>
          <td><?= h($priceMaterial->material_supplier->name) ?></td>
          <td><?= h($priceMaterial['price']) ?></td>
        </tr>
      </table>

      <table>
      <tr>
        <td width="270"><strong>取引開始日</strong></td>
        <td width="270"><strong>取引終了日</strong></td>
      </tr>
      <tr>
        <td><?= h($priceMaterial['start_deal']) ?></td>
        <td><?= h($priceMaterial['finish_deal']) ?></td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="560"><strong>ロット説明</strong></td>
      </tr>
      <tr>
        <td><?= h($priceMaterial['lot_remarks']) ?></td>
      </tr>
    </table>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('原料単価一覧へ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
</form>
