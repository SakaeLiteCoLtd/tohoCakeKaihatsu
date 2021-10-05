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

<?= $this->Form->create($product, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<?php
//<nav class="large-3 medium-4 columns">
?>

<?php

if($this->request->getData('status_kensahyou') == 0){
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
        <td><strong>検査表に表示</strong></td>
        <td><strong>幅測定器モード番号</strong></td>
        </tr>
        <tr>
        <td><?= h($name) ?></td>
        <td><?= h($tanni) ?></td>
        <td><?= h($weight) ?></td>
        <td><?= h($status_kensahyou_name) ?></td>
        <td><?= h($ig_bank_modes) ?></td>
        </tr>
      </table>

      <?php if ($status_kensahyou_flag > 0): ?>

      <table>
      <tr>
      <td><strong>製品コード</strong></td>
      <td><strong>長さ（mm）</strong></td>
        <td><strong>カット長さ（mm）</strong></td>
        <td width="50"><strong>上限</strong></td>
        <td width="50"><strong>下限</strong></td>
        <td width="200"><strong>備考</strong></td>
      </tr>
      
      <?php for($k=1; $k<=$tuikalength; $k++): ?>

      <tr>
      <td><?= h(${"product_code".$k}) ?></td>
      <td><?= h(${"length".$k}) ?></td>
      <td><?= h(${"length_cut".$k}) ?></td>
      <td><?= h(${"length_upper_limit".$k}) ?></td>
      <td><?= h(${"length_lower_limit".$k}) ?></td>
      <td><?= h(${"bik".$k}) ?></td>

      <?php endfor;?>

     </table>

     <?php else : ?>

<table>
<tr>
<td><strong>製品コード</strong></td>
<td><strong>長さ（mm）</strong></td>
        <td><strong>カット長さ（mm）</strong></td>
  <td width="200"><strong>備考</strong></td>
</tr>

<?php for($k=1; $k<=$tuikalength; $k++): ?>

<tr>
<td><?= h(${"product_code".$k}) ?></td>
      <td><?= h(${"length".$k}) ?></td>
      <td><?= h(${"length_cut".$k}) ?></td>
      <td><?= h(${"bik".$k}) ?></td>
</tr>

<?php endfor;?>

</table>

<?php endif; ?>

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
