<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductMaterialMachine $productMaterialMachine
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Product Material Machines'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Product Material Parents'), ['controller' => 'ProductMaterialParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Material Parent'), ['controller' => 'ProductMaterialParents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Machine Materials'), ['controller' => 'ProductMachineMaterials', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Machine Material'), ['controller' => 'ProductMachineMaterials', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="productMaterialMachines form large-9 medium-8 columns content">
    <?= $this->Form->create($productMaterialMachine) ?>
    <fieldset>
        <legend><?= __('Add Product Material Machine') ?></legend>
        <?php
            echo $this->Form->control('product_material_parent_id', ['options' => $productMaterialParents]);
            echo $this->Form->control('cylinder_numer');
            echo $this->Form->control('cylinder_name');
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
