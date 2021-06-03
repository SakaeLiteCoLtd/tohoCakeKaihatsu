<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlpriceProductmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlpriceProductmenu = new htmlpriceProductmenu();
 $htmlpriceProduct = $htmlpriceProductmenu->priceProductsmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlpriceProduct;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>
<br>
<div class="priceProducts index large-9 medium-8 columns content">
  <h2><font color=red><?= __('製品単価一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
              <th scope="col" style='width:100'><font color=black><?= __('No.') ?></font></th>
                <th scope="col" style='width:200'><?= $this->Paginator->sort('product_id', ['label'=>"社内品番"]) ?></th>
                <th scope="col" style='width:200'><?= $this->Paginator->sort('custmoer_id', ['label'=>"顧客"]) ?></th>
                <th scope="col" style='width:200'><?= $this->Paginator->sort('price', ['label'=>"単価"]) ?></th>
                <th scope="col" style='width:100' class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($priceProducts as $priceProduct): ?>
            <tr>
              <td><?= h($i) ?></td>
              <td><?= h($priceProduct->product->product_code) ?></td>
              <td><?= h($priceProduct->customer->name) ?></td>
              <td><?= $this->Number->format($priceProduct->price) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('詳細'), ['action' => 'detail', $priceProduct->id]) ?>
                </td>
            </tr>
          <?php
          $i = $i + 1;
          ?>
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
