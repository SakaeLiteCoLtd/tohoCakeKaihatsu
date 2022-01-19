<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductMaterialParent[]|\Cake\Collection\CollectionInterface $productMaterialParents
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Product Material Parent'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Result Parents'), ['controller' => 'InspectionDataResultParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Result Parent'), ['controller' => 'InspectionDataResultParents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Material Machines'), ['controller' => 'ProductMaterialMachines', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Material Machine'), ['controller' => 'ProductMaterialMachines', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="productMaterialParents index large-9 medium-8 columns content">
    <h3><?= __('Product Material Parents') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('material_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('version') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_active') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productMaterialParents as $productMaterialParent): ?>
            <tr>
                <td><?= $this->Number->format($productMaterialParent->id) ?></td>
                <td><?= $productMaterialParent->has('product') ? $this->Html->link($productMaterialParent->product->name, ['controller' => 'Products', 'action' => 'view', $productMaterialParent->product->id]) : '' ?></td>
                <td><?= $this->Number->format($productMaterialParent->material_id) ?></td>
                <td><?= $this->Number->format($productMaterialParent->version) ?></td>
                <td><?= $this->Number->format($productMaterialParent->is_active) ?></td>
                <td><?= $this->Number->format($productMaterialParent->delete_flag) ?></td>
                <td><?= h($productMaterialParent->created_at) ?></td>
                <td><?= $this->Number->format($productMaterialParent->created_staff) ?></td>
                <td><?= h($productMaterialParent->updated_at) ?></td>
                <td><?= $this->Number->format($productMaterialParent->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $productMaterialParent->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $productMaterialParent->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $productMaterialParent->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productMaterialParent->id)]) ?>
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
