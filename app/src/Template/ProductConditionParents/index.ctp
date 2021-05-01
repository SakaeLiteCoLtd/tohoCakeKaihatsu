<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductConditionParent[]|\Cake\Collection\CollectionInterface $productConditionParents
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Product Condition Parent'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Conditon Children'), ['controller' => 'ProductConditonChildren', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Conditon Child'), ['controller' => 'ProductConditonChildren', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="productConditionParents index large-9 medium-8 columns content">
    <h3><?= __('Product Condition Parents') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('version') ?></th>
                <th scope="col"><?= $this->Paginator->sort('start_datetime') ?></th>
                <th scope="col"><?= $this->Paginator->sort('finish_datetime') ?></th>
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
            <?php foreach ($productConditionParents as $productConditionParent): ?>
            <tr>
                <td><?= $this->Number->format($productConditionParent->id) ?></td>
                <td><?= $productConditionParent->has('product') ? $this->Html->link($productConditionParent->product->name, ['controller' => 'Products', 'action' => 'view', $productConditionParent->product->id]) : '' ?></td>
                <td><?= $this->Number->format($productConditionParent->version) ?></td>
                <td><?= h($productConditionParent->start_datetime) ?></td>
                <td><?= h($productConditionParent->finish_datetime) ?></td>
                <td><?= $this->Number->format($productConditionParent->is_active) ?></td>
                <td><?= $this->Number->format($productConditionParent->delete_flag) ?></td>
                <td><?= h($productConditionParent->created_at) ?></td>
                <td><?= $this->Number->format($productConditionParent->created_staff) ?></td>
                <td><?= h($productConditionParent->updated_at) ?></td>
                <td><?= $this->Number->format($productConditionParent->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $productConditionParent->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $productConditionParent->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $productConditionParent->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productConditionParent->id)]) ?>
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
