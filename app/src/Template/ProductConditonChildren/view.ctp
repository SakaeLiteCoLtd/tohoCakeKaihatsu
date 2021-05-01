<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductConditonChild $productConditonChild
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Product Conditon Child'), ['action' => 'edit', $productConditonChild->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Product Conditon Child'), ['action' => 'delete', $productConditonChild->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productConditonChild->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Product Conditon Children'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Conditon Child'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Product Condition Parents'), ['controller' => 'ProductConditionParents', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Condition Parent'), ['controller' => 'ProductConditionParents', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="productConditonChildren view large-9 medium-8 columns content">
    <h3><?= h($productConditonChild->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Product Condition Parent') ?></th>
            <td><?= $productConditonChild->has('product_condition_parent') ? $this->Html->link($productConditonChild->product_condition_parent->id, ['controller' => 'ProductConditionParents', 'action' => 'view', $productConditonChild->product_condition_parent->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cylinder Name') ?></th>
            <td><?= h($productConditonChild->cylinder_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Screw Mesh') ?></th>
            <td><?= h($productConditonChild->screw_mesh) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Screw Number') ?></th>
            <td><?= h($productConditonChild->screw_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($productConditonChild->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cylinder Number') ?></th>
            <td><?= $this->Number->format($productConditonChild->cylinder_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Temp 1') ?></th>
            <td><?= $this->Number->format($productConditonChild->temp_1) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Temp 2') ?></th>
            <td><?= $this->Number->format($productConditonChild->temp_2) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Temp 3') ?></th>
            <td><?= $this->Number->format($productConditonChild->temp_3) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Temp 4') ?></th>
            <td><?= $this->Number->format($productConditonChild->temp_4) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Temp 5') ?></th>
            <td><?= $this->Number->format($productConditonChild->temp_5) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Temp 6') ?></th>
            <td><?= $this->Number->format($productConditonChild->temp_6) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Temp 7') ?></th>
            <td><?= $this->Number->format($productConditonChild->temp_7) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Extrude Roatation') ?></th>
            <td><?= $this->Number->format($productConditonChild->extrude_roatation) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Extrusion Load') ?></th>
            <td><?= $this->Number->format($productConditonChild->extrusion_load) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Pickup Speed') ?></th>
            <td><?= $this->Number->format($productConditonChild->pickup_speed) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($productConditonChild->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($productConditonChild->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($productConditonChild->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($productConditonChild->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($productConditonChild->updated_at) ?></td>
        </tr>
    </table>
</div>
