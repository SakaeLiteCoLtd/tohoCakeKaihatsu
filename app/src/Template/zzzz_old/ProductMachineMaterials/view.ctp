<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductMachineMaterial $productMachineMaterial
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Product Machine Material'), ['action' => 'edit', $productMachineMaterial->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Product Machine Material'), ['action' => 'delete', $productMachineMaterial->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productMachineMaterial->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Product Machine Materials'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Machine Material'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Product Material Machines'), ['controller' => 'ProductMaterialMachines', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Material Machine'), ['controller' => 'ProductMaterialMachines', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Product Material Lot Numbers'), ['controller' => 'ProductMaterialLotNumbers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Material Lot Number'), ['controller' => 'ProductMaterialLotNumbers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="productMachineMaterials view large-9 medium-8 columns content">
    <h3><?= h($productMachineMaterial->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Product Material Machine') ?></th>
            <td><?= $productMachineMaterial->has('product_material_machine') ? $this->Html->link($productMachineMaterial->product_material_machine->id, ['controller' => 'ProductMaterialMachines', 'action' => 'view', $productMachineMaterial->product_material_machine->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Material Grade') ?></th>
            <td><?= h($productMachineMaterial->material_grade) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Material Maker') ?></th>
            <td><?= h($productMachineMaterial->material_maker) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($productMachineMaterial->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Material Number') ?></th>
            <td><?= $this->Number->format($productMachineMaterial->material_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mixing Ratio') ?></th>
            <td><?= $this->Number->format($productMachineMaterial->mixing_ratio) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Dry Temp') ?></th>
            <td><?= $this->Number->format($productMachineMaterial->dry_temp) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Dry Hour') ?></th>
            <td><?= $this->Number->format($productMachineMaterial->dry_hour) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Recycled Mixing Ratio') ?></th>
            <td><?= $this->Number->format($productMachineMaterial->recycled_mixing_ratio) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($productMachineMaterial->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($productMachineMaterial->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($productMachineMaterial->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($productMachineMaterial->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($productMachineMaterial->updated_at) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Product Material Lot Numbers') ?></h4>
        <?php if (!empty($productMachineMaterial->product_material_lot_numbers)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Product Machine Material Id') ?></th>
                <th scope="col"><?= __('Datetme') ?></th>
                <th scope="col"><?= __('Lot Number') ?></th>
                <th scope="col"><?= __('Staff Id') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($productMachineMaterial->product_material_lot_numbers as $productMaterialLotNumbers): ?>
            <tr>
                <td><?= h($productMaterialLotNumbers->id) ?></td>
                <td><?= h($productMaterialLotNumbers->product_machine_material_id) ?></td>
                <td><?= h($productMaterialLotNumbers->datetme) ?></td>
                <td><?= h($productMaterialLotNumbers->lot_number) ?></td>
                <td><?= h($productMaterialLotNumbers->staff_id) ?></td>
                <td><?= h($productMaterialLotNumbers->delete_flag) ?></td>
                <td><?= h($productMaterialLotNumbers->created_at) ?></td>
                <td><?= h($productMaterialLotNumbers->created_staff) ?></td>
                <td><?= h($productMaterialLotNumbers->updated_at) ?></td>
                <td><?= h($productMaterialLotNumbers->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ProductMaterialLotNumbers', 'action' => 'view', $productMaterialLotNumbers->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ProductMaterialLotNumbers', 'action' => 'edit', $productMaterialLotNumbers->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ProductMaterialLotNumbers', 'action' => 'delete', $productMaterialLotNumbers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productMaterialLotNumbers->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
