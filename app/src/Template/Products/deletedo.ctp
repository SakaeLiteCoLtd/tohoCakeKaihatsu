<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlproductmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlproductmenu = new htmlproductmenu();
 $htmlproduct = $htmlproductmenu->productmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlproduct;
?>

<form method="post" action="/products/index">

<?= $this->Form->create($product, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<?php
//<nav class="large-3 medium-4 columns">
?>

<nav class="sample non-sample">
    <?= $this->Form->create($product) ?>
    <fieldset>

      <legend><strong style="font-size: 15pt; color:red"><?= __('製品情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>社内品番</strong></td>
            <td width="280"><strong>顧客品番</strong></td>
        	</tr>
          <tr>
            <td><?= h($product['product_code']) ?></td>
            <td><?= h($product['customer_product_code']) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>品名</strong></td>
            <td width="280"><strong>顧客</strong></td>
        	</tr>
          <tr>
            <td><?= h($product['name']) ?></td>
            <td><?= h($product->customer->name) ?></td>
        	</tr>
        </table>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('製品メニュートップへ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
