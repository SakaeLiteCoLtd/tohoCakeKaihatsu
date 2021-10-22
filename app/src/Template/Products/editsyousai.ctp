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

if($status_kensahyou == 0){
  $status_kensahyou_name = "表示";
}else{
  $status_kensahyou_name = "非表示";
}
?>

<?= $this->Form->create($product, ['url' => ['action' => 'editform']]) ?>
<br><br><br>

<?php
//<nav class="large-3 medium-4 columns">
?>

<nav class="sample non-sample">

    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('製品情報詳細') ?></strong></legend>
        <br>

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
            <td width="90"><strong>単位</strong></td>
            <td width="90"><strong>単重(g/m)</strong></td>
            <td><strong>幅測定器モード番号</strong></td>
        	</tr>
          <tr>
          <td><?= h($name) ?></td>
          <td><?= h($tanni) ?></td>
          <td><?= h($weight) ?></td>
          <td><?= h($ig_bank_modes) ?></td>
       	</tr>
        </table>

     <br>

<table>
 <tr>
 <td><strong>製品コード</strong></td>
 <td width="200"><strong>品名</strong></td>
 <td width="90"><strong>検査表に表示</strong></td>
<td><strong>長さ（mm）</strong></td>
 <td><strong>カット長さ（mm）</strong></td>
   <td width="50"><strong>公差下限</strong></td>
   <td width="50"><strong>公差上限</strong></td>
   <td width="120"><strong>備考</strong></td>
 </tr>
 
 <?php for($i=0; $i<count($ProductName); $i++): ?>

 <tr>
 <td><?= h($ProductName[$i]["product_code"]) ?></td>
 <td><?= h($ProductName[$i]["name"]) ?></td>
 <?php
      if($ProductName[$i]["status_kensahyou"] == 0){
        $status_kensahyou_name = "表示";
      }else{
        $status_kensahyou_name = "非表示";
      }
?>

      <td><?= h($status_kensahyou_name) ?></td>
 <td><?= h($ProductName[$i]["length"]) ?></td>
 <td><?= h($ProductName[$i]["length_cut"]) ?></td>
 <td><?= h($ProductName[$i]["length_lower_limit"]) ?></td>
 <td><?= h($ProductName[$i]["length_upper_limit"]) ?></td>
 <td><?= h($ProductName[$i]["bik"]) ?></td>

 <?php endfor;?>

</table>

     <table>
      <tbody class='sample non-sample'>
        <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border:none"><?= $this->Form->submit(__('編集・削除')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$factory_id, 'label'=>false)) ?>
    <?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$name, 'label'=>false)) ?>

    </fieldset>

    <?= $this->Form->end() ?>
  </nav>
