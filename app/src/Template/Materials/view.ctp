<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Material $material
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Material'), ['action' => 'edit', $material->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Material'), ['action' => 'delete', $material->id], ['confirm' => __('Are you sure you want to delete # {0}?', $material->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Materials'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Material'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Price Materials'), ['controller' => 'PriceMaterials', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Price Material'), ['controller' => 'PriceMaterials', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Product Materials'), ['controller' => 'ProductMaterials', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Material'), ['controller' => 'ProductMaterials', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="materials view large-9 medium-8 columns content">
    <h3><?= h($material->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Material Code') ?></th>
            <td><?= h($material->material_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Grade') ?></th>
            <td><?= h($material->grade) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Color') ?></th>
            <td><?= h($material->color) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Maker') ?></th>
            <td><?= h($material->maker) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($material->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Type Id') ?></th>
            <td><?= $this->Number->format($material->type_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Active') ?></th>
            <td><?= $this->Number->format($material->is_active) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($material->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($material->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($material->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($material->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($material->updated_at) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Price Materials') ?></h4>
        <?php if (!empty($material->price_materials)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Material Id') ?></th>
                <th scope="col"><?= __('Material Supplier Id') ?></th>
                <th scope="col"><?= __('Price') ?></th>
                <th scope="col"><?= __('Lot Remarks') ?></th>
                <th scope="col"><?= __('Start Deal') ?></th>
                <th scope="col"><?= __('Finish Deal') ?></th>
                <th scope="col"><?= __('Is Active') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($material->price_materials as $priceMaterials): ?>
            <tr>
                <td><?= h($priceMaterials->id) ?></td>
                <td><?= h($priceMaterials->material_id) ?></td>
                <td><?= h($priceMaterials->material_supplier_id) ?></td>
                <td><?= h($priceMaterials->price) ?></td>
                <td><?= h($priceMaterials->lot_remarks) ?></td>
                <td><?= h($priceMaterials->start_deal) ?></td>
                <td><?= h($priceMaterials->finish_deal) ?></td>
                <td><?= h($priceMaterials->is_active) ?></td>
                <td><?= h($priceMaterials->delete_flag) ?></td>
                <td><?= h($priceMaterials->created_at) ?></td>
                <td><?= h($priceMaterials->created_staff) ?></td>
                <td><?= h($priceMaterials->updated_at) ?></td>
                <td><?= h($priceMaterials->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'PriceMaterials', 'action' => 'view', $priceMaterials->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'PriceMaterials', 'action' => 'edit', $priceMaterials->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'PriceMaterials', 'action' => 'delete', $priceMaterials->id], ['confirm' => __('Are you sure you want to delete # {0}?', $priceMaterials->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Product Materials') ?></h4>
        <?php if (!empty($material->product_materials)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Product Id') ?></th>
                <th scope="col"><?= __('Material Id') ?></th>
                <th scope="col"><?= __('Is Active') ?></th>
                <th scope="col"><?= __('Delete Flag') ?></th>
                <th scope="col"><?= __('Created At') ?></th>
                <th scope="col"><?= __('Created Staff') ?></th>
                <th scope="col"><?= __('Updated At') ?></th>
                <th scope="col"><?= __('Updated Staff') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($material->product_materials as $productMaterials): ?>
            <tr>
                <td><?= h($productMaterials->id) ?></td>
                <td><?= h($productMaterials->product_id) ?></td>
                <td><?= h($productMaterials->material_id) ?></td>
                <td><?= h($productMaterials->is_active) ?></td>
                <td><?= h($productMaterials->delete_flag) ?></td>
                <td><?= h($productMaterials->created_at) ?></td>
                <td><?= h($productMaterials->created_staff) ?></td>
                <td><?= h($productMaterials->updated_at) ?></td>
                <td><?= h($productMaterials->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ProductMaterials', 'action' => 'view', $productMaterials->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ProductMaterials', 'action' => 'edit', $productMaterials->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ProductMaterials', 'action' => 'delete', $productMaterials->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productMaterials->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
