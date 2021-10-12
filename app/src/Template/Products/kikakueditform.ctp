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

<?= $this->Form->create($product, ['url' => ['action' => 'kikakueditconfirm']]) ?>
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
          <td width="90"><strong>単位</strong></td>
            <td width="90"><strong>単重(g/m)</strong></td>
            <td width="50"><strong>幅測定器モード番号</strong></td>
        	</tr>
          <tr>
          <td><?= h($ProductName[$i]["tanni"]) ?></td>
          <td><?= h($ProductName[$i]["weight"]) ?></td>
            <td><?= $this->Form->control('ig_bank_modes', ['options' => $arrig_bank_modes, 'value'=>$ig_bank_modes, 'label'=>false]) ?></td>
            <?= $this->Form->control('tanni', array('type'=>'hidden', 'value'=>$ProductName[$i]["tanni"], 'label'=>false)) ?>
      <?= $this->Form->control('weight', array('type'=>'hidden', 'value'=>$ProductName[$i]["weight"], 'label'=>false)) ?>
        	</tr>
        </table>

     <br>

     <table>
      <tr>
      <td><strong>製品コード</strong></td>
      <td width="150"><strong>品名</strong></td>
      <td width="90"><strong>検査表に表示</strong></td>
      <td width="90"><strong>長さ<br>（mm）</strong></td>
      <td width="90"><strong style="font-size: 11pt">カット長さ<br>（mm）</strong></td>
        <td width="50"><strong style="font-size: 11pt">上限</strong></td>
        <td width="50"><strong style="font-size: 11pt">下限</strong></td>
        <td width="120"><strong>備考</strong></td>
      </tr>
      
      <?php for($i=0; $i<count($ProductName); $i++): ?>

      <tr>
      <td><?= h($ProductName[$i]["product_code"]) ?></td>
      <td><?= h($ProductName[$i]["name"]) ?></td>
      <td><?= $this->Form->control('status_kensahyou'.$i, ['options' => $arrStatusKensahyou, 'value'=>$ProductName[$i]["status_kensahyou"], 'label'=>false]) ?></td>
      <td><?= h($ProductName[$i]["length"]) ?></td>
      <td><?= $this->Form->control
      ('length_cut'.$i, array('type'=>'text', 'label'=>false, 'value'=>$ProductName[$i]["length_cut"], 'pattern' => '^[0-9.-]+$','size'=>3,  'title'=>'半角数字で入力して下さい。')) ?></td>
      <td><?= $this->Form->control
      ('length_upper_limit'.$i, array('type'=>'text', 'label'=>false, 'value'=>$ProductName[$i]["length_upper_limit"], 'pattern' => '^[0-9.-]+$','size'=>1,  'title'=>'半角数字で入力して下さい。')) ?></td>
      <td><?= $this->Form->control
      ('length_lower_limit'.$i, array('type'=>'text', 'label'=>false, 'value'=>$ProductName[$i]["length_lower_limit"], 'pattern' => '^[0-9.-]+$','size'=>1,  'title'=>'半角数字で入力して下さい。')) ?></td>
      <td><?= $this->Form->control
      ('bik'.$i, array('type'=>'text', 'label'=>false, 'value'=>$ProductName[$i]["bik"], 'size'=>15)) ?></td>
      </tr>

      <?= $this->Form->control('product_code'.$i, array('type'=>'hidden', 'value'=>$ProductName[$i]["product_code"], 'label'=>false)) ?>
      <?= $this->Form->control('name'.$i, array('type'=>'hidden', 'value'=>$ProductName[$i]["name"], 'label'=>false)) ?>
      <?= $this->Form->control('length'.$i, array('type'=>'hidden', 'value'=>$ProductName[$i]["length"], 'label'=>false)) ?>
      <?= $this->Form->control('num', array('type'=>'hidden', 'value'=>$i, 'label'=>false)) ?>

      <?= $this->Form->control('delete'.$i, array('type'=>'hidden', 'value'=>0, 'label'=>false)) ?>

      <?php endfor;?>

     </table>

     <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    </fieldset>

    <?= $this->Form->end() ?>
  </nav>
