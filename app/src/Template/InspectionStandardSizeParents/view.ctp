<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InspectionStandardSizeParent $inspectionStandardSizeParent
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Inspection Standard Size Parent'), ['action' => 'edit', $inspectionStandardSizeParent->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Inspection Standard Size Parent'), ['action' => 'delete', $inspectionStandardSizeParent->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionStandardSizeParent->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Parents'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Parent'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Data Result Parents'), ['controller' => 'InspectionDataResultParents', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Data Result Parent'), ['controller' => 'InspectionDataResultParents', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Inspection Standard Size Children'), ['controller' => 'InspectionStandardSizeChildren', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Inspection Standard Size Child'), ['controller' => 'InspectionStandardSizeChildren', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="inspectionStandardSizeParents view large-9 medium-8 columns content">
    <h3><?= h($inspectionStandardSizeParent->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Product') ?></th>
            <td><?= $inspectionStandardSizeParent->has('product') ? $this->Html->link($inspectionStandardSizeParent->product->name, ['controller' => 'Products', 'action' => 'view', $inspectionStandardSizeParent->product->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Image File Name Dir') ?></th>
            <td><?= h($inspectionStandardSizeParent->image_file_name_dir) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($inspectionStandardSizeParent->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Version') ?></th>
            <td><?= $this->Number->format($inspectionStandardSizeParent->version) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Active') ?></th>
            <td><?= $this->Number->format($inspectionStandardSizeParent->is_active) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($inspectionStandardSizeParent->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($inspectionStandardSizeParent->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($inspectionStandardSizeParent->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($inspectionStandardSizeParent->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($inspectionStandardSizeParent->updated_at) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Inspection Data Result Parents') ?></h4>
        <?php if (!empty($inspectionStandardSizeParent->inspection_data_result_parents)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Inspection Standard Size Parent Id') ?></th>
                <th scope="col"><?= __('Product Conditon Parent Id') ?></th>
                <th scope="col"><?= __('Product Material Parent Id') ?></th>
                <th scope="col"><?= __('Datetime') ?></th>
                <th scope="col"><?= __('Staff Id') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($inspectionStandardSizeParent->inspection_data_result_parents as $inspectionDataResultParents): ?>
            <tr>
                <td><?= h($inspectionDataResultParents->id) ?></td>
                <td><?= h($inspectionDataResultParents->inspection_standard_size_parent_id) ?></td>
                <td><?= h($inspectionDataResultParents->product_conditon_parent_id) ?></td>
                <td><?= h($inspectionDataResultParents->product_material_parent_id) ?></td>
                <td><?= h($inspectionDataResultParents->datetime) ?></td>
                <td><?= h($inspectionDataResultParents->staff_id) ?></td>
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
    <div class="related">
        <h4><?= __('Related Inspection Standard Size Children') ?></h4>
        <?php if (!empty($inspectionStandardSizeParent->inspection_standard_size_children)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Inspection Standard Size Parent Id') ?></th>
                <th scope="col"><?= __('Size Number') ?></th>
                <th scope="col"><?= __('Size Name') ?></th>
                <th scope="col"><?= __('Size') ?></th>
                <th scope="col"><?= __('Upper Limit') ?></th>
                <th scope="col"><?= __('Lower Limit') ?></th>
                <th scope="col"><?= __('Measuring Instrument') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($inspectionStandardSizeParent->inspection_standard_size_children as $inspectionStandardSizeChildren): ?>
            <tr>
                <td><?= h($inspectionStandardSizeChildren->id) ?></td>
                <td><?= h($inspectionStandardSizeChildren->inspection_standard_size_parent_id) ?></td>
                <td><?= h($inspectionStandardSizeChildren->size_number) ?></td>
                <td><?= h($inspectionStandardSizeChildren->size_name) ?></td>
                <td><?= h($inspectionStandardSizeChildren->size) ?></td>
                <td><?= h($inspectionStandardSizeChildren->upper_limit) ?></td>
                <td><?= h($inspectionStandardSizeChildren->lower_limit) ?></td>
                <td><?= h($inspectionStandardSizeChildren->measuring_instrument) ?></td>
                <td><?= h($inspectionStandardSizeChildren->delete_flag) ?></td>
                <td><?= h($inspectionStandardSizeChildren->created_at) ?></td>
                <td><?= h($inspectionStandardSizeChildren->created_staff) ?></td>
                <td><?= h($inspectionStandardSizeChildren->updated_at) ?></td>
                <td><?= h($inspectionStandardSizeChildren->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'InspectionStandardSizeChildren', 'action' => 'view', $inspectionStandardSizeChildren->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'InspectionStandardSizeChildren', 'action' => 'edit', $inspectionStandardSizeChildren->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'InspectionStandardSizeChildren', 'action' => 'delete', $inspectionStandardSizeChildren->id], ['confirm' => __('Are you sure you want to delete # {0}?', $inspectionStandardSizeChildren->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
