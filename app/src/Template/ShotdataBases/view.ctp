<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ShotdataBase $shotdataBase
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Shotdata Base'), ['action' => 'edit', $shotdataBase->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Shotdata Base'), ['action' => 'delete', $shotdataBase->id], ['confirm' => __('Are you sure you want to delete # {0}?', $shotdataBase->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Shotdata Bases'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Shotdata Base'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Factories'), ['controller' => 'Factories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Factory'), ['controller' => 'Factories', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Product Condition Parents'), ['controller' => 'ProductConditionParents', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Condition Parent'), ['controller' => 'ProductConditionParents', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="shotdataBases view large-9 medium-8 columns content">
    <h3><?= h($shotdataBase->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Factory') ?></th>
            <td><?= $shotdataBase->has('factory') ? $this->Html->link($shotdataBase->factory->name, ['controller' => 'Factories', 'action' => 'view', $shotdataBase->factory->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Product Condition Parent') ?></th>
            <td><?= $shotdataBase->has('product_condition_parent') ? $this->Html->link($shotdataBase->product_condition_parent->id, ['controller' => 'ProductConditionParents', 'action' => 'view', $shotdataBase->product_condition_parent->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($shotdataBase->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Valid Data Num') ?></th>
            <td><?= $this->Number->format($shotdataBase->valid_data_num) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Stop Time') ?></th>
            <td><?= $this->Number->format($shotdataBase->stop_time) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Extrusion Switch Conf') ?></th>
            <td><?= $this->Number->format($shotdataBase->extrusion_switch_conf) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Pickup Switch Conf') ?></th>
            <td><?= $this->Number->format($shotdataBase->pickup_switch_conf) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Value Mode') ?></th>
            <td><?= $this->Number->format($shotdataBase->value_mode) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Value Ave') ?></th>
            <td><?= $this->Number->format($shotdataBase->value_ave) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Value Max') ?></th>
            <td><?= $this->Number->format($shotdataBase->value_max) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Value Min') ?></th>
            <td><?= $this->Number->format($shotdataBase->value_min) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Value Std') ?></th>
            <td><?= $this->Number->format($shotdataBase->value_std) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status Sencer') ?></th>
            <td><?= $this->Number->format($shotdataBase->status_sencer) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($shotdataBase->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($shotdataBase->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Datetime') ?></th>
            <td><?= h($shotdataBase->datetime) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($shotdataBase->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($shotdataBase->updated_at) ?></td>
        </tr>
    </table>
</div>
