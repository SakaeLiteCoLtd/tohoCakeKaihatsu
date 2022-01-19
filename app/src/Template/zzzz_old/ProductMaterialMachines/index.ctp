<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductMaterialMachine[]|\Cake\Collection\CollectionInterface $productMaterialMachines
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Product Material Machine'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Material Parents'), ['controller' => 'ProductMaterialParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Material Parent'), ['controller' => 'ProductMaterialParents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Machine Materials'), ['controller' => 'ProductMachineMaterials', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Machine Material'), ['controller' => 'ProductMachineMaterials', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="productMaterialMachines index large-9 medium-8 columns content">
    <h3><?= __('Product Material Machines') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_material_parent_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cylinder_numer') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cylinder_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productMaterialMachines as $productMaterialMachine): ?>
            <tr>
                <td><?= $this->Number->format($productMaterialMachine->id) ?></td>
                <td><?= $productMaterialMachine->has('product_material_parent') ? $this->Html->link($productMaterialMachine->product_material_parent->id, ['controller' => 'ProductMaterialParents', 'action' => 'view', $productMaterialMachine->product_material_parent->id]) : '' ?></td>
                <td><?= $this->Number->format($productMaterialMachine->cylinder_numer) ?></td>
                <td><?= h($productMaterialMachine->cylinder_name) ?></td>
                <td><?= $this->Number->format($productMaterialMachine->delete_flag) ?></td>
                <td><?= h($productMaterialMachine->created_at) ?></td>
                <td><?= $this->Number->format($productMaterialMachine->created_staff) ?></td>
                <td><?= h($productMaterialMachine->updated_at) ?></td>
                <td><?= $this->Number->format($productMaterialMachine->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $productMaterialMachine->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $productMaterialMachine->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $productMaterialMachine->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productMaterialMachine->id)]) ?>
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
