<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionDataConditonChild $inspectionDataConditonChild
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $inspectionDataConditonChild->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionDataConditonChild->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Conditon Children'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Conditon Parents'), ['controller' => 'InspectionDataConditonParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Conditon Parent'), ['controller' => 'InspectionDataConditonParents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Conditon Children'), ['controller' => 'ProductConditonChildren', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Conditon Child'), ['controller' => 'ProductConditonChildren', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="inspectionDataConditonChildren form large-9 medium-8 columns content">
    <?= $this->Form->create($inspectionDataConditonChild) ?>
    <fieldset>
        <legend><?= __('Edit Inspection Data Conditon Child') ?></legend>
        <?php
            echo $this->Form->control('inspection_data_conditon_parent_id', ['options' => $inspectionDataConditonParents]);
            echo $this->Form->control('product_conditon_child_id', ['options' => $productConditonChildren]);
            echo $this->Form->control('inspection_temp_1');
            echo $this->Form->control('inspection_temp_2');
            echo $this->Form->control('inspection_temp_3');
            echo $this->Form->control('inspection_temp_4');
            echo $this->Form->control('inspection_temp_5');
            echo $this->Form->control('inspection_temp_6');
            echo $this->Form->control('inspection_temp_7');
            echo $this->Form->control('inspection_extrude_roatation');
            echo $this->Form->control('inspection_extrusion_load');
            echo $this->Form->control('inspection_pickup_speed');
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
