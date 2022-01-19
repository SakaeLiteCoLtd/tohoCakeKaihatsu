<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DailyReport $dailyReport
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Daily Reports'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Result Parents'), ['controller' => 'InspectionDataResultParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Result Parent'), ['controller' => 'InspectionDataResultParents', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="dailyReports form large-9 medium-8 columns content">
    <?= $this->Form->create($dailyReport) ?>
    <fieldset>
        <legend><?= __('Add Daily Report') ?></legend>
        <?php
            echo $this->Form->control('product_id', ['options' => $products]);
            echo $this->Form->control('machine_num');
            echo $this->Form->control('start_datetime');
            echo $this->Form->control('finish_datetime');
            echo $this->Form->control('amount');
            echo $this->Form->control('sum_weight');
            echo $this->Form->control('total_loss_weight');
            echo $this->Form->control('bik');
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
