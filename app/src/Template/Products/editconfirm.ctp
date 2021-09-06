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

<?= $this->Form->create($product, ['url' => ['action' => 'editdo']]) ?>

<?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$this->request->getData('factory_id'), 'label'=>false)) ?>
<?= $this->Form->control('namemoto', array('type'=>'hidden', 'value'=>$this->request->getData('namemoto'), 'label'=>false)) ?>
<?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$this->request->getData('name'), 'label'=>false)) ?>
<?= $this->Form->control('tanni', array('type'=>'hidden', 'value'=>$this->request->getData('tanni'), 'label'=>false)) ?>
<?= $this->Form->control('weight', array('type'=>'hidden', 'value'=>$this->request->getData('weight'), 'label'=>false)) ?>
<?= $this->Form->control('factory_name', array('type'=>'hidden', 'value'=>$this->request->getData('factory_name'), 'label'=>false)) ?>
<?= $this->Form->control('status_kensahyou', array('type'=>'hidden', 'value'=>$this->request->getData('status_kensahyou'), 'label'=>false)) ?>
<?= $this->Form->control('customer_name', array('type'=>'hidden', 'value'=>$this->request->getData('customer_name'), 'label'=>false)) ?>
<br><br><br>

<?php
if($this->request->getData('status_kensahyou') == 1){
  $status_kensahyou_name = "表示";
}else{
  $status_kensahyou_name = "非表示";
}
?>

<?php
//<nav class="large-3 medium-4 columns">
?>

<nav class="sample non-sample">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('製品情報編集・削除') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のように更新します。よろしければ「決定」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="180"><strong>自社工場</strong></td>
          <td width="380"><strong>得意先</strong></td>
        </tr>
        <tr>
        <td><?= h($this->request->getData('factory_name')) ?></td>
        <td><?= h($this->request->getData('customer_name')) ?></td>
        </tr>
      </table>

      <table>
          <tr>
          <td width="380"><strong>品名</strong></td>
          <td width="90"><strong>単位</strong></td>
            <td width="90"><strong>単重(g/m)</strong></td>
            <td width="50"><strong>検査表</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('name')) ?></td>
            <td><?= h($this->request->getData('tanni')) ?></td>
            <td><?= h($this->request->getData('weight')) ?></td>
            <td><?= h($status_kensahyou_name) ?></td>
        	</tr>
        </table>

     <br>

     <table>
      <tr>
      <td><strong>管理No.</strong></td>
      <td width="300"><strong>品名</strong></td>
      <td><strong>長さ（mm）</strong></td>
      <td><strong>カット長さ（mm）</strong></td>
      </tr>
      
      <?php for($i=0; $i<count($arrKoushinproduct); $i++): ?>

      <tr>
      <td><?= h($arrKoushinproduct[$i]["product_code"]) ?></td>
      <td><?= h($arrKoushinproduct[$i]["name"]) ?></td>
      <td><?= h($arrKoushinproduct[$i]["length"]) ?></td>
      <td><?= h($arrKoushinproduct[$i]["length_cut"]) ?></td>
      </tr>

      <?= $this->Form->control('product_code'.$i, array('type'=>'hidden', 'value'=>$arrKoushinproduct[$i]["product_code"], 'label'=>false)) ?>
      <?= $this->Form->control('name'.$i, array('type'=>'hidden', 'value'=>$arrKoushinproduct[$i]["name"], 'label'=>false)) ?>
      <?= $this->Form->control('length'.$i, array('type'=>'hidden', 'value'=>$arrKoushinproduct[$i]["length"], 'label'=>false)) ?>
      <?= $this->Form->control('length_cut'.$i, array('type'=>'hidden', 'value'=>$arrKoushinproduct[$i]["length_cut"], 'label'=>false)) ?>
      <?= $this->Form->control('num', array('type'=>'hidden', 'value'=>$i, 'label'=>false)) ?>

      <?php endfor;?>

      </table>

      <?php if (count($arrDeleteproduct) > 0) : ?>

      <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータは削除します。') ?></strong></td></tr>
          </tbody>
        </table>

        <table>
      <tr>
      <td><strong>管理No.</strong></td>
      <td width="300"><strong>品名</strong></td>
      <td><strong>長さ（mm）</strong></td>
      <td><strong>カット長さ（mm）</strong></td>
      </tr>

      <?php for($i=0; $i<count($arrDeleteproduct); $i++): ?>

        <tr>
        <td><?= h($arrDeleteproduct[$i]["product_code"]) ?></td>
        <td><?= h($arrDeleteproduct[$i]["name"]) ?></td>
        <td><?= h($arrDeleteproduct[$i]["length"]) ?></td>
        <td><?= h($arrDeleteproduct[$i]["length_cut"]) ?></td>
        </tr>

        <?= $this->Form->control('delete_product_code'.$i, array('type'=>'hidden', 'value'=>$arrDeleteproduct[$i]["product_code"], 'label'=>false)) ?>
        <?= $this->Form->control('delete_name'.$i, array('type'=>'hidden', 'value'=>$arrDeleteproduct[$i]["name"], 'label'=>false)) ?>
        <?= $this->Form->control('delete_length_cut'.$i, array('type'=>'hidden', 'value'=>$arrDeleteproduct[$i]["length_cut"], 'label'=>false)) ?>
        <?= $this->Form->control('delete_length'.$i, array('type'=>'hidden', 'value'=>$arrDeleteproduct[$i]["length"], 'label'=>false)) ?>
        <?= $this->Form->control('delete_num', array('type'=>'hidden', 'value'=>$i, 'label'=>false)) ?>

        <?php endfor;?>

     </table>

     <?php else : ?>
      <?php endif; ?>

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
    <br><br><br>
    <?= $this->Form->end() ?>
  </nav>
