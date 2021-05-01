<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionStandardSizeChild $inspectionStandardSizeChild
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $inspectionStandardSizeChild->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionStandardSizeChild->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Children'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Parents'), ['controller' => 'InspectionStandardSizeParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Parent'), ['controller' => 'InspectionStandardSizeParents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Result Children'), ['controller' => 'InspectionDataResultChildren', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Result Child'), ['controller' => 'InspectionDataResultChildren', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="inspectionStandardSizeChildren form large-9 medium-8 columns content">
    <?= $this->Form->create($inspectionStandardSizeChild) ?>
    <fieldset>
        <legend><?= __('Edit Inspection Standard Size Child') ?></legend>
        <?php
            echo $this->Form->control('inspection_standard_size_parent_id', ['options' => $inspectionStandardSizeParents]);
            echo $this->Form->control('size_number');
            echo $this->Form->control('size_name');
            echo $this->Form->control('size');
            echo $this->Form->control('upper_limit');
            echo $this->Form->control('lower_limit');
            echo $this->Form->control('measuring_instrument');
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
