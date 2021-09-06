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

<?= $this->Form->create($product, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<?php
//<nav class="large-3 medium-4 columns">
?>

<?php

if($this->request->getData('status_kensahyou') == 1){
  $status_kensahyou_name = "表示";
}else{
  $status_kensahyou_name = "非表示";
}
?>

<nav class="sample non-sample">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('製品新規登録') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="280"><strong>自社工場</strong></td>
          <td width="150"><strong>検査表に表示</strong></td>
        </tr>
        <tr>
        <td><?= h($factory_name) ?></td>
        <td><?= h($status_kensahyou_name) ?></td>
        </tr>
      </table>

      <table>
      <tr>
        <td width="320"><strong>得意先</strong></td>
        <td><strong>品名</strong></td>
        <td><strong>長さ（mm）</strong></td>
        <td><strong>カット長さ（mm）</strong></td>
        <td><strong>単位</strong></td>
        <td><strong>単重(g/m)</strong></td>
     </tr>
      
      <?php for($k=1; $k<=$tuikalength; $k++): ?>

      <tr>
      <td><?= h($customer_name) ?></td>
      <td><?= h($name) ?></td>
      <td><?= h(${"length".$k}) ?></td>
      <?= $this->Form->control('length'.$k, array('type'=>'hidden', 'value'=>${"length".$k}, 'label'=>false)) ?>
      <td><?= h(${"length_cut".$k}) ?></td>
      <?= $this->Form->control('length_cut'.$k, array('type'=>'hidden', 'value'=>${"length_cut".$k}, 'label'=>false)) ?>
      <td><?= h($tanni) ?></td>
      <td><?= h($weight) ?></td>

      <?php endfor;?>

     </table>

    </fieldset>

    <table>
      <tr>
        <tbody class='sample non-sample'>
        <td style="border-style: none;"><div><?= $this->Form->submit('製品メニュートップへ戻る', array('name' => 'top')); ?></div></td>
      </tbody>
    </tr>
    </table>

    <?= $this->Form->end() ?>
  </nav>
