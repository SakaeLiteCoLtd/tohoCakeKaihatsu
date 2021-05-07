<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Position $position
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Position'), ['action' => 'edit', $position->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Position'), ['action' => 'delete', $position->id], ['confirm' => __('Are you sure you want to delete # {0}?', $position->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Positions'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Position'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Offices'), ['controller' => 'Offices', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Office'), ['controller' => 'Offices', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Staffs'), ['controller' => 'Staffs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Staff'), ['controller' => 'Staffs', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="positions view large-9 medium-8 columns content">
    <h3><?= h($position->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Office') ?></th>
            <td><?= $position->has('office') ? $this->Html->link($position->office->name, ['controller' => 'Offices', 'action' => 'view', $position->office->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Position') ?></th>
            <td><?= h($position->position) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($position->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($position->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($position->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($position->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($position->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($position->updated_at) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Staffs') ?></h4>
        <?php if (!empty($position->staffs)): ?>
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
            <?php foreach ($position->staffs as $staffs): ?>
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
