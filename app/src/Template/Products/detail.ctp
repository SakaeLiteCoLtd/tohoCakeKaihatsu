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

<?= $this->Form->create($products, ['url' => ['action' => 'detail']]) ?>

<?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
<br><br><br>

<nav class="large-3 medium-4 columns">
    <fieldset>
      <table>
      <tbody class='sample non-sample'>
        <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('詳細表示') ?></strong></td></tr>
      </tbody>
    </table>

    <table>
      <tr>
        <td width="280"><strong>工場・営業所名</strong></td>
      </tr>
      <tr>
        <td><?= h($factory_name) ?></td>
      </tr>
    </table>
    <table>
    <tr>
      <td width="280"><strong>社内品番</strong></td>
      <td width="280"><strong>顧客品番</strong></td>
    </tr>
    <tr>
      <td><?= h($product_code) ?></td>
      <td><?= h($customer_product_code) ?></td>
    </tr>
  </table>

  <table>
  <tr>
    <td width="280"><strong>品名</strong></td>
    <td width="280"><strong>顧客</strong></td>
  </tr>
  <tr>
    <td><?= h($name) ?></td>
    <td><?= h($customer_name) ?></td>
  </tr>
</table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('編集', array('name' => 'edit')); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border-style: none;"><div><?= $this->Form->submit('削除', array('name' => 'delete')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <br><br><br>

    <?= $this->Form->end() ?>
  </nav>
