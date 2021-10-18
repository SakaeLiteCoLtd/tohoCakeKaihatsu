<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlaccountmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlaccountmenu = new htmlaccountmenu();
 $htmlproductcodemenus = $htmlaccountmenu->productcodemenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlproductcodemenus;
?>

<?= $this->Form->create($product, ['url' => ['action' => 'productcodeeditdo']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('製品コード修正') ?></strong></legend>
        <table>
          <tbody class='sample non-sample'>
          <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __("以下の内容でよろしければ「決定」ボタンを押してください") ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
        <td width="200"><strong>新製品コード</strong></td>
        <td width="200"><strong>製品コード（変更前）</strong></td>
        </tr>
        <tr>
        <td><?= h($product_code) ?></td>
        <td><?= h($product_code_moto) ?></td>
        </tr>
      </table>

      <table>
        <tr>
          <td width="180"><strong>工場名</strong></td>
          <td width="380"><strong>得意先</strong></td>
        </tr>
        <tr>
        <td><?= h($factory_name) ?></td>
        <td><?= h($customer_name) ?></td>
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

     <?php if (count($ProductName) > 0): ?>
      <table>
          <tbody class='sample non-sample'>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red">
          <?= __($mess) ?></strong></td></tr>
          </tbody>
        </table>

      <?php else :?>
          <?php endif; ?>


<table>
 <tr>
 <td><strong>製品コード</strong></td>
 <td width="200"><strong>品名</strong></td>
<td><strong>長さ（mm）</strong></td>
 <td><strong>カット長さ（mm）</strong></td>
   <td width="50"><strong>上限</strong></td>
   <td width="50"><strong>下限</strong></td>
   <td width="120"><strong>備考</strong></td>
 </tr>
 
 <?php for($i=0; $i<count($ProductName); $i++): ?>

 <tr>
 <td><?= h($ProductName[$i]["product_code"]) ?></td>
 <td><?= h($ProductName[$i]["name"]) ?></td>
 <td><?= h($ProductName[$i]["length"]) ?></td>
 <td><?= h($ProductName[$i]["length_cut"]) ?></td>
 <td><?= h($ProductName[$i]["length_upper_limit"]) ?></td>
 <td><?= h($ProductName[$i]["length_lower_limit"]) ?></td>
 <td><?= h($ProductName[$i]["bik"]) ?></td>

 <?php endfor;?>

</table>

     <table>
      <tbody class='sample non-sample'>
        <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border:none"><?= $this->Form->submit(__('確認')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$factory_id, 'label'=>false)) ?>
    <?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
    <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>

    </fieldset>

    <?= $this->Form->end() ?>
  </nav>
