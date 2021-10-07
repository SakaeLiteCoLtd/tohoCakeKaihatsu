<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Seikeiki[]|\Cake\Collection\CollectionInterface $seikeikis
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Seikeiki'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Factories'), ['controller' => 'Factories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Factory'), ['controller' => 'Factories', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="seikeikis index large-9 medium-8 columns content">
    <h3><?= __('Seikeikis') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('factory_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($seikeikis as $seikeiki): ?>
            <tr>
                <td><?= $this->Number->format($seikeiki->id) ?></td>
                <td><?= $seikeiki->has('factory') ? $this->Html->link($seikeiki->factory->name, ['controller' => 'Factories', 'action' => 'view', $seikeiki->factory->id]) : '' ?></td>
                <td><?= h($seikeiki->name) ?></td>
                <td><?= $this->Number->format($seikeiki->delete_flag) ?></td>
                <td><?= h($seikeiki->created_at) ?></td>
                <td><?= $this->Number->format($seikeiki->created_staff) ?></td>
                <td><?= h($seikeiki->updated_at) ?></td>
                <td><?= $this->Number->format($seikeiki->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $seikeiki->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $seikeiki->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $seikeiki->id], ['confirm' => __('Are you sure you want to delete # {0}?', $seikeiki->id)]) ?>
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
