<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductMaterialLotNumber $productMaterialLotNumber
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Product Material Lot Number'), ['action' => 'edit', $productMaterialLotNumber->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Product Material Lot Number'), ['action' => 'delete', $productMaterialLotNumber->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productMaterialLotNumber->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Product Material Lot Numbers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Material Lot Number'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Product Machine Materials'), ['controller' => 'ProductMachineMaterials', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Machine Material'), ['controller' => 'ProductMachineMaterials', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Staffs'), ['controller' => 'Staffs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Staff'), ['controller' => 'Staffs', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="productMaterialLotNumbers view large-9 medium-8 columns content">
    <h3><?= h($productMaterialLotNumber->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Product Machine Material') ?></th>
            <td><?= $productMaterialLotNumber->has('product_machine_material') ? $this->Html->link($productMaterialLotNumber->product_machine_material->id, ['controller' => 'ProductMachineMaterials', 'action' => 'view', $productMaterialLotNumber->product_machine_material->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lot Number') ?></th>
            <td><?= h($productMaterialLotNumber->lot_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Staff') ?></th>
            <td><?= $productMaterialLotNumber->has('staff') ? $this->Html->link($productMaterialLotNumber->staff->name, ['controller' => 'Staffs', 'action' => 'view', $productMaterialLotNumber->staff->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($productMaterialLotNumber->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($productMaterialLotNumber->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($productMaterialLotNumber->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($productMaterialLotNumber->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Datetme') ?></th>
            <td><?= h($productMaterialLotNumber->datetme) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($productMaterialLotNumber->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($productMaterialLotNumber->updated_at) ?></td>
        </tr>
    </table>
</div>
