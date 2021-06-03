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

<?= $this->Form->create($priceMaterials, ['url' => ['action' => 'detail']]) ?>

<?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
<br><br><br>

<nav class="large-3 medium-4 columns">
    <fieldset>
      <table>
      <tbody class='sample non-sample'>
        <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('詳細表示') ?></strong></td></tr>
      </tbody>
    </table>

    <table>
    <tr>
      <td width="170"><strong>原料</strong></td>
      <td width="170"><strong>原料仕入先</strong></td>
      <td width="170"><strong>単価</strong></td>
    </tr>
    <tr>
      <td><?= h($material_code) ?></td>
      <td><?= h($materialSupplier) ?></td>
      <td><?= h($price) ?></td>
    </tr>
  </table>

  <table>
  <tr>
    <td width="270"><strong>取引開始日</strong></td>
    <td width="270"><strong>取引終了日</strong></td>
  </tr>
  <tr>
    <td><?= h($start_deal) ?></td>
    <td><?= h($finish_deal) ?></td>
  </tr>
</table>
<table>
  <tr>
    <td width="560"><strong>ロット説明</strong></td>
  </tr>
  <tr>
    <td><?= h($lot_remarks) ?></td>
  </tr>
</table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('編集', array('name' => 'edit')); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border-style: none;"><div><?= $this->Form->submit('削除', array('name' => 'delete')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <br><br><br>

    <?= $this->Form->end() ?>
  </nav>
