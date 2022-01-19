<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductMachineMaterial[]|\Cake\Collection\CollectionInterface $productMachineMaterials
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Product Machine Material'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Material Machines'), ['controller' => 'ProductMaterialMachines', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Material Machine'), ['controller' => 'ProductMaterialMachines', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Material Lot Numbers'), ['controller' => 'ProductMaterialLotNumbers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Material Lot Number'), ['controller' => 'ProductMaterialLotNumbers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="productMachineMaterials index large-9 medium-8 columns content">
    <h3><?= __('Product Machine Materials') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_material_machine_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('material_number') ?></th>
                <th scope="col"><?= $this->Paginator->sort('material_grade') ?></th>
                <th scope="col"><?= $this->Paginator->sort('material_maker') ?></th>
                <th scope="col"><?= $this->Paginator->sort('mixing_ratio') ?></th>
                <th scope="col"><?= $this->Paginator->sort('dry_temp') ?></th>
                <th scope="col"><?= $this->Paginator->sort('dry_hour') ?></th>
                <th scope="col"><?= $this->Paginator->sort('recycled_mixing_ratio') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productMachineMaterials as $productMachineMaterial): ?>
            <tr>
                <td><?= $this->Number->format($productMachineMaterial->id) ?></td>
                <td><?= $productMachineMaterial->has('product_material_machine') ? $this->Html->link($productMachineMaterial->product_material_machine->id, ['controller' => 'ProductMaterialMachines', 'action' => 'view', $productMachineMaterial->product_material_machine->id]) : '' ?></td>
                <td><?= $this->Number->format($productMachineMaterial->material_number) ?></td>
                <td><?= h($productMachineMaterial->material_grade) ?></td>
                <td><?= h($productMachineMaterial->material_maker) ?></td>
                <td><?= $this->Number->format($productMachineMaterial->mixing_ratio) ?></td>
                <td><?= $this->Number->format($productMachineMaterial->dry_temp) ?></td>
                <td><?= $this->Number->format($productMachineMaterial->dry_hour) ?></td>
                <td><?= $this->Number->format($productMachineMaterial->recycled_mixing_ratio) ?></td>
                <td><?= $this->Number->format($productMachineMaterial->delete_flag) ?></td>
                <td><?= h($productMachineMaterial->created_at) ?></td>
                <td><?= $this->Number->format($productMachineMaterial->created_staff) ?></td>
                <td><?= h($productMachineMaterial->updated_at) ?></td>
                <td><?= $this->Number->format($productMachineMaterial->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $productMachineMaterial->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $productMachineMaterial->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $productMachineMaterial->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productMachineMaterial->id)]) ?>
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
