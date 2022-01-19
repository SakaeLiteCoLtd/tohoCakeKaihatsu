<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionDataResultParent $inspectionDataResultParent
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Inspection Data Result Parent'), ['action' => 'edit', $inspectionDataResultParent->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Inspection Data Result Parent'), ['action' => 'delete', $inspectionDataResultParent->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionDataResultParent->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Data Result Parents'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Data Result Parent'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Data Conditon Parents'), ['controller' => 'InspectionDataConditonParents', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Data Conditon Parent'), ['controller' => 'InspectionDataConditonParents', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Parents'), ['controller' => 'InspectionStandardSizeParents', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Parent'), ['controller' => 'InspectionStandardSizeParents', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Staffs'), ['controller' => 'Staffs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Staff'), ['controller' => 'Staffs', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Data Result Children'), ['controller' => 'InspectionDataResultChildren', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Data Result Child'), ['controller' => 'InspectionDataResultChildren', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="inspectionDataResultParents view large-9 medium-8 columns content">
    <h3><?= h($inspectionDataResultParent->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Inspection Data Conditon Parent') ?></th>
            <td><?= $inspectionDataResultParent->has('inspection_data_conditon_parent') ? $this->Html->link($inspectionDataResultParent->inspection_data_conditon_parent->id, ['controller' => 'InspectionDataConditonParents', 'action' => 'view', $inspectionDataResultParent->inspection_data_conditon_parent->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Inspection Standard Size Parent') ?></th>
            <td><?= $inspectionDataResultParent->has('inspection_standard_size_parent') ? $this->Html->link($inspectionDataResultParent->inspection_standard_size_parent->id, ['controller' => 'InspectionStandardSizeParents', 'action' => 'view', $inspectionDataResultParent->inspection_standard_size_parent->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lot Number') ?></th>
            <td><?= h($inspectionDataResultParent->lot_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Staff') ?></th>
            <td><?= $inspectionDataResultParent->has('staff') ? $this->Html->link($inspectionDataResultParent->staff->name, ['controller' => 'Staffs', 'action' => 'view', $inspectionDataResultParent->staff->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($inspectionDataResultParent->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Product Conditon Parent Id') ?></th>
            <td><?= $this->Number->format($inspectionDataResultParent->product_conditon_parent_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Appearance') ?></th>
            <td><?= $this->Number->format($inspectionDataResultParent->appearance) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Result Weight') ?></th>
            <td><?= $this->Number->format($inspectionDataResultParent->result_weight) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Judge') ?></th>
            <td><?= $this->Number->format($inspectionDataResultParent->judge) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($inspectionDataResultParent->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($inspectionDataResultParent->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($inspectionDataResultParent->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Datetime') ?></th>
            <td><?= h($inspectionDataResultParent->datetime) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($inspectionDataResultParent->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($inspectionDataResultParent->updated_at) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Inspection Data Result Children') ?></h4>
        <?php if (!empty($inspectionDataResultParent->inspection_data_result_children)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Inspection Data Result Parent Id') ?></th>
                <th scope="col"><?= __('Inspection Standard Size Child Id') ?></th>
                <th scope="col"><?= __('Result Size') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($inspectionDataResultParent->inspection_data_result_children as $inspectionDataResultChildren): ?>
            <tr>
                <td><?= h($inspectionDataResultChildren->id) ?></td>
                <td><?= h($inspectionDataResultChildren->inspection_data_result_parent_id) ?></td>
                <td><?= h($inspectionDataResultChildren->inspection_standard_size_child_id) ?></td>
                <td><?= h($inspectionDataResultChildren->result_size) ?></td>
                <td><?= h($inspectionDataResultChildren->delete_flag) ?></td>
                <td><?= h($inspectionDataResultChildren->created_at) ?></td>
                <td><?= h($inspectionDataResultChildren->created_staff) ?></td>
                <td><?= h($inspectionDataResultChildren->updated_at) ?></td>
                <td><?= h($inspectionDataResultChildren->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'InspectionDataResultChildren', 'action' => 'view', $inspectionDataResultChildren->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'InspectionDataResultChildren', 'action' => 'edit', $inspectionDataResultChildren->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'InspectionDataResultChildren', 'action' => 'delete', $inspectionDataResultChildren->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionDataResultChildren->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
