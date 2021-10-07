<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Seikeiki $seikeiki
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Seikeikis'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Factories'), ['controller' => 'Factories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Factory'), ['controller' => 'Factories', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="seikeikis form large-9 medium-8 columns content">
    <?= $this->Form->create($seikeiki) ?>
    <fieldset>
        <legend><?= __('Add Seikeiki') ?></legend>
        <?php
            echo $this->Form->control('factory_id', ['options' => $factories]);
            echo $this->Form->control('name');
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
