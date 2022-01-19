<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductMaterialParent $productMaterialParent
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Product Material Parent'), ['action' => 'edit', $productMaterialParent->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Product Material Parent'), ['action' => 'delete', $productMaterialParent->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productMaterialParent->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Product Material Parents'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Material Parent'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Data Result Parents'), ['controller' => 'InspectionDataResultParents', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Data Result Parent'), ['controller' => 'InspectionDataResultParents', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Product Material Machines'), ['controller' => 'ProductMaterialMachines', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Material Machine'), ['controller' => 'ProductMaterialMachines', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="productMaterialParents view large-9 medium-8 columns content">
    <h3><?= h($productMaterialParent->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Product') ?></th>
            <td><?= $productMaterialParent->has('product') ? $this->Html->link($productMaterialParent->product->name, ['controller' => 'Products', 'action' => 'view', $productMaterialParent->product->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($productMaterialParent->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Material Id') ?></th>
            <td><?= $this->Number->format($productMaterialParent->material_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Version') ?></th>
            <td><?= $this->Number->format($productMaterialParent->version) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Active') ?></th>
            <td><?= $this->Number->format($productMaterialParent->is_active) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($productMaterialParent->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($productMaterialParent->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($productMaterialParent->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($productMaterialParent->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($productMaterialParent->updated_at) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Inspection Data Result Parents') ?></h4>
        <?php if (!empty($productMaterialParent->inspection_data_result_parents)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Inspection Standard Size Parent Id') ?></th>
                <th scope="col"><?= __('Product Conditon Parent Id') ?></th>
                <th scope="col"><?= __('Product Material Parent Id') ?></th>
                <th scope="col"><?= __('Datetime') ?></th>
                <th scope="col"><?= __('Staff Id') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($productMaterialParent->inspection_data_result_parents as $inspectionDataResultParents): ?>
            <tr>
                <td><?= h($inspectionDataResultParents->id) ?></td>
                <td><?= h($inspectionDataResultParents->inspection_standard_size_parent_id) ?></td>
                <td><?= h($inspectionDataResultParents->product_conditon_parent_id) ?></td>
                <td><?= h($inspectionDataResultParents->product_material_parent_id) ?></td>
                <td><?= h($inspectionDataResultParents->datetime) ?></td>
                <td><?= h($inspectionDataResultParents->staff_id) ?></td>
                <td><?= h($inspectionDataResultParents->delete_flag) ?></td>
                <td><?= h($inspectionDataResultParents->created_at) ?></td>
                <td><?= h($inspectionDataResultParents->created_staff) ?></td>
                <td><?= h($inspectionDataResultParents->updated_at) ?></td>
                <td><?= h($inspectionDataResultParents->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'InspectionDataResultParents', 'action' => 'view', $inspectionDataResultParents->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'InspectionDataResultParents', 'action' => 'edit', $inspectionDataResultParents->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'InspectionDataResultParents', 'action' => 'delete', $inspectionDataResultParents->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionDataResultParents->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Product Material Machines') ?></h4>
        <?php if (!empty($productMaterialParent->product_material_machines)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Product Material Parent Id') ?></th>
                <th scope="col"><?= __('Cylinder Numer') ?></th>
                <th scope="col"><?= __('Cylinder Name') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($productMaterialParent->product_material_machines as $productMaterialMachines): ?>
            <tr>
                <td><?= h($productMaterialMachines->id) ?></td>
                <td><?= h($productMaterialMachines->product_material_parent_id) ?></td>
                <td><?= h($productMaterialMachines->cylinder_numer) ?></td>
                <td><?= h($productMaterialMachines->cylinder_name) ?></td>
                <td><?= h($productMaterialMachines->delete_flag) ?></td>
                <td><?= h($productMaterialMachines->created_at) ?></td>
                <td><?= h($productMaterialMachines->created_staff) ?></td>
                <td><?= h($productMaterialMachines->updated_at) ?></td>
                <td><?= h($productMaterialMachines->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ProductMaterialMachines', 'action' => 'view', $productMaterialMachines->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ProductMaterialMachines', 'action' => 'edit', $productMaterialMachines->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ProductMaterialMachines', 'action' => 'delete', $productMaterialMachines->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productMaterialMachines->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
