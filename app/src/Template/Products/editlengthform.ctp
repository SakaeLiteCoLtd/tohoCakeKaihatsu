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

<?= $this->Form->create($product, ['url' => ['action' => 'editlengthform']]) ?>
<br><br><br>
<?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$factory_id, 'label'=>false)) ?>
<?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$product_name_code, 'label'=>false)) ?>
<?= $this->Form->control('tuikalength', array('type'=>'hidden', 'value'=>$tuikalength, 'label'=>false)) ?>
<?= $this->Form->control('product_name_code', array('type'=>'hidden', 'value'=>$product_name_code, 'label'=>false)) ?>

<nav class="sample non-sample">

    <?= $this->Form->create($product) ?>
    <fieldset>
    <legend><strong style="font-size: 15pt; color:red"><?= __('製品長さ追加') ?></strong></legend>
        <br>

        <table>
        <tr>
          <td width="280"><strong>工場名</strong></td>
        </tr>
        <tr>
        <td><?= h($factory_name) ?></td>
        </tr>
      </table>

      <table>
          <tbody class='sample non-sample'>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt;"><?= __('登録済み長さ一覧') ?></strong></td></tr>
          </tbody>
        </table>

    <table>
      <tr>
        <td><strong>製品コード</strong></td>
        <td width="200"><strong>品名</strong></td>
        <td width="50"><strong>検査表<br>に表示</strong></td>
        <td><strong>長さ（mm）</strong></td>
        <td width="80"><strong>カット長さ（mm）</strong></td>
        <td width="50"><strong>長さ<br>測定<br>有無</strong></td>
        <td width="50"><strong>公差<br>上限</strong></td>
        <td width="50"><strong>公差<br>下限</strong></td>
        <td width="200"><strong>備考</strong></td>
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

      <?php
      if($ProductName[$i]["status_length"] == 0){
        $status_length_name = "有";
      }else{
        $status_length_name = "無";
      }
?>

      <td><?= h($status_length_name) ?></td>

      <td><?= h($ProductName[$i]["length_upper_limit"]) ?></td>
      <td><?= h($ProductName[$i]["length_lower_limit"]) ?></td>
      <td><?= h($ProductName[$i]["bik"]) ?></td>
      </tr>

      <?php endfor;?>

     </table>

     <br>
     <table>
          <tbody class='sample non-sample'>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt;"><?= __('長さを追加してください') ?></strong></td></tr>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
          </tbody>
        </table>

     <table>
      <tr>
        <td><strong>品名</strong></td>
        <td width="50"><strong>検査表<br>に表示</strong></td>
        <td width="20"><strong style="font-size: 9pt">長さ<br>（mm）</strong></td>
        <td width="20"><strong style="font-size: 9pt">カット<br>長さ<br>（mm）</strong></td>
        <td width="80"><strong>長さ測定<br>有無</strong></td>
       <td width="20"><strong style="font-size: 10pt">※公差<br>上限</strong></td>
       <td width="20"><strong style="font-size: 10pt">※公差<br>下限</strong></td>
        <td width="20"><strong style="font-size: 10pt">※測定器具</strong></td>
        <td width="80"><strong>※備考</strong></td>
      </tr>
      
      <?php for($k=1; $k<=$tuikalength; $k++): ?>

      <tr>
      <td><?= $this->Form->control
      ('name'.$k, array('type'=>'text', 'label'=>false, 'value'=>${"name".$k}, 'required' => 'true', 'size'=>10)) ?></td>
      <td><?= $this->Form->control('status_kensahyou'.$k, ['options' => $arrStatusKensahyou, 'label'=>false]) ?></td>
      <td><?= $this->Form->control
      ('length'.$k, array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6, 'required' => 'true', 'autocomplete'=>"off",'size'=>1)) ?></td>
      <td><?= $this->Form->control
      ('length_cut'.$k, array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6, 'required' => 'true', 'autocomplete'=>"off",'size'=>1)) ?></td>
      <td><?= $this->Form->control('status_length'.$k, ['options' => $arrStatusLength, 'label'=>false]) ?></td>
      <td><?= $this->Form->control
      ('length_upper_limit'.$k, array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6, 'autocomplete'=>"off",'size'=>1)) ?></td>
      <td><?= $this->Form->control
      ('length_lower_limit'.$k, array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6, 'autocomplete'=>"off",'size'=>1)) ?></td>
      <td><?= $this->Form->control('length_measuring_instrument'.$k, ['options' => $arrkensakigu, 'label'=>false]) ?></td>
        <td><?= $this->Form->control('bik'.$k, array('type'=>'text', 'label'=>false, 'size'=>7)) ?></td>
      </tr>

      <?php endfor;?>

     </table>

     <table>
      <tbody class='sample non-sample'>
      <tr>
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

    <?= $this->Form->end() ?>
  </nav>
