<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Office $office
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Office'), ['action' => 'edit', $office->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Office'), ['action' => 'delete', $office->id], ['confirm' => __('Are you sure you want to delete # {0}?', $office->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Offices'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Office'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Departments'), ['controller' => 'Departments', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Department'), ['controller' => 'Departments', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Occupations'), ['controller' => 'Occupations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Occupation'), ['controller' => 'Occupations', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Positions'), ['controller' => 'Positions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Position'), ['controller' => 'Positions', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Staffs'), ['controller' => 'Staffs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Staff'), ['controller' => 'Staffs', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="offices view large-9 medium-8 columns content">
    <h3><?= h($office->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($office->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $office->has('company') ? $this->Html->link($office->company->name, ['controller' => 'Companies', 'action' => 'view', $office->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($office->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($office->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($office->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($office->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($office->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($office->updated_at) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Departments') ?></h4>
        <?php if (!empty($office->departments)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Office Id') ?></th>
                <th scope="col"><?= __('Department') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($office->departments as $departments): ?>
            <tr>
                <td><?= h($departments->id) ?></td>
                <td><?= h($departments->office_id) ?></td>
                <td><?= h($departments->department) ?></td>
                <td><?= h($departments->delete_flag) ?></td>
                <td><?= h($departments->created_at) ?></td>
                <td><?= h($departments->created_staff) ?></td>
                <td><?= h($departments->updated_at) ?></td>
                <td><?= h($departments->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Departments', 'action' => 'view', $departments->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Departments', 'action' => 'edit', $departments->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Departments', 'action' => 'delete', $departments->id], ['confirm' => __('Are you sure you want to delete # {0}?', $departments->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Occupations') ?></h4>
        <?php if (!empty($office->occupations)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Office Id') ?></th>
                <th scope="col"><?= __('Occupation') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($office->occupations as $occupations): ?>
            <tr>
                <td><?= h($occupations->id) ?></td>
                <td><?= h($occupations->office_id) ?></td>
                <td><?= h($occupations->occupation) ?></td>
                <td><?= h($occupations->delete_flag) ?></td>
                <td><?= h($occupations->created_at) ?></td>
                <td><?= h($occupations->created_staff) ?></td>
                <td><?= h($occupations->updated_at) ?></td>
                <td><?= h($occupations->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Occupations', 'action' => 'view', $occupations->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Occupations', 'action' => 'edit', $occupations->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Occupations', 'action' => 'delete', $occupations->id], ['confirm' => __('Are you sure you want to delete # {0}?', $occupations->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Positions') ?></h4>
        <?php if (!empty($office->positions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Office Id') ?></th>
                <th scope="col"><?= __('Position') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($office->positions as $positions): ?>
            <tr>
                <td><?= h($positions->id) ?></td>
                <td><?= h($positions->office_id) ?></td>
                <td><?= h($positions->position) ?></td>
                <td><?= h($positions->delete_flag) ?></td>
                <td><?= h($positions->created_at) ?></td>
                <td><?= h($positions->created_staff) ?></td>
                <td><?= h($positions->updated_at) ?></td>
                <td><?= h($positions->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Positions', 'action' => 'view', $positions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Positions', 'action' => 'edit', $positions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Positions', 'action' => 'delete', $positions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $positions->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Staffs') ?></h4>
        <?php if (!empty($office->staffs)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Office Id') ?></th>
                <th scope="col"><?= __('Department Id') ?></th>
                <th scope="col"><?= __('Occupation Id') ?></th>
                <th scope="col"><?= __('Position Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Sex') ?></th>
                <th scope="col"><?= __('Mail') ?></th>
                <th scope="col"><?= __('Tel') ?></th>
                <th scope="col"><?= __('Address') ?></th>
                <th scope="col"><?= __('Birth') ?></th>
                <th scope="col"><?= __('Date Start') ?></th>
                <th scope="col"><?= __('Date Finish') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($office->staffs as $staffs): ?>
            <tr>
                <td><?= h($staffs->id) ?></td>
                <td><?= h($staffs->office_id) ?></td>
                <td><?= h($staffs->department_id) ?></td>
                <td><?= h($staffs->occupation_id) ?></td>
                <td><?= h($staffs->position_id) ?></td>
                <td><?= h($staffs->name) ?></td>
                <td><?= h($staffs->sex) ?></td>
                <td><?= h($staffs->mail) ?></td>
                <td><?= h($staffs->tel) ?></td>
                <td><?= h($staffs->address) ?></td>
                <td><?= h($staffs->birth) ?></td>
                <td><?= h($staffs->date_start) ?></td>
                <td><?= h($staffs->date_finish) ?></td>
                <td><?= h($staffs->delete_flag) ?></td>
                <td><?= h($staffs->created_at) ?></td>
                <td><?= h($staffs->created_staff) ?></td>
                <td><?= h($staffs->updated_at) ?></td>
                <td><?= h($staffs->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Staffs', 'action' => 'view', $staffs->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Staffs', 'action' => 'edit', $staffs->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Staffs', 'action' => 'delete', $staffs->id], ['confirm' => __('Are you sure you want to delete # {0}?', $staffs->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
