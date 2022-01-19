<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductMaterial $productMaterial
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Product Material'), ['action' => 'edit', $productMaterial->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Product Material'), ['action' => 'delete', $productMaterial->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productMaterial->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Product Materials'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Material'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Materials'), ['controller' => 'Materials', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Material'), ['controller' => 'Materials', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="productMaterials view large-9 medium-8 columns content">
    <h3><?= h($productMaterial->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Product') ?></th>
            <td><?= $productMaterial->has('product') ? $this->Html->link($productMaterial->product->name, ['controller' => 'Products', 'action' => 'view', $productMaterial->product->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Material') ?></th>
            <td><?= $productMaterial->has('material') ? $this->Html->link($productMaterial->material->id, ['controller' => 'Materials', 'action' => 'view', $productMaterial->material->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($productMaterial->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Active') ?></th>
            <td><?= $this->Number->format($productMaterial->is_active) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($productMaterial->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($productMaterial->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($productMaterial->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($productMaterial->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($productMaterial->updated_at) ?></td>
        </tr>
    </table>
</div>
