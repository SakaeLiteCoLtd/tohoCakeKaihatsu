<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlaccountmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlaccountmenu = new htmlaccountmenu();
 $htmlcustomercodemenus = $htmlaccountmenu->customercodemenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlcustomercodemenus;
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

<?= $this->Form->create($customer, ['url' => ['action' => 'customercodeeditform']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($customer) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('得意先・仕入先情報検索') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt"><?= __('コードを変更するデータを入力してください') ?></strong></td></tr>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

      <table>
      <tr>
        <td width="320"><strong>得意先・仕入先名_部署（営業所）名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30, 'id'=>"customer_name_list")) ?>
        </td>
      </tr>
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
