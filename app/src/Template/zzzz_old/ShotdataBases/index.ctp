<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ShotdataBase[]|\Cake\Collection\CollectionInterface $shotdataBases
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Shotdata Base'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Factories'), ['controller' => 'Factories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Factory'), ['controller' => 'Factories', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Condition Parents'), ['controller' => 'ProductConditionParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Condition Parent'), ['controller' => 'ProductConditionParents', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="shotdataBases index large-9 medium-8 columns content">
    <h3><?= __('Shotdata Bases') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('factory_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_condition_parent_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('datetime') ?></th>
                <th scope="col"><?= $this->Paginator->sort('valid_data_num') ?></th>
                <th scope="col"><?= $this->Paginator->sort('stop_time') ?></th>
                <th scope="col"><?= $this->Paginator->sort('extrusion_switch_conf') ?></th>
                <th scope="col"><?= $this->Paginator->sort('pickup_switch_conf') ?></th>
                <th scope="col"><?= $this->Paginator->sort('value_mode') ?></th>
                <th scope="col"><?= $this->Paginator->sort('value_ave') ?></th>
                <th scope="col"><?= $this->Paginator->sort('value_max') ?></th>
                <th scope="col"><?= $this->Paginator->sort('value_min') ?></th>
                <th scope="col"><?= $this->Paginator->sort('value_std') ?></th>
                <th scope="col"><?= $this->Paginator->sort('status_sencer') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($shotdataBases as $shotdataBase): ?>
            <tr>
                <td><?= $this->Number->format($shotdataBase->id) ?></td>
                <td><?= $shotdataBase->has('factory') ? $this->Html->link($shotdataBase->factory->name, ['controller' => 'Factories', 'action' => 'view', $shotdataBase->factory->id]) : '' ?></td>
                <td><?= $shotdataBase->has('product_condition_parent') ? $this->Html->link($shotdataBase->product_condition_parent->id, ['controller' => 'ProductConditionParents', 'action' => 'view', $shotdataBase->product_condition_parent->id]) : '' ?></td>
                <td><?= h($shotdataBase->datetime) ?></td>
                <td><?= $this->Number->format($shotdataBase->valid_data_num) ?></td>
                <td><?= $this->Number->format($shotdataBase->stop_time) ?></td>
                <td><?= $this->Number->format($shotdataBase->extrusion_switch_conf) ?></td>
                <td><?= $this->Number->format($shotdataBase->pickup_switch_conf) ?></td>
                <td><?= $this->Number->format($shotdataBase->value_mode) ?></td>
                <td><?= $this->Number->format($shotdataBase->value_ave) ?></td>
                <td><?= $this->Number->format($shotdataBase->value_max) ?></td>
                <td><?= $this->Number->format($shotdataBase->value_min) ?></td>
                <td><?= $this->Number->format($shotdataBase->value_std) ?></td>
                <td><?= $this->Number->format($shotdataBase->status_sencer) ?></td>
                <td><?= $this->Number->format($shotdataBase->delete_flag) ?></td>
                <td><?= h($shotdataBase->created_at) ?></td>
                <td><?= h($shotdataBase->updated_at) ?></td>
                <td><?= $this->Number->format($shotdataBase->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $shotdataBase->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $shotdataBase->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $shotdataBase->id], ['confirm' => __('Are you sure you want to delete # {0}?', $shotdataBase->id)]) ?>
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
