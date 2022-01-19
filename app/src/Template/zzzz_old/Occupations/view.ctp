<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Occupation $occupation
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Occupation'), ['action' => 'edit', $occupation->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Occupation'), ['action' => 'delete', $occupation->id], ['confirm' => __('Are you sure you want to delete # {0}?', $occupation->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Occupations'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Occupation'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Offices'), ['controller' => 'Offices', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Office'), ['controller' => 'Offices', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Staffs'), ['controller' => 'Staffs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Staff'), ['controller' => 'Staffs', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="occupations view large-9 medium-8 columns content">
    <h3><?= h($occupation->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Office') ?></th>
            <td><?= $occupation->has('office') ? $this->Html->link($occupation->office->name, ['controller' => 'Offices', 'action' => 'view', $occupation->office->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Occupation') ?></th>
            <td><?= h($occupation->occupation) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($occupation->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($occupation->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($occupation->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($occupation->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($occupation->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($occupation->updated_at) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Staffs') ?></h4>
        <?php if (!empty($occupation->staffs)): ?>
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
            <?php foreach ($occupation->staffs as $staffs): ?>
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
