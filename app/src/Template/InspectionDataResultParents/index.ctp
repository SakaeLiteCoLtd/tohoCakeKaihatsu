<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionDataResultParent[]|\Cake\Collection\CollectionInterface $inspectionDataResultParents
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Result Parent'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Conditon Parents'), ['controller' => 'InspectionDataConditonParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Conditon Parent'), ['controller' => 'InspectionDataConditonParents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Parents'), ['controller' => 'InspectionStandardSizeParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Parent'), ['controller' => 'InspectionStandardSizeParents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Staffs'), ['controller' => 'Staffs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Staff'), ['controller' => 'Staffs', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Result Children'), ['controller' => 'InspectionDataResultChildren', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Result Child'), ['controller' => 'InspectionDataResultChildren', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="inspectionDataResultParents index large-9 medium-8 columns content">
    <h3><?= __('Inspection Data Result Parents') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_data_conditon_parent_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_standard_size_parent_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_conditon_parent_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('lot_number') ?></th>
                <th scope="col"><?= $this->Paginator->sort('datetime') ?></th>
                <th scope="col"><?= $this->Paginator->sort('staff_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('appearance') ?></th>
                <th scope="col"><?= $this->Paginator->sort('result_weight') ?></th>
                <th scope="col"><?= $this->Paginator->sort('judge') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inspectionDataResultParents as $inspectionDataResultParent): ?>
            <tr>
                <td><?= $this->Number->format($inspectionDataResultParent->id) ?></td>
                <td><?= $inspectionDataResultParent->has('inspection_data_conditon_parent') ? $this->Html->link($inspectionDataResultParent->inspection_data_conditon_parent->id, ['controller' => 'InspectionDataConditonParents', 'action' => 'view', $inspectionDataResultParent->inspection_data_conditon_parent->id]) : '' ?></td>
                <td><?= $inspectionDataResultParent->has('inspection_standard_size_parent') ? $this->Html->link($inspectionDataResultParent->inspection_standard_size_parent->id, ['controller' => 'InspectionStandardSizeParents', 'action' => 'view', $inspectionDataResultParent->inspection_standard_size_parent->id]) : '' ?></td>
                <td><?= $this->Number->format($inspectionDataResultParent->product_conditon_parent_id) ?></td>
                <td><?= h($inspectionDataResultParent->lot_number) ?></td>
                <td><?= h($inspectionDataResultParent->datetime) ?></td>
                <td><?= $inspectionDataResultParent->has('staff') ? $this->Html->link($inspectionDataResultParent->staff->name, ['controller' => 'Staffs', 'action' => 'view', $inspectionDataResultParent->staff->id]) : '' ?></td>
                <td><?= $this->Number->format($inspectionDataResultParent->appearance) ?></td>
                <td><?= $this->Number->format($inspectionDataResultParent->result_weight) ?></td>
                <td><?= $this->Number->format($inspectionDataResultParent->judge) ?></td>
                <td><?= $this->Number->format($inspectionDataResultParent->delete_flag) ?></td>
                <td><?= h($inspectionDataResultParent->created_at) ?></td>
                <td><?= $this->Number->format($inspectionDataResultParent->created_staff) ?></td>
                <td><?= h($inspectionDataResultParent->updated_at) ?></td>
                <td><?= $this->Number->format($inspectionDataResultParent->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $inspectionDataResultParent->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $inspectionDataResultParent->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $inspectionDataResultParent->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionDataResultParent->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
