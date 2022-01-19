<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductMaterialMachine $productMaterialMachine
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Product Material Machine'), ['action' => 'edit', $productMaterialMachine->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Product Material Machine'), ['action' => 'delete', $productMaterialMachine->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productMaterialMachine->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Product Material Machines'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Material Machine'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Product Material Parents'), ['controller' => 'ProductMaterialParents', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Material Parent'), ['controller' => 'ProductMaterialParents', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Product Machine Materials'), ['controller' => 'ProductMachineMaterials', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Machine Material'), ['controller' => 'ProductMachineMaterials', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="productMaterialMachines view large-9 medium-8 columns content">
    <h3><?= h($productMaterialMachine->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Product Material Parent') ?></th>
            <td><?= $productMaterialMachine->has('product_material_parent') ? $this->Html->link($productMaterialMachine->product_material_parent->id, ['controller' => 'ProductMaterialParents', 'action' => 'view', $productMaterialMachine->product_material_parent->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cylinder Name') ?></th>
            <td><?= h($productMaterialMachine->cylinder_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($productMaterialMachine->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cylinder Numer') ?></th>
            <td><?= $this->Number->format($productMaterialMachine->cylinder_numer) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($productMaterialMachine->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($productMaterialMachine->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($productMaterialMachine->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($productMaterialMachine->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($productMaterialMachine->updated_at) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Product Machine Materials') ?></h4>
        <?php if (!empty($productMaterialMachine->product_machine_materials)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Product Material Machine Id') ?></th>
                <th scope="col"><?= __('Material Number') ?></th>
                <th scope="col"><?= __('Material Grade') ?></th>
                <th scope="col"><?= __('Material Maker') ?></th>
                <th scope="col"><?= __('Mixing Ratio') ?></th>
                <th scope="col"><?= __('Dry Temp') ?></th>
                <th scope="col"><?= __('Dry Hour') ?></th>
                <th scope="col"><?= __('Recycled Mixing Ratio') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($productMaterialMachine->product_machine_materials as $productMachineMaterials): ?>
            <tr>
                <td><?= h($productMachineMaterials->id) ?></td>
                <td><?= h($productMachineMaterials->product_material_machine_id) ?></td>
                <td><?= h($productMachineMaterials->material_number) ?></td>
                <td><?= h($productMachineMaterials->material_grade) ?></td>
                <td><?= h($productMachineMaterials->material_maker) ?></td>
                <td><?= h($productMachineMaterials->mixing_ratio) ?></td>
                <td><?= h($productMachineMaterials->dry_temp) ?></td>
                <td><?= h($productMachineMaterials->dry_hour) ?></td>
                <td><?= h($productMachineMaterials->recycled_mixing_ratio) ?></td>
                <td><?= h($productMachineMaterials->delete_flag) ?></td>
                <td><?= h($productMachineMaterials->created_at) ?></td>
                <td><?= h($productMachineMaterials->created_staff) ?></td>
                <td><?= h($productMachineMaterials->updated_at) ?></td>
                <td><?= h($productMachineMaterials->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ProductMachineMaterials', 'action' => 'view', $productMachineMaterials->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ProductMachineMaterials', 'action' => 'edit', $productMachineMaterials->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ProductMachineMaterials', 'action' => 'delete', $productMachineMaterials->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productMachineMaterials->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
