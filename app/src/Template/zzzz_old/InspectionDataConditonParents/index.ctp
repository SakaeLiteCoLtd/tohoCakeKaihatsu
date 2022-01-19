<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionDataConditonParent[]|\Cake\Collection\CollectionInterface $inspectionDataConditonParents
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Conditon Parent'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Conditon Children'), ['controller' => 'InspectionDataConditonChildren', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Conditon Child'), ['controller' => 'InspectionDataConditonChildren', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="inspectionDataConditonParents index large-9 medium-8 columns content">
    <h3><?= __('Inspection Data Conditon Parents') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('datetime') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inspectionDataConditonParents as $inspectionDataConditonParent): ?>
            <tr>
                <td><?= $this->Number->format($inspectionDataConditonParent->id) ?></td>
                <td><?= h($inspectionDataConditonParent->datetime) ?></td>
                <td><?= $this->Number->format($inspectionDataConditonParent->delete_flag) ?></td>
                <td><?= h($inspectionDataConditonParent->created_at) ?></td>
                <td><?= $this->Number->format($inspectionDataConditonParent->created_staff) ?></td>
                <td><?= h($inspectionDataConditonParent->updated_at) ?></td>
                <td><?= $this->Number->format($inspectionDataConditonParent->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $inspectionDataConditonParent->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $inspectionDataConditonParent->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $inspectionDataConditonParent->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionDataConditonParent->id)]) ?>
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
