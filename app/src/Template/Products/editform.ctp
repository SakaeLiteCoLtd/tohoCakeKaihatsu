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

<?= $this->Form->create($product, ['url' => ['action' => 'editconfirm']]) ?>
<br><br><br>
<?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$factory_id, 'label'=>false)) ?>
<?= $this->Form->control('namemoto', array('type'=>'hidden', 'value'=>$name, 'label'=>false)) ?>
<?= $this->Form->control('factory_name', array('type'=>'hidden', 'value'=>$factory_name, 'label'=>false)) ?>
<?= $this->Form->control('customer_name', array('type'=>'hidden', 'value'=>$customer_name, 'label'=>false)) ?>

<?php
//<nav class="large-3 medium-4 columns">
?>

<nav class="sample non-sample">

    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('製品情報編集・削除') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red">
          <?= __('製品コードと得意先は変更できません。') ?>
          </strong></td></tr>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
          </tbody>
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
          <td width="90"><strong>※単位</strong></td>
            <td width="90"><strong>※単重(g/m)</strong></td>
            <td><strong>幅測定器検査モード</strong></td>
        	</tr>
          <tr>
          <td><?= $this->Form->control('tanni', ['options' => $arrTanni, 'value'=>$tanni, 'label'=>false]) ?></td>
            <td><?= $this->Form->control('weight', array('type'=>'text', 'value'=>$weight, 'pattern'=>'^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'label'=>false, 'size'=>6, 'autocomplete'=>"off")) ?></td>
            <td><?= $this->Form->control('ig_bank_modes', ['options' => $arrig_bank_modes, 'value'=>$ig_bank_modes, 'label'=>false]) ?></td>
        	</tr>
        </table>

     <br>

     <table>
      <tr>
      <td><strong>製品コード</strong></td>
      <td width="100"><strong>品名</strong></td>
      <td width="20"><strong style="font-size: 9pt">検査表<br>に表示</strong></td>
      <td width="40"><strong>長さ<br>（mm）</strong></td>
      <td width="40"><strong style="font-size: 9pt">カット長さ（mm）</strong></td>
      <td width="40"><strong>長さ<br>測定<br>有無</strong></td>
       <td width="20"><strong style="font-size: 10pt">※公差<br>下限</strong></td>
        <td width="20"><strong style="font-size: 10pt">※公差<br>上限</strong></td>
        <td width="80"><strong>※備考</strong></td>
      <td><strong>削除</strong></td>
      </tr>
      
      <?php for($i=0; $i<count($ProductName); $i++): ?>

      <tr>
      <td><?= h($ProductName[$i]["product_code"]) ?></td>
      <td><?= $this->Form->control
      ('name'.$i, array('type'=>'text', 'label'=>false, 'value'=>$ProductName[$i]["name"], 'required' => 'true')) ?></td>
      <td><?= $this->Form->control('status_kensahyou'.$i, ['options' => $arrStatusKensahyou, 'value'=>$ProductName[$i]["status_kensahyou"], 'label'=>false]) ?></td>
      <td><?= $this->Form->control
      ('length'.$i, array('type'=>'text', 'label'=>false, 'value'=>$ProductName[$i]["length"], 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>3, 'required' => 'true', 'autocomplete'=>"off")) ?></td>
      <td><?= $this->Form->control
      ('length_cut'.$i, array('type'=>'text', 'label'=>false, 'value'=>$ProductName[$i]["length_cut"], 'pattern' => '^[0-9.-]+$','size'=>3,  'title'=>'半角数字で入力して下さい。', 'required' => 'true', 'autocomplete'=>"off")) ?></td>
      <td><?= $this->Form->control('status_length'.$i, ['options' => $arrStatusLength, 'value'=>$ProductName[$i]["status_length"], 'label'=>false]) ?></td>
      <td><?= $this->Form->control
      ('length_lower_limit'.$i, array('type'=>'text', 'label'=>false, 'value'=>$ProductName[$i]["length_lower_limit"], 'pattern' => '^[0-9.-]+$','size'=>1,  'title'=>'半角数字で入力して下さい。', 'autocomplete'=>"off")) ?></td>
      <td><?= $this->Form->control
      ('length_upper_limit'.$i, array('type'=>'text', 'label'=>false, 'value'=>$ProductName[$i]["length_upper_limit"], 'pattern' => '^[0-9.-]+$','size'=>1,  'title'=>'半角数字で入力して下さい。', 'autocomplete'=>"off")) ?></td>
      <td><?= $this->Form->control
      ('bik'.$i, array('type'=>'text', 'label'=>false, 'value'=>$ProductName[$i]["bik"], 'size'=>10)) ?></td>
      <td><?= $this->Form->control('delete'.$i, array('type'=>'checkbox', 'label'=>false)) ?></td>
      </tr>

      <?= $this->Form->control('product_code'.$i, array('type'=>'hidden', 'value'=>$ProductName[$i]["product_code"], 'label'=>false)) ?>
      <?= $this->Form->control('num', array('type'=>'hidden', 'value'=>$i, 'label'=>false)) ?>

      <?php endfor;?>

     </table>

     <table>
      <tbody class='sample non-sample'>
        <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>
    <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 12pt">
            <?= __('「※」のついている欄は空白のまま登録できます。') ?>
          </strong></td></tr>
          </tbody>
        </table>

    </fieldset>

    <?= $this->Form->end() ?>
  </nav>
