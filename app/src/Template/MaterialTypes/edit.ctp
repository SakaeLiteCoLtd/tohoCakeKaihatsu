<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MaterialType $materialType
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $materialType->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $materialType->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Material Types'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="materialTypes form large-9 medium-8 columns content">
    <?= $this->Form->create($materialType) ?>
    <fieldset>
        <legend><?= __('Edit Material Type') ?></legend>
        <?php
            echo $this->Form->control('type');
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
