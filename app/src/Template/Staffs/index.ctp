<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Staff[]|\Cake\Collection\CollectionInterface $staffs
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Staff'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Offices'), ['controller' => 'Offices', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Office'), ['controller' => 'Offices', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Departments'), ['controller' => 'Departments', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Department'), ['controller' => 'Departments', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Occupations'), ['controller' => 'Occupations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Occupation'), ['controller' => 'Occupations', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Positions'), ['controller' => 'Positions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Position'), ['controller' => 'Positions', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Staff Abilities'), ['controller' => 'StaffAbilities', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Staff Ability'), ['controller' => 'StaffAbilities', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="staffs index large-9 medium-8 columns content">
    <h3><?= __('Staffs') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('office_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('department_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('occupation_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('position_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sex') ?></th>
                <th scope="col"><?= $this->Paginator->sort('mail') ?></th>
                <th scope="col"><?= $this->Paginator->sort('tel') ?></th>
                <th scope="col"><?= $this->Paginator->sort('address') ?></th>
                <th scope="col"><?= $this->Paginator->sort('birth') ?></th>
                <th scope="col"><?= $this->Paginator->sort('date_start') ?></th>
                <th scope="col"><?= $this->Paginator->sort('date_finish') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($staffs as $staff): ?>
            <tr>
                <td><?= $this->Number->format($staff->id) ?></td>
                <td><?= $staff->has('office') ? $this->Html->link($staff->office->name, ['controller' => 'Offices', 'action' => 'view', $staff->office->id]) : '' ?></td>
                <td><?= $staff->has('department') ? $this->Html->link($staff->department->id, ['controller' => 'Departments', 'action' => 'view', $staff->department->id]) : '' ?></td>
                <td><?= $staff->has('occupation') ? $this->Html->link($staff->occupation->id, ['controller' => 'Occupations', 'action' => 'view', $staff->occupation->id]) : '' ?></td>
                <td><?= $staff->has('position') ? $this->Html->link($staff->position->id, ['controller' => 'Positions', 'action' => 'view', $staff->position->id]) : '' ?></td>
                <td><?= h($staff->name) ?></td>
                <td><?= $this->Number->format($staff->sex) ?></td>
                <td><?= h($staff->mail) ?></td>
                <td><?= h($staff->tel) ?></td>
                <td><?= h($staff->address) ?></td>
                <td><?= h($staff->birth) ?></td>
                <td><?= h($staff->date_start) ?></td>
                <td><?= h($staff->date_finish) ?></td>
                <td><?= $this->Number->format($staff->delete_flag) ?></td>
                <td><?= h($staff->created_at) ?></td>
                <td><?= $this->Number->format($staff->created_staff) ?></td>
                <td><?= h($staff->updated_at) ?></td>
                <td><?= $this->Number->format($staff->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $staff->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $staff->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $staff->id], ['confirm' => __('Are you sure you want to delete # {0}?', $staff->id)]) ?>
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
