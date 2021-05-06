<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Factory $factory
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $factory->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $factory->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Factories'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Staffs'), ['controller' => 'Staffs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Staff'), ['controller' => 'Staffs', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="factories form large-9 medium-8 columns content">
    <?= $this->Form->create($factory) ?>
    <fieldset>
        <legend><?= __('Edit Factory') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('company_id', ['options' => $companies]);
            echo $this->Form->control('staff_id', ['options' => $staffs]);
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
