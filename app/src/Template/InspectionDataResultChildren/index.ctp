<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionDataResultChild[]|\Cake\Collection\CollectionInterface $inspectionDataResultChildren
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Result Child'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Result Parents'), ['controller' => 'InspectionDataResultParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Result Parent'), ['controller' => 'InspectionDataResultParents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Children'), ['controller' => 'InspectionStandardSizeChildren', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Child'), ['controller' => 'InspectionStandardSizeChildren', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="inspectionDataResultChildren index large-9 medium-8 columns content">
    <h3><?= __('Inspection Data Result Children') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_data_result_parent_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_standard_size_child_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('result_size') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inspectionDataResultChildren as $inspectionDataResultChild): ?>
            <tr>
                <td><?= $this->Number->format($inspectionDataResultChild->id) ?></td>
                <td><?= $inspectionDataResultChild->has('inspection_data_result_parent') ? $this->Html->link($inspectionDataResultChild->inspection_data_result_parent->id, ['controller' => 'InspectionDataResultParents', 'action' => 'view', $inspectionDataResultChild->inspection_data_result_parent->id]) : '' ?></td>
                <td><?= $inspectionDataResultChild->has('inspection_standard_size_child') ? $this->Html->link($inspectionDataResultChild->inspection_standard_size_child->id, ['controller' => 'InspectionStandardSizeChildren', 'action' => 'view', $inspectionDataResultChild->inspection_standard_size_child->id]) : '' ?></td>
                <td><?= $this->Number->format($inspectionDataResultChild->result_size) ?></td>
                <td><?= $this->Number->format($inspectionDataResultChild->delete_flag) ?></td>
                <td><?= h($inspectionDataResultChild->created_at) ?></td>
                <td><?= $this->Number->format($inspectionDataResultChild->created_staff) ?></td>
                <td><?= h($inspectionDataResultChild->updated_at) ?></td>
                <td><?= $this->Number->format($inspectionDataResultChild->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $inspectionDataResultChild->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $inspectionDataResultChild->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $inspectionDataResultChild->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionDataResultChild->id)]) ?>
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
