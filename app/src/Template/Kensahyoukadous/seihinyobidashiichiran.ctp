<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyouseihinmenus = $htmlkensahyoukadoumenu->kensahyouseihinmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>
<table>
  <tbody class='sample non-sample' style='border: none;text-align: left; background-color:#E6FFFF'>
    <td style='border: none;text-align: left; background-color:#E6FFFF'>
      <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyouseihinmenu' /><font size='4' color=black>製品関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/seihinyobidashimenu' /><font size='4' color=black>製品呼出</font></a>
      <font size='4'>　　　　　　　　　　　　</font>
      <font size='4'>　　　　　　　　　　　　</font>
      <font size='4'>　　　　　　　　　　　　</font>
      <font size='4'>　　　　　　　　　　　　</font>
    </a></td>
  </tbody>
</table>

<div class="products index large-9 medium-8 columns content">

  <table>
      <tbody class='sample non-sample'>
        <tr alien='right'>
          <td style="border-style:none; background-color: #E6FFFF"><?= __("　　　　　　　　　　　　　　　　　") ?></td>
          <td style="border-style:none; background-color: #E6FFFF"><?= __("　　　　　　　　　　　　　　　　　") ?></td>
          <td style="border:none; background-color: #E6FFFF" class="actions"><?= $this->Html->link(__('データの新しい順に並び替え'), ['action' => 'seihinyobidashiichiran', "narabikae"]) ?></td>
        </tr>
      </tbody>
    </table>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
              <th scope="col" style='width:100'><font color=black><?= __('No.') ?></font></th>
                <th scope="col" style='width:200'><?= $this->Paginator->sort('product_code', ['label'=>"製品コード"]) ?></th>
                <th scope="col" style='width:200'><?= $this->Paginator->sort('name', ['label'=>"品名"]) ?></th>
                <th scope="col" style='width:200'><?= $this->Paginator->sort('custmoer_id', ['label'=>"得意先"]) ?></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
              <td><?= h($i) ?></td>
                <td><?= h($product->product_code) ?></td>
                <td><?= h($product->name) ?></td>
                <td><?= h($product->customer->name) ?></td>
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
