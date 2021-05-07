<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Staff $staff
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Staff'), ['action' => 'edit', $staff->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Staff'), ['action' => 'delete', $staff->id], ['confirm' => __('Are you sure you want to delete # {0}?', $staff->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Staffs'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Staff'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Offices'), ['controller' => 'Offices', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Office'), ['controller' => 'Offices', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Departments'), ['controller' => 'Departments', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Department'), ['controller' => 'Departments', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Occupations'), ['controller' => 'Occupations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Occupation'), ['controller' => 'Occupations', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Positions'), ['controller' => 'Positions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Position'), ['controller' => 'Positions', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Staff Abilities'), ['controller' => 'StaffAbilities', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Staff Ability'), ['controller' => 'StaffAbilities', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="staffs view large-9 medium-8 columns content">
    <h3><?= h($staff->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Office') ?></th>
            <td><?= $staff->has('office') ? $this->Html->link($staff->office->name, ['controller' => 'Offices', 'action' => 'view', $staff->office->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Department') ?></th>
            <td><?= $staff->has('department') ? $this->Html->link($staff->department->id, ['controller' => 'Departments', 'action' => 'view', $staff->department->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Occupation') ?></th>
            <td><?= $staff->has('occupation') ? $this->Html->link($staff->occupation->id, ['controller' => 'Occupations', 'action' => 'view', $staff->occupation->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Position') ?></th>
            <td><?= $staff->has('position') ? $this->Html->link($staff->position->id, ['controller' => 'Positions', 'action' => 'view', $staff->position->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($staff->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mail') ?></th>
            <td><?= h($staff->mail) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tel') ?></th>
            <td><?= h($staff->tel) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Address') ?></th>
            <td><?= h($staff->address) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($staff->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sex') ?></th>
            <td><?= $this->Number->format($staff->sex) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($staff->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($staff->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($staff->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Birth') ?></th>
            <td><?= h($staff->birth) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date Start') ?></th>
            <td><?= h($staff->date_start) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date Finish') ?></th>
            <td><?= h($staff->date_finish) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($staff->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($staff->updated_at) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Staff Abilities') ?></h4>
        <?php if (!empty($staff->staff_abilities)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Staff Id') ?></th>
                <th scope="col"><?= __('Menu Id') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($staff->staff_abilities as $staffAbilities): ?>
            <tr>
                <td><?= h($staffAbilities->id) ?></td>
                <td><?= h($staffAbilities->staff_id) ?></td>
                <td><?= h($staffAbilities->menu_id) ?></td>
                <td><?= h($staffAbilities->delete_flag) ?></td>
                <td><?= h($staffAbilities->created_at) ?></td>
                <td><?= h($staffAbilities->created_staff) ?></td>
                <td><?= h($staffAbilities->updated_at) ?></td>
                <td><?= h($staffAbilities->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'StaffAbilities', 'action' => 'view', $staffAbilities->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'StaffAbilities', 'action' => 'edit', $staffAbilities->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'StaffAbilities', 'action' => 'delete', $staffAbilities->id], ['confirm' => __('Are you sure you want to delete # {0}?', $staffAbilities->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Users') ?></h4>
        <?php if (!empty($staff->users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('User Code') ?></th>
                <th scope="col"><?= __('Password') ?></th>
                <th scope="col"><?= __('Staff Id') ?></th>
                <th scope="col"><?= __('Super User') ?></th>
                <th scope="col"><?= __('Group Name') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($staff->users as $users): ?>
            <tr>
                <td><?= h($users->id) ?></td>
                <td><?= h($users->user_code) ?></td>
                <td><?= h($users->password) ?></td>
                <td><?= h($users->staff_id) ?></td>
                <td><?= h($users->super_user) ?></td>
                <td><?= h($users->group_name) ?></td>
                <td><?= h($users->delete_flag) ?></td>
                <td><?= h($users->created_at) ?></td>
                <td><?= h($users->created_staff) ?></td>
                <td><?= h($users->updated_at) ?></td>
                <td><?= h($users->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $users->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
