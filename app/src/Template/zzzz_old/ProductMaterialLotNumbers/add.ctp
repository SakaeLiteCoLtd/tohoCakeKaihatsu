<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductMaterialLotNumber $productMaterialLotNumber
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Product Material Lot Numbers'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Product Machine Materials'), ['controller' => 'ProductMachineMaterials', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Machine Material'), ['controller' => 'ProductMachineMaterials', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Staffs'), ['controller' => 'Staffs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Staff'), ['controller' => 'Staffs', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="productMaterialLotNumbers form large-9 medium-8 columns content">
    <?= $this->Form->create($productMaterialLotNumber) ?>
    <fieldset>
        <legend><?= __('Add Product Material Lot Number') ?></legend>
        <?php
            echo $this->Form->control('product_machine_material_id', ['options' => $productMachineMaterials]);
            echo $this->Form->control('datetme');
            echo $this->Form->control('lot_number');
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
