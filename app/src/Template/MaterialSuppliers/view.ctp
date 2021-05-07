<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MaterialSupplier $materialSupplier
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Material Supplier'), ['action' => 'edit', $materialSupplier->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Material Supplier'), ['action' => 'delete', $materialSupplier->id], ['confirm' => __('Are you sure you want to delete # {0}?', $materialSupplier->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Material Suppliers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Material Supplier'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Price Materials'), ['controller' => 'PriceMaterials', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Price Material'), ['controller' => 'PriceMaterials', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="materialSuppliers view large-9 medium-8 columns content">
    <h3><?= h($materialSupplier->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($materialSupplier->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Office') ?></th>
            <td><?= h($materialSupplier->office) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Department') ?></th>
            <td><?= h($materialSupplier->department) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Address') ?></th>
            <td><?= h($materialSupplier->address) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tel') ?></th>
            <td><?= h($materialSupplier->tel) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Fax') ?></th>
            <td><?= h($materialSupplier->fax) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($materialSupplier->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Active') ?></th>
            <td><?= $this->Number->format($materialSupplier->is_active) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($materialSupplier->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($materialSupplier->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($materialSupplier->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($materialSupplier->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($materialSupplier->updated_at) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Price Materials') ?></h4>
        <?php if (!empty($materialSupplier->price_materials)): ?>
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
            <?php foreach ($materialSupplier->price_materials as $priceMaterials): ?>
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
</div>
