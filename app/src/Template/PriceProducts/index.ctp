<?php
 use App\myClass\menulists\htmlpriceMaterialmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlpriceMaterialmenu = new htmlpriceMaterialmenu();
 $htmlpriceMaterial = $htmlpriceMaterialmenu->priceMaterialsmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlpriceMaterial;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>

<div class="priceMaterials index large-9 medium-8 columns content" style="width:70%">
  <h2><font color=red><?= __('製品単価一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('custmoer_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('price') ?></th>
                <th scope="col"><?= $this->Paginator->sort('start_deal') ?></th>
                <th scope="col"><?= $this->Paginator->sort('finish_deal') ?></th>
                <th scope="col" class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($priceProducts as $priceProduct): ?>
            <tr>
                <td><?= $this->Number->format($priceProduct->id) ?></td>
                <td><?= $priceProduct->has('product') ? $this->Html->link($priceProduct->product->name, ['controller' => 'Products', 'action' => 'view', $priceProduct->product->id]) : '' ?></td>
                <td><?= $this->Number->format($priceProduct->custmoer_id) ?></td>
                <td><?= $this->Number->format($priceProduct->price) ?></td>
                <td><?= h($priceProduct->start_deal) ?></td>
                <td><?= h($priceProduct->finish_deal) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('編集'), ['action' => 'editform', $priceMaterial->id]) ?>
                    <?= $this->Html->link(__('削除'), ['action' => 'deleteconfirm', $priceMaterial->id]) ?>
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
