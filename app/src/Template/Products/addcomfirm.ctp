<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlproductmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlproductmenu = new htmlproductmenu();
 $htmlproduct = $htmlproductmenu->productmenus($check_gyoumu);
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

<?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$factory_id, 'label'=>false)) ?>
<?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$name, 'label'=>false)) ?>
<?= $this->Form->control('tanni', array('type'=>'hidden', 'value'=>$tanni, 'label'=>false)) ?>
<?= $this->Form->control('weight', array('type'=>'hidden', 'value'=>$weight, 'label'=>false)) ?>
<?= $this->Form->control('customer_name', array('type'=>'hidden', 'value'=>$customer_name, 'label'=>false)) ?>
<?= $this->Form->control('tuikalength', array('type'=>'hidden', 'value'=>$tuikalength, 'label'=>false)) ?>
<?= $this->Form->control('ig_bank_modes', array('type'=>'hidden', 'value'=>$ig_bank_modes, 'label'=>false)) ?>
<br><br><br>

<?php
if($ig_bank_modes == 0){
  $ig_bank_modes_name = "X-Y";
}else{
  $ig_bank_modes_name = "Y-Y";
}
?>

<nav class="sample non-sample">
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
      <td><strong>検査表に表示</strong></td>
      <td><strong>長さ（mm）</strong></td>
        <td><strong>カット長さ（mm）</strong></td>
        <td><strong>長さ測定有無</strong></td>
        <td width="50"><strong>公差下限</strong></td>
        <td width="50"><strong>公差上限</strong></td>
        <td width="200"><strong>備考</strong></td>
      </tr>
      
      <?php for($k=1; $k<=$tuikalength; $k++): ?>

      <tr>
      <td><?= h(${"status_kensahyou_name".$k}) ?></td>
      <?= $this->Form->control('status_kensahyou'.$k, array('type'=>'hidden', 'value'=>${"status_kensahyou".$k}, 'label'=>false)) ?>
      <?= $this->Form->control('status_kensahyou_name'.$k, array('type'=>'hidden', 'value'=>${"status_kensahyou_name".$k}, 'label'=>false)) ?>
      <td><?= h(${"length".$k}) ?></td>
      <?= $this->Form->control('length'.$k, array('type'=>'hidden', 'value'=>${"length".$k}, 'label'=>false)) ?>
      <td><?= h(${"length_cut".$k}) ?></td>
      <?= $this->Form->control('length_cut'.$k, array('type'=>'hidden', 'value'=>${"length_cut".$k}, 'label'=>false)) ?>
      <td><?= h(${"status_length_name".$k}) ?></td>
      <?= $this->Form->control('status_length'.$k, array('type'=>'hidden', 'value'=>${"status_length".$k}, 'label'=>false)) ?>
      <?= $this->Form->control('status_length_name'.$k, array('type'=>'hidden', 'value'=>${"status_length_name".$k}, 'label'=>false)) ?>
      <td><?= h(${"length_lower_limit".$k}) ?></td>
      <?= $this->Form->control('length_lower_limit'.$k, array('type'=>'hidden', 'value'=>${"length_lower_limit".$k}, 'label'=>false)) ?>
      <td><?= h(${"length_upper_limit".$k}) ?></td>
      <?= $this->Form->control('length_upper_limit'.$k, array('type'=>'hidden', 'value'=>${"length_upper_limit".$k}, 'label'=>false)) ?>
      <td><?= h(${"bik".$k}) ?></td>
      <?= $this->Form->control('bik'.$k, array('type'=>'hidden', 'value'=>${"bik".$k}, 'label'=>false)) ?>

      <?php endfor;?>

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
