<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PriceProduct $priceProduct
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $priceProduct->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $priceProduct->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Price Products'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="priceProducts form large-9 medium-8 columns content">
    <?= $this->Form->create($priceProduct) ?>
    <fieldset>
        <legend><?= __('Edit Price Product') ?></legend>
        <?php
            echo $this->Form->control('product_id', ['options' => $products]);
            echo $this->Form->control('custmoer_id');
            echo $this->Form->control('price');
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
