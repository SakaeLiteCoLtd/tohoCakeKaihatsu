<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlproductmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlproductmenu = new htmlproductmenu();
 $htmlproduct = $htmlproductmenu->productmenus($check_gyoumu);
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlproduct;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>

<br>
<div class="products index large-9 medium-8 columns content">
  
<?php if ($check_gyoumu == 1) : ?>

<div style="text-align: right;">
<a style="text-decoration: none" alien="center" href='/products/addform' class="buttonlayout"/><font size='4' color=black><?= __('▷新規登録') ?></font></a>
</div>
<?php else : ?>
  <?php endif; ?>

  <h2><font color=red><?= __('製品一覧') ?></font></h2>

  <?php
/*
  <table>
      <tbody class='sample non-sample'>
        <tr alien='right'>
          <td style="border-style:none; background-color: #E6FFFF"><?= __("　　　　　　　　　　　　　　　　　") ?></td>
          <td style="border-style:none; background-color: #E6FFFF"><?= __("　　　　　　　　　　　　　　　　　") ?></td>
          <td style="border:none; background-color: #E6FFFF" class="actions"><?= $this->Html->link(__('データの新しい順に並び替え'), ['action' => 'ichiran', "narabikae"]) ?></td>
        </tr>
      </tbody>
    </table>
*/
    ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
              <th scope="col" style='width:50'><font color=black><?= __('No.') ?></font></th>
                <th scope="col" style='width:160'><?= $this->Paginator->sort('product_code', ['label'=>"製品コード"]) ?></th>
                <th scope="col" style='width:400'><?= $this->Paginator->sort('name', ['label'=>"品名"]) ?></th>
                <th scope="col" style='width:250'><?= $this->Paginator->sort('custmoer_id', ['label'=>"得意先"]) ?></th>

                <?php if ($check_gyoumu == 1) : ?>

                <th scope="col" style='width:70'><?= __('') ?></th>

                <?php else : ?>
  <?php endif; ?>

            </tr>
        </thead>

        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
              <td><?= h($i) ?></td>
                <td><?= h($product->product_code) ?></td>
                <td><?= h($product->name) ?></td>
                <td><?= h($product->customer->name) ?></td>

                <?php if ($check_gyoumu == 1) : ?>

                <td class="actions">
                  <?= $this->Html->link(__('編集'), ['action' => 'detail', $product->id]) ?>
                </td>

                <?php else : ?>
  <?php endif; ?>

            </tr>

          <?php
          $i = $i + 1;
          ?>

            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
          <?= $this->Paginator->first('<< ' . __('最初のページ')) ?>
          <?= $this->Paginator->prev('< ' . __('前へ')) ?>
          <?= $this->Paginator->numbers() ?>
          <?= $this->Paginator->next(__('次へ') . ' >') ?>
          <?= $this->Paginator->last(__('最後のページ') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
