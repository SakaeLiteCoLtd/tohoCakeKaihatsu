<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionDataResultChild $inspectionDataResultChild
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Result Children'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Result Parents'), ['controller' => 'InspectionDataResultParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Result Parent'), ['controller' => 'InspectionDataResultParents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Children'), ['controller' => 'InspectionStandardSizeChildren', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Child'), ['controller' => 'InspectionStandardSizeChildren', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="inspectionDataResultChildren form large-9 medium-8 columns content">
    <?= $this->Form->create($inspectionDataResultChild) ?>
    <fieldset>
        <legend><?= __('Add Inspection Data Result Child') ?></legend>
        <?php
            echo $this->Form->control('inspection_data_result_parent_id', ['options' => $inspectionDataResultParents]);
            echo $this->Form->control('inspection_standard_size_child_id', ['options' => $inspectionStandardSizeChildren]);
            echo $this->Form->control('result_size');
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
