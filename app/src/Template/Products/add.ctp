<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Products'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Price Products'), ['controller' => 'PriceProducts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Price Product'), ['controller' => 'PriceProducts', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Materials'), ['controller' => 'ProductMaterials', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Material'), ['controller' => 'ProductMaterials', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="products form large-9 medium-8 columns content">
    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><?= __('Add Product') ?></legend>
        <?php
            echo $this->Form->control('product_code');
            echo $this->Form->control('customer_product_code');
            echo $this->Form->control('name');
            echo $this->Form->control('custmoer_id');
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
