<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlpriceProductmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlpriceProductmenu = new htmlpriceProductmenu();
 $htmlpriceProduct = $htmlpriceProductmenu->priceProductsmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlpriceProduct;
?>

<form method="post" action="/priceProducts/editconfirm">

<?= $this->Form->create($priceProduct, ['url' => ['action' => 'editconfirm']]) ?>
<br><br><br>

<nav class="large-3 medium-4 columns">

    <?= $this->Form->create($priceProduct) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('製品単価情報編集') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('データを編集してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="150"><strong>製品</strong></td>
          <td width="150"><strong>顧客</strong></td>
          <td width="160"><strong>単価</strong></td>
        </tr>
        <tr>
          <td><?= $this->Form->control('product_id', ['options' => $arrProducts, 'label'=>false]) ?></td>
          <td><?= $this->Form->control('custmoer_id', ['options' => $arrCustomers, 'label'=>false]) ?></td>
          <td><?= $this->Form->control('price', array('type'=>'text', 'label'=>false)) ?></td>
        </tr>
      </table>

      <table>
      <tr>
        <td width="270"><strong>取引開始日</strong></td>
        <td width="270"><strong>取引終了日</strong></td>
      </tr>
      <tr>
        <td><?= $this->Form->input("start_deal", array('type' => 'date', 'minYear' => date('Y') - 70, 'monthNames' => false, 'label'=>false)); ?></td>
        <td><?= $this->Form->input("finish_deal", array('type' => 'date', 'monthNames' => false, 'label'=>false, 'empty' => true)); ?></td>
      </tr>
    </table>

    <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
