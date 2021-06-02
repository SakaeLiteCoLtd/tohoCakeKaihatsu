<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductLength[]|\Cake\Collection\CollectionInterface $productLengths
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Product Length'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="productLengths index large-9 medium-8 columns content">
    <h3><?= __('Product Lengths') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('length') ?></th>
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
            <?php foreach ($productLengths as $productLength): ?>
            <tr>
                <td><?= $this->Number->format($productLength->id) ?></td>
                <td><?= $productLength->has('product') ? $this->Html->link($productLength->product->name, ['controller' => 'Products', 'action' => 'view', $productLength->product->id]) : '' ?></td>
                <td><?= $this->Number->format($productLength->length) ?></td>
                <td><?= $this->Number->format($productLength->is_active) ?></td>
                <td><?= $this->Number->format($productLength->delete_flag) ?></td>
                <td><?= h($productLength->created_at) ?></td>
                <td><?= $this->Number->format($productLength->created_staff) ?></td>
                <td><?= h($productLength->updated_at) ?></td>
                <td><?= $this->Number->format($productLength->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $productLength->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $productLength->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $productLength->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productLength->id)]) ?>
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
