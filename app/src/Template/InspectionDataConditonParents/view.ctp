<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionDataConditonParent $inspectionDataConditonParent
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Inspection Data Conditon Parent'), ['action' => 'edit', $inspectionDataConditonParent->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Inspection Data Conditon Parent'), ['action' => 'delete', $inspectionDataConditonParent->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionDataConditonParent->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Data Conditon Parents'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Data Conditon Parent'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Data Conditon Children'), ['controller' => 'InspectionDataConditonChildren', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Data Conditon Child'), ['controller' => 'InspectionDataConditonChildren', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="inspectionDataConditonParents view large-9 medium-8 columns content">
    <h3><?= h($inspectionDataConditonParent->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonParent->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonParent->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonParent->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($inspectionDataConditonParent->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Datetime') ?></th>
            <td><?= h($inspectionDataConditonParent->datetime) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($inspectionDataConditonParent->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($inspectionDataConditonParent->updated_at) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Inspection Data Conditon Children') ?></h4>
        <?php if (!empty($inspectionDataConditonParent->inspection_data_conditon_children)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Inspection Data Conditon Parent Id') ?></th>
                <th scope="col"><?= __('Product Conditon Child Id') ?></th>
                <th scope="col"><?= __('Inspection Temp 1') ?></th>
                <th scope="col"><?= __('Inspection Temp 2') ?></th>
                <th scope="col"><?= __('Inspection Temp 3') ?></th>
                <th scope="col"><?= __('Inspection Temp 4') ?></th>
                <th scope="col"><?= __('Inspection Temp 5') ?></th>
                <th scope="col"><?= __('Inspection Temp 6') ?></th>
                <th scope="col"><?= __('Inspection Temp 7') ?></th>
                <th scope="col"><?= __('Inspection Extrude Roatation') ?></th>
                <th scope="col"><?= __('Inspection Extrusion Load') ?></th>
                <th scope="col"><?= __('Inspection Pickup Speed') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($inspectionDataConditonParent->inspection_data_conditon_children as $inspectionDataConditonChildren): ?>
            <tr>
                <td><?= h($inspectionDataConditonChildren->id) ?></td>
                <td><?= h($inspectionDataConditonChildren->inspection_data_conditon_parent_id) ?></td>
                <td><?= h($inspectionDataConditonChildren->product_conditon_child_id) ?></td>
                <td><?= h($inspectionDataConditonChildren->inspection_temp_1) ?></td>
                <td><?= h($inspectionDataConditonChildren->inspection_temp_2) ?></td>
                <td><?= h($inspectionDataConditonChildren->inspection_temp_3) ?></td>
                <td><?= h($inspectionDataConditonChildren->inspection_temp_4) ?></td>
                <td><?= h($inspectionDataConditonChildren->inspection_temp_5) ?></td>
                <td><?= h($inspectionDataConditonChildren->inspection_temp_6) ?></td>
                <td><?= h($inspectionDataConditonChildren->inspection_temp_7) ?></td>
                <td><?= h($inspectionDataConditonChildren->inspection_extrude_roatation) ?></td>
                <td><?= h($inspectionDataConditonChildren->inspection_extrusion_load) ?></td>
                <td><?= h($inspectionDataConditonChildren->inspection_pickup_speed) ?></td>
                <td><?= h($inspectionDataConditonChildren->delete_flag) ?></td>
                <td><?= h($inspectionDataConditonChildren->created_at) ?></td>
                <td><?= h($inspectionDataConditonChildren->created_staff) ?></td>
                <td><?= h($inspectionDataConditonChildren->updated_at) ?></td>
                <td><?= h($inspectionDataConditonChildren->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'InspectionDataConditonChildren', 'action' => 'view', $inspectionDataConditonChildren->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'InspectionDataConditonChildren', 'action' => 'edit', $inspectionDataConditonChildren->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'InspectionDataConditonChildren', 'action' => 'delete', $inspectionDataConditonChildren->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionDataConditonChildren->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
