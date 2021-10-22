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
if($this->request->getData('status_kensahyou') == 0){
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
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
        <td width="180"><strong>工場名</strong></td>
          <td width="380"><strong>得意先</strong></td>
        </tr>
        <tr>
        <td><?= h($this->request->getData('factory_name')) ?></td>
        <td><?= h($this->request->getData('customer_name')) ?></td>
        </tr>
      </table>

      <table>
          <tr>
            <td width="90"><strong>単位</strong></td>
            <td width="90"><strong>単重(g/m)</strong></td>
            <td width="50"><strong>幅測定器モード番号</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('tanni')) ?></td>
            <td><?= h($this->request->getData('weight')) ?></td>
            <td><?= h($this->request->getData('ig_bank_modes')) ?></td>
       	</tr>
        </table>

     <br>

     <table>
      <tr>
      <td><strong>製品コード</strong></td>
      <td width="200"><strong>品名</strong></td>
      <td width="50"><strong>検査表に表示</strong></td>
      <td><strong>長さ（mm）</strong></td>
      <td><strong>カット長さ（mm）</strong></td>
        <td width="50"><strong>公差下限</strong></td>
        <td width="50"><strong>公差上限</strong></td>
        <td width="120"><strong>備考</strong></td>
      </tr>
      
      <?php for($i=0; $i<count($arrupdateproduct); $i++): ?>

        <tr>
      <td><?= h($arrupdateproduct[$i]["product_code"]) ?></td>
      <td><?= h($arrupdateproduct[$i]["name"]) ?></td>
      <td><?= h($arrupdateproduct[$i]["status_kensahyou_name"]) ?></td>
      <td><?= h($arrupdateproduct[$i]["length"]) ?></td>
      <td><?= h($arrupdateproduct[$i]["length_cut"]) ?></td>
      <td><?= h($arrupdateproduct[$i]["length_lower_limit"]) ?></td>
      <td><?= h($arrupdateproduct[$i]["length_upper_limit"]) ?></td>
      <td><?= h($arrupdateproduct[$i]["bik"]) ?></td>
      </tr>

      <?php endfor;?>

     </table>

     <?php if (count($arrdeleteproduct) > 0) : ?>

     <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下削除') ?></strong></td></tr>
          </tbody>
        </table>

        <table>
      <tr>
      <td><strong>製品コード</strong></td>
      <td width="300"><strong>品名</strong></td>
      <td width="50"><strong>検査表に表示</strong></td>
      <td><strong>長さ（mm）</strong></td>
      <td><strong>カット長さ（mm）</strong></td>
      <td width="120"><strong>備考</strong></td>
      </tr>

      <?php for($i=0; $i<count($arrdeleteproduct); $i++): ?>

        <tr>
        <td><?= h($arrdeleteproduct[$i]["product_code"]) ?></td>
        <td><?= h($arrdeleteproduct[$i]["name"]) ?></td>
        <td><?= h($arrdeleteproduct[$i]["status_kensahyou_name"]) ?></td>
        <td><?= h($arrdeleteproduct[$i]["length"]) ?></td>
        <td><?= h($arrdeleteproduct[$i]["length_cut"]) ?></td>
        <td><?= h($arrdeleteproduct[$i]["bik"]) ?></td>
        </tr>

        <?php endfor;?>

     </table>

     <?php else : ?>
      <?php endif; ?>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('製品メニュートップへ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>
    <br><br><br>
    <?= $this->Form->end() ?>
  </nav>
