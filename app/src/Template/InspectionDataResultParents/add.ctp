<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionDataResultParent $inspectionDataResultParent
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Result Parents'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Parents'), ['controller' => 'InspectionStandardSizeParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Parent'), ['controller' => 'InspectionStandardSizeParents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Material Parents'), ['controller' => 'ProductMaterialParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Material Parent'), ['controller' => 'ProductMaterialParents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Staffs'), ['controller' => 'Staffs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Staff'), ['controller' => 'Staffs', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Result Children'), ['controller' => 'InspectionDataResultChildren', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Result Child'), ['controller' => 'InspectionDataResultChildren', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="inspectionDataResultParents form large-9 medium-8 columns content">
    <?= $this->Form->create($inspectionDataResultParent) ?>
    <fieldset>
        <legend><?= __('Add Inspection Data Result Parent') ?></legend>
        <?php
            echo $this->Form->control('inspection_standard_size_parent_id', ['options' => $inspectionStandardSizeParents]);
            echo $this->Form->control('product_conditon_parent_id');
            echo $this->Form->control('product_material_parent_id', ['options' => $productMaterialParents]);
            echo $this->Form->control('datetime');
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
