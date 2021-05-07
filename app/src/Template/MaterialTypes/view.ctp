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
        <li><?= $this->Html->link(__('Edit Material Type'), ['action' => 'edit', $materialType->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Material Type'), ['action' => 'delete', $materialType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $materialType->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Material Types'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Material Type'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="materialTypes view large-9 medium-8 columns content">
    <h3><?= h($materialType->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Type') ?></th>
            <td><?= h($materialType->type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($materialType->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($materialType->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($materialType->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($materialType->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($materialType->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($materialType->updated_at) ?></td>
        </tr>
    </table>
</div>
