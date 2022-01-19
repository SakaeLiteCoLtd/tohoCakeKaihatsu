<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionStandardSizeChild[]|\Cake\Collection\CollectionInterface $inspectionStandardSizeChildren
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Child'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Parents'), ['controller' => 'InspectionStandardSizeParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Parent'), ['controller' => 'InspectionStandardSizeParents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Result Children'), ['controller' => 'InspectionDataResultChildren', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Result Child'), ['controller' => 'InspectionDataResultChildren', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="inspectionStandardSizeChildren index large-9 medium-8 columns content">
    <h3><?= __('Inspection Standard Size Children') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_standard_size_parent_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('size_number') ?></th>
                <th scope="col"><?= $this->Paginator->sort('size_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('size') ?></th>
                <th scope="col"><?= $this->Paginator->sort('upper_limit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('lower_limit') ?></th>
                <th scope="col"><?= $this->Paginator->sort('measuring_instrument') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inspectionStandardSizeChildren as $inspectionStandardSizeChild): ?>
            <tr>
                <td><?= $this->Number->format($inspectionStandardSizeChild->id) ?></td>
                <td><?= $inspectionStandardSizeChild->has('inspection_standard_size_parent') ? $this->Html->link($inspectionStandardSizeChild->inspection_standard_size_parent->id, ['controller' => 'InspectionStandardSizeParents', 'action' => 'view', $inspectionStandardSizeChild->inspection_standard_size_parent->id]) : '' ?></td>
                <td><?= $this->Number->format($inspectionStandardSizeChild->size_number) ?></td>
                <td><?= h($inspectionStandardSizeChild->size_name) ?></td>
                <td><?= $this->Number->format($inspectionStandardSizeChild->size) ?></td>
                <td><?= $this->Number->format($inspectionStandardSizeChild->upper_limit) ?></td>
                <td><?= $this->Number->format($inspectionStandardSizeChild->lower_limit) ?></td>
                <td><?= h($inspectionStandardSizeChild->measuring_instrument) ?></td>
                <td><?= $this->Number->format($inspectionStandardSizeChild->delete_flag) ?></td>
                <td><?= h($inspectionStandardSizeChild->created_at) ?></td>
                <td><?= $this->Number->format($inspectionStandardSizeChild->created_staff) ?></td>
                <td><?= h($inspectionStandardSizeChild->updated_at) ?></td>
                <td><?= $this->Number->format($inspectionStandardSizeChild->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $inspectionStandardSizeChild->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $inspectionStandardSizeChild->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $inspectionStandardSizeChild->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionStandardSizeChild->id)]) ?>
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
