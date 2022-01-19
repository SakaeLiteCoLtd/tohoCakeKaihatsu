<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ShotWork $shotWork
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Shot Works'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Factories'), ['controller' => 'Factories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Factory'), ['controller' => 'Factories', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Condition Parents'), ['controller' => 'ProductConditionParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Condition Parent'), ['controller' => 'ProductConditionParents', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="shotWorks form large-9 medium-8 columns content">
    <?= $this->Form->create($shotWork) ?>
    <fieldset>
        <legend><?= __('Add Shot Work') ?></legend>
        <?php
            echo $this->Form->control('factory_id', ['options' => $factories]);
            echo $this->Form->control('product_condition_parent_id', ['options' => $productConditionParents]);
            echo $this->Form->control('datetime_start');
            echo $this->Form->control('datetime_finish');
            echo $this->Form->control('delete_flag');
            echo $this->Form->control('created_at');
            echo $this->Form->control('updated_at', ['empty' => true]);
            echo $this->Form->control('updated_staff');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
