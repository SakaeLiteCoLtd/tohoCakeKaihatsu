<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlaccountmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlaccountmenu = new htmlaccountmenu();
 $htmlproductdeletemenus = $htmlaccountmenu->productdeletemenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlproductdeletemenus;
?>

<?= $this->Form->create($product, ['url' => ['action' => 'productdeleteddo']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('削除済み製品復元') ?></strong></legend>
        <table>
          <tbody class='sample non-sample'>
          <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __("以下の製品を復元します。よろしければ「決定」ボタンを押してください") ?></strong></td></tr>
          </tbody>
        </table>
        <br>

      <table>
        <tr>
          <td width="180"><strong>工場名</strong></td>
          <td width="380"><strong>得意先</strong></td>
          <td width="200"><strong>製品コード</strong></td>
        </tr>
        <tr>
        <td><?= h($factory_name) ?></td>
        <td><?= h($customer_name) ?></td>
        <td><?= h($product_code) ?></td>
        </tr>
      </table>

      <table>
          <tr>
            <td width="380"><strong>品名</strong></td>
            <td width="90"><strong>長さ（mm）</strong></td>
            <td width="90"><strong>単位</strong></td>
            <td width="90"><strong>単重(g/m)</strong></td>
        	</tr>
          <tr>
          <td><?= h($name) ?></td>
          <td><?= h($length) ?></td>
          <td><?= h($tanni) ?></td>
          <td><?= h($weight) ?></td>
       	</tr>
        </table>

     <br>

     <table>
      <tbody class='sample non-sample'>
        <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border:none"><?= $this->Form->submit(__('決定')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
    <?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$factory_id, 'label'=>false)) ?>

    </fieldset>

    <?= $this->Form->end() ?>
  </nav>
