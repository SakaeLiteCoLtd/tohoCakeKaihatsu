<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductMaterialLotNumber[]|\Cake\Collection\CollectionInterface $productMaterialLotNumbers
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Product Material Lot Number'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Machine Materials'), ['controller' => 'ProductMachineMaterials', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Machine Material'), ['controller' => 'ProductMachineMaterials', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Staffs'), ['controller' => 'Staffs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Staff'), ['controller' => 'Staffs', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="productMaterialLotNumbers index large-9 medium-8 columns content">
    <h3><?= __('Product Material Lot Numbers') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_machine_material_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('datetme') ?></th>
                <th scope="col"><?= $this->Paginator->sort('lot_number') ?></th>
                <th scope="col"><?= $this->Paginator->sort('staff_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productMaterialLotNumbers as $productMaterialLotNumber): ?>
            <tr>
                <td><?= $this->Number->format($productMaterialLotNumber->id) ?></td>
                <td><?= $productMaterialLotNumber->has('product_machine_material') ? $this->Html->link($productMaterialLotNumber->product_machine_material->id, ['controller' => 'ProductMachineMaterials', 'action' => 'view', $productMaterialLotNumber->product_machine_material->id]) : '' ?></td>
                <td><?= h($productMaterialLotNumber->datetme) ?></td>
                <td><?= h($productMaterialLotNumber->lot_number) ?></td>
                <td><?= $productMaterialLotNumber->has('staff') ? $this->Html->link($productMaterialLotNumber->staff->name, ['controller' => 'Staffs', 'action' => 'view', $productMaterialLotNumber->staff->id]) : '' ?></td>
                <td><?= $this->Number->format($productMaterialLotNumber->delete_flag) ?></td>
                <td><?= h($productMaterialLotNumber->created_at) ?></td>
                <td><?= $this->Number->format($productMaterialLotNumber->created_staff) ?></td>
                <td><?= h($productMaterialLotNumber->updated_at) ?></td>
                <td><?= $this->Number->format($productMaterialLotNumber->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $productMaterialLotNumber->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $productMaterialLotNumber->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $productMaterialLotNumber->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productMaterialLotNumber->id)]) ?>
                </td>
            </tr>
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
