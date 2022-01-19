<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ShotdataBase $shotdataBase
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Shotdata Bases'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Factories'), ['controller' => 'Factories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Factory'), ['controller' => 'Factories', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Condition Parents'), ['controller' => 'ProductConditionParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Condition Parent'), ['controller' => 'ProductConditionParents', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="shotdataBases form large-9 medium-8 columns content">
    <?= $this->Form->create($shotdataBase) ?>
    <fieldset>
        <legend><?= __('Add Shotdata Base') ?></legend>
        <?php
            echo $this->Form->control('factory_id', ['options' => $factories]);
            echo $this->Form->control('product_condition_parent_id', ['options' => $productConditionParents]);
            echo $this->Form->control('datetime');
            echo $this->Form->control('valid_data_num');
            echo $this->Form->control('stop_time');
            echo $this->Form->control('extrusion_switch_conf');
            echo $this->Form->control('pickup_switch_conf');
            echo $this->Form->control('value_mode');
            echo $this->Form->control('value_ave');
            echo $this->Form->control('value_max');
            echo $this->Form->control('value_min');
            echo $this->Form->control('value_std');
            echo $this->Form->control('status_sencer');
            echo $this->Form->control('delete_flag');
            echo $this->Form->control('created_at');
            echo $this->Form->control('updated_at', ['empty' => true]);
            echo $this->Form->control('updated_staff');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
