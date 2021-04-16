<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\StaffAbility[]|\Cake\Collection\CollectionInterface $staffAbilities
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Staff Ability'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Staffs'), ['controller' => 'Staffs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Staff'), ['controller' => 'Staffs', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Menus'), ['controller' => 'Menus', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Menu'), ['controller' => 'Menus', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="staffAbilities index large-9 medium-8 columns content">
    <h3><?= __('Staff Abilities') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('staff_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('menu_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($staffAbilities as $staffAbility): ?>
            <tr>
                <td><?= $this->Number->format($staffAbility->id) ?></td>
                <td><?= $staffAbility->has('staff') ? $this->Html->link($staffAbility->staff->name, ['controller' => 'Staffs', 'action' => 'view', $staffAbility->staff->id]) : '' ?></td>
                <td><?= $staffAbility->has('menu') ? $this->Html->link($staffAbility->menu->id, ['controller' => 'Menus', 'action' => 'view', $staffAbility->menu->id]) : '' ?></td>
                <td><?= $this->Number->format($staffAbility->delete_flag) ?></td>
                <td><?= h($staffAbility->created_at) ?></td>
                <td><?= $this->Number->format($staffAbility->created_staff) ?></td>
                <td><?= h($staffAbility->updated_at) ?></td>
                <td><?= $this->Number->format($staffAbility->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $staffAbility->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $staffAbility->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $staffAbility->id], ['confirm' => __('Are you sure you want to delete # {0}?', $staffAbility->id)]) ?>
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
