<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DailyReport[]|\Cake\Collection\CollectionInterface $dailyReports
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Daily Report'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Result Parents'), ['controller' => 'InspectionDataResultParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Result Parent'), ['controller' => 'InspectionDataResultParents', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="dailyReports index large-9 medium-8 columns content">
    <h3><?= __('Daily Reports') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('machine_num') ?></th>
                <th scope="col"><?= $this->Paginator->sort('start_datetime') ?></th>
                <th scope="col"><?= $this->Paginator->sort('finish_datetime') ?></th>
                <th scope="col"><?= $this->Paginator->sort('amount') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sum_weight') ?></th>
                <th scope="col"><?= $this->Paginator->sort('total_loss_weight') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dailyReports as $dailyReport): ?>
            <tr>
                <td><?= $this->Number->format($dailyReport->id) ?></td>
                <td><?= $dailyReport->has('product') ? $this->Html->link($dailyReport->product->name, ['controller' => 'Products', 'action' => 'view', $dailyReport->product->id]) : '' ?></td>
                <td><?= $this->Number->format($dailyReport->machine_num) ?></td>
                <td><?= h($dailyReport->start_datetime) ?></td>
                <td><?= h($dailyReport->finish_datetime) ?></td>
                <td><?= $this->Number->format($dailyReport->amount) ?></td>
                <td><?= $this->Number->format($dailyReport->sum_weight) ?></td>
                <td><?= $this->Number->format($dailyReport->total_loss_weight) ?></td>
                <td><?= $this->Number->format($dailyReport->delete_flag) ?></td>
                <td><?= h($dailyReport->created_at) ?></td>
                <td><?= $this->Number->format($dailyReport->created_staff) ?></td>
                <td><?= h($dailyReport->updated_at) ?></td>
                <td><?= $this->Number->format($dailyReport->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $dailyReport->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $dailyReport->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $dailyReport->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dailyReport->id)]) ?>
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
