<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ShotWork $shotWork
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Shot Work'), ['action' => 'edit', $shotWork->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Shot Work'), ['action' => 'delete', $shotWork->id], ['confirm' => __('Are you sure you want to delete # {0}?', $shotWork->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Shot Works'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Shot Work'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Factories'), ['controller' => 'Factories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Factory'), ['controller' => 'Factories', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Product Condition Parents'), ['controller' => 'ProductConditionParents', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Condition Parent'), ['controller' => 'ProductConditionParents', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="shotWorks view large-9 medium-8 columns content">
    <h3><?= h($shotWork->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Factory') ?></th>
            <td><?= $shotWork->has('factory') ? $this->Html->link($shotWork->factory->name, ['controller' => 'Factories', 'action' => 'view', $shotWork->factory->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Product Condition Parent') ?></th>
            <td><?= $shotWork->has('product_condition_parent') ? $this->Html->link($shotWork->product_condition_parent->id, ['controller' => 'ProductConditionParents', 'action' => 'view', $shotWork->product_condition_parent->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($shotWork->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($shotWork->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($shotWork->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Datetime Start') ?></th>
            <td><?= h($shotWork->datetime_start) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Datetime Finish') ?></th>
            <td><?= h($shotWork->datetime_finish) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($shotWork->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($shotWork->updated_at) ?></td>
        </tr>
    </table>
</div>
