<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PriceMaterial $priceMaterial
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Price Material'), ['action' => 'edit', $priceMaterial->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Price Material'), ['action' => 'delete', $priceMaterial->id], ['confirm' => __('Are you sure you want to delete # {0}?', $priceMaterial->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Price Materials'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Price Material'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Materials'), ['controller' => 'Materials', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Material'), ['controller' => 'Materials', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Material Suppliers'), ['controller' => 'MaterialSuppliers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Material Supplier'), ['controller' => 'MaterialSuppliers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="priceMaterials view large-9 medium-8 columns content">
    <h3><?= h($priceMaterial->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Material') ?></th>
            <td><?= $priceMaterial->has('material') ? $this->Html->link($priceMaterial->material->id, ['controller' => 'Materials', 'action' => 'view', $priceMaterial->material->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Material Supplier') ?></th>
            <td><?= $priceMaterial->has('material_supplier') ? $this->Html->link($priceMaterial->material_supplier->name, ['controller' => 'MaterialSuppliers', 'action' => 'view', $priceMaterial->material_supplier->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lot Remarks') ?></th>
            <td><?= h($priceMaterial->lot_remarks) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($priceMaterial->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Price') ?></th>
            <td><?= $this->Number->format($priceMaterial->price) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Active') ?></th>
            <td><?= $this->Number->format($priceMaterial->is_active) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($priceMaterial->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($priceMaterial->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($priceMaterial->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Start Deal') ?></th>
            <td><?= h($priceMaterial->start_deal) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Finish Deal') ?></th>
            <td><?= h($priceMaterial->finish_deal) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($priceMaterial->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($priceMaterial->updated_at) ?></td>
        </tr>
    </table>
</div>
