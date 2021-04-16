<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\StaffAbility $staffAbility
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Staff Ability'), ['action' => 'edit', $staffAbility->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Staff Ability'), ['action' => 'delete', $staffAbility->id], ['confirm' => __('Are you sure you want to delete # {0}?', $staffAbility->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Staff Abilities'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Staff Ability'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Staffs'), ['controller' => 'Staffs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Staff'), ['controller' => 'Staffs', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Menus'), ['controller' => 'Menus', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Menu'), ['controller' => 'Menus', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="staffAbilities view large-9 medium-8 columns content">
    <h3><?= h($staffAbility->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Staff') ?></th>
            <td><?= $staffAbility->has('staff') ? $this->Html->link($staffAbility->staff->name, ['controller' => 'Staffs', 'action' => 'view', $staffAbility->staff->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Menu') ?></th>
            <td><?= $staffAbility->has('menu') ? $this->Html->link($staffAbility->menu->id, ['controller' => 'Menus', 'action' => 'view', $staffAbility->menu->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($staffAbility->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($staffAbility->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($staffAbility->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($staffAbility->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($staffAbility->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($staffAbility->updated_at) ?></td>
        </tr>
    </table>
</div>
