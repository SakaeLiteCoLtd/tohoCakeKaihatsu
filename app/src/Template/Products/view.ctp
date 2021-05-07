<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Product'), ['action' => 'edit', $product->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Product'), ['action' => 'delete', $product->id], ['confirm' => __('Are you sure you want to delete # {0}?', $product->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Price Products'), ['controller' => 'PriceProducts', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Price Product'), ['controller' => 'PriceProducts', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Product Materials'), ['controller' => 'ProductMaterials', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product Material'), ['controller' => 'ProductMaterials', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="products view large-9 medium-8 columns content">
    <h3><?= h($product->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Product Code') ?></th>
            <td><?= h($product->product_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Customer Product Code') ?></th>
            <td><?= h($product->customer_product_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($product->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($product->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Custmoer Id') ?></th>
            <td><?= $this->Number->format($product->custmoer_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Active') ?></th>
            <td><?= $this->Number->format($product->is_active) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Delete Flag') ?></th>
            <td><?= $this->Number->format($product->delete_flag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created Staff') ?></th>
            <td><?= $this->Number->format($product->created_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated Staff') ?></th>
            <td><?= $this->Number->format($product->updated_staff) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created At') ?></th>
            <td><?= h($product->created_at) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated At') ?></th>
            <td><?= h($product->updated_at) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Price Products') ?></h4>
        <?php if (!empty($product->price_products)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Product Id') ?></th>
                <th scope="col"><?= __('Custmoer Id') ?></th>
                <th scope="col"><?= __('Price') ?></th>
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
            <?php foreach ($product->price_products as $priceProducts): ?>
            <tr>
                <td><?= h($priceProducts->id) ?></td>
                <td><?= h($priceProducts->product_id) ?></td>
                <td><?= h($priceProducts->custmoer_id) ?></td>
                <td><?= h($priceProducts->price) ?></td>
                <td><?= h($priceProducts->start_deal) ?></td>
                <td><?= h($priceProducts->finish_deal) ?></td>
                <td><?= h($priceProducts->is_active) ?></td>
                <td><?= h($priceProducts->delete_flag) ?></td>
                <td><?= h($priceProducts->created_at) ?></td>
                <td><?= h($priceProducts->created_staff) ?></td>
                <td><?= h($priceProducts->updated_at) ?></td>
                <td><?= h($priceProducts->updated_staff) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'PriceProducts', 'action' => 'view', $priceProducts->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'PriceProducts', 'action' => 'edit', $priceProducts->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'PriceProducts', 'action' => 'delete', $priceProducts->id], ['confirm' => __('Are you sure you want to delete # {0}?', $priceProducts->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Product Materials') ?></h4>
        <?php if (!empty($product->product_materials)): ?>
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
            <?php foreach ($product->product_materials as $productMaterials): ?>
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
