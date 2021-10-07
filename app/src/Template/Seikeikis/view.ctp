<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Seikeiki $seikeiki
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Seikeiki'), ['action' => 'edit', $seikeiki->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Seikeiki'), ['action' => 'delete', $seikeiki->id], ['confirm' => __('Are you sure you want to delete # {0}?', $seikeiki->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Seikeikis'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Seikeiki'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Factories'), ['controller' => 'Factories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Factory'), ['controller' => 'Factories', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="seikeikis view large-9 medium-8 columns content">
    <h3><?= h($seikeiki->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Factory') ?></th>
            <td><?= $seikeiki->has('factory') ? $this->Html->link($seikeiki->factory->name, ['controller' => 'Factories', 'action' => 'view', $seikeiki->factory->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($seikeiki->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($seikeiki->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($seikeiki->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($seikeiki->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($seikeiki->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($seikeiki->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($seikeiki->updated_at) ?></td>
        </tr>
    </table>
</div>
