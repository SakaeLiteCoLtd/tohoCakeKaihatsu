<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
?>
<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
$arrCustomer_name_list = json_encode($arrCustomer_name_list);//jsに配列を受け渡すために変換
$arrProduct_name_list = json_encode($arrProduct_name_list);//jsに配列を受け渡すために変換
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

  $(function() {
      // 入力補完候補の単語リスト
      let wordlist = <?php echo $arrProduct_name_list; ?>
      // 入力補完を実施する要素に単語リストを設定
      $("#product_name_list").autocomplete({
        source: wordlist
      });
  });

</script>


<table class='sample hesdermenu'>
  <tbody>
    <td style='border: none;'>
      <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/menu' /><font size='4' color=black>測定データ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/addformpre' /><font size='4' color=black>新規登録</font></a>
    </td>
  </tbody>
</table>

<br><br><br>
<?= $this->Form->create($product, ['url' => ['action' => 'addformpre']]) ?>

<?= $this->Form->control('user_code', array('type'=>'hidden', 'value'=>$user_code, 'label'=>false)) ?>
<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>

<br>
<div align="center"><font size="3"><?= __("製品名を入力して「次へ」ボタンを押してください。") ?></font></div>
<div align="center"><font color="red" size="3"><?= __($mess) ?></font></div>
<br>

<table>
  <tbody style="background-color: #FFFFCC">
    <tr>
      <td width="400" colspan=2><strong>顧客名</strong></td>
      <td width="400"><strong>製品名</strong></td>
    </tr>
    <tr>
      <td style="border-right-style:none">
        <?= $this->Form->control('customer_name', array('type'=>'text', 'label'=>false, 'id'=>"customer_name_list")) ?>
      </td>
      <td style="border-left-style:none"><?= $this->Form->submit(('顧客絞込'), array('name' => 'customer')) ?></td>
      <td style="border: 1px solid black">
      <?php if ($customer_check == 0): ?>

<?= $this->Form->control('product_name', array('type'=>'text', 'label'=>false, 'id'=>"product_name_list")) ?>

<?php else : ?>

  <?= $this->Form->control('product_name', ['options' => $arrProduct_names, 'label'=>false, "empty"=>"選択してください"]) ?>

  <?php endif; ?>
      </td>
    </tr>
  </tbody>
</table>

<br>

<table>
  <tbody class='sample non-sample'>
    <tr>
    <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('次へ'), array('name' => 'next')) ?></td>
    </tr>
  </tbody>
</table>
