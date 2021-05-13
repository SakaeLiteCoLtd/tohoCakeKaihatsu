<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductLength $productLength
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Product Length'), ['action' => 'edit', $productLength->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Product Length'), ['action' => 'delete', $productLength->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productLength->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Product Lengths'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Length'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="productLengths view large-9 medium-8 columns content">
    <h3><?= h($productLength->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Product') ?></th>
            <td><?= $productLength->has('product') ? $this->Html->link($productLength->product->name, ['controller' => 'Products', 'action' => 'view', $productLength->product->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($productLength->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Length') ?></th>
            <td><?= $this->Number->format($productLength->length) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Active') ?></th>
            <td><?= $this->Number->format($productLength->is_active) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($productLength->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($productLength->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($productLength->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($productLength->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($productLength->updated_at) ?></td>
        </tr>
    </table>
</div>
