<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductConditionParent $productConditionParent
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Product Condition Parent'), ['action' => 'edit', $productConditionParent->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Product Condition Parent'), ['action' => 'delete', $productConditionParent->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productConditionParent->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Product Condition Parents'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Condition Parent'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Product Conditon Children'), ['controller' => 'ProductConditonChildren', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Conditon Child'), ['controller' => 'ProductConditonChildren', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="productConditionParents view large-9 medium-8 columns content">
    <h3><?= h($productConditionParent->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Product') ?></th>
            <td><?= $productConditionParent->has('product') ? $this->Html->link($productConditionParent->product->name, ['controller' => 'Products', 'action' => 'view', $productConditionParent->product->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($productConditionParent->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Version') ?></th>
            <td><?= $this->Number->format($productConditionParent->version) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Active') ?></th>
            <td><?= $this->Number->format($productConditionParent->is_active) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($productConditionParent->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($productConditionParent->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($productConditionParent->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Start Datetime') ?></th>
            <td><?= h($productConditionParent->start_datetime) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Finish Datetime') ?></th>
            <td><?= h($productConditionParent->finish_datetime) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($productConditionParent->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($productConditionParent->updated_at) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Product Conditon Children') ?></h4>
        <?php if (!empty($productConditionParent->product_conditon_children)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Product Condition Parent Id') ?></th>
                <th scope="col"><?= __('Cylinder Number') ?></th>
                <th scope="col"><?= __('Cylinder Name') ?></th>
                <th scope="col"><?= __('Temp 1') ?></th>
                <th scope="col"><?= __('Temp 2') ?></th>
                <th scope="col"><?= __('Temp 3') ?></th>
                <th scope="col"><?= __('Temp 4') ?></th>
                <th scope="col"><?= __('Temp 5') ?></th>
                <th scope="col"><?= __('Temp 6') ?></th>
                <th scope="col"><?= __('Temp 7') ?></th>
                <th scope="col"><?= __('Extrude Roatation') ?></th>
                <th scope="col"><?= __('Extrusion Load') ?></th>
                <th scope="col"><?= __('Pickup Speed') ?></th>
                <th scope="col"><?= __('Screw Mesh') ?></th>
                <th scope="col"><?= __('Screw Number') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($productConditionParent->product_conditon_children as $productConditonChildren): ?>
            <tr>
                <td><?= h($productConditonChildren->id) ?></td>
                <td><?= h($productConditonChildren->product_condition_parent_id) ?></td>
                <td><?= h($productConditonChildren->cylinder_number) ?></td>
                <td><?= h($productConditonChildren->cylinder_name) ?></td>
                <td><?= h($productConditonChildren->temp_1) ?></td>
                <td><?= h($productConditonChildren->temp_2) ?></td>
                <td><?= h($productConditonChildren->temp_3) ?></td>
                <td><?= h($productConditonChildren->temp_4) ?></td>
                <td><?= h($productConditonChildren->temp_5) ?></td>
                <td><?= h($productConditonChildren->temp_6) ?></td>
                <td><?= h($productConditonChildren->temp_7) ?></td>
                <td><?= h($productConditonChildren->extrude_roatation) ?></td>
                <td><?= h($productConditonChildren->extrusion_load) ?></td>
                <td><?= h($productConditonChildren->pickup_speed) ?></td>
                <td><?= h($productConditonChildren->screw_mesh) ?></td>
                <td><?= h($productConditonChildren->screw_number) ?></td>
                <td><?= h($productConditonChildren->delete_flag) ?></td>
                <td><?= h($productConditonChildren->created_at) ?></td>
                <td><?= h($productConditonChildren->created_staff) ?></td>
                <td><?= h($productConditonChildren->updated_at) ?></td>
                <td><?= h($productConditonChildren->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ProductConditonChildren', 'action' => 'view', $productConditonChildren->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ProductConditonChildren', 'action' => 'edit', $productConditonChildren->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ProductConditonChildren', 'action' => 'delete', $productConditonChildren->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productConditonChildren->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
