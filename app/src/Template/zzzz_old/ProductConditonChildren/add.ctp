<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductConditonChild $productConditonChild
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Product Conditon Children'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Product Condition Parents'), ['controller' => 'ProductConditionParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Condition Parent'), ['controller' => 'ProductConditionParents', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="productConditonChildren form large-9 medium-8 columns content">
    <?= $this->Form->create($productConditonChild) ?>
    <fieldset>
        <legend><?= __('Add Product Conditon Child') ?></legend>
        <?php
            echo $this->Form->control('product_condition_parent_id', ['options' => $productConditionParents]);
            echo $this->Form->control('cylinder_number');
            echo $this->Form->control('cylinder_name');
            echo $this->Form->control('temp_1');
            echo $this->Form->control('temp_2');
            echo $this->Form->control('temp_3');
            echo $this->Form->control('temp_4');
            echo $this->Form->control('temp_5');
            echo $this->Form->control('temp_6');
            echo $this->Form->control('temp_7');
            echo $this->Form->control('extrude_roatation');
            echo $this->Form->control('extrusion_load');
            echo $this->Form->control('pickup_speed');
            echo $this->Form->control('screw_mesh');
            echo $this->Form->control('screw_number');
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
