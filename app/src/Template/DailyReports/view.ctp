<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DailyReport $dailyReport
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Daily Report'), ['action' => 'edit', $dailyReport->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Daily Report'), ['action' => 'delete', $dailyReport->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dailyReport->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Daily Reports'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Daily Report'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Data Result Parents'), ['controller' => 'InspectionDataResultParents', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Data Result Parent'), ['controller' => 'InspectionDataResultParents', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="dailyReports view large-9 medium-8 columns content">
    <h3><?= h($dailyReport->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Product') ?></th>
            <td><?= $dailyReport->has('product') ? $this->Html->link($dailyReport->product->name, ['controller' => 'Products', 'action' => 'view', $dailyReport->product->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($dailyReport->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Machine Num') ?></th>
            <td><?= $this->Number->format($dailyReport->machine_num) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Amount') ?></th>
            <td><?= $this->Number->format($dailyReport->amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sum Weight') ?></th>
            <td><?= $this->Number->format($dailyReport->sum_weight) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Total Loss Weight') ?></th>
            <td><?= $this->Number->format($dailyReport->total_loss_weight) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($dailyReport->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($dailyReport->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($dailyReport->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Start Datetime') ?></th>
            <td><?= h($dailyReport->start_datetime) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Finish Datetime') ?></th>
            <td><?= h($dailyReport->finish_datetime) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($dailyReport->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($dailyReport->updated_at) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Bik') ?></h4>
        <?= $this->Text->autoParagraph(h($dailyReport->bik)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Inspection Data Result Parents') ?></h4>
        <?php if (!empty($dailyReport->inspection_data_result_parents)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Daily Report Id') ?></th>
                <th scope="col"><?= __('Inspection Data Conditon Parent Id') ?></th>
                <th scope="col"><?= __('Inspection Standard Size Parent Id') ?></th>
                <th scope="col"><?= __('Product Condition Parent Id') ?></th>
                <th scope="col"><?= __('Product Id') ?></th>
                <th scope="col"><?= __('Lot Number') ?></th>
                <th scope="col"><?= __('Datetime') ?></th>
                <th scope="col"><?= __('Staff Id') ?></th>
                <th scope="col"><?= __('Appearance') ?></th>
                <th scope="col"><?= __('Result Weight') ?></th>
                <th scope="col"><?= __('Judge') ?></th>
                <th scope="col"><?= __('Kanryou Flag') ?></th>
                <th scope="col"><?= __('Loss Amount') ?></th>
                <th scope="col"><?= __('Bik') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($dailyReport->inspection_data_result_parents as $inspectionDataResultParents): ?>
            <tr>
                <td><?= h($inspectionDataResultParents->id) ?></td>
                <td><?= h($inspectionDataResultParents->daily_report_id) ?></td>
                <td><?= h($inspectionDataResultParents->inspection_data_conditon_parent_id) ?></td>
                <td><?= h($inspectionDataResultParents->inspection_standard_size_parent_id) ?></td>
                <td><?= h($inspectionDataResultParents->product_condition_parent_id) ?></td>
                <td><?= h($inspectionDataResultParents->product_id) ?></td>
                <td><?= h($inspectionDataResultParents->lot_number) ?></td>
                <td><?= h($inspectionDataResultParents->datetime) ?></td>
                <td><?= h($inspectionDataResultParents->staff_id) ?></td>
                <td><?= h($inspectionDataResultParents->appearance) ?></td>
                <td><?= h($inspectionDataResultParents->result_weight) ?></td>
                <td><?= h($inspectionDataResultParents->judge) ?></td>
                <td><?= h($inspectionDataResultParents->kanryou_flag) ?></td>
                <td><?= h($inspectionDataResultParents->loss_amount) ?></td>
                <td><?= h($inspectionDataResultParents->bik) ?></td>
                <td><?= h($inspectionDataResultParents->delete_flag) ?></td>
                <td><?= h($inspectionDataResultParents->created_at) ?></td>
                <td><?= h($inspectionDataResultParents->created_staff) ?></td>
                <td><?= h($inspectionDataResultParents->updated_at) ?></td>
                <td><?= h($inspectionDataResultParents->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'InspectionDataResultParents', 'action' => 'view', $inspectionDataResultParents->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'InspectionDataResultParents', 'action' => 'edit', $inspectionDataResultParents->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'InspectionDataResultParents', 'action' => 'delete', $inspectionDataResultParents->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionDataResultParents->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
