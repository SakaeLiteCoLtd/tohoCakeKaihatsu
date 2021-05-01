<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductMachineMaterial $productMachineMaterial
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $productMachineMaterial->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $productMachineMaterial->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Product Machine Materials'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Product Material Machines'), ['controller' => 'ProductMaterialMachines', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Material Machine'), ['controller' => 'ProductMaterialMachines', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Material Lot Numbers'), ['controller' => 'ProductMaterialLotNumbers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Material Lot Number'), ['controller' => 'ProductMaterialLotNumbers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="productMachineMaterials form large-9 medium-8 columns content">
    <?= $this->Form->create($productMachineMaterial) ?>
    <fieldset>
        <legend><?= __('Edit Product Machine Material') ?></legend>
        <?php
            echo $this->Form->control('product_material_machine_id', ['options' => $productMaterialMachines]);
            echo $this->Form->control('material_number');
            echo $this->Form->control('material_grade');
            echo $this->Form->control('material_maker');
            echo $this->Form->control('mixing_ratio');
            echo $this->Form->control('dry_temp');
            echo $this->Form->control('dry_hour');
            echo $this->Form->control('recycled_mixing_ratio');
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
