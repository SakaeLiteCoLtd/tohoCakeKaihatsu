<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlimgmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlimgmenu = new htmlimgmenu();
 $htmlimgmenu = $htmlimgmenu->Imgmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlimgmenu;
?>


<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
$arrCustomer_name_list = json_encode($arrCustomer_name_list);//javaに配列を受け渡すために変換
$arrProduct_name_list = json_encode($arrProduct_name_list);//javaに配列を受け渡すために変換
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


<br><br><br>

<nav class="large-3 medium-4 columns">
  <?= $this->Form->create($inspectionStandardSizeParents, ['url' => ['action' => 'addpre']]) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('検査表画像新規登録') ?></strong></legend>
    </fieldset>

    <br>
    <div align="center"><font color="red" size="3"><?= __($mess) ?></font></div>
    <br>

    <table>
  <tbody style="background-color: #FFFFCC">
    <tr>
      <td width="350" colspan=2><strong>顧客名</strong></td>
      <td width="350"><strong>製品名</strong></td>
    </tr>
    <tr>
      <td style="border-right-style:none">
        <?= $this->Form->control('customer_name', array('type'=>'text', 'label'=>false, 'size'=>25, 'id'=>"customer_name_list")) ?>
      </td>
      <td style="border-left-style:none"><?= $this->Form->submit(('顧客絞込'), array('name' => 'customer')) ?></td>
      <td style="border: 1px solid black">
        <?= $this->Form->control('product_name', array('type'=>'text', 'label'=>false, 'size'=>35, 'id'=>"product_name_list")) ?>
      </td>
    </tr>
  </tbody>
</table>

<br>

<table>
  <tbody class='sample non-sample'>
    <tr>
      <td style="border:none"><?= $this->Form->submit(('次へ'), array('name' => 'next')) ?></td>
    </tr>
  </tbody>
</table>
