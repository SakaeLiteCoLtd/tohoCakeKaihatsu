<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlproductmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlproductmenu = new htmlproductmenu();
 $htmlproduct = $htmlproductmenu->productmenus($check_gyoumu);
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
$arrCustomer_name_list = json_encode($arrCustomer_name_list);//jsに配列を受け渡すために変換
?>

<script>

$(function() {
    // 入力補完候補の単語リスト
    let wordlist = <?php echo $arrCustomer_name_list; ?>
    // 入力補完を実施する要素に単語リストを設定
    $("#customer_name_list").autocomplete({
      source: wordlist
    });
});

</script>

<?php
     echo $htmllogin;
?>
<?php
     echo $htmlproduct;
?>

<?= $this->Form->create($product, ['url' => ['action' => 'addformlength']]) ?>
<br><br><br>

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
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt"><?= __('登録するデータを入力してください') ?></strong></td></tr>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td><strong>工場名</strong></td>
        </tr>
        <tr>
          <td><?= $this->Form->control('factory_id', ['options' => $arrFactories, 'label'=>false]) ?></td>
        </tr>
      </table>

      <table>
      <tr>
        <td width="260"><strong>得意先</strong></td>
        <td width="260"><strong>品名</strong></td>
        <td width="50"><strong>※単位</strong></td>
        <td width="50"><strong>※単重(g/m)</strong></td>
        <td width="80"><strong>幅測定器<br>モード番号</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('customer_name', array('type'=>'text', 'label'=>false, 'size'=>25, 'id'=>"customer_name_list", 'autocomplete'=>"off", 'required'=>true)) ?>
        </td>
        <td><?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>25, 'autocomplete'=>"off")) ?></td>
        <td><?= $this->Form->control('tanni', ['options' => $arrTanni, 'label'=>false]) ?></td>
        <td><?= $this->Form->control('weight', array('type'=>'text', 'pattern'=>'^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'label'=>false, 'size'=>6, 'autocomplete'=>"off")) ?></td>
        <td><?= $this->Form->control('ig_bank_modes', ['options' => $arrig_bank_modes, 'label'=>false]) ?></td>
      </tr>
    </table>
    <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 12pt">
            <?= __('「※」のついている欄は空白のまま登録できます。') ?>
          </strong></td></tr>
          </tbody>
        </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
