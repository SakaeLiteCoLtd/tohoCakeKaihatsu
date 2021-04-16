<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Occupation[]|\Cake\Collection\CollectionInterface $occupations
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Occupation'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Offices'), ['controller' => 'Offices', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Office'), ['controller' => 'Offices', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Staffs'), ['controller' => 'Staffs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Staff'), ['controller' => 'Staffs', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="occupations index large-9 medium-8 columns content">
    <h3><?= __('Occupations') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('office_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('occupation') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($occupations as $occupation): ?>
            <tr>
                <td><?= $this->Number->format($occupation->id) ?></td>
                <td><?= $occupation->has('office') ? $this->Html->link($occupation->office->name, ['controller' => 'Offices', 'action' => 'view', $occupation->office->id]) : '' ?></td>
                <td><?= h($occupation->occupation) ?></td>
                <td><?= $this->Number->format($occupation->delete_flag) ?></td>
                <td><?= h($occupation->created_at) ?></td>
                <td><?= $this->Number->format($occupation->created_staff) ?></td>
                <td><?= h($occupation->updated_at) ?></td>
                <td><?= $this->Number->format($occupation->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $occupation->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $occupation->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $occupation->id], ['confirm' => __('Are you sure you want to delete # {0}?', $occupation->id)]) ?>
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
