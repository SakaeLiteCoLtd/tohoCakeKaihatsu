<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php

if(!isset($_SESSION)){
  session_start();
  header('Expires:-1');
  header('Cache-Control:');
  header('Pragma:');
}

 use App\myClass\menulists\htmlproductmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlproductmenu = new htmlproductmenu();
 $htmlproduct = $htmlproductmenu->productmenus($check_gyoumu);
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>

<?php
if($this->request->getData('ig_bank_modes') == 0){
  $ig_bank_modes_name = "X-Y";
}else{
  $ig_bank_modes_name = "Y-Y";
}
?>

<?php
     echo $htmllogin;
?>
<?php
     echo $htmlproduct;
?>

<?= $this->Form->create($product, ['url' => ['action' => 'addformlength']]) ?>
<br><br><br>
<?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$this->request->getData('factory_id'), 'label'=>false)) ?>
<?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$this->request->getData('name'), 'label'=>false)) ?>
<?= $this->Form->control('tanni', array('type'=>'hidden', 'value'=>$this->request->getData('tanni'), 'label'=>false)) ?>
<?= $this->Form->control('weight', array('type'=>'hidden', 'value'=>$this->request->getData('weight'), 'label'=>false)) ?>
<?= $this->Form->control('customer_name', array('type'=>'hidden', 'value'=>$this->request->getData('customer_name'), 'label'=>false)) ?>
<?= $this->Form->control('status_kensahyou', array('type'=>'hidden', 'value'=>$this->request->getData('status_kensahyou'), 'label'=>false)) ?>
<?= $this->Form->control('tuikalength', array('type'=>'hidden', 'value'=>$tuikalength, 'label'=>false)) ?>
<?= $this->Form->control('ig_bank_modes', array('type'=>'hidden', 'value'=>$this->request->getData('ig_bank_modes'), 'label'=>false)) ?>

<?php
//<nav class="large-3 medium-4 columns">
?>

<nav class="sample non-sample">

    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('製品新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt;"><?= __('長さを入力してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
        <td width="150"><strong>工場名</strong></td>
        <td width="350"><strong>得意先</strong></td>
        </tr>
        <tr>
        <td><?= h($factory_name) ?></td>
        <td><?= h($customer_name) ?></td>
        </tr>
      </table>
      <table>
        <tr>
        <td width="280"><strong>品名</strong></td>
        <td><strong>単位</strong></td>
        <td><strong>単重(g/m)</strong></td>
        <td><strong>幅測定器検査モード</strong></td>
        </tr>
        <tr>
        <td><?= h($name) ?></td>
        <td><?= h($tanni) ?></td>
        <td><?= h($weight) ?></td>
        <td><?= h($ig_bank_modes_name) ?></td>
        </tr>
      </table>

      <table>
      <tr>
      <td width="50"><strong>検査表に表示</strong></td>
        <td width="50"><strong>長さ（mm）</strong></td>
        <td width="50"><strong>カット長さ（mm）</strong></td>
        <td width="50"><strong>長さ測定有無</strong></td>
        <td width="50"><strong>※公差上限</strong></td>
        <td width="50"><strong>※公差下限</strong></td>
        <td width="50"><strong>※測定器具</strong></td>
        <td width="200"><strong>※備考</strong></td>
      </tr>
      
      <?php for($k=1; $k<=$tuikalength; $k++): ?>

      <tr>
      <td><?= $this->Form->control('status_kensahyou'.$k, ['options' => $arrStatusKensahyou, 'label'=>false]) ?></td>
      <td><?= $this->Form->control
      ('length'.$k, array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6, 'required' => 'true', 'autocomplete'=>"off")) ?></td>
      <td><?= $this->Form->control
      ('length_cut'.$k, array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6, 'required' => 'true', 'autocomplete'=>"off")) ?></td>
      <td><?= $this->Form->control('status_length'.$k, ['options' => $arrStatusLength, 'label'=>false]) ?></td>
      <td><?= $this->Form->control
      ('length_upper_limit'.$k, array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6, 'autocomplete'=>"off")) ?></td>
      <td><?= $this->Form->control
      ('length_lower_limit'.$k, array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6, 'autocomplete'=>"off")) ?></td>
      <td><?= $this->Form->control('length_measuring_instrument'.$k, ['options' => $arrkensakigu, 'label'=>false]) ?></td>
        <td><?= $this->Form->control('bik'.$k, array('type'=>'text', 'label'=>false, 'autocomplete'=>"off")) ?></td>
      </tr>

      <?php endfor;?>

     </table>

    <table>
      <tbody class='sample non-sample'>
      <tr>
      <td style="border:none">　　　　　　　　　　　　</td>
      <td style="border:none">　　　　　　　　　　　　</td>
      <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('長さ追加'), array('name' => 'tuika')) ?></td>
      <td style="border:none"><?= $this->Form->submit(('登録確認'), array('name' => 'kakuninn')) ?></td>
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
    <br><br><br>

    <?= $this->Form->end() ?>
  </nav>
