<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductConditonChild[]|\Cake\Collection\CollectionInterface $productConditonChildren
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Product Conditon Child'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Condition Parents'), ['controller' => 'ProductConditionParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Condition Parent'), ['controller' => 'ProductConditionParents', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="productConditonChildren index large-9 medium-8 columns content">
    <h3><?= __('Product Conditon Children') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_condition_parent_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cylinder_number') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cylinder_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('temp_1') ?></th>
                <th scope="col"><?= $this->Paginator->sort('temp_2') ?></th>
                <th scope="col"><?= $this->Paginator->sort('temp_3') ?></th>
                <th scope="col"><?= $this->Paginator->sort('temp_4') ?></th>
                <th scope="col"><?= $this->Paginator->sort('temp_5') ?></th>
                <th scope="col"><?= $this->Paginator->sort('temp_6') ?></th>
                <th scope="col"><?= $this->Paginator->sort('temp_7') ?></th>
                <th scope="col"><?= $this->Paginator->sort('extrude_roatation') ?></th>
                <th scope="col"><?= $this->Paginator->sort('extrusion_load') ?></th>
                <th scope="col"><?= $this->Paginator->sort('pickup_speed') ?></th>
                <th scope="col"><?= $this->Paginator->sort('screw_mesh') ?></th>
                <th scope="col"><?= $this->Paginator->sort('screw_number') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productConditonChildren as $productConditonChild): ?>
            <tr>
                <td><?= $this->Number->format($productConditonChild->id) ?></td>
                <td><?= $productConditonChild->has('product_condition_parent') ? $this->Html->link($productConditonChild->product_condition_parent->id, ['controller' => 'ProductConditionParents', 'action' => 'view', $productConditonChild->product_condition_parent->id]) : '' ?></td>
                <td><?= $this->Number->format($productConditonChild->cylinder_number) ?></td>
                <td><?= h($productConditonChild->cylinder_name) ?></td>
                <td><?= $this->Number->format($productConditonChild->temp_1) ?></td>
                <td><?= $this->Number->format($productConditonChild->temp_2) ?></td>
                <td><?= $this->Number->format($productConditonChild->temp_3) ?></td>
                <td><?= $this->Number->format($productConditonChild->temp_4) ?></td>
                <td><?= $this->Number->format($productConditonChild->temp_5) ?></td>
                <td><?= $this->Number->format($productConditonChild->temp_6) ?></td>
                <td><?= $this->Number->format($productConditonChild->temp_7) ?></td>
                <td><?= $this->Number->format($productConditonChild->extrude_roatation) ?></td>
                <td><?= $this->Number->format($productConditonChild->extrusion_load) ?></td>
                <td><?= $this->Number->format($productConditonChild->pickup_speed) ?></td>
                <td><?= h($productConditonChild->screw_mesh) ?></td>
                <td><?= h($productConditonChild->screw_number) ?></td>
                <td><?= $this->Number->format($productConditonChild->delete_flag) ?></td>
                <td><?= h($productConditonChild->created_at) ?></td>
                <td><?= $this->Number->format($productConditonChild->created_staff) ?></td>
                <td><?= h($productConditonChild->updated_at) ?></td>
                <td><?= $this->Number->format($productConditonChild->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $productConditonChild->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $productConditonChild->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $productConditonChild->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productConditonChild->id)]) ?>
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
