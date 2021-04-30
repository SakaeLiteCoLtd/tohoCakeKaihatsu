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

<?php
$this->layout = false;
echo $this->Html->css('index');
?>

<div class="productMaterials index large-9 medium-8 columns content" style="width:70%">
  <h2><font color=red><?= __('製品原料一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('material_id') ?></th>
                <th scope="col" class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productMaterials as $productMaterial): ?>
            <tr>
                <td><?= $this->Number->format($productMaterial->id) ?></td>
                <td><?= $productMaterial->has('product') ? $this->Html->link($productMaterial->product->product_code, ['controller' => 'Products', 'action' => 'view', $productMaterial->product->id]) : '' ?></td>
                <td><?= $productMaterial->has('material') ? $this->Html->link($productMaterial->material->material_code, ['controller' => 'Materials', 'action' => 'view', $productMaterial->material->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('編集'), ['action' => 'editform', $productMaterial->id]) ?>
                    <?= $this->Html->link(__('削除'), ['action' => 'deleteconfirm', $productMaterial->id]) ?>
                </td>
            </tr>

          <?php
          $i = $i + 1;
          ?>

            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
