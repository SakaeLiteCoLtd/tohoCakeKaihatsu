<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionStandardSizeParent[]|\Cake\Collection\CollectionInterface $inspectionStandardSizeParents
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Parent'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Result Parents'), ['controller' => 'InspectionDataResultParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Result Parent'), ['controller' => 'InspectionDataResultParents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Children'), ['controller' => 'InspectionStandardSizeChildren', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Child'), ['controller' => 'InspectionStandardSizeChildren', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="inspectionStandardSizeParents index large-9 medium-8 columns content">
    <h3><?= __('Inspection Standard Size Parents') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('image_file_name_dir') ?></th>
                <th scope="col"><?= $this->Paginator->sort('version') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_active') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inspectionStandardSizeParents as $inspectionStandardSizeParent): ?>
            <tr>
                <td><?= $this->Number->format($inspectionStandardSizeParent->id) ?></td>
                <td><?= $inspectionStandardSizeParent->has('product') ? $this->Html->link($inspectionStandardSizeParent->product->name, ['controller' => 'Products', 'action' => 'view', $inspectionStandardSizeParent->product->id]) : '' ?></td>
                <td><?= h($inspectionStandardSizeParent->image_file_name_dir) ?></td>
                <td><?= $this->Number->format($inspectionStandardSizeParent->version) ?></td>
                <td><?= $this->Number->format($inspectionStandardSizeParent->is_active) ?></td>
                <td><?= $this->Number->format($inspectionStandardSizeParent->delete_flag) ?></td>
                <td><?= h($inspectionStandardSizeParent->created_at) ?></td>
                <td><?= $this->Number->format($inspectionStandardSizeParent->created_staff) ?></td>
                <td><?= h($inspectionStandardSizeParent->updated_at) ?></td>
                <td><?= $this->Number->format($inspectionStandardSizeParent->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $inspectionStandardSizeParent->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $inspectionStandardSizeParent->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $inspectionStandardSizeParent->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionStandardSizeParent->id)]) ?>
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
