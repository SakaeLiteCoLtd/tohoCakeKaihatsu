<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionDataConditonChild[]|\Cake\Collection\CollectionInterface $inspectionDataConditonChildren
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Conditon Child'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Inspection Data Conditon Parents'), ['controller' => 'InspectionDataConditonParents', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Inspection Data Conditon Parent'), ['controller' => 'InspectionDataConditonParents', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Product Conditon Children'), ['controller' => 'ProductConditonChildren', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product Conditon Child'), ['controller' => 'ProductConditonChildren', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="inspectionDataConditonChildren index large-9 medium-8 columns content">
    <h3><?= __('Inspection Data Conditon Children') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_data_conditon_parent_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('product_conditon_child_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_temp_1') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_temp_2') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_temp_3') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_temp_4') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_temp_5') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_temp_6') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_temp_7') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_extrude_roatation') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_extrusion_load') ?></th>
                <th scope="col"><?= $this->Paginator->sort('inspection_pickup_speed') ?></th>
                <th scope="col"><?= $this->Paginator->sort('delete_flag') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_staff') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_at') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inspectionDataConditonChildren as $inspectionDataConditonChild): ?>
            <tr>
                <td><?= $this->Number->format($inspectionDataConditonChild->id) ?></td>
                <td><?= $inspectionDataConditonChild->has('inspection_data_conditon_parent') ? $this->Html->link($inspectionDataConditonChild->inspection_data_conditon_parent->id, ['controller' => 'InspectionDataConditonParents', 'action' => 'view', $inspectionDataConditonChild->inspection_data_conditon_parent->id]) : '' ?></td>
                <td><?= $inspectionDataConditonChild->has('product_conditon_child') ? $this->Html->link($inspectionDataConditonChild->product_conditon_child->id, ['controller' => 'ProductConditonChildren', 'action' => 'view', $inspectionDataConditonChild->product_conditon_child->id]) : '' ?></td>
                <td><?= $this->Number->format($inspectionDataConditonChild->inspection_temp_1) ?></td>
                <td><?= $this->Number->format($inspectionDataConditonChild->inspection_temp_2) ?></td>
                <td><?= $this->Number->format($inspectionDataConditonChild->inspection_temp_3) ?></td>
                <td><?= $this->Number->format($inspectionDataConditonChild->inspection_temp_4) ?></td>
                <td><?= $this->Number->format($inspectionDataConditonChild->inspection_temp_5) ?></td>
                <td><?= $this->Number->format($inspectionDataConditonChild->inspection_temp_6) ?></td>
                <td><?= $this->Number->format($inspectionDataConditonChild->inspection_temp_7) ?></td>
                <td><?= $this->Number->format($inspectionDataConditonChild->inspection_extrude_roatation) ?></td>
                <td><?= $this->Number->format($inspectionDataConditonChild->inspection_extrusion_load) ?></td>
                <td><?= $this->Number->format($inspectionDataConditonChild->inspection_pickup_speed) ?></td>
                <td><?= $this->Number->format($inspectionDataConditonChild->delete_flag) ?></td>
                <td><?= h($inspectionDataConditonChild->created_at) ?></td>
                <td><?= $this->Number->format($inspectionDataConditonChild->created_staff) ?></td>
                <td><?= h($inspectionDataConditonChild->updated_at) ?></td>
                <td><?= $this->Number->format($inspectionDataConditonChild->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $inspectionDataConditonChild->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $inspectionDataConditonChild->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $inspectionDataConditonChild->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionDataConditonChild->id)]) ?>
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
