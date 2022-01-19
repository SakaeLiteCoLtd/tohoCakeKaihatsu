<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionStandardSizeParent $inspectionStandardSizeParent
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $inspectionStandardSizeParent->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionStandardSizeParent->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Parents'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Result Parents'), ['controller' => 'InspectionDataResultParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Result Parent'), ['controller' => 'InspectionDataResultParents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Children'), ['controller' => 'InspectionStandardSizeChildren', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Child'), ['controller' => 'InspectionStandardSizeChildren', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="inspectionStandardSizeParents form large-9 medium-8 columns content">
    <?= $this->Form->create($inspectionStandardSizeParent) ?>
    <fieldset>
        <legend><?= __('Edit Inspection Standard Size Parent') ?></legend>
        <?php
            echo $this->Form->control('product_id', ['options' => $products]);
            echo $this->Form->control('image_file_name_dir');
            echo $this->Form->control('version');
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
