<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionDataConditonParent $inspectionDataConditonParent
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $inspectionDataConditonParent->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionDataConditonParent->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Conditon Parents'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Conditon Children'), ['controller' => 'InspectionDataConditonChildren', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Conditon Child'), ['controller' => 'InspectionDataConditonChildren', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="inspectionDataConditonParents form large-9 medium-8 columns content">
    <?= $this->Form->create($inspectionDataConditonParent) ?>
    <fieldset>
        <legend><?= __('Edit Inspection Data Conditon Parent') ?></legend>
        <?php
            echo $this->Form->control('datetime');
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
