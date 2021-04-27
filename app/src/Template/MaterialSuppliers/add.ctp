<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MaterialSupplier $materialSupplier
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Material Suppliers'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Price Materials'), ['controller' => 'PriceMaterials', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Price Material'), ['controller' => 'PriceMaterials', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="materialSuppliers form large-9 medium-8 columns content">
    <?= $this->Form->create($materialSupplier) ?>
    <fieldset>
        <legend><?= __('Add Material Supplier') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('office');
            echo $this->Form->control('department');
            echo $this->Form->control('address');
            echo $this->Form->control('tel');
            echo $this->Form->control('fax');
            echo $this->Form->control('is_active');
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
