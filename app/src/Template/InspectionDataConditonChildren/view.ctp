<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionDataConditonChild $inspectionDataConditonChild
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Inspection Data Conditon Child'), ['action' => 'edit', $inspectionDataConditonChild->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Inspection Data Conditon Child'), ['action' => 'delete', $inspectionDataConditonChild->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionDataConditonChild->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Data Conditon Children'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Data Conditon Child'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Data Conditon Parents'), ['controller' => 'InspectionDataConditonParents', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Data Conditon Parent'), ['controller' => 'InspectionDataConditonParents', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Product Conditon Children'), ['controller' => 'ProductConditonChildren', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Conditon Child'), ['controller' => 'ProductConditonChildren', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="inspectionDataConditonChildren view large-9 medium-8 columns content">
    <h3><?= h($inspectionDataConditonChild->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Inspection Data Conditon Parent') ?></th>
            <td><?= $inspectionDataConditonChild->has('inspection_data_conditon_parent') ? $this->Html->link($inspectionDataConditonChild->inspection_data_conditon_parent->id, ['controller' => 'InspectionDataConditonParents', 'action' => 'view', $inspectionDataConditonChild->inspection_data_conditon_parent->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Product Conditon Child') ?></th>
            <td><?= $inspectionDataConditonChild->has('product_conditon_child') ? $this->Html->link($inspectionDataConditonChild->product_conditon_child->id, ['controller' => 'ProductConditonChildren', 'action' => 'view', $inspectionDataConditonChild->product_conditon_child->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonChild->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Inspection Temp 1') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonChild->inspection_temp_1) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Inspection Temp 2') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonChild->inspection_temp_2) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Inspection Temp 3') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonChild->inspection_temp_3) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Inspection Temp 4') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonChild->inspection_temp_4) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Inspection Temp 5') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonChild->inspection_temp_5) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Inspection Temp 6') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonChild->inspection_temp_6) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Inspection Temp 7') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonChild->inspection_temp_7) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Inspection Extrude Roatation') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonChild->inspection_extrude_roatation) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Inspection Extrusion Load') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonChild->inspection_extrusion_load) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Inspection Pickup Speed') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonChild->inspection_pickup_speed) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonChild->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonChild->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonChild->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($inspectionDataConditonChild->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($inspectionDataConditonChild->updated_at) ?></td>
        </tr>
    </table>
</div>
