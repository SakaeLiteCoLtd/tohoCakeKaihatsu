<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionDataResultChild $inspectionDataResultChild
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Inspection Data Result Child'), ['action' => 'edit', $inspectionDataResultChild->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Inspection Data Result Child'), ['action' => 'delete', $inspectionDataResultChild->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionDataResultChild->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Data Result Children'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Data Result Child'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Data Result Parents'), ['controller' => 'InspectionDataResultParents', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Data Result Parent'), ['controller' => 'InspectionDataResultParents', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Children'), ['controller' => 'InspectionStandardSizeChildren', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Child'), ['controller' => 'InspectionStandardSizeChildren', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="inspectionDataResultChildren view large-9 medium-8 columns content">
    <h3><?= h($inspectionDataResultChild->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Inspection Data Result Parent') ?></th>
            <td><?= $inspectionDataResultChild->has('inspection_data_result_parent') ? $this->Html->link($inspectionDataResultChild->inspection_data_result_parent->id, ['controller' => 'InspectionDataResultParents', 'action' => 'view', $inspectionDataResultChild->inspection_data_result_parent->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Inspection Standard Size Child') ?></th>
            <td><?= $inspectionDataResultChild->has('inspection_standard_size_child') ? $this->Html->link($inspectionDataResultChild->inspection_standard_size_child->id, ['controller' => 'InspectionStandardSizeChildren', 'action' => 'view', $inspectionDataResultChild->inspection_standard_size_child->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($inspectionDataResultChild->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Result Size') ?></th>
            <td><?= $this->Number->format($inspectionDataResultChild->result_size) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($inspectionDataResultChild->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($inspectionDataResultChild->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($inspectionDataResultChild->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($inspectionDataResultChild->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($inspectionDataResultChild->updated_at) ?></td>
        </tr>
    </table>
</div>
