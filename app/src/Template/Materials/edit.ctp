<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Material $material
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $material->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $material->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Materials'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Price Materials'), ['controller' => 'PriceMaterials', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Price Material'), ['controller' => 'PriceMaterials', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Materials'), ['controller' => 'ProductMaterials', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Material'), ['controller' => 'ProductMaterials', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="materials form large-9 medium-8 columns content">
    <?= $this->Form->create($material) ?>
    <fieldset>
        <legend><?= __('Edit Material') ?></legend>
        <?php
            echo $this->Form->control('material_code');
            echo $this->Form->control('grade');
            echo $this->Form->control('color');
            echo $this->Form->control('maker');
            echo $this->Form->control('type_id');
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
