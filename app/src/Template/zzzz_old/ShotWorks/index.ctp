<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ShotWork[]|\Cake\Collection\CollectionInterface $shotWorks
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Shot Work'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Factories'), ['controller' => 'Factories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Factory'), ['controller' => 'Factories', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Condition Parents'), ['controller' => 'ProductConditionParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Condition Parent'), ['controller' => 'ProductConditionParents', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="shotWorks index large-9 medium-8 columns content">
    <h3><?= __('Shot Works') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('factory_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_condition_parent_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('datetime_start') ?></th>
                <th scope="col"><?= $this->Paginator->sort('datetime_finish') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($shotWorks as $shotWork): ?>
            <tr>
                <td><?= $this->Number->format($shotWork->id) ?></td>
                <td><?= $shotWork->has('factory') ? $this->Html->link($shotWork->factory->name, ['controller' => 'Factories', 'action' => 'view', $shotWork->factory->id]) : '' ?></td>
                <td><?= $shotWork->has('product_condition_parent') ? $this->Html->link($shotWork->product_condition_parent->id, ['controller' => 'ProductConditionParents', 'action' => 'view', $shotWork->product_condition_parent->id]) : '' ?></td>
                <td><?= h($shotWork->datetime_start) ?></td>
                <td><?= h($shotWork->datetime_finish) ?></td>
                <td><?= $this->Number->format($shotWork->delete_flag) ?></td>
                <td><?= h($shotWork->created_at) ?></td>
                <td><?= h($shotWork->updated_at) ?></td>
                <td><?= $this->Number->format($shotWork->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $shotWork->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $shotWork->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $shotWork->id], ['confirm' => __('Are you sure you want to delete # {0}?', $shotWork->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
