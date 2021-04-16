<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\StaffAbility $staffAbility
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $staffAbility->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $staffAbility->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Staff Abilities'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Staffs'), ['controller' => 'Staffs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Staff'), ['controller' => 'Staffs', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Menus'), ['controller' => 'Menus', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Menu'), ['controller' => 'Menus', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="staffAbilities form large-9 medium-8 columns content">
    <?= $this->Form->create($staffAbility) ?>
    <fieldset>
        <legend><?= __('Edit Staff Ability') ?></legend>
        <?php
            echo $this->Form->control('staff_id', ['options' => $staffs]);
            echo $this->Form->control('menu_id', ['options' => $menus]);
            echo $this->Form->control('delete_flag');
            echo $this->Form->control('created_at');
            echo $this->Form->control('created_staff');
            echo $this->Form->control('updated_at', ['empty' => true]);
            echo $this->Form->control('updated_staff');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
