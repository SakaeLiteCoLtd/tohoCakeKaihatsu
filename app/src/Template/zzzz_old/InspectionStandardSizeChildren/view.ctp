<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionStandardSizeChild $inspectionStandardSizeChild
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Inspection Standard Size Child'), ['action' => 'edit', $inspectionStandardSizeChild->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Inspection Standard Size Child'), ['action' => 'delete', $inspectionStandardSizeChild->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionStandardSizeChild->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Children'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Child'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Parents'), ['controller' => 'InspectionStandardSizeParents', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Parent'), ['controller' => 'InspectionStandardSizeParents', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Data Result Children'), ['controller' => 'InspectionDataResultChildren', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Data Result Child'), ['controller' => 'InspectionDataResultChildren', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="inspectionStandardSizeChildren view large-9 medium-8 columns content">
    <h3><?= h($inspectionStandardSizeChild->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Inspection Standard Size Parent') ?></th>
            <td><?= $inspectionStandardSizeChild->has('inspection_standard_size_parent') ? $this->Html->link($inspectionStandardSizeChild->inspection_standard_size_parent->id, ['controller' => 'InspectionStandardSizeParents', 'action' => 'view', $inspectionStandardSizeChild->inspection_standard_size_parent->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Size Name') ?></th>
            <td><?= h($inspectionStandardSizeChild->size_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Measuring Instrument') ?></th>
            <td><?= h($inspectionStandardSizeChild->measuring_instrument) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($inspectionStandardSizeChild->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Size Number') ?></th>
            <td><?= $this->Number->format($inspectionStandardSizeChild->size_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Size') ?></th>
            <td><?= $this->Number->format($inspectionStandardSizeChild->size) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Upper Limit') ?></th>
            <td><?= $this->Number->format($inspectionStandardSizeChild->upper_limit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lower Limit') ?></th>
            <td><?= $this->Number->format($inspectionStandardSizeChild->lower_limit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($inspectionStandardSizeChild->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($inspectionStandardSizeChild->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($inspectionStandardSizeChild->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($inspectionStandardSizeChild->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($inspectionStandardSizeChild->updated_at) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Inspection Data Result Children') ?></h4>
        <?php if (!empty($inspectionStandardSizeChild->inspection_data_result_children)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Inspection Data Result Parent Id') ?></th>
                <th scope="col"><?= __('Inspection Standard Size Child Id') ?></th>
                <th scope="col"><?= __('Result Size') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($inspectionStandardSizeChild->inspection_data_result_children as $inspectionDataResultChildren): ?>
            <tr>
                <td><?= h($inspectionDataResultChildren->id) ?></td>
                <td><?= h($inspectionDataResultChildren->inspection_data_result_parent_id) ?></td>
                <td><?= h($inspectionDataResultChildren->inspection_standard_size_child_id) ?></td>
                <td><?= h($inspectionDataResultChildren->result_size) ?></td>
                <td><?= h($inspectionDataResultChildren->delete_flag) ?></td>
                <td><?= h($inspectionDataResultChildren->created_at) ?></td>
                <td><?= h($inspectionDataResultChildren->created_staff) ?></td>
                <td><?= h($inspectionDataResultChildren->updated_at) ?></td>
                <td><?= h($inspectionDataResultChildren->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'InspectionDataResultChildren', 'action' => 'view', $inspectionDataResultChildren->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'InspectionDataResultChildren', 'action' => 'edit', $inspectionDataResultChildren->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'InspectionDataResultChildren', 'action' => 'delete', $inspectionDataResultChildren->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionDataResultChildren->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
