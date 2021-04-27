<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PriceMaterial $priceMaterial
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $priceMaterial->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $priceMaterial->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Price Materials'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Materials'), ['controller' => 'Materials', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Material'), ['controller' => 'Materials', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Material Suppliers'), ['controller' => 'MaterialSuppliers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Material Supplier'), ['controller' => 'MaterialSuppliers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="priceMaterials form large-9 medium-8 columns content">
    <?= $this->Form->create($priceMaterial) ?>
    <fieldset>
        <legend><?= __('Edit Price Material') ?></legend>
        <?php
            echo $this->Form->control('material_id', ['options' => $materials]);
            echo $this->Form->control('material_supplier_id', ['options' => $materialSuppliers]);
            echo $this->Form->control('price');
            echo $this->Form->control('lot_remarks');
            echo $this->Form->control('start_deal');
            echo $this->Form->control('finish_deal', ['empty' => true]);
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
