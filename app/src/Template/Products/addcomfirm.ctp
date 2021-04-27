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

<?= $this->Form->create($product, ['url' => ['action' => 'adddo']]) ?>

<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$this->request->getData('product_code'), 'label'=>false)) ?>
<?= $this->Form->control('customer_product_code', array('type'=>'hidden', 'value'=>$this->request->getData('customer_product_code'), 'label'=>false)) ?>
<?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$this->request->getData('name'), 'label'=>false)) ?>
<?= $this->Form->control('customer_id', array('type'=>'hidden', 'value'=>$this->request->getData('customer_id'), 'label'=>false)) ?>

<nav class="large-3 medium-4 columns" style="width:70%">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('製品新規登録') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを登録します。よろしければ「決定」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="280"><strong>社内品番</strong></td>
          <td width="280"><strong>品番（顧客）</strong></td>
        </tr>
        <tr>
          <td><?= h($this->request->getData('product_code')) ?></td>
          <td><?= h($this->request->getData('customer_product_code')) ?></td>
        </tr>
      </table>

      <table>
      <tr>
        <td width="280"><strong>品名</strong></td>
        <td width="280"><strong>顧客</strong></td>
      </tr>
      <tr>
        <td><?= h($this->request->getData('name')) ?></td>
        <td><?= h($customer_name) ?></td>
      </tr>
    </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border-style: none;"><div><?= $this->Form->submit('決定', array('name' => 'kettei')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
